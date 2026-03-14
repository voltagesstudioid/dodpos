<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderReceipt;
use App\Models\PurchaseOrderReceiptItem;
use App\Models\PurchaseOrderShortageReport;
use App\Models\StockMovement;
use App\Models\SupplierDebt;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class WarehouseReceiptController extends Controller
{
    /**
     * Display a listing of pending POs for warehouse receipt.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = PurchaseOrder::with(['supplier'])
            ->whereIn('status', ['ordered', 'partial'])
            ->when($search, function ($query) use ($search) {
                $query->where('po_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('gudang.terima_po.index', compact('orders'));
    }

    /**
     * Show the blind receipt form for a specific PO.
     */
    public function show(PurchaseOrder $order)
    {
        // Only allow receiving if it's ordered or partially received
        abort_if(! in_array($order->status, ['ordered', 'partial']), 404);

        // Load items with their specific unit conversions and base products
        $relations = ['items.product', 'items.unit', 'supplier'];
        if (Schema::hasTable('purchase_order_receipts')) {
            $relations[] = 'receipts.receiver';
            $relations[] = 'receipts.items.product';
            $relations[] = 'receipts.purchaseReturn';
            $relations[] = 'receipts.reorderPurchaseOrder';
        }
        $order->load($relations);

        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();

        return view('gudang.terima_po.show', compact('order', 'warehouses'));
    }

    /**
     * Process the receipt of items.
     */
    public function store(Request $request, PurchaseOrder $order)
    {
        abort_if(! in_array($order->status, ['ordered', 'partial']), 403, 'PO tidak dapat diproses (Status: '.$order->status.').');

        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'shortage_notes' => 'nullable|string|max:2000',
            'receipt_notes' => 'nullable|string|max:2000',
            'receipt_photos' => 'nullable|array|max:6',
            'receipt_photos.*' => 'image|mimes:jpg,jpeg,png|max:4096',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:purchase_order_items,id',
            'items.*.checked' => 'nullable|in:1',
            'items.*.result' => 'nullable|in:accepted,rejected',
            'items.*.qty' => 'nullable|numeric|min:0',
            'items.*.expired_date' => 'nullable|date',
            'items.*.batch_number' => 'nullable|string|max:100',
            'items.*.quality_ok' => 'nullable|boolean',
            'items.*.spec_ok' => 'nullable|boolean',
            'items.*.packaging_ok' => 'nullable|boolean',
            'items.*.qc_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $anyProcessed = false;

            $photos = [];
            if ($request->hasFile('receipt_photos')) {
                foreach ($request->file('receipt_photos', []) as $file) {
                    if (! $file) {
                        continue;
                    }
                    $photos[] = $file->store('po-receipts/'.$order->po_number, 'public');
                }
            }

            $receipt = PurchaseOrderReceipt::create([
                'purchase_order_id' => $order->id,
                'warehouse_id' => (int) $request->warehouse_id,
                'received_by' => Auth::id(),
                'status' => 'completed',
                'photos' => $photos ?: null,
                'notes' => $request->input('receipt_notes'),
            ]);

            $anyQtyReceived = false;

            foreach ($request->items as $receive) {
                $checked = isset($receive['checked']) && (string) $receive['checked'] === '1';
                $qty = isset($receive['qty']) ? (float) $receive['qty'] : 0;
                $resultInput = $receive['result'] ?? null;

                if (! $checked && ($qty <= 0) && $resultInput !== 'rejected') {
                    continue;
                }

                $item = PurchaseOrderItem::findOrFail($receive['item_id']);

                // Ensure the item belongs to this PO
                if ($item->purchase_order_id !== $order->id) {
                    continue;
                }

                $remaining = $item->qty_ordered - $item->qty_received;
                $qtyInt = (int) $qty;

                if ($qtyInt > $remaining) {
                    DB::rollBack();

                    return back()->with('error', "Qty diterima untuk {$item->product->name} melebihi sisa pesanan ({$remaining}).")->withInput();
                }

                $result = 'accepted';
                if ($resultInput === 'rejected' || $qtyInt === 0) {
                    $result = 'rejected';
                } elseif ($qtyInt < $remaining) {
                    $result = 'partial';
                }

                $qualityOk = array_key_exists('quality_ok', $receive) ? (bool) $receive['quality_ok'] : true;
                $specOk = array_key_exists('spec_ok', $receive) ? (bool) $receive['spec_ok'] : true;
                $packagingOk = array_key_exists('packaging_ok', $receive) ? (bool) $receive['packaging_ok'] : true;

                if ($result !== 'accepted' || ! $qualityOk || ! $specOk || ! $packagingOk) {
                    $receipt->status = 'partial';
                }

                if ($qtyInt > 0) {
                    $anyQtyReceived = true;
                    // 1. Update item received qty (still in PO unit)
                    $item->qty_received += $qtyInt;
                    $item->save();

                    // 2. Calculate real base stock quantity using the conversion factor
                    $baseQty = $qtyInt * max(1, (int) $item->conversion_factor);

                    // 3. Add base stock to warehouse specifically
                    $productStock = ProductStock::firstOrNew([
                        'product_id' => $item->product_id,
                        'warehouse_id' => $request->warehouse_id,
                        'location_id' => null,
                        'expired_date' => $receive['expired_date'] ?? null,
                        'batch_number' => $receive['batch_number'] ?? null,
                    ]);
                    $productStock->stock = ($productStock->stock ?? 0) + $baseQty;
                    $productStock->save();

                    // 4. Update global product stock
                    $item->product->increment('stock', $baseQty);

                    // 5. Record stock movement (in base qty)
                    $unitName = $item->unit ? $item->unit->name : 'Pcs';
                    $movementNote = "[Terima PO] {$order->po_number} — {$order->supplier->name} ({$qtyInt} {$unitName})";

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'warehouse_id' => $request->warehouse_id,
                        'location_id' => null,
                        'type' => 'in',
                        'source_type' => 'purchase_order',
                        'purchase_order_id' => $order->id,
                        'reference_number' => $order->po_number,
                        'batch_number' => $receive['batch_number'] ?? null,
                        'expired_date' => $receive['expired_date'] ?? null,
                        'quantity' => $baseQty,
                        'notes' => $movementNote,
                        'user_id' => Auth::id(),
                    ]);

                    $anyProcessed = true;
                } else {
                    $baseQty = 0;
                    $anyProcessed = true;
                }

                PurchaseOrderReceiptItem::create([
                    'receipt_id' => $receipt->id,
                    'purchase_order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'qty_remaining_before' => (int) $remaining,
                    'qty_received_po_unit' => $qtyInt,
                    'qty_received_base' => (int) $baseQty,
                    'result' => $result,
                    'batch_number' => $receive['batch_number'] ?? null,
                    'expired_date' => $receive['expired_date'] ?? null,
                    'quality_ok' => $qualityOk,
                    'spec_ok' => $specOk,
                    'packaging_ok' => $packagingOk,
                    'notes' => $receive['qc_notes'] ?? null,
                ]);
            }

            if (! $anyProcessed) {
                DB::rollBack();

                if ($photos) {
                    foreach ($photos as $p) {
                        Storage::disk('public')->delete($p);
                    }
                }

                return back()->with('error', 'Tidak ada item yang diproses. Centang minimal satu item atau isi qty penerimaan.')->withInput();
            }

            // Determine new PO status based on all items
            $order->refresh();
            $order->load('items');
            $allReceived = $order->items->every(fn ($i) => $i->qty_received >= $i->qty_ordered);

            if ($anyQtyReceived) {
                $order->update(['status' => $allReceived ? 'received' : 'partial']);
            }

            if ($allReceived && $receipt->status !== 'partial') {
                $receipt->status = 'completed';
            }
            $receipt->needs_followup = $receipt->status === 'partial';
            $receipt->followup_status = $receipt->needs_followup ? 'open' : null;
            $receipt->save();

            if (! $allReceived) {
                $items = $order->items()
                    ->with(['product', 'unit'])
                    ->get()
                    ->filter(fn ($it) => (int) $it->qty_received < (int) $it->qty_ordered)
                    ->map(function ($it) {
                        return [
                            'product_id' => $it->product_id,
                            'product_name' => $it->product?->name ?? '',
                            'sku' => $it->product?->sku ?? '',
                            'unit' => $it->unit?->abbreviation ?? $it->product?->unit?->abbreviation ?? null,
                            'qty_ordered' => (int) $it->qty_ordered,
                            'qty_received' => (int) $it->qty_received,
                            'qty_missing' => max(0, (int) $it->qty_ordered - (int) $it->qty_received),
                        ];
                    })
                    ->values()
                    ->all();

                if (count($items) > 0) {
                    PurchaseOrderShortageReport::create([
                        'purchase_order_id' => $order->id,
                        'reported_by' => Auth::id(),
                        'items' => $items,
                        'notes' => $request->input('shortage_notes'),
                    ]);
                }
            }

            if ($anyQtyReceived) {
                // Auto-create Supplier Debt (Hutang) once when PO start being received
                $isCredit = true;
                if (\Illuminate\Support\Facades\Schema::hasColumn('purchase_orders', 'payment_term')) {
                    $isCredit = ($order->payment_term === 'credit');
                }
                if ($isCredit && $order->supplier_id && $order->total_amount > 0) {
                    $exists = SupplierDebt::where('purchase_order_id', $order->id)->exists();
                    if (! $exists) {
                        SupplierDebt::create([
                            'invoice_number' => SupplierDebt::generateInvoiceNumber(),
                            'supplier_id' => $order->supplier_id,
                            'purchase_order_id' => $order->id,
                            'transaction_date' => now()->toDateString(),
                            'due_date' => ($order->due_date ? $order->due_date->format('Y-m-d') : now()->addDays(30)->toDateString()),
                            'total_amount' => $order->total_amount,
                            'paid_amount' => 0,
                            'status' => 'unpaid',
                            'notes' => 'Auto: Hutang dari PO '.$order->po_number,
                        ]);
                    }
                }
            }

            DB::commit();

            $statusMsg = $allReceived ? 'Semua barang telah diterima penuh.' : 'Barang diterima sebagian (Backorder).';

            return redirect()->route('gudang.terimapo.index')
                ->with('success', "Penerimaan PO #{$order->po_number} berhasil diproses. {$statusMsg}");

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($photos) && $photos) {
                foreach ($photos as $p) {
                    Storage::disk('public')->delete($p);
                }
            }

            return back()->with('error', 'Kesalahan sistem penerimaan: '.$e->getMessage())->withInput();
        }
    }
}

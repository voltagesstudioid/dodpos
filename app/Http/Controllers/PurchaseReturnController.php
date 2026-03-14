<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductUnitConversion;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseReturn::with(['supplier', 'purchaseOrder'])->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('return_number', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $returns = $query->paginate(15)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        return view('pembelian.retur.index', compact('returns', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::whereIn('status', ['received', 'partial'])
            ->with('supplier')
            ->latest()
            ->get();
        $products = Product::with(['unit', 'unitConversions.unit'])->where('stock', '>', 0)->orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();

        $productsData = $products->map(function ($p) {
            $conversions = [];
            if ($p->unit) {
                $conversions[] = [
                    'unit_id' => $p->unit_id,
                    'name' => $p->unit->name,
                    'factor' => 1,
                    'price' => $p->purchase_price ?? 0,
                ];
            }
            foreach ($p->unitConversions as $uc) {
                if ($uc->unit) {
                    $conversions[] = [
                        'unit_id' => $uc->unit_id,
                        'name' => $uc->unit->name,
                        'factor' => (int) $uc->conversion_factor,
                        'price' => (float) ($uc->purchase_price ?? (($p->purchase_price ?? 0) * $uc->conversion_factor)),
                    ];
                }
            }

            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'price' => $p->purchase_price ?? 0,
                'unit' => $p->unit ? $p->unit->name : '',
                'conversions' => $conversions,
            ];
        })->values()->toArray();

        return view('pembelian.retur.create', compact('suppliers', 'purchaseOrders', 'products', 'units', 'productsData', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'return_date' => 'required|date',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $total = collect($request->items)->sum(fn ($i) => $i['quantity'] * $i['price']);

            $return = PurchaseReturn::create([
                'return_number' => PurchaseReturn::generateNumber(),
                'supplier_id' => $request->supplier_id,
                'purchase_order_id' => $request->purchase_order_id ?: null,
                'warehouse_id' => $request->warehouse_id,
                'return_date' => $request->return_date,
                'status' => 'draft',
                'total_amount' => $total,
                'reason' => $request->reason,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                PurchaseReturnItem::create([
                    'purchase_return_id' => $return->id,
                    'product_id' => $item['product_id'],
                    'unit_id' => $item['unit_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['price'],
                    'reason' => $item['reason'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('pembelian.retur.show', $return)
                ->with('success', "Retur {$return->return_number} berhasil dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membuat retur: '.$e->getMessage())->withInput();
        }
    }

    public function show(PurchaseReturn $retur)
    {
        $retur->load(['supplier', 'purchaseOrder', 'warehouse', 'items.product', 'items.unit', 'createdBy']);

        return view('pembelian.retur.show', compact('retur'));
    }

    public function approve(PurchaseReturn $retur)
    {
        if ($retur->status !== 'draft') {
            return back()->with('error', 'Hanya retur dengan status Draft yang bisa disetujui.');
        }
        $retur->update(['status' => 'approved']);

        return back()->with('success', 'Retur disetujui.');
    }

    public function process(PurchaseReturn $retur)
    {
        if ($retur->status !== 'approved') {
            return back()->with('error', 'Hanya retur dengan status Disetujui yang bisa diproses.');
        }

        try {
            DB::beginTransaction();

            $retur->loadMissing(['supplier', 'warehouse', 'items.product']);
            if (! $retur->warehouse_id) {
                DB::rollBack();

                return back()->with('error', 'Gudang sumber retur belum ditentukan.');
            }

            foreach ($retur->items as $item) {
                $product = $item->product;
                $factor = 1;
                if ($product && $product->unit_id && $item->unit_id && (int) $item->unit_id !== (int) $product->unit_id) {
                    $uc = ProductUnitConversion::where('product_id', $item->product_id)
                        ->where('unit_id', $item->unit_id)
                        ->first();
                    if (! $uc) {
                        throw new \RuntimeException("Konversi satuan tidak ditemukan untuk produk {$product->name}.");
                    }
                    $factor = (int) $uc->conversion_factor;
                }

                $baseQty = (int) $item->quantity * $factor;
                if ($baseQty <= 0) {
                    continue;
                }

                $this->deductWarehouseStock($item->product_id, $retur->warehouse_id, $baseQty);

                Product::where('id', $item->product_id)->decrement('stock', $baseQty);

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $retur->warehouse_id,
                    'location_id' => null,
                    'type' => 'out',
                    'source_type' => 'purchase_return',
                    'purchase_order_id' => $retur->purchase_order_id,
                    'reference_number' => $retur->return_number,
                    'batch_number' => null,
                    'expired_date' => null,
                    'quantity' => $baseQty,
                    'balance' => 0,
                    'notes' => 'Retur ke supplier: '.$retur->supplier->name,
                    'user_id' => Auth::id(),
                ]);
            }

            $retur->update(['status' => 'returned']);
            DB::commit();

            return back()->with('success', 'Retur selesai diproses. Stok telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses retur: '.$e->getMessage());
        }
    }

    private function deductWarehouseStock(int $productId, int $warehouseId, int $baseQty): void
    {
        $remaining = $baseQty;
        $stocks = ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('stock', '>', 0)
            ->orderByRaw('CASE WHEN expired_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('expired_date')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $s) {
            if ($remaining <= 0) {
                break;
            }
            $deduct = min($remaining, (int) $s->stock);
            if ($deduct <= 0) {
                continue;
            }
            $s->decrement('stock', $deduct);
            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            $productName = Product::find($productId)?->name ?? (string) $productId;
            throw new \RuntimeException("Stok gudang tidak mencukupi untuk produk {$productName}, kurang {$remaining} (satuan dasar).");
        }
    }

    public function cancel(PurchaseReturn $retur)
    {
        if ($retur->status === 'returned') {
            return back()->with('error', 'Retur yang sudah selesai tidak bisa dibatalkan.');
        }
        $retur->update(['status' => 'draft']);

        return back()->with('success', 'Retur dikembalikan ke status Draft.');
    }

    public function destroy(PurchaseReturn $retur)
    {
        if ($retur->status !== 'draft') {
            return back()->with('error', 'Hanya retur Draft yang bisa dihapus.');
        }
        $retur->delete();

        return redirect()->route('pembelian.retur.index')->with('success', 'Retur dihapus.');
    }
}

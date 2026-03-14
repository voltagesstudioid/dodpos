<?php

namespace App\Http\Controllers;

use App\Models\CustomerCredit;
use App\Models\PosReturn;
use App\Models\PosReturnItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosReturnController extends Controller
{
    public function create($transaksi)
    {
        $transaksi = Transaction::with(['details.product', 'details.warehouse', 'customer'])->findOrFail($transaksi);

        if ($transaksi->status !== 'completed') {
            return redirect()->route('transaksi.show', ['transaksi' => $transaksi->id])->with('error', 'Hanya transaksi selesai yang bisa diretur.');
        }

        $returnedByDetail = PosReturnItem::query()
            ->whereHas('posReturn', fn ($q) => $q->where('transaction_id', $transaksi->id)->where('status', 'completed'))
            ->selectRaw('transaction_detail_id, SUM(quantity) as qty')
            ->groupBy('transaction_detail_id')
            ->pluck('qty', 'transaction_detail_id')
            ->toArray();

        $warehouses = Warehouse::query()->where('active', true)->orderBy('name')->get();

        $rows = $transaksi->details->map(function ($d) use ($returnedByDetail) {
            $returned = (int) ($returnedByDetail[$d->id] ?? 0);
            $available = max(0, (int) $d->quantity - $returned);

            return [
                'detail_id' => $d->id,
                'product_id' => $d->product_id,
                'product_name' => $d->product?->name ?? 'Produk dihapus',
                'sku' => $d->product?->sku,
                'qty_sold' => (int) $d->quantity,
                'qty_returned' => $returned,
                'qty_available' => $available,
                'price' => (float) $d->price,
                'warehouse_id' => $d->warehouse_id,
                'warehouse_name' => $d->warehouse?->name,
            ];
        });

        return view('transaksi.retur.create', compact('transaksi', 'rows', 'warehouses'));
    }

    public function store(Request $request, $transaksi)
    {
        $transaksi = Transaction::with(['details.product', 'details.warehouse', 'customer'])->findOrFail($transaksi);

        if ($transaksi->status !== 'completed') {
            return redirect()->route('transaksi.show', ['transaksi' => $transaksi->id])->with('error', 'Hanya transaksi selesai yang bisa diretur.');
        }

        $validated = $request->validate([
            'refund_method' => 'required|in:tunai,transfer,tanpa_refund',
            'refund_reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.detail_id' => 'required|exists:transaction_details,id',
            'items.*.quantity' => 'required|integer|min:0',
            'items.*.warehouse_id' => 'nullable|exists:warehouses,id',
        ]);

        if (($validated['refund_method'] ?? null) === 'transfer' && blank($validated['refund_reference'] ?? null)) {
            return back()->with('error', 'ID transaksi transfer wajib diisi untuk refund transfer.')->withInput();
        }

        $detailIds = collect($validated['items'])->pluck('detail_id')->unique()->values()->all();

        $details = TransactionDetail::query()
            ->where('transaction_id', $transaksi->id)
            ->whereIn('id', $detailIds)
            ->with(['product', 'warehouse'])
            ->get()
            ->keyBy('id');

        $returnedByDetail = PosReturnItem::query()
            ->whereHas('posReturn', fn ($q) => $q->where('transaction_id', $transaksi->id)->where('status', 'completed'))
            ->selectRaw('transaction_detail_id, SUM(quantity) as qty')
            ->groupBy('transaction_detail_id')
            ->pluck('qty', 'transaction_detail_id')
            ->toArray();

        $itemsToReturn = [];
        foreach ($validated['items'] as $row) {
            $detailId = (int) $row['detail_id'];
            $qty = (int) $row['quantity'];
            if ($qty <= 0) {
                continue;
            }

            $detail = $details->get($detailId);
            if (! $detail) {
                return back()->with('error', 'Item retur tidak valid.')->withInput();
            }

            $returned = (int) ($returnedByDetail[$detailId] ?? 0);
            $available = max(0, (int) $detail->quantity - $returned);
            if ($qty > $available) {
                return back()->with('error', 'Qty retur melebihi sisa qty yang bisa diretur.')->withInput();
            }

            $warehouseId = $detail->warehouse_id ?: (int) ($row['warehouse_id'] ?? 0);
            if (! $warehouseId) {
                return back()->with('error', 'Gudang retur wajib dipilih untuk transaksi lama.')->withInput();
            }

            $price = (float) $detail->price;
            $subtotal = round($price * $qty, 2);

            $itemsToReturn[] = [
                'transaction_detail_id' => $detailId,
                'product_id' => $detail->product_id,
                'warehouse_id' => $warehouseId,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        if (count($itemsToReturn) === 0) {
            return back()->with('error', 'Pilih minimal 1 item untuk diretur.')->withInput();
        }

        $refundAmount = round(array_sum(array_column($itemsToReturn, 'subtotal')), 2);

        try {
            DB::beginTransaction();

            $posReturn = PosReturn::create([
                'return_number' => PosReturn::generateNumber(),
                'transaction_id' => $transaksi->id,
                'customer_id' => $transaksi->customer_id,
                'user_id' => Auth::id(),
                'return_date' => now()->toDateString(),
                'refund_method' => $validated['refund_method'],
                'refund_reference' => $validated['refund_reference'] ?? null,
                'refund_amount' => $refundAmount,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsToReturn as $item) {
                PosReturnItem::create([
                    'pos_return_id' => $posReturn->id,
                    'transaction_detail_id' => $item['transaction_detail_id'],
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                Product::where('id', $item['product_id'])->increment('stock', $item['quantity']);

                $stock = ProductStock::query()
                    ->where('product_id', $item['product_id'])
                    ->where('warehouse_id', $item['warehouse_id'])
                    ->whereNull('location_id')
                    ->whereNull('batch_number')
                    ->whereNull('expired_date')
                    ->lockForUpdate()
                    ->first();

                if (! $stock) {
                    $stock = ProductStock::create([
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $item['warehouse_id'],
                        'location_id' => null,
                        'batch_number' => null,
                        'expired_date' => null,
                        'stock' => 0,
                    ]);
                }

                $stock->stock += $item['quantity'];
                $stock->save();

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'location_id' => null,
                    'type' => 'in',
                    'source_type' => 'pos_return',
                    'reference_number' => $posReturn->return_number,
                    'batch_number' => null,
                    'expired_date' => null,
                    'quantity' => $item['quantity'],
                    'balance' => $stock->stock,
                    'notes' => '[POS Retur] '.$posReturn->return_number.' dari transaksi #'.$transaksi->id,
                    'user_id' => Auth::id(),
                ]);
            }

            if ($transaksi->payment_method === 'kredit' && $transaksi->customer_id) {
                $credit = CustomerCredit::query()
                    ->where('transaction_id', $transaksi->id)
                    ->where('type', 'debt')
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->lockForUpdate()
                    ->first();

                if ($credit) {
                    $prevAmount = (float) ($credit->amount ?? 0);
                    $prevPaid = (float) ($credit->paid_amount ?? 0);

                    $newAmount = max(0, $prevAmount - $refundAmount);
                    $overpaid = max(0, $prevPaid - $newAmount);

                    $newPaid = min($prevPaid, $newAmount);
                    $newStatus = $newPaid >= $newAmount ? 'paid' : ($newPaid > 0 ? 'partial' : 'unpaid');

                    $credit->update([
                        'amount' => $newAmount,
                        'paid_amount' => $newPaid,
                        'status' => $newStatus,
                        'notes' => ($credit->notes ? $credit->notes.' | ' : '').
                            '[RETUR] '.$posReturn->return_number.' refund Rp '.number_format($refundAmount, 0, ',', '.'),
                    ]);

                    if ($overpaid > 0) {
                        $creditNoteStatus = $validated['refund_method'] === 'tanpa_refund' ? 'unpaid' : 'paid';
                        $creditNotePaid = $creditNoteStatus === 'paid' ? $overpaid : 0;

                        CustomerCredit::create([
                            'credit_number' => CustomerCredit::generateNumber('credit'),
                            'customer_id' => $transaksi->customer_id,
                            'transaction_id' => $transaksi->id,
                            'type' => 'credit',
                            'transaction_date' => today(),
                            'due_date' => null,
                            'amount' => $overpaid,
                            'paid_amount' => $creditNotePaid,
                            'status' => $creditNoteStatus,
                            'description' => 'Credit note retur POS - '.$posReturn->return_number,
                            'created_by' => Auth::id(),
                        ]);
                    }

                    $credit->customer?->refreshDebt();
                }
            }

            DB::commit();

            return redirect()->route('transaksi.retur.show', $posReturn)->with('success', 'Retur berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memproses retur: '.$e->getMessage())->withInput();
        }
    }

    public function show(PosReturn $retur)
    {
        $retur->load(['transaction.user', 'transaction.customer', 'items.product', 'items.warehouse', 'user']);

        return view('transaksi.retur.show', compact('retur'));
    }
}

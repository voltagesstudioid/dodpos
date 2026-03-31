<?php

namespace App\Http\Controllers;

use App\Models\CustomerCredit;
use App\Models\PosReturn;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Support\SearchSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.product'])->latest();

        // Filters
        if ($request->search) {
            $sanitizedSearch = SearchSanitizer::sanitize($request->search);
            $query->where('id', $request->search)
                ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$sanitizedSearch}%"));
        }
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(25)->withQueryString();

        // Summary cards (for current filter scope)
        $summaryQuery = Transaction::query();
        if ($request->date_from) {
            $summaryQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $summaryQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->payment_method) {
            $summaryQuery->where('payment_method', $request->payment_method);
        }

        $totalRevenue = (clone $summaryQuery)->where('status', 'completed')->sum('total_amount');
        $totalCount = (clone $summaryQuery)->where('status', 'completed')->count();
        $todayRevenue = Transaction::whereDate('created_at', today())->where('status', 'completed')->sum('total_amount');
        $todayCount = Transaction::whereDate('created_at', today())->where('status', 'completed')->count();

        return view('transaksi.index', compact(
            'transactions', 'totalRevenue', 'totalCount', 'todayRevenue', 'todayCount'
        ));
    }

    public function show(Transaction $transaksi)
    {
        $transaksi->load(['user', 'details.product.category', 'details.warehouse', 'customer']);

        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Void transaksi: kembalikan stok, batalkan CustomerCredit jika ada.
     */
    public function destroy(Transaction $transaksi)
    {
        if ($transaksi->status === 'voided') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        try {
            DB::beginTransaction();

            $hasReturn = PosReturn::query()
                ->where('transaction_id', $transaksi->id)
                ->where('status', 'completed')
                ->exists();
            if ($hasReturn) {
                DB::rollBack();

                return back()->with('error', 'Tidak bisa void transaksi yang sudah punya retur.');
            }

            $transaksi->load('details');

            $details = $transaksi->details;
            $allDetailsMissingWarehouse = $details->every(fn ($d) => empty($d->warehouse_id));

            if ($allDetailsMissingWarehouse) {
                $movementRows = StockMovement::query()
                    ->selectRaw('product_id, warehouse_id, location_id, SUM(quantity) as qty')
                    ->where('source_type', 'pos_transaction')
                    ->where('reference_number', 'TRX-'.$transaksi->id)
                    ->where('type', 'out')
                    ->groupBy('product_id', 'warehouse_id', 'location_id')
                    ->get();

                if ($movementRows->isNotEmpty()) {
                    foreach ($movementRows as $row) {
                        $productId = (int) $row->product_id;
                        $warehouseId = $row->warehouse_id ? (int) $row->warehouse_id : null;
                        $locationId = $row->location_id ? (int) $row->location_id : null;
                        $qty = (int) $row->qty;

                        if ($qty <= 0) {
                            continue;
                        }

                        Product::where('id', $productId)->increment('stock', $qty);

                        $stock = null;
                        if ($warehouseId) {
                            $stock = ProductStock::query()
                                ->where('product_id', $productId)
                                ->where('warehouse_id', $warehouseId)
                                ->where('location_id', $locationId)
                                ->whereNull('batch_number')
                                ->whereNull('expired_date')
                                ->lockForUpdate()
                                ->first();

                            if (! $stock) {
                                $stock = ProductStock::create([
                                    'product_id' => $productId,
                                    'warehouse_id' => $warehouseId,
                                    'location_id' => $locationId,
                                    'batch_number' => null,
                                    'expired_date' => null,
                                    'stock' => 0,
                                ]);
                            }

                            $stock->stock += $qty;
                            $stock->save();
                        }

                        StockMovement::create([
                            'product_id' => $productId,
                            'warehouse_id' => $warehouseId,
                            'location_id' => $locationId,
                            'type' => 'in',
                            'source_type' => 'void_transaction',
                            'reference_number' => 'VOID-TRX-'.$transaksi->id,
                            'quantity' => $qty,
                            'balance' => ($stock->stock ?? 0),
                            'notes' => '[VOID] Pembatalan Transaksi #'.$transaksi->id,
                            'user_id' => Auth::id(),
                        ]);
                    }
                } else {
                    foreach ($details as $detail) {
                        Product::where('id', $detail->product_id)
                            ->increment('stock', $detail->quantity);

                        $warehouseId = ProductStock::where('product_id', $detail->product_id)
                            ->orderBy('created_at', 'asc')
                            ->value('warehouse_id');

                        $stock = null;
                        if ($warehouseId) {
                            $stock = ProductStock::query()
                                ->where('product_id', $detail->product_id)
                                ->where('warehouse_id', $warehouseId)
                                ->whereNull('location_id')
                                ->whereNull('batch_number')
                                ->whereNull('expired_date')
                                ->lockForUpdate()
                                ->first();

                            if (! $stock) {
                                $stock = ProductStock::create([
                                    'product_id' => $detail->product_id,
                                    'warehouse_id' => $warehouseId,
                                    'location_id' => null,
                                    'batch_number' => null,
                                    'expired_date' => null,
                                    'stock' => 0,
                                ]);
                            }

                            $stock->stock += $detail->quantity;
                            $stock->save();
                        }

                        StockMovement::create([
                            'product_id' => $detail->product_id,
                            'warehouse_id' => $warehouseId,
                            'type' => 'in',
                            'source_type' => 'void_transaction',
                            'reference_number' => 'VOID-TRX-'.$transaksi->id,
                            'quantity' => $detail->quantity,
                            'balance' => ($stock->stock ?? 0),
                            'notes' => '[VOID] Pembatalan Transaksi #'.$transaksi->id,
                            'user_id' => Auth::id(),
                        ]);
                    }
                }
            } else {
                foreach ($details as $detail) {
                    Product::where('id', $detail->product_id)
                        ->increment('stock', $detail->quantity);

                    $warehouseId = $detail->warehouse_id;
                    if (! $warehouseId) {
                        $warehouseId = ProductStock::where('product_id', $detail->product_id)
                            ->orderBy('created_at', 'asc')
                            ->value('warehouse_id');
                    }

                    $stock = null;
                    if ($warehouseId) {
                        $stock = ProductStock::query()
                            ->where('product_id', $detail->product_id)
                            ->where('warehouse_id', $warehouseId)
                            ->whereNull('location_id')
                            ->whereNull('batch_number')
                            ->whereNull('expired_date')
                            ->lockForUpdate()
                            ->first();

                        if (! $stock) {
                            $stock = ProductStock::create([
                                'product_id' => $detail->product_id,
                                'warehouse_id' => $warehouseId,
                                'location_id' => null,
                                'batch_number' => null,
                                'expired_date' => null,
                                'stock' => 0,
                            ]);
                        }

                        $stock->stock += $detail->quantity;
                        $stock->save();
                    }

                    StockMovement::create([
                        'product_id' => $detail->product_id,
                        'warehouse_id' => $warehouseId,
                        'type' => 'in',
                        'source_type' => 'void_transaction',
                        'reference_number' => 'VOID-TRX-'.$transaksi->id,
                        'quantity' => $detail->quantity,
                        'balance' => ($stock->stock ?? 0),
                        'notes' => '[VOID] Pembatalan Transaksi #'.$transaksi->id,
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            // ── 2. Batalkan CustomerCredit jika transaksi ini kredit ──
            if ($transaksi->customer_id && $transaksi->payment_method === 'kredit') {
                $credit = CustomerCredit::where('transaction_id', $transaksi->id)
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->lockForUpdate()
                    ->first();

                if ($credit) {
                    $paid = (float) ($credit->paid_amount ?? 0);

                    $credit->update([
                        'amount' => $paid,
                        'status' => 'paid',
                        'notes' => ($credit->notes ? $credit->notes.' | ' : '').
                            '[VOID] Transaksi #'.$transaksi->id.' di-void oleh '.Auth::user()->name.' pada '.now()->format('d/m/Y H:i'),
                    ]);

                    // Refresh hutang pelanggan
                    $customer = $credit->customer;
                    if ($customer) {
                        $customer->refreshDebt();
                    }
                }
            }

            // ── 3. Tandai transaksi sebagai void ─────────────────────
            $transaksi->update(['status' => 'voided']);

            DB::commit();

            return back()->with('success', 'Transaksi #'.$transaksi->id.' berhasil dibatalkan (void). Stok dan piutang pelanggan telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TransactionController::destroy — Gagal void transaksi #'.$transaksi->id, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => Auth::id(),
            ]);

            return back()->with('error', 'Gagal membatalkan transaksi. Silakan coba lagi.');
        }
    }
}

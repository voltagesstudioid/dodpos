<?php

namespace App\Http\Controllers;

use App\Models\CustomerCredit;
use App\Models\PosReturn;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Support\SearchSanitizer;
use App\Support\WarehouseConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));

        // Admin1/admin2 hanya bisa melihat transaksi mereka sendiri
        $isOwnOnly = in_array($role, ['admin1', 'admin2'], true);

        // Admin3/Admin4: filter by their warehouse
        $userWhId = ($role === 'admin3' || $role === 'admin4')
            ? WarehouseConfig::getAllowedId($role)
            : null;

        // Only show root transactions (not additional/child transactions)
        $query = Transaction::with(['user', 'details.product', 'additionalTransactions', 'customer'])
            ->whereNull('parent_transaction_id')
            ->when($isOwnOnly, fn ($q) => $q->where('user_id', Auth::id()))
            ->when($userWhId, fn ($q) => $q->whereHas('details', fn ($d) => $d->where('warehouse_id', $userWhId)))
            ->latest();

        // Filters
        if ($request->search) {
            $sanitizedSearch = SearchSanitizer::sanitize($request->search);
            $query->where(function ($q) use ($request, $sanitizedSearch) {
                $q->where('id', $request->search)
                    ->orWhereHas('user', fn ($q2) => $q2->where('name', 'like', "%{$sanitizedSearch}%"))
                    ->orWhereHas('customer', fn ($q2) => $q2->where('name', 'like', "%{$sanitizedSearch}%"));
            });
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
        // Filter by customer type (eceran/grosir)
        if ($request->customer_type) {
            $query->whereHas('customer', fn ($q) => $q->where('category', $request->customer_type));
        }
        // Filter by sale type (eceran/grosir) - dari kasir
        if ($request->sale_type) {
            $query->where('sale_type', $request->sale_type);
        }
        // Filter by cashier (supervisor only)
        if ($request->user_id && ! $isOwnOnly) {
            $query->where('user_id', $request->user_id);
        }

        $transactions = $query->paginate(25)->withQueryString();

        // Summary cards (for current filter scope)
        // Summary only counts root transactions (same scope as main query)
        $summaryQuery = Transaction::query()->whereNull('parent_transaction_id')
            ->when($isOwnOnly, fn ($q) => $q->where('user_id', Auth::id()));
        if ($request->date_from) {
            $summaryQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $summaryQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->payment_method) {
            $summaryQuery->where('payment_method', $request->payment_method);
        }
        if ($request->sale_type) {
            $summaryQuery->where('sale_type', $request->sale_type);
        }
        if ($request->user_id && ! $isOwnOnly) {
            $summaryQuery->where('user_id', $request->user_id);
        }
        if ($request->status) {
            $summaryQuery->where('status', $request->status);
        }
        if ($request->customer_type) {
            $summaryQuery->whereHas('customer', fn ($q) => $q->where('category', $request->customer_type));
        }

        $totalRevenue = (clone $summaryQuery)->where('status', 'completed')->sum('total_amount');
        $totalCount = (clone $summaryQuery)->where('status', 'completed')->count();

        // Today stats: only root transactions, completed only
        $todayRevenue = Transaction::whereNull('parent_transaction_id')
            ->when($isOwnOnly, fn ($q) => $q->where('user_id', Auth::id()))
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');
        $todayCount = Transaction::whereNull('parent_transaction_id')
            ->when($isOwnOnly, fn ($q) => $q->where('user_id', Auth::id()))
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        // Kasir users (for supervisor filter dropdown)
        $kasirUsers = $isOwnOnly ? collect() : \App\Models\User::whereIn('role', ['supervisor', 'admin1', 'admin2'])->orderBy('name')->get(['id', 'name', 'role']);

        return view('transaksi.index', compact(
            'transactions', 'totalRevenue', 'totalCount', 'todayRevenue', 'todayCount', 'kasirUsers'
        ));
    }

    public function show(Transaction $transaksi)
    {
        // Load all required relations including return history
        $transaksi->load([
            'user',
            'customer',
            'vehicle',
            'details.product.category',
            'details.warehouse',
            'additionalTransactions.details.product.category',
            'returns.items.product',
            'returns.user',
        ]);

        // Collect IDs of detail rows that have been returned (for UI indicators)
        $returnedDetailIds = $transaksi->returns
            ->filter(fn ($r) => $r->status === 'completed')
            ->flatMap(fn ($r) => $r->items->pluck('transaction_detail_id'))
            ->unique()
            ->values()
            ->toArray();

        $hasReturns = count($returnedDetailIds) > 0;

        return view('transaksi.show', compact('transaksi', 'returnedDetailIds', 'hasReturns'));
    }

    /**
     * Void transaksi: kembalikan stok, batalkan CustomerCredit jika ada.
     */
    public function destroy(Transaction $transaksi)
    {
        // Admin3/Admin4 are view-only — cannot void transactions
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if (in_array($role, ['admin3', 'admin4'], true)) {
            abort(403, 'Anda tidak memiliki izin untuk membatalkan transaksi.');
        }

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

            $transaksi->load(['details', 'additionalTransactions.details']);

            $details = $transaksi->details;
            $additionalTransactions = $transaksi->additionalTransactions;
            $allDetailsMissingWarehouse = $details->every(fn ($d) => empty($d->warehouse_id));

            if ($allDetailsMissingWarehouse) {
                $movementRows = StockMovement::query()
                    ->selectRaw('product_id, warehouse_id, location_id, SUM(quantity) as qty')
                    ->where('source_type', 'pos_transaction')
                    ->where(function ($q) use ($transaksi) {
                        $q->where('reference_number', 'TRX-'.$transaksi->id)
                          ->orWhere('reference_number', 'POS-GROSIR-'.$transaksi->id)
                          ->orWhere('reference_number', 'POS-ECERAN-'.$transaksi->id);
                    })
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
                                ->orderBy('stock', 'desc')
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

                    // Try to restore stock to the original batch/location via StockMovement
                    $originalMovement = null;
                    if ($warehouseId) {
                        $originalMovement = StockMovement::query()
                            ->where('product_id', $detail->product_id)
                            ->where('warehouse_id', $warehouseId)
                            ->where('source_type', 'pos_transaction')
                            ->whereIn('reference_number', [
                                'TRX-'.$transaksi->id,
                                'POS-ECERAN-'.$transaksi->id,
                                'POS-GROSIR-'.$transaksi->id,
                            ])
                            ->where('type', 'out')
                            ->where('quantity', $detail->quantity)
                            ->first();
                    }

                    if ($originalMovement) {
                        // Restore to the exact batch/location
                        $stock = ProductStock::query()
                            ->where('product_id', $detail->product_id)
                            ->where('warehouse_id', $originalMovement->warehouse_id)
                            ->where('location_id', $originalMovement->location_id)
                            ->where('batch_number', $originalMovement->batch_number)
                            ->where('expired_date', $originalMovement->expired_date)
                            ->lockForUpdate()
                            ->first();

                        if (! $stock) {
                            $stock = ProductStock::create([
                                'product_id' => $detail->product_id,
                                'warehouse_id' => $originalMovement->warehouse_id,
                                'location_id' => $originalMovement->location_id,
                                'batch_number' => $originalMovement->batch_number,
                                'expired_date' => $originalMovement->expired_date,
                                'stock' => 0,
                            ]);
                        }

                        $stock->stock += $detail->quantity;
                        $stock->save();

                        $warehouseId = $originalMovement->warehouse_id;
                    } else {
                        // Fallback: find or create generic ProductStock for this warehouse
                        if (! $warehouseId) {
                            $warehouseId = ProductStock::where('product_id', $detail->product_id)
                                ->orderBy('created_at', 'asc')
                                ->value('warehouse_id');
                        }

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
                    }

                    StockMovement::create([
                        'product_id' => $detail->product_id,
                        'warehouse_id' => $warehouseId,
                        'location_id' => $originalMovement?->location_id ?? null,
                        'batch_number' => $originalMovement?->batch_number ?? null,
                        'expired_date' => $originalMovement?->expired_date ?? null,
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

            // ── 3. Void semua transaksi tambahan dan kembalikan stok ──
            foreach ($additionalTransactions as $addTrans) {
                if ($addTrans->status !== 'voided') {
                    foreach ($addTrans->details as $detail) {
                        Product::where('id', $detail->product_id)
                            ->increment('stock', $detail->quantity);

                        $warehouseId = $detail->warehouse_id;

                        // Restore to original batch/location via StockMovement
                        $originalMovement = null;
                        if ($warehouseId) {
                            $originalMovement = StockMovement::query()
                                ->where('product_id', $detail->product_id)
                                ->where('warehouse_id', $warehouseId)
                                ->where('source_type', 'pos_transaction')
                                ->whereIn('reference_number', [
                                    'TRX-'.$addTrans->id,
                                    'POS-ECERAN-'.$addTrans->id,
                                    'POS-GROSIR-'.$addTrans->id,
                                ])
                                ->where('type', 'out')
                                ->where('quantity', $detail->quantity)
                                ->first();
                        }

                        $stock = null;
                        if ($originalMovement) {
                            $stock = ProductStock::query()
                                ->where('product_id', $detail->product_id)
                                ->where('warehouse_id', $originalMovement->warehouse_id)
                                ->where('location_id', $originalMovement->location_id)
                                ->where('batch_number', $originalMovement->batch_number)
                                ->where('expired_date', $originalMovement->expired_date)
                                ->lockForUpdate()
                                ->first();

                            if (! $stock) {
                                $stock = ProductStock::create([
                                    'product_id' => $detail->product_id,
                                    'warehouse_id' => $originalMovement->warehouse_id,
                                    'location_id' => $originalMovement->location_id,
                                    'batch_number' => $originalMovement->batch_number,
                                    'expired_date' => $originalMovement->expired_date,
                                    'stock' => 0,
                                ]);
                            }

                            $stock->stock += $detail->quantity;
                            $stock->save();

                            $warehouseId = $originalMovement->warehouse_id;
                        } else {
                            if (! $warehouseId) {
                                $warehouseId = ProductStock::where('product_id', $detail->product_id)
                                    ->orderBy('created_at', 'asc')
                                    ->value('warehouse_id');
                            }

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
                        }

                        StockMovement::create([
                            'product_id' => $detail->product_id,
                            'warehouse_id' => $warehouseId,
                            'location_id' => $originalMovement?->location_id ?? null,
                            'batch_number' => $originalMovement?->batch_number ?? null,
                            'expired_date' => $originalMovement?->expired_date ?? null,
                            'type' => 'in',
                            'source_type' => 'void_transaction',
                            'reference_number' => 'VOID-TRX-'.$addTrans->id,
                            'quantity' => $detail->quantity,
                            'balance' => ($stock->stock ?? 0),
                            'notes' => '[VOID] Pembatalan Transaksi Tambahan #'.$addTrans->id.' (Parent: #'.$transaksi->id.')',
                            'user_id' => Auth::id(),
                        ]);
                    }

                    $addTrans->update(['status' => 'voided']);
                }
            }

            // ── 4. Tandai transaksi utama sebagai void ────────────────
            $transaksi->update(['status' => 'voided']);

            // ── 5. Batalkan Pick Order yang terhubung ─────────────────
            if (class_exists(\App\Models\PosPickOrder::class)) {
                $pickOrders = \App\Models\PosPickOrder::where('transaction_id', $transaksi->id)
                    ->whereNotIn('status', ['cancelled'])
                    ->get();
                foreach ($pickOrders as $pickOrder) {
                    $pickOrder->update([
                        'status' => 'cancelled',
                        'notes'  => ($pickOrder->notes ? $pickOrder->notes . ' | ' : '') .
                            '[VOID] Transaksi #' . $transaksi->id . ' dibatalkan oleh ' . Auth::user()->name . ' pada ' . now()->format('d/m/Y H:i'),
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', 'Transaksi #' . $transaksi->id . ' berhasil dibatalkan (void). Stok, piutang pelanggan, dan pick order telah diperbarui.');

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

    /**
     * Laporan Barang Terjual — menampilkan detail produk yang terjual dari seluruh transaksi.
     */
    public function soldItems(Request $request)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));

        // Admin1/admin2 hanya bisa melihat data penjualan mereka sendiri
        $isOwnOnly = in_array($role, ['admin1', 'admin2'], true);

        // Admin3/Admin4: filter by their warehouse
        $userWhId = ($role === 'admin3' || $role === 'admin4')
            ? WarehouseConfig::getAllowedId($role)
            : null;

        $query = \App\Models\TransactionDetail::with(['product.category', 'transaction.user', 'transaction.customer', 'warehouse'])
            ->whereHas('transaction', function ($q) use ($isOwnOnly) {
                $q->where('status', 'completed');
                if ($isOwnOnly) {
                    $q->where('user_id', Auth::id());
                }
            })
            ->orderBy('transaction_details.created_at', 'desc');

        if ($userWhId) {
            $query->where('transaction_details.warehouse_id', $userWhId);
        }

        if ($request->date_from) {
            $query->whereHas('transaction', fn ($q) => $q->whereDate('created_at', '>=', $request->date_from));
        }
        if ($request->date_to) {
            $query->whereHas('transaction', fn ($q) => $q->whereDate('created_at', '<=', $request->date_to));
        }
        if ($request->sale_type) {
            $query->whereHas('transaction', fn ($q) => $q->where('sale_type', $request->sale_type));
        }
        if ($request->user_id && ! $isOwnOnly) {
            $query->whereHas('transaction', fn ($q) => $q->where('user_id', $request->user_id));
        }
        if ($request->search) {
            $sanitizedSearch = SearchSanitizer::sanitize($request->search);
            $query->where(function ($q) use ($sanitizedSearch) {
                $q->whereHas('product', fn ($q2) => $q2->where('name', 'like', "%{$sanitizedSearch}%"));
            });
        }

        $summaryBase = \App\Models\TransactionDetail::query()
            ->whereHas('transaction', function ($q) use ($isOwnOnly, $request) {
                $q->where('status', 'completed');
                if ($isOwnOnly) {
                    $q->where('user_id', Auth::id());
                }
                if ($request->date_from) {
                    $q->whereDate('created_at', '>=', $request->date_from);
                }
                if ($request->date_to) {
                    $q->whereDate('created_at', '<=', $request->date_to);
                }
                if ($request->sale_type) {
                    $q->where('sale_type', $request->sale_type);
                }
                if ($request->user_id && ! $isOwnOnly) {
                    $q->where('user_id', $request->user_id);
                }
            });

        if ($userWhId) {
            $summaryBase->where('transaction_details.warehouse_id', $userWhId);
        }

        if ($request->search) {
            $sanitizedSearch = SearchSanitizer::sanitize($request->search);
            $summaryBase->where(function ($q) use ($sanitizedSearch) {
                $q->whereHas('product', fn ($q2) => $q2->where('name', 'like', "%{$sanitizedSearch}%"));
            });
        }

        $totalQty = (clone $summaryBase)->sum(\DB::raw('COALESCE(unit_qty, quantity)'));
        $totalRevenue = (clone $summaryBase)->sum('subtotal');
        $totalRows = (clone $summaryBase)->count();

        $items = $query->paginate(30)->withQueryString();

        // Per-kasir revenue summary (supervisor only)
        $perKasir = collect();
        if (! $isOwnOnly) {
            $baseQ = \App\Models\Transaction::where('status', 'completed')->whereNull('parent_transaction_id');
            if ($request->date_from) {
                $baseQ->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $baseQ->whereDate('created_at', '<=', $request->date_to);
            }
            if ($request->sale_type) {
                $baseQ->where('sale_type', $request->sale_type);
            }
            if ($request->user_id) {
                $baseQ->where('user_id', $request->user_id);
            }
            if ($userWhId) {
                $baseQ->where('source_warehouse_id', $userWhId);
            }
            $perKasir = (clone $baseQ)
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->select('users.id as user_id', 'users.name', 'users.role',
                    DB::raw('COUNT(transactions.id) as trx_count'),
                    DB::raw('SUM(transactions.total_amount) as revenue'))
                ->groupBy('users.id', 'users.name', 'users.role')
                ->orderBy('revenue', 'desc')
                ->get();
        }

        // Kasir users (for filter dropdown)
        $kasirUsers = $isOwnOnly ? collect() : \App\Models\User::whereIn('role', ['supervisor', 'admin1', 'admin2'])->orderBy('name')->get(['id', 'name', 'role']);

        return view('transaksi.barang_terjual', compact(
            'items', 'totalQty', 'totalRevenue', 'totalRows', 'kasirUsers', 'perKasir'
        ));
    }
}

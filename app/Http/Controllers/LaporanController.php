<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\StockOpnameSession;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Warehouse;
use App\Support\Export\TabularExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaporanController extends Controller
{
    private function shouldMaskStock(): bool
    {
        $user = auth()->user();
        $role = strtolower((string) ($user?->role ?? ''));

        // Admin3 dan admin4 selalu di-mask untuk mencegah manipulasi saat opname
        return in_array($role, ['admin3', 'admin4'], true);
    }

    public function pembelian(Request $request)
    {
        $isPrint = $request->boolean('print');
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $baseQuery = PurchaseOrder::whereBetween('order_date', [$dateFrom, $dateTo]);

        if ($request->supplier_id) {
            $baseQuery->where('supplier_id', $request->supplier_id);
        }
        if ($request->status) {
            $baseQuery->where('status', $request->status);
        }

        $totalOrders = (clone $baseQuery)->count();
        $totalAmount = (clone $baseQuery)->where('status', '!=', 'cancelled')->sum('total_amount');
        $totalReceived = (clone $baseQuery)->where('status', 'received')->sum('total_amount');
        $totalPending = (clone $baseQuery)->whereIn('status', ['draft', 'ordered'])->sum('total_amount');

        $statusCounts = (clone $baseQuery)
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('status')
            ->pluck(DB::raw('COUNT(*)'), 'status');

        $bySupplier = (clone $baseQuery)
            ->where('status', '!=', 'cancelled')
            ->select('supplier_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
            ->with('supplier:id,name')
            ->groupBy('supplier_id')
            ->orderByDesc('amount')
            ->take(10)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->supplier?->name ?? 'Unknown',
                'count' => $row->count,
                'amount' => $row->amount,
            ]);

        $perPage = $isPrint ? 5000 : 25;
        $orders = (clone $baseQuery)
            ->with(['supplier:id,name', 'user:id,name'])
            ->withCount('items')
            ->orderBy('order_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $suppliers = Supplier::where('active', true)->orderBy('name')->get(['id', 'name']);

        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-pembelian-'.$dateFrom.'-sd-'.$dateTo.'.'.$export;
            $headers = [
                'po_number',
                'tanggal_order',
                'supplier',
                'status',
                'total_amount',
                'jumlah_item',
                'dibuat_oleh',
            ];

            $rows = (function () use ($baseQuery) {
                $list = (clone $baseQuery)
                    ->with(['supplier:id,name', 'user:id,name'])
                    ->withCount('items')
                    ->orderBy('order_date', 'desc')
                    ->get();

                foreach ($list as $o) {
                    $statusLabel = match ($o->status) {
                        'draft' => 'Draft',
                        'ordered' => 'Dipesan',
                        'partial' => 'Diterima Sebagian',
                        'received' => 'Diterima Penuh',
                        'cancelled' => 'Dibatalkan',
                        default => $o->status ?? '',
                    };
                    yield [
                        $o->po_number,
                        optional($o->order_date)->format('Y-m-d'),
                        $o->supplier?->name ?? '',
                        $statusLabel,
                        (float) ($o->total_amount ?? 0),
                        (int) ($o->items_count ?? 0),
                        $o->user?->name ?? '',
                    ];
                }
            })();

            return $export === 'csv'
                ? TabularExport::streamCsv($filename, $headers, $rows)
                : TabularExport::streamXlsx($filename, $headers, $rows);
        }

        return view('laporan.pembelian', compact(
            'orders',
            'suppliers',
            'totalOrders',
            'totalAmount',
            'totalReceived',
            'totalPending',
            'bySupplier',
            'statusCounts',
            'dateFrom',
            'dateTo',
            'isPrint'
        ));
    }

    public function penjualan(Request $request)
    {
        $isPrint = $request->boolean('print');
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        $baseQuery = Transaction::where('status', 'completed')
            ->whereNull('parent_transaction_id')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($request->payment_method) {
            $baseQuery->where('payment_method', $request->payment_method);
        }
        if ($request->kasir_id) {
            $baseQuery->where('user_id', $request->kasir_id);
        }

        $totalTrx = (clone $baseQuery)->count();

        $rootIds = (clone $baseQuery)->pluck('id');

        $rootOmzet = (clone $baseQuery)->sum('total_amount');
        $childOmzet = Transaction::where('status', 'completed')
            ->whereIn('parent_transaction_id', $rootIds)
            ->sum('total_amount');
        $totalOmzet = $rootOmzet + $childOmzet;
        $avgPerTrx = $totalTrx > 0 ? $totalOmzet / $totalTrx : 0;

        $childTrxIds = Transaction::where('status', 'completed')
            ->whereIn('parent_transaction_id', $rootIds)
            ->pluck('id');
        $allTrxIds = $rootIds->merge($childTrxIds);

        $totalItems = TransactionDetail::whereIn('transaction_id', $allTrxIds)->sum('quantity');

        $childAmountByParent = Transaction::where('status', 'completed')
            ->whereIn('parent_transaction_id', $rootIds)
            ->select('parent_transaction_id', DB::raw('SUM(total_amount) as child_total'))
            ->groupBy('parent_transaction_id')
            ->pluck('child_total', 'parent_transaction_id');

        $dailyRootAmounts = (clone $baseQuery)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        $dailyChildAmounts = Transaction::where('transactions.status', 'completed')
            ->whereIn('transactions.parent_transaction_id', $rootIds)
            ->join('transactions as parents', 'transactions.parent_transaction_id', '=', 'parents.id')
            ->selectRaw('DATE(parents.created_at) as date, SUM(transactions.total_amount) as total')
            ->groupBy(DB::raw('DATE(parents.created_at)'))
            ->pluck('total', 'date');

        $dailyData = collect();
        $allDates = $dailyRootAmounts->keys()->merge($dailyChildAmounts->keys())->unique()->sort()->values();
        foreach ($allDates as $date) {
            $dailyData[$date] = ($dailyRootAmounts[$date] ?? 0) + ($dailyChildAmounts[$date] ?? 0);
        }

        $rootTrxForAgg = (clone $baseQuery)->get(['id', 'payment_method', 'user_id', 'total_amount']);

        $paymentAgg = [];
        $cashierAgg = [];
        foreach ($rootTrxForAgg as $rt) {
            $total = (float) $rt->total_amount + (float) ($childAmountByParent[$rt->id] ?? 0);

            $pm = $rt->payment_method ?? '-';
            if (!isset($paymentAgg[$pm])) {
                $paymentAgg[$pm] = ['count' => 0, 'amount' => 0];
            }
            $paymentAgg[$pm]['count']++;
            $paymentAgg[$pm]['amount'] += $total;

            $uid = $rt->user_id;
            if (!isset($cashierAgg[$uid])) {
                $cashierAgg[$uid] = ['count' => 0, 'amount' => 0];
            }
            $cashierAgg[$uid]['count']++;
            $cashierAgg[$uid]['amount'] += $total;
        }

        $byPayment = collect($paymentAgg)
            ->map(fn ($data, $method) => [
                'method' => $method,
                'label' => match (strtolower($method)) {
                    'cash', 'tunai' => 'Tunai',
                    'transfer' => 'Transfer',
                    'qris' => 'QRIS',
                    'debit' => 'Kartu Debit',
                    'kredit' => 'Limit Pelanggan',
                    default => ucfirst($method),
                },
                'count' => $data['count'],
                'amount' => $data['amount'],
            ])
            ->sortByDesc('amount')
            ->values();

        $topProducts = TransactionDetail::with('product:id,name,sku')
            ->whereIn('transaction_id', $allTrxIds)
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        $userNames = User::whereIn('id', array_keys($cashierAgg))
            ->pluck('name', 'id');
        $byCashier = collect($cashierAgg)
            ->map(fn ($data, $userId) => [
                'name' => $userNames[$userId] ?? 'Unknown',
                'count' => $data['count'],
                'amount' => $data['amount'],
            ])
            ->sortByDesc('amount')
            ->values();

        $perPage = $isPrint ? 5000 : 25;
        $transactions = (clone $baseQuery)
            ->with(['user:id,name', 'details', 'additionalTransactions.details'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $kasirs = User::whereIn('role', ['supervisor', 'admin1', 'admin2'])->orderBy('name')->get(['id', 'name']);

        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-penjualan-'.$dateFrom.'-sd-'.$dateTo.'.'.$export;
            $headers = [
                'trx_id',
                'tanggal',
                'waktu',
                'kasir',
                'metode',
                'total',
                'bayar',
                'kembalian',
                'jumlah_item',
            ];

            $rows = (function () use ($baseQuery) {
                $list = (clone $baseQuery)
                    ->with(['user:id,name', 'details', 'additionalTransactions.details'])
                    ->orderBy('created_at', 'desc')
                    ->get();

                foreach ($list as $t) {
                    $itemCount = $t->details->count();
                    foreach ($t->additionalTransactions as $at) {
                        $itemCount += $at->details->count();
                    }
                    yield [
                        (int) $t->id,
                        optional($t->created_at)->format('Y-m-d'),
                        optional($t->created_at)->format('H:i:s'),
                        $t->user?->name ?? '',
                        (string) ($t->payment_method ?? ''),
                        (float) $t->grand_total,
                        (float) $t->total_paid,
                        (float) ($t->total_paid - $t->grand_total),
                        $itemCount,
                    ];
                }
            })();

            return $export === 'csv'
                ? TabularExport::streamCsv($filename, $headers, $rows)
                : TabularExport::streamXlsx($filename, $headers, $rows);
        }

        return view('laporan.penjualan', compact(
            'transactions',
            'kasirs',
            'totalTrx',
            'totalOmzet',
            'avgPerTrx',
            'totalItems',
            'dailyData',
            'byPayment',
            'topProducts',
            'byCashier',
            'dateFrom',
            'dateTo',
            'isPrint'
        ));
    }

    public function stok(Request $request)
    {
        $isPrint     = $request->boolean('print');
        $warehouseId = $request->warehouse_id;
        $categoryId  = $request->category_id;
        $search      = $request->search;

        // ── Summary KPI cards ─────────────────────────────────────────
        $totalProducts   = Product::count();
        $totalStockQty   = ProductStock::where('stock', '>', 0)->sum('stock');

        // Low stock: bandingkan total stok produk (products.stock) dengan min_stok
        // Lebih akurat daripada mengecek per baris product_stocks
        $lowStockCount = Product::where('stock', '>', 0)
            ->where('min_stock', '>', 0)
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();

        $today = Carbon::today();
        $expiredCount = ProductStock::whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->where('expired_date', '<', $today)
            ->count();

        $nearExpiredCount = ProductStock::whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->whereBetween('expired_date', [$today, (clone $today)->addDays(30)])
            ->count();

        $totalStockValue = DB::table('product_stocks')
            ->join('products as p', 'product_stocks.product_id', '=', 'p.id')
            ->where('product_stocks.stock', '>', 0)
            ->selectRaw('COALESCE(SUM(product_stocks.stock * COALESCE(p.purchase_price, 0)), 0) as val')
            ->value('val') ?? 0;

        // ── Per-warehouse stock summary (sidebar) ────────────────────
        $warehouses = Warehouse::withCount([
                'productStocks as stock_lines' => fn ($q) => $q->where('stock', '>', 0),
            ])
            ->withSum([
                'productStocks as total_qty' => fn ($q) => $q->where('stock', '>', 0),
            ], 'stock')
            ->orderBy('name')
            ->get();

        // ── Product stock table (paginated) ───────────────────────────
        $stockQuery = Product::with(['category', 'unit'])
            ->where('stock', '>', 0)
            ->when($search, fn ($q) => $q->where(function ($sq) use ($search) {
                $sq->where('name', 'like', "%{$search}%")
                   ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId));

        if ($warehouseId) {
            $stockQuery->whereHas('productStocks', fn ($q) => $q->where('warehouse_id', $warehouseId)->where('stock', '>', 0));
        }

        $perPage = $isPrint ? 5000 : 25;
        $products = $stockQuery->orderBy('stock', 'desc')->paginate($perPage)->withQueryString();

        // ── Per-product stok di masing-masing gudang ──────────────────
        $productIds = $products->pluck('id');
        $warehouseStocks = ProductStock::with('warehouse:id,name,code')
            ->whereIn('product_id', $productIds)
            ->where('stock', '>', 0)
            ->when($warehouseId, fn ($q) => $q->where('warehouse_id', $warehouseId))
            ->selectRaw('product_id, warehouse_id, SUM(stock) as total_stock')
            ->groupBy('product_id', 'warehouse_id')
            ->get()
            ->groupBy('product_id');

        // ── Stok menipis sidebar widget ──────────────────────────────
        // Ambil produk dengan rasio stock/min_stok paling kecil (paling kritis)
        $lowStockProducts = Product::with(['category'])
            ->where('stock', '>', 0)
            ->where('min_stock', '>', 0)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderByRaw('(stock / NULLIF(min_stock, 0)) ASC')
            ->take(10)
            ->get();

        // ── Aktivitas stok terbaru ───────────────────────────────────
        $recentMovements = StockMovement::with(['product:id,name', 'warehouse:id,name'])
            ->latest()
            ->take(12)
            ->get();

        // ── Kategori untuk filter dropdown ───────────────────────────
        $categories = \App\Models\Category::orderBy('name')->get();

        // ── Stock masking untuk role tertentu ────────────────────────
        $maskStock = $this->shouldMaskStock();

        // ── Export CSV / XLSX ────────────────────────────────────────
        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.' . $export;
            $headers  = [
                'sku', 'nama_produk', 'kategori', 'satuan',
                'stok_global', 'min_stok', 'status', 'nilai_stok',
            ];

            $rows = (function () use ($stockQuery, $maskStock) {
                $list = (clone $stockQuery)
                    ->with(['category:id,name', 'unit:id,name,abbreviation'])
                    ->orderBy('stock', 'desc')
                    ->get();

                foreach ($list as $p) {
                    $stock   = $maskStock ? 0 : (float) ($p->stock ?? 0);
                    $minStok = $maskStock ? 0 : (float) ($p->min_stock ?? 0);
                    $nilai   = $maskStock ? 0 : (float) (($p->stock ?? 0) * ($p->purchase_price ?? 0));
                    $isLow   = !$maskStock && ($p->min_stock ?? 0) > 0 && $p->stock <= $p->min_stock;
                    $status  = $stock <= 0 ? 'HABIS' : ($isLow ? 'MENIPIS' : 'AMAN');

                    yield [
                        (string) ($p->sku ?? ''),
                        (string) ($p->name ?? ''),
                        (string) ($p->category?->name ?? ''),
                        (string) ($p->unit?->abbreviation ?? ''),
                        $stock,
                        $minStok,
                        $status,
                        $nilai,
                    ];
                }
            })();

            return $export === 'csv'
                ? TabularExport::streamCsv($filename, $headers, $rows)
                : TabularExport::streamXlsx($filename, $headers, $rows);
        }

        return view('laporan.stok', compact(
            'totalProducts', 'totalStockQty', 'totalStockValue',
            'lowStockCount', 'expiredCount', 'nearExpiredCount',
            'warehouses', 'products', 'lowStockProducts', 'recentMovements',
            'categories', 'warehouseId', 'categoryId', 'search',
            'warehouseStocks', 'maskStock', 'isPrint'
        ));
    }

    public function keuangan(Request $request)
    {
        $isPrint = $request->boolean('print');
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;

        // Validasi tanggal: fallback ke awal/akhir bulan jika tidak valid
        try {
            $dateFrom = $dateFrom ? Carbon::parse($dateFrom)->format('Y-m-d') : now()->startOfMonth()->format('Y-m-d');
            $dateTo   = $dateTo   ? Carbon::parse($dateTo)->format('Y-m-d')   : now()->format('Y-m-d');
        } catch (\Exception $e) {
            $dateFrom = now()->startOfMonth()->format('Y-m-d');
            $dateTo   = now()->format('Y-m-d');
        }

        if ($dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        // ── Pendapatan dari transaksi induk (parent) ─────────────────
        $rootTrxQuery = Transaction::where('status', 'completed')
            ->whereNull('parent_transaction_id')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        $rootIds   = (clone $rootTrxQuery)->pluck('id');
        $rootRev   = (clone $rootTrxQuery)->sum('total_amount');

        // Pendapatan dari transaksi anak (additional transactions)
        $childRev = Transaction::where('status', 'completed')
            ->whereIn('parent_transaction_id', $rootIds)
            ->sum('total_amount');

        $totalRevenue = $rootRev + $childRev;

        // ── HPP (Harga Pokok Penjualan) ───────────────────────────────
        // Dihitung dari purchase_price produk saat ini (approksimasi)
        $allTrxIds = $rootIds->merge(
            Transaction::where('status', 'completed')
                ->whereIn('parent_transaction_id', $rootIds)
                ->pluck('id')
        );

        $totalHPP = 0;
        if ($allTrxIds->isNotEmpty()) {
            $totalHPP = TransactionDetail::whereIn('transaction_id', $allTrxIds)
                ->join('products', 'transaction_details.product_id', '=', 'products.id')
                ->selectRaw('SUM(transaction_details.quantity * COALESCE(products.purchase_price, 0)) as hpp')
                ->value('hpp') ?? 0;
        }

        // ── Biaya Operasional ─────────────────────────────────────────
        $totalOperasional = 0;
        if (class_exists(\App\Models\OperationalExpense::class)) {
            $totalOperasional = \App\Models\OperationalExpense::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->sum('amount');
        }

        // ── Total pengeluaran: PO diterima + Biaya Operasional ────────
        $totalPoReceived = PurchaseOrder::whereIn('status', ['received', 'partial'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->sum('total_amount');

        $totalPengeluaran = $totalPoReceived + $totalOperasional;

        // ── Laba Kotor = Revenue - HPP ────────────────────────────────
        $labaKotor = $totalRevenue - $totalHPP;

        // ── Laba Bersih = Laba Kotor - (PO + Biaya Operasional) ──────
        $netProfit = $labaKotor - $totalPengeluaran;

        // ── Daily chart data ──────────────────────────────────────────
        // Revenue harian (parent + child)
        $dailyParent = (clone $rootTrxQuery)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        $dailyChild = collect();
        if ($rootIds->isNotEmpty()) {
            $dailyChild = Transaction::where('transactions.status', 'completed')
                ->whereIn('transactions.parent_transaction_id', $rootIds)
                ->join('transactions as parents', 'transactions.parent_transaction_id', '=', 'parents.id')
                ->selectRaw('DATE(parents.created_at) as date, SUM(transactions.total_amount) as total')
                ->groupBy(DB::raw('DATE(parents.created_at)'))
                ->pluck('total', 'date');
        }

        // Pengeluaran harian (PO + biaya operasional)
        $dailyPo = PurchaseOrder::whereIn('status', ['received', 'partial'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date');

        $dailyOps = collect();
        if (class_exists(\App\Models\OperationalExpense::class)) {
            $dailyOps = \App\Models\OperationalExpense::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->pluck('total', 'date');
        }

        $dates = collect();
        $start = Carbon::parse($dateFrom);
        $end   = Carbon::parse($dateTo);
        while ($start->lte($end)) {
            $ds  = $start->format('Y-m-d');
            $rev = (float) ($dailyParent->get($ds, 0)) + (float) ($dailyChild->get($ds, 0));
            $exp = (float) ($dailyPo->get($ds, 0)) + (float) ($dailyOps->get($ds, 0));
            $dates->push([
                'date'     => $start->format('d M Y'),
                'revenue'  => $rev,
                'expense'  => $exp,
                'profit'   => $rev - $exp,
            ]);
            $start->addDay();
        }

        // ── Export CSV / XLSX ────────────────────────────────────────
        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-keuangan-'.$dateFrom.'-sd-'.$dateTo.'.'.$export;
            $headers  = ['tanggal', 'pendapatan', 'pengeluaran', 'laba_bersih'];

            $rows = (function () use ($dates) {
                foreach ($dates as $row) {
                    yield [
                        (string) $row['date'],
                        (float)  $row['revenue'],
                        (float)  $row['expense'],
                        (float)  $row['profit'],
                    ];
                }
            })();

            return $export === 'csv'
                ? TabularExport::streamCsv($filename, $headers, $rows)
                : TabularExport::streamXlsx($filename, $headers, $rows);
        }

        return view('laporan.keuangan', compact(
            'totalRevenue', 'totalHPP', 'totalOperasional',
            'totalPoReceived', 'totalPengeluaran',
            'labaKotor', 'netProfit', 'dates',
            'dateFrom', 'dateTo', 'isPrint'
        ));
    }

    public function pelanggan(Request $request)
    {
        $isPrint = $request->boolean('print');
        $search = $request->search;

        $totalCustomers    = \App\Models\Customer::count();
        $activeCustomers   = \App\Models\Customer::where('is_active', true)->count();
        $totalDebt         = \App\Models\Customer::sum('current_debt');
        $customersWithDebt = \App\Models\Customer::where('current_debt', '>', 0)->count();
        $avgDebt           = $customersWithDebt > 0
            ? \App\Models\Customer::where('current_debt', '>', 0)->avg('current_debt')
            : 0;

        $query = \App\Models\Customer::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('current_debt')
            ->orderBy('name')
            ->paginate($isPrint ? 5000 : 20)
            ->withQueryString();

        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-pelanggan-'.now()->format('Y-m-d').'.'.$export;
            $headers = [
                'nama',
                'telepon',
                'aktif',
                'limit_piutang',
                'sisa_limit',
                'piutang_berjalan',
            ];

            $exportQuery = \App\Models\Customer::query();
            if ($search) {
                $exportQuery->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $rows = (function () use ($exportQuery) {
                $list = (clone $exportQuery)
                    ->orderByDesc('current_debt')
                    ->orderBy('name')
                    ->get();

                foreach ($list as $c) {
                    yield [
                        (string) ($c->name ?? ''),
                        (string) ($c->phone ?? ''),
                        (bool) ($c->is_active ?? true),
                        (float) ($c->credit_limit ?? 0),
                        (float) ($c->remaining_credit_limit ?? 0),
                        (float) ($c->current_debt ?? 0),
                    ];
                }
            })();

            return $export === 'csv'
                ? TabularExport::streamCsv($filename, $headers, $rows)
                : TabularExport::streamXlsx($filename, $headers, $rows);
        }

        return view('laporan.pelanggan', compact(
            'totalCustomers', 'activeCustomers', 'totalDebt', 'customersWithDebt',
            'avgDebt', 'customers', 'search', 'isPrint'
        ));
    }

    public function supplier(Request $request)
    {
        $isPrint = $request->boolean('print');
        $search = $request->search;

        $totalSuppliers = Supplier::where('active', true)->count();
        $totalDebt = \App\Models\SupplierDebt::whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('total_amount - paid_amount'));

        $suppliersWithDebt = \App\Models\SupplierDebt::whereIn('status', ['unpaid', 'partial'])
            ->distinct('supplier_id')
            ->count('supplier_id');

        $query = Supplier::with(['debts' => function ($q) {
            $q->whereIn('status', ['unpaid', 'partial']);
        }]);

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%");
        }

        $suppliers = $query->orderBy('name')->paginate($isPrint ? 5000 : 20)->withQueryString();

        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-supplier-'.now()->format('Y-m-d').'.'.$export;
            $headers = [
                'nama',
                'kontak_person',
                'telepon',
                'aktif',
                'total_tagihan',
                'sisa_hutang',
            ];

            $rows = (function () use ($query) {
                $list = (clone $query)->orderBy('name')->get();
                foreach ($list as $s) {
                    $outstandingDebts = $s->debts->whereIn('status', ['unpaid', 'partial']);
                    $totalInvoiceAmt = (float) $outstandingDebts->sum('total_amount');
                    $totalRemainingAmt = (float) $outstandingDebts->sum(fn ($d) => $d->total_amount - $d->paid_amount);

                    yield [
                        (string) ($s->name ?? ''),
                        (string) ($s->contact_person ?? ''),
                        (string) ($s->phone ?? ''),
                        (bool) ($s->active ?? true),
                        $totalInvoiceAmt,
                        $totalRemainingAmt,
                    ];
                }
            })();

            return $export === 'csv'
                ? TabularExport::streamCsv($filename, $headers, $rows)
                : TabularExport::streamXlsx($filename, $headers, $rows);
        }

        return view('laporan.supplier', compact(
            'totalSuppliers', 'totalDebt', 'suppliersWithDebt', 'suppliers', 'search', 'isPrint'
        ));
    }
}

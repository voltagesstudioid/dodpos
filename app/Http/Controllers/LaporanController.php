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

        // Summary stats — dihitung di DB level, bukan load semua ke memory
        $totalOrders = (clone $baseQuery)->count();
        $totalAmount = (clone $baseQuery)->sum('total_amount');
        $totalReceived = (clone $baseQuery)->whereIn('status', ['received', 'partial'])->sum('total_amount');
        $totalPending = (clone $baseQuery)->whereIn('status', ['draft', 'ordered'])->sum('total_amount');

        // Top supplier — dihitung di DB level
        $bySupplier = (clone $baseQuery)
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

        // Paginated list — tidak load semua sekaligus
        $perPage = $isPrint ? 5000 : 25;
        $orders = (clone $baseQuery)
            ->with(['supplier:id,name', 'user:id,name'])
            ->orderBy('order_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);

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
                    yield [
                        $o->po_number,
                        optional($o->order_date)->format('Y-m-d'),
                        $o->supplier?->name ?? '',
                        (string) ($o->status ?? ''),
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
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($request->payment_method) {
            $baseQuery->where('payment_method', $request->payment_method);
        }
        if ($request->kasir_id) {
            $baseQuery->where('user_id', $request->kasir_id);
        }

        // Summary stats — dihitung di DB level (tidak load semua transaksi)
        $totalTrx = (clone $baseQuery)->count();
        $totalOmzet = (clone $baseQuery)->sum('total_amount');
        $avgPerTrx = $totalTrx > 0 ? $totalOmzet / $totalTrx : 0;

        // Total items — dihitung via join, bukan N+1
        $totalItems = TransactionDetail::whereHas('transaction', function ($q) use ($dateFrom, $dateTo, $request) {
            $q->where('status', 'completed')
                ->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo);
            if ($request->payment_method) {
                $q->where('payment_method', $request->payment_method);
            }
            if ($request->kasir_id) {
                $q->where('user_id', $request->kasir_id);
            }
        })->sum('quantity');

        // Daily chart data
        $dailyData = (clone $baseQuery)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Payment breakdown — dihitung di DB level
        $byPayment = (clone $baseQuery)
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('payment_method')
            ->get()
            ->map(fn ($row) => [
                'method' => $row->payment_method,
                'label' => match (strtolower($row->payment_method ?? '')) {
                    'cash', 'tunai' => 'Tunai',
                    'transfer', 'qris' => 'Transfer / QRIS',
                    'debit' => 'Kartu Debit',
                    'kredit' => 'Kredit Pelanggan',
                    default => ucfirst($row->payment_method ?? '-'),
                },
                'count' => $row->count,
                'amount' => $row->amount,
            ])
            ->sortByDesc('amount')
            ->values();

        // Top products — sudah efisien (DB aggregation)
        $topProducts = TransactionDetail::with('product:id,name,sku')
            ->whereHas('transaction', function ($q) use ($dateFrom, $dateTo) {
                $q->where('status', 'completed')
                    ->whereDate('created_at', '>=', $dateFrom)
                    ->whereDate('created_at', '<=', $dateTo);
            })
            ->selectRaw('product_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->take(10)
            ->get();

        // Per-cashier stats — dihitung di DB level
        $byCashier = (clone $baseQuery)
            ->select('user_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as amount'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderByDesc('amount')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->user?->name ?? 'Unknown',
                'count' => $row->count,
                'amount' => $row->amount,
            ]);

        // Paginated transaction list
        $perPage = $isPrint ? 5000 : 25;
        $transactions = (clone $baseQuery)
            ->with(['user:id,name', 'details'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $kasirs = User::orderBy('name')->get(['id', 'name']);

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
                    ->with(['user:id,name'])
                    ->withCount('details')
                    ->orderBy('created_at', 'desc')
                    ->get();

                foreach ($list as $t) {
                    yield [
                        (int) $t->id,
                        optional($t->created_at)->format('Y-m-d'),
                        optional($t->created_at)->format('H:i:s'),
                        $t->user?->name ?? '',
                        (string) ($t->payment_method ?? ''),
                        (float) ($t->total_amount ?? 0),
                        (float) ($t->paid_amount ?? 0),
                        (float) ($t->change_amount ?? 0),
                        (int) ($t->details_count ?? 0),
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
        $isPrint = $request->boolean('print');
        $warehouseId = $request->warehouse_id;
        $categoryId  = $request->category_id;
        $search      = $request->search;

        // ── Summary KPI cards ─────────────────────────────────────────
        // Total produk terdaftar (semua, termasuk stok 0)
        $totalProducts = Product::count();

        // Total qty fisik: ambil dari product_stocks agar akurat dan real-time
        $totalStockQty = ProductStock::where('stock', '>', 0)->sum('stock');

        // "Hampir habis" = stock aktif (> 0) tapi sudah di bawah atau sama dengan batas minimum
        // Gunakan DB::table agar tidak ada ambiguitas join dari Eloquent model scopes
        $lowStockCount = DB::table('product_stocks')
            ->join('products', 'product_stocks.product_id', '=', 'products.id')
            ->where('product_stocks.stock', '>', 0)
            ->where('products.min_stock', '>', 0)
            ->whereColumn('product_stocks.stock', '<=', 'products.min_stock')
            ->distinct('product_stocks.product_id')
            ->count('product_stocks.product_id');

        // Batch kadaluarsa: tanggal sudah terlewat (strictly < hari ini)
        $expiredCount = ProductStock::whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->where('expired_date', '<', Carbon::today())
            ->count();

        // Akan expired: hari ini s/d 30 hari ke depan (tidak overlap dengan expired)
        $nearExpiredCount = ProductStock::whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->whereBetween('expired_date', [Carbon::today(), Carbon::today()->copy()->addDays(30)])
            ->count();

        // Nilai stok total (stok × harga beli) — untuk informasi tambahan
        $totalStockValue = DB::table('product_stocks')
            ->join('products as p', 'product_stocks.product_id', '=', 'p.id')
            ->where('product_stocks.stock', '>', 0)
            ->selectRaw('COALESCE(SUM(product_stocks.stock * COALESCE(p.purchase_price, 0)), 0) as val')
            ->value('val') ?? 0;

        // ── Per-warehouse stock summary (sidebar) ────────────────────
        // Hanya hitung record dengan stock > 0 agar angka relevan
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
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId));

        if ($warehouseId) {
            $stockQuery->whereHas('productStocks', fn ($q) => $q->where('warehouse_id', $warehouseId)->where('stock', '>', 0));
        }

        $perPage = $isPrint ? 5000 : 25;
        $products = $stockQuery->orderBy('stock', 'desc')->paginate($perPage)->withQueryString();

        // ── Per-product stok di masing-masing gudang (dari ProductStock) ────
        $productIds = $products->pluck('id');
        $warehouseStocks = ProductStock::with('warehouse:id,name,code')
            ->whereIn('product_id', $productIds)
            ->where('stock', '>', 0)
            ->when($warehouseId, fn ($q) => $q->where('warehouse_id', $warehouseId))
            ->selectRaw('product_id, warehouse_id, SUM(stock) as total_stock')
            ->groupBy('product_id', 'warehouse_id')
            ->get()
            ->groupBy('product_id');

        // ── Stok menipis (sidebar widget) — global, tidak terfilter ──
        // Ambil max 10, urutkan dari yang paling kritis (stok terendah relatif thd min_stock)
        // Gunakan DB::table agar tidak ada ambiguitas join
        $lowStockProductIds = DB::table('product_stocks')
            ->join('products as p2', 'product_stocks.product_id', '=', 'p2.id')
            ->where('product_stocks.stock', '>', 0)
            ->where('p2.min_stock', '>', 0)
            ->whereColumn('product_stocks.stock', '<=', 'p2.min_stock')
            ->orderByRaw('(product_stocks.stock / p2.min_stock) ASC')
            ->distinct('product_stocks.product_id')
            ->pluck('product_stocks.product_id')
            ->take(10);

        $lowStockProducts = Product::with(['category'])
            ->whereIn('id', $lowStockProductIds)
            ->orderByRaw('(stock / NULLIF(min_stock, 0)) ASC')
            ->take(10)
            ->get();

        // ── Aktivitas stok terbaru (sidebar widget) ──────────────────
        $recentMovements = StockMovement::with(['product:id,name', 'warehouse:id,name'])
            ->latest()
            ->take(12)
            ->get();

        // ── Kategori untuk filter dropdown ───────────────────────────
        $categories = \App\Models\Category::orderBy('name')->get();

        $maskStock = $this->shouldMaskStock();

        // ── Export CSV / XLSX ────────────────────────────────────────
        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.' . $export;
            $headers  = [
                'sku',
                'nama_produk',
                'kategori',
                'satuan',
                'stok_global',
                'min_stok',
                'status',
                'nilai_stok',
            ];

            $rows = (function () use ($stockQuery) {
                $list = (clone $stockQuery)
                    ->with(['category:id,name', 'unit:id,name,abbreviation'])
                    ->orderBy('stock', 'desc')
                    ->get();

                foreach ($list as $p) {
                    $isLow  = ($p->min_stock ?? 0) > 0 && $p->stock <= $p->min_stock;
                    $status = $p->stock <= 0 ? 'HABIS' : ($isLow ? 'MENIPIS' : 'AMAN');

                    yield [
                        (string) ($p->sku ?? ''),
                        (string) ($p->name ?? ''),
                        (string) ($p->category?->name ?? ''),
                        (string) ($p->unit?->abbreviation ?? ''),
                        (float)  ($p->stock ?? 0),
                        (float)  ($p->min_stock ?? 0),
                        $status,
                        (float)  (($p->stock ?? 0) * ($p->purchase_price ?? 0)),
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
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // ── Pendapatan (Revenue) dari transaksi POS selesai ──────────
        $totalRevenue = Transaction::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->sum('total_amount');

        // ── HPP (Harga Pokok Penjualan) — dihitung dari purchase_price per item ──
        // Ini lebih akurat daripada menggunakan total PO amount
        $totalHPP = TransactionDetail::whereHas('transaction', function ($q) use ($dateFrom, $dateTo) {
            $q->where('status', 'completed')
                ->whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo);
        })
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->selectRaw('SUM(transaction_details.quantity * COALESCE(products.purchase_price, 0)) as hpp')
            ->value('hpp') ?? 0;

        // ── Biaya Operasional ─────────────────────────────────────────
        $totalOperasional = 0;
        if (class_exists(\App\Models\OperationalExpense::class)) {
            $totalOperasional = \App\Models\OperationalExpense::whereDate('created_at', '>=', $dateFrom)
                ->whereDate('created_at', '<=', $dateTo)
                ->sum('amount');
        }

        // ── Pembelian (PO yang diterima dalam periode ini) ────────────
        $totalPembelian = PurchaseOrder::whereIn('status', ['received', 'partial'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->sum('total_amount');

        // ── Laba Kotor = Revenue - HPP ────────────────────────────────
        $labaKotor = $totalRevenue - $totalHPP;

        // ── Laba Bersih = Laba Kotor - Biaya Operasional ─────────────
        $netProfit = $labaKotor - $totalOperasional;

        // ── Daily chart data ──────────────────────────────────────────
        $dailyRevenue = Transaction::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dailyExpense = PurchaseOrder::whereIn('status', ['received', 'partial'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $dates = collect();
        $start = Carbon::parse($dateFrom);
        $end = Carbon::parse($dateTo);
        while ($start->lte($end)) {
            $dateStr = $start->format('Y-m-d');
            $dates->push([
                'date' => $start->format('d M Y'),
                'revenue' => $dailyRevenue->get($dateStr, 0),
                'expense' => $dailyExpense->get($dateStr, 0),
            ]);
            $start->addDay();
        }

        $export = strtolower((string) $request->query('export', ''));
        if (in_array($export, ['csv', 'xlsx'], true)) {
            $filename = 'laporan-keuangan-'.$dateFrom.'-sd-'.$dateTo.'.'.$export;
            $headers = [
                'tanggal',
                'pendapatan',
                'pengeluaran',
                'laba_bersih',
            ];

            $rows = (function () use ($dates) {
                foreach ($dates as $row) {
                    $profit = (float) $row['revenue'] - (float) $row['expense'];
                    yield [
                        (string) $row['date'],
                        (float) $row['revenue'],
                        (float) $row['expense'],
                        (float) $profit,
                    ];
                }
            })();

            return $export === 'csv'
                ? TabularExport::streamCsv($filename, $headers, $rows)
                : TabularExport::streamXlsx($filename, $headers, $rows);
        }

        return view('laporan.keuangan', compact(
            'totalRevenue', 'totalHPP', 'totalOperasional', 'totalPembelian',
            'labaKotor', 'netProfit', 'dates',
            'dateFrom', 'dateTo', 'isPrint'
        ));
    }

    public function pelanggan(Request $request)
    {
        $isPrint = $request->boolean('print');
        $search = $request->search;

        $totalCustomers = \App\Models\Customer::count();
        $totalDebt = \App\Models\Customer::sum('current_debt');
        $customersWithDebt = \App\Models\Customer::where('current_debt', '>', 0)->count();

        $query = \App\Models\Customer::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
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

            $rows = (function () use ($query) {
                $list = (clone $query)
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
            'totalCustomers', 'totalDebt', 'customersWithDebt', 'customers', 'search', 'isPrint'
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

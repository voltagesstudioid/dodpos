<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    private function getCacheKey(string $type, ?int $userId = null): string
    {
        $userId = $userId ?? Auth::id() ?? 0;
        $date = now()->format('Y-m-d');
        return "dashboard_{$type}_{$userId}_{$date}";
    }
    public function index()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        switch ($user->role) {
            case 'supervisor':
                $weeklySales = Cache::remember($this->getCacheKey('weekly_sales'), 300, function () {
                    $sales = [];
                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i)->toDateString();
                        $label = now()->subDays($i)->locale('id')->isoFormat('ddd');
                        $amount = \App\Models\Transaction::whereDate('created_at', $date)
                            ->where('status', 'completed')
                            ->sum('total_amount');
                        $sales[] = [
                            'label' => $label,
                            'amount' => $amount,
                            'date' => $date,
                        ];
                    }

                    $maxAmount = collect($sales)->max('amount') ?: 1;
                    foreach ($sales as &$sale) {
                        $sale['percentage'] = ($sale['amount'] / $maxAmount) * 100;
                    }

                    return $sales;
                });

                // Notifikasi Penting (cached for 2 minutes)
                $alerts = Cache::remember($this->getCacheKey('alerts'), 120, function () {
                    $alertList = [];
                    
                    // PO Terlambat
                    $latePOs = \App\Models\PurchaseOrder::whereIn('status', ['ordered', 'partial'])
                        ->whereNotNull('expected_date')
                        ->whereDate('expected_date', '<', now())
                        ->count();
                    if ($latePOs > 0) {
                        $alertList[] = [
                            'type' => 'danger',
                            'icon' => '⚠️',
                            'title' => $latePOs . ' PO Terlambat',
                            'message' => 'Purchase order melewati estimasi tanggal terima',
                            'link' => route('pembelian.order', ['status' => 'ordered']),
                        ];
                    }
                    
                    // Hutang Jatuh Tempo
                    $overdueDebts = \App\Models\SupplierDebt::where('status', '!=', 'paid')
                        ->where('due_date', '<', now())
                        ->count();
                    if ($overdueDebts > 0) {
                        $alertList[] = [
                            'type' => 'warning',
                            'icon' => '💳',
                            'title' => $overdueDebts . ' Hutang Jatuh Tempo',
                            'message' => 'Pembayaran ke supplier sudah lewat tanggal jatuh tempo',
                            'link' => route('pembelian.hutang.index', ['status' => 'unpaid']),
                        ];
                    }
                    
                    // Stok Minimum
                    $lowStock = \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count();
                    if ($lowStock > 0) {
                        $alertList[] = [
                            'type' => 'info',
                            'icon' => '📦',
                            'title' => $lowStock . ' Produk Stok Minimum',
                            'message' => 'Produk mencapai batas stok minimum',
                            'link' => route('gudang.minstok'),
                        ];
                    }

                    // Barang Expired / Akan Expired
                    $expiredStock = \App\Models\ProductStock::whereNotNull('expired_date')
                        ->where('stock', '>', 0)
                        ->where('expired_date', '<=', now()->addDays(30))
                        ->count();
                    if ($expiredStock > 0) {
                        $alertList[] = [
                            'type' => 'danger',
                            'icon' => '⚠️',
                            'title' => $expiredStock . ' Batch Akan Expired',
                            'message' => 'Barang akan kadaluarsa dalam 30 hari',
                            'link' => route('gudang.expired'),
                        ];
                    }

                    // Opname Pending Approval
                    $pendingOpname = \App\Models\StockOpnameSession::where('status', 'submitted')->count();
                    if ($pendingOpname > 0) {
                        $alertList[] = [
                            'type' => 'warning',
                            'icon' => '📋',
                            'title' => $pendingOpname . ' Opname Menunggu',
                            'message' => 'Sesi opname menunggu approval supervisor',
                            'link' => route('gudang.opname_approval.index'),
                        ];
                    }

                    return $alertList;
                });

                return view('dashboard', compact('weeklySales', 'alerts'));
            case 'admin_sales':
                return redirect()->route('admin-sales.dashboard');
            case 'admin1':
                // Dashboard untuk Admin 1 (Kasir Utama & Pencatatan Sales)
                // Metrik: Ringkasan Penjualan Harian, Omzet, dan Uang Masuk Hari Ini
                $today = now()->toDateString();

                // Omzet Penjualan POS Hari Ini
                $omzetPOS = \App\Models\Transaction::whereDate('created_at', $today)
                    ->where('status', 'completed')
                    ->sum('total_amount');

                // Setoran Armada Hari Ini
                $totalSetoranArmada = 0;

                $totalUangMasuk = $omzetPOS + $totalSetoranArmada;

                // Jumlah Transaksi POS Hari Ini
                $jumlahTransaksi = \App\Models\Transaction::whereDate('created_at', $today)
                    ->where('status', 'completed')
                    ->count();

                return view('dashboard.admin1', compact(
                    'omzetPOS',
                    'totalSetoranArmada',
                    'totalUangMasuk',
                    'jumlahTransaksi'
                ));
            case 'admin2':
                // Dashboard untuk Admin 2 (Kasir Biasa & Operasional)
                // Metrik: Omzet POS (Shift dia sendiri), Pengeluaran Operasional
                $today = now()->toDateString();

                // Omzet POS (hanya session yang dibuat oleh user ini)
                $omzetPOS = \App\Models\Transaction::whereDate('created_at', $today)
                    ->where('status', 'completed')
                    ->where('user_id', $user->id)
                    ->sum('total_amount');

                // Pengeluaran Operasional Hari Ini
                $pengeluaranOperasional = \App\Models\OperationalExpense::whereDate('date', $today)
                    ->sum('amount');

                return view('dashboard.admin2', compact(
                    'omzetPOS',
                    'pengeluaranOperasional'
                ));
            case 'admin3':
                // Dashboard untuk Admin 3 (Gudang Masuk, Opname & Master Data)
                $today = now()->toDateString();
                $warehouseId = 1;

                // Penerimaan Non-PO hari ini
                $penerimaanHariIni = class_exists(\App\Models\StockMovement::class) ? \App\Models\StockMovement::where('type', 'in')
                    ->whereNull('purchase_order_id')
                    ->where('warehouse_id', $warehouseId)
                    ->whereDate('created_at', $today)
                    ->count() : 0;

                // PO hari ini
                $poHariIni = class_exists(\App\Models\PurchaseOrder::class) ? \App\Models\PurchaseOrder::whereDate('created_at', $today)->count() : 0;

                // Produk Expired (< 30 hari)
                $limitDate = \Carbon\Carbon::now()->addDays(30);
                $produkExpired = class_exists(\App\Models\ProductStock::class) ? \App\Models\ProductStock::whereNotNull('expired_date')
                    ->where('stock', '>', 0)
                    ->where('warehouse_id', $warehouseId)
                    ->where('expired_date', '<=', $limitDate)
                    ->count() : 0;

                // Produk Minimum Stok
                $produkMinStok = class_exists(\App\Models\Product::class) ? \App\Models\Product::whereColumn('stock', '<=', 'min_stock')->count() : 0;

                $warehouseInboundTrend = [];
                if (class_exists(\App\Models\StockMovement::class)) {
                    $start = now()->subDays(13)->startOfDay();
                    for ($i = 0; $i < 14; $i++) {
                        $date = $start->copy()->addDays($i);
                        $key = $date->toDateString();
                        $label = $date->locale('id')->isoFormat('D MMM');

                        $nonPo = \App\Models\StockMovement::where('warehouse_id', $warehouseId)
                            ->where('type', 'in')
                            ->whereNull('purchase_order_id')
                            ->whereDate('created_at', $key)
                            ->count();

                        $poIn = \App\Models\StockMovement::where('warehouse_id', $warehouseId)
                            ->where('type', 'in')
                            ->whereNotNull('purchase_order_id')
                            ->whereDate('created_at', $key)
                            ->count();

                        $warehouseInboundTrend[] = [
                            'date' => $key,
                            'label' => $label,
                            'non_po' => (int) $nonPo,
                            'po' => (int) $poIn,
                            'total' => (int) $nonPo + (int) $poIn,
                        ];
                    }
                }

                $maxTrend = max(1, (int) collect($warehouseInboundTrend)->max('total'));
                $warehouseInboundTrend = collect($warehouseInboundTrend)->map(function ($row) use ($maxTrend) {
                    $row['pct_total'] = ((int) $row['total'] / $maxTrend) * 100;
                    $row['pct_non_po'] = $row['total'] > 0 ? ((int) $row['non_po'] / (int) $row['total']) * 100 : 0;
                    $row['pct_po'] = $row['total'] > 0 ? ((int) $row['po'] / (int) $row['total']) * 100 : 0;

                    return $row;
                })->values();

                $recentMovements = collect();
                if (class_exists(\App\Models\StockMovement::class)) {
                    $recentMovements = \App\Models\StockMovement::with(['product', 'user'])
                        ->where('warehouse_id', $warehouseId)
                        ->orderByDesc('created_at')
                        ->limit(10)
                        ->get();
                }

                $topMinStockProducts = collect();
                if (class_exists(\App\Models\Product::class)) {
                    $topMinStockProducts = \App\Models\Product::query()
                        ->select(['id', 'name', 'sku', 'stock', 'min_stock'])
                        ->whereNotNull('min_stock')
                        ->whereColumn('stock', '<=', 'min_stock')
                        ->orderByRaw('(min_stock - stock) desc')
                        ->limit(8)
                        ->get();
                }

                $expiringSoon = collect();
                if (class_exists(\App\Models\ProductStock::class)) {
                    $expiringSoon = \App\Models\ProductStock::with('product')
                        ->where('warehouse_id', $warehouseId)
                        ->whereNotNull('expired_date')
                        ->where('stock', '>', 0)
                        ->where('expired_date', '<=', $limitDate)
                        ->orderBy('expired_date')
                        ->limit(8)
                        ->get();
                }

                $opnameToday = [
                    'status' => 'missing',
                    'at' => null,
                ];
                if (class_exists(\App\Models\StockOpnameSession::class) && Schema::hasTable('stock_opname_sessions')) {
                    $approved = \App\Models\StockOpnameSession::query()
                        ->where('warehouse_id', $warehouseId)
                        ->where('status', 'approved')
                        ->whereDate('approved_at', $today)
                        ->orderByDesc('approved_at')
                        ->first();

                    if ($approved) {
                        $opnameToday['status'] = 'approved';
                        $opnameToday['at'] = $approved->approved_at;
                    } else {
                        $submitted = \App\Models\StockOpnameSession::query()
                            ->where('warehouse_id', $warehouseId)
                            ->whereIn('status', ['submitted', 'approved'])
                            ->whereDate('submitted_at', $today)
                            ->orderByDesc('submitted_at')
                            ->first();

                        if ($submitted) {
                            $opnameToday['status'] = 'submitted';
                            $opnameToday['at'] = $submitted->submitted_at;
                        }
                    }
                }

                return view('dashboard.admin3', compact(
                    'penerimaanHariIni',
                    'poHariIni',
                    'produkExpired',
                    'produkMinStok',
                    'warehouseInboundTrend',
                    'recentMovements',
                    'topMinStockProducts',
                    'expiringSoon',
                    'warehouseId',
                    'opnameToday',
                ));
            case 'admin4':
                // Dashboard untuk Admin 4 (Gudang Keluar & Loading Armada)
                $today = now()->toDateString();
                $warehouseId = 2;

                $pengeluaranHariIni = class_exists(\App\Models\StockMovement::class) ? \App\Models\StockMovement::where('type', 'out')
                    ->whereDate('created_at', $today)
                    ->count() : 0;
                $transferGudangHariIni = class_exists(\App\Models\StockTransfer::class) ? \App\Models\StockTransfer::whereDate('created_at', $today)->count() : 0;
                $opnameHariIni = class_exists(\App\Models\StockMovement::class) ? \App\Models\StockMovement::where('type', 'adjustment')
                    ->whereDate('created_at', $today)
                    ->count() : 0;

                $opnameToday = [
                    'status' => 'missing',
                    'at' => null,
                ];
                if (class_exists(\App\Models\StockOpnameSession::class) && Schema::hasTable('stock_opname_sessions')) {
                    $approved = \App\Models\StockOpnameSession::query()
                        ->where('warehouse_id', $warehouseId)
                        ->where('status', 'approved')
                        ->whereDate('approved_at', $today)
                        ->orderByDesc('approved_at')
                        ->first();

                    if ($approved) {
                        $opnameToday['status'] = 'approved';
                        $opnameToday['at'] = $approved->approved_at;
                    } else {
                        $submitted = \App\Models\StockOpnameSession::query()
                            ->where('warehouse_id', $warehouseId)
                            ->whereIn('status', ['submitted', 'approved'])
                            ->whereDate('submitted_at', $today)
                            ->orderByDesc('submitted_at')
                            ->first();

                        if ($submitted) {
                            $opnameToday['status'] = 'submitted';
                            $opnameToday['at'] = $submitted->submitted_at;
                        }
                    }
                }

                return view('dashboard.admin4', compact(
                    'pengeluaranHariIni',
                    'transferGudangHariIni',
                    'opnameHariIni',
                    'warehouseId',
                    'opnameToday',
                ));
            default:
                // Role tidak dikenali atau tidak memiliki dashboard khusus
                return redirect()->route('login')->withErrors(['role' => 'Akun Anda tidak memiliki dashboard yang aktif. Hubungi Administrator.']);
        }
    }
}

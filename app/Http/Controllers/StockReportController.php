<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\StockOpnameSession;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockReportController extends Controller
{
    private function shouldMaskStock(): bool
    {
        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));
        if (! in_array($role, ['admin3', 'admin4'], true)) {
            return false;
        }
        if (! Schema::hasTable('stock_opname_sessions')) {
            return false;
        }
        if (! class_exists(StockOpnameSession::class)) {
            return false;
        }

        $start = now()->startOfDay();
        $end = now()->endOfDay();

        return ! StockOpnameSession::query()
            ->whereIn('status', ['submitted', 'approved'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('submitted_at', [$start, $end])
                    ->orWhereBetween('approved_at', [$start, $end])
                    ->orWhereBetween('created_at', [$start, $end]);
            })
            ->exists();
    }

    // Rekap Stok Per Gudang & Rak
    public function index(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');
        $search = $request->input('search');

        $warehouses = Warehouse::orderBy('name')->get();

        $stocks = ProductStock::with(['product', 'warehouse', 'location'])
            ->when($warehouseId, function ($q) use ($warehouseId) {
                return $q->where('warehouse_id', $warehouseId);
            })
            ->when($search, function ($q) use ($search) {
                return $q->whereHas('product', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->where('stock', '>', 0) // Hanya tampilkan yang ada stoknya
            ->orderBy('warehouse_id')
            ->orderBy('product_id')
            ->paginate(20)
            ->withQueryString();

        $maskStock = $this->shouldMaskStock();

        return view('gudang.stok.index', compact('stocks', 'warehouses', 'warehouseId', 'maskStock'));
    }

    // Peringatan Barang Expired
    public function expired(Request $request)
    {
        // Cari barang yang expired_date-nya sudah lewat, atau akan expired dalam 30 hari ke depan
        $daysThreshold = 30; // Bisa dibuat dinamis jika perlu
        $limitDate = Carbon::now()->addDays($daysThreshold);

        $expiredStocks = ProductStock::with(['product', 'warehouse', 'location'])
            ->whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->where('expired_date', '<=', $limitDate)
            ->orderBy('expired_date', 'asc')
            ->paginate(20);

        $maskStock = $this->shouldMaskStock();

        return view('gudang.stok.expired', compact('expiredStocks', 'daysThreshold', 'maskStock'));
    }

    // Peringatan Minimum Stok
    public function minimumStock(Request $request)
    {
        // Mencari produk yang total stoknya (global) di bawah atau sama dengan min_stock
        $lowStockProducts = Product::with(['category'])
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->paginate(20);

        $maskStock = $this->shouldMaskStock();

        return view('gudang.stok.minimum', compact('lowStockProducts', 'maskStock'));
    }

    // Dashboard Gudang
    public function dashboard(Request $request)
    {
        $period = $request->input('period', 'month');
        $startDate = match($period) {
            'quarter' => now()->subMonths(3)->startOfMonth(),
            'year' => now()->subMonths(12)->startOfMonth(),
            default => now()->subMonths(1)->startOfMonth(),
        };

        // Statistik Stok
        $totalProducts = Product::count();
        $totalStockValue = ProductStock::where('stock', '>', 0)
            ->join('products', 'product_stocks.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(product_stocks.stock * COALESCE(products.purchase_price, 0)) as total_value'))
            ->value('total_value') ?? 0;

        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();
        $expiredCount = ProductStock::whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->where('expired_date', '<=', now()->addDays(30))
            ->count();

        // Statistik Pergerakan
        $inboundCount = StockMovement::where('type', 'in')
            ->where('created_at', '>=', $startDate)
            ->count();
        $outboundCount = StockMovement::where('type', 'out')
            ->where('created_at', '>=', $startDate)
            ->count();
        $transferCount = StockTransfer::where('created_at', '>=', $startDate)->count();

        // Data untuk Chart
        $chartData = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $chartData['inbound'][] = StockMovement::where('type', 'in')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('quantity');
            
            $chartData['outbound'][] = StockMovement::where('type', 'out')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('quantity');
        }

        // Top Gudang
        $topWarehouses = Warehouse::withCount(['productStocks as total_items' => function($q) {
                $q->where('stock', '>', 0);
            }])
            ->withSum(['productStocks as stock_value' => function($q) {
                $q->where('stock', '>', 0);
            }], 'stock')
            ->orderByDesc('stock_value')
            ->limit(5)
            ->get();

        // Recent Activities
        $recentMovements = StockMovement::with(['product', 'warehouse', 'user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Opname Summary
        $opnameStats = [
            'draft' => StockOpnameSession::where('status', 'draft')->count(),
            'submitted' => StockOpnameSession::where('status', 'submitted')->count(),
            'approved' => StockOpnameSession::where('status', 'approved')->count(),
        ];

        return view('gudang.dashboard', compact(
            'totalProducts', 'totalStockValue', 'lowStockCount', 'expiredCount',
            'inboundCount', 'outboundCount', 'transferCount',
            'chartData', 'months', 'topWarehouses', 'recentMovements',
            'opnameStats', 'period'
        ));
    }

    // Export Stok
    public function export(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');
        
        $stocks = ProductStock::with(['product', 'warehouse', 'location'])
            ->when($warehouseId, function ($q) use ($warehouseId) {
                return $q->where('warehouse_id', $warehouseId);
            })
            ->where('stock', '>', 0)
            ->orderBy('warehouse_id')
            ->orderBy('product_id')
            ->get();

        $filename = 'stok-gudang-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stocks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['SKU', 'Nama Produk', 'Kategori', 'Gudang', 'Lokasi/Rak', 'Batch', 'Expired', 'Stok', 'Satuan']);
            
            foreach ($stocks as $stock) {
                fputcsv($file, [
                    $stock->product->sku ?? '-',
                    $stock->product->name ?? '-',
                    $stock->product->category->name ?? '-',
                    $stock->warehouse->name ?? '-',
                    $stock->location->name ?? 'Area Umum',
                    $stock->batch_number ?? '-',
                    $stock->expired_date ? $stock->expired_date->format('d/m/Y') : '-',
                    $stock->stock,
                    $stock->product->unit->abbreviation ?? '',
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}

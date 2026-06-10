<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        
        // Hanya admin3 dan admin4 yang perlu masking
        if (! in_array($role, ['admin3', 'admin4'], true)) {
            return false;
        }
        
        // Cek apakah ada sesi opname yang aktif hari ini
        $today = now()->toDateString();
        
        return ! StockOpnameSession::query()
            ->whereIn('status', ['submitted', 'approved'])
            ->where(function ($q) use ($today) {
                $q->whereDate('created_at', $today)
                  ->orWhereDate('submitted_at', $today)
                  ->orWhereDate('approved_at', $today);
            })
            ->exists();
    }

    // Rekap Stok Per Gudang & Rak
    public function index(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');
        $categoryId = $request->input('category_id');
        $search = $request->input('search');
        $sort = $request->input('sort', 'product');
        $dir = $request->input('dir', 'asc') === 'desc' ? 'desc' : 'asc';

        $warehouses = Warehouse::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        // Query dasar dengan eager loading
        $query = ProductStock::with([
            'product.category',
            'product.unit',
            'warehouse',
            'location'
        ])->where('product_stocks.stock', '>', 0);

        // Terapkan filter warehouse
        if ($warehouseId) {
            $query->where('product_stocks.warehouse_id', $warehouseId);
        }

        // Terapkan filter kategori dengan join yang lebih efisien
        if ($categoryId) {
            $query->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        // Terapkan pencarian dengan whereHas untuk performa lebih baik
        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Hitung statistik dengan query terpisah untuk akurasi
        $statsQuery = ProductStock::with(['product'])
            ->where('product_stocks.stock', '>', 0);

        // Terapkan filter yang sama untuk statistik
        if ($warehouseId) {
            $statsQuery->where('product_stocks.warehouse_id', $warehouseId);
        }
        if ($categoryId) {
            $statsQuery->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        if ($search) {
            $statsQuery->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Hitung total records dan active warehouses
        $totalRecords = (clone $statsQuery)->count();
        $activeWarehouses = (clone $statsQuery)->distinct('warehouse_id')->count('warehouse_id');

        // Hitung stok rendah — gunakan DB::table terpisah agar tidak ada double JOIN
        // (tidak bisa reuse $statsQuery karena sudah ada join yang mungkin ditambahkan sebelumnya)
        $lowStockCountQuery = DB::table('product_stocks')
            ->join('products', 'product_stocks.product_id', '=', 'products.id')
            ->where('product_stocks.stock', '>', 0)
            ->where('products.min_stock', '>', 0)
            ->whereColumn('product_stocks.stock', '<=', 'products.min_stock');

        if ($warehouseId) {
            $lowStockCountQuery->where('product_stocks.warehouse_id', $warehouseId);
        }
        if ($categoryId) {
            $lowStockCountQuery->join('categories', 'products.category_id', '=', 'categories.id')
                               ->where('products.category_id', $categoryId);
        }
        $lowStockCount = $lowStockCountQuery->count();

        // Hitung total nilai stok — gunakan DB::table terpisah dengan alias p2
        $totalStockValueQuery = DB::table('product_stocks')
            ->join('products as p2', 'product_stocks.product_id', '=', 'p2.id')
            ->where('product_stocks.stock', '>', 0);

        if ($warehouseId) {
            $totalStockValueQuery->where('product_stocks.warehouse_id', $warehouseId);
        }
        $totalStockValue = $totalStockValueQuery
            ->sum(DB::raw('product_stocks.stock * COALESCE(p2.purchase_price, 0)'));

        // Terapkan sorting dengan join yang diperlukan
        if ($sort === 'stock') {
            $query->orderBy('product_stocks.stock', $dir);
        } elseif ($sort === 'category') {
            $query->join('products as p_sort', 'product_stocks.product_id', '=', 'p_sort.id')
                  ->join('categories as c_sort', 'p_sort.category_id', '=', 'c_sort.id')
                  ->orderBy('c_sort.name', $dir);
        } elseif ($sort === 'warehouse') {
            $query->join('warehouses as w_sort', 'product_stocks.warehouse_id', '=', 'w_sort.id')
                  ->orderBy('w_sort.name', $dir);
        } else { // 'product' (default)
            $query->join('products as p_sort', 'product_stocks.product_id', '=', 'p_sort.id')
                  ->orderBy('p_sort.name', $dir);
        }

        // Paginate hasil
        $stocks = $query->paginate(20)->withQueryString();

        $maskStock = $this->shouldMaskStock();

        return view('gudang.stok.index', compact(
            'stocks', 'warehouses', 'categories', 'warehouseId', 'categoryId', 'maskStock',
            'totalRecords', 'activeWarehouses', 'lowStockCount', 'totalStockValue',
            'sort', 'dir'
        ));
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
        $categoryId = $request->input('category_id');
        $search = $request->input('search');
        $maskStock = $this->shouldMaskStock();

        $stocks = ProductStock::with(['product.category', 'product.unit', 'warehouse', 'location'])
            ->where('product_stocks.stock', '>', 0)
            ->when($warehouseId, fn($q) => $q->where('product_stocks.warehouse_id', $warehouseId))
            ->when($categoryId, fn($q) => $q->whereHas('product', function ($sub) use ($categoryId) {
                $sub->where('category_id', $categoryId);
            }))
            ->when($search, fn($q) => $q->whereHas('product', function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->orderBy('warehouse_id')
            ->orderBy('product_id')
            ->get();

        $filename = 'stok-gudang-' . now()->format('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stocks, $maskStock) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['SKU', 'Nama Produk', 'Kategori', 'Gudang', 'Lokasi/Rak', 'Batch', 'Expired', 'Stok', 'Satuan', 'Status']);
            
            foreach ($stocks as $stock) {
                $minStk = $stock->product->min_stock ?? 0;
                $isLow = $stock->stock > 0 && $stock->stock <= $minStk;
                $isEmpty = $stock->stock == 0;
                $displayStock = $maskStock ? '***' : number_format($stock->stock);
                $status = $isEmpty ? 'Kosong' : ($isLow ? 'Hampir Habis' : 'Aman');
                
                fputcsv($file, [
                    $stock->product->sku ?? '-',
                    $stock->product->name ?? '-',
                    $stock->product->category->name ?? '-',
                    $stock->warehouse->name ?? '-',
                    $stock->location->name ?? 'Area Umum',
                    $stock->batch_number ?? '-',
                    $stock->expired_date ? \Carbon\Carbon::parse($stock->expired_date)->format('d/m/Y') : '-',
                    $displayStock,
                    $stock->product->unit->abbreviation ?? '',
                    $status,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}

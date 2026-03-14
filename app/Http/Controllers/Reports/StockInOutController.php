<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\ProductStock;
use Carbon\Carbon;

class StockInOutController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $warehouseId = $request->input('warehouse_id');
        $search = trim((string) $request->input('search'));

        if (!$dateFrom && !$dateTo) {
            $dateFrom = now()->subDays(7)->toDateString();
            $dateTo = now()->toDateString();
        }

        $inQuery = StockMovement::with(['product', 'warehouse', 'user'])
            ->where('type', 'in');
        $outQuery = StockMovement::with(['product', 'warehouse', 'user'])
            ->where('type', 'out');

        if ($dateFrom) {
            $inQuery->whereDate('created_at', '>=', $dateFrom);
            $outQuery->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $inQuery->whereDate('created_at', '<=', $dateTo);
            $outQuery->whereDate('created_at', '<=', $dateTo);
        }
        if ($warehouseId) {
            $inQuery->where('warehouse_id', $warehouseId);
            $outQuery->where('warehouse_id', $warehouseId);
        }
        if ($search !== '') {
            $inQuery->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$search}%")
                                                     ->orWhere('sku', 'like', "%{$search}%"));
            });
            $outQuery->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$search}%")
                                                     ->orWhere('sku', 'like', "%{$search}%"));
            });
        }

        $ins = $inQuery->orderBy('created_at', 'desc')->paginate(15, ['*'], 'ins_page')->withQueryString();
        $outs = $outQuery->orderBy('created_at', 'desc')->paginate(15, ['*'], 'outs_page')->withQueryString();

        $inTotal = (int) $ins->getCollection()->sum('quantity');
        $outTotal = (int) $outs->getCollection()->sum('quantity');

        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();

        $pendingPos = PurchaseOrder::with('supplier')
            ->whereIn('status', ['ordered', 'partial'])
            ->orderBy('order_date', 'desc')
            ->limit(5)
            ->get();

        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->where('min_stock', '>', 0)->count();
        $expiredCount = ProductStock::whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->where('expired_date', '<', Carbon::today())
            ->count();
        $nearExpiredCount = ProductStock::whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->whereBetween('expired_date', [Carbon::today(), Carbon::today()->addDays(30)])
            ->count();

        $nearExpiredStocks = ProductStock::with(['product:id,name,sku', 'warehouse:id,name'])
            ->whereNotNull('expired_date')
            ->where('stock', '>', 0)
            ->whereBetween('expired_date', [Carbon::today(), Carbon::today()->addDays(30)])
            ->orderBy('expired_date')
            ->take(5)
            ->get();
        $lowStockProducts = Product::with('category:id,name')
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('min_stock', '>', 0)
            ->orderBy('stock')
            ->take(5)
            ->get(['id','name','sku','stock','min_stock','category_id']);

        $reorderSuggestions = Product::with('category:id,name')
            ->whereColumn('stock', '<=', 'min_stock')
            ->where('min_stock', '>', 0)
            ->orderByRaw('(min_stock - stock) desc')
            ->take(10)
            ->get(['id','name','sku','stock','min_stock','category_id','purchase_price']);

        $reorderSummary = [
            'count' => $reorderSuggestions->count(),
            'total_qty' => $reorderSuggestions->sum(function($p) { return max(($p->min_stock ?? 0) - ($p->stock ?? 0), 0); }),
            'total_value' => $reorderSuggestions->sum(function($p) { $q = max(($p->min_stock ?? 0) - ($p->stock ?? 0), 0); return $q * (float) ($p->purchase_price ?? 0); }),
        ];

        $lastDraftPo = PurchaseOrder::where('status', 'draft')->latest()->first();
        $draftPos = PurchaseOrder::where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get(['id','po_number']);

        return view('gudang.inout.index', compact(
            'ins', 'outs', 'dateFrom', 'dateTo', 'inTotal', 'outTotal', 'warehouses', 'warehouseId', 'search', 'pendingPos',
            'lowStockCount', 'expiredCount', 'nearExpiredCount', 'nearExpiredStocks', 'lowStockProducts', 'reorderSuggestions', 'lastDraftPo', 'draftPos', 'reorderSummary'
        ));
    }
}

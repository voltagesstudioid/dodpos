<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockOpnameSession;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
        $lowStockProducts = Product::with(['category', 'brand'])
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->paginate(20);

        $maskStock = $this->shouldMaskStock();

        return view('gudang.stok.minimum', compact('lowStockProducts', 'maskStock'));
    }
}

<?php

namespace App\Http\Controllers\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KanvasLoading;
use App\Models\KanvasLoadingItem;
use App\Models\KanvasWarehouseStock;
use App\Models\KanvasVehicleStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $query = KanvasLoading::query()
            ->with(['sales', 'admin'])
            ->withCount('items')
            ->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('id', 'like', '%' . $q . '%')
                    ->orWhere('status', 'like', '%' . $q . '%')
                    ->orWhereHas('sales', fn ($u) => $u->where('name', 'like', '%' . $q . '%'))
                    ->orWhereHas('admin', fn ($u) => $u->where('name', 'like', '%' . $q . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $totalCount = (clone $query)->count();
        $completedCount = (clone $query)->where('status', 'completed')->count();
        $draftCount = (clone $query)->where('status', 'loading')->count();
        $todayCount = (clone $query)->whereDate('date', today())->count();

        $loadings = $query->paginate(10)->withQueryString();

        return view('kanvas.loading.index', compact(
            'loadings',
            'totalCount',
            'completedCount',
            'draftCount',
            'todayCount',
        ));
    }

    public function create()
    {
        $salesList = User::where('role', 'sales_kanvas')->get();
        // Kita tidak lagi melempar seluruh $products dari backend langsung.
        // Cukup render view kosongan, sisanya di-handle AJAX 'searchProducts'
                                        
        return view('kanvas.loading.create', compact('salesList'));
    }

    /**
     * Endpoint API internal untuk dipanggil AJAX dari halaman Create Loading.
     * Mengembalikan list stok gudang sesuai keyword (Nama / Barcode).
     */
    public function searchProducts(Request $request)
    {
        $keyword = $request->query('q', '');
        
        $query = KanvasWarehouseStock::with('product')
                    ->where('qty_tersedia', '>', 0);
                    
        if (!empty($keyword)) {
            $query->whereHas('product', function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('barcode', 'like', "%{$keyword}%");
            });
        }
        
        $stocks = $query->limit(50)->get();
        
        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:kanvas_products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $loading = KanvasLoading::create([
                'sales_id' => $request->sales_id,
                'admin_id' => Auth::id(),
                'date' => Carbon::today(),
                'status' => 'completed', 
            ]);

            foreach ($request->items as $item) {
                // Kurangi stok gudang
                $warehouseStock = KanvasWarehouseStock::where('product_id', $item['product_id'])->first();
                if (!$warehouseStock || $warehouseStock->qty_tersedia < $item['qty']) {
                    throw new \Exception("Stok gudang tidak cukup untuk product_id: " . $item['product_id']);
                }
                $warehouseStock->decrement('qty_tersedia', $item['qty']);

                // Catat di SJ (Loading Items)
                KanvasLoadingItem::create([
                    'loading_id' => $loading->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty']
                ]);

                // Tambah atau Update ke mobil
                $vehicleStock = KanvasVehicleStock::where('sales_id', $request->sales_id)
                                                  ->where('product_id', $item['product_id'])
                                                  ->first();
                if ($vehicleStock) {
                    $vehicleStock->increment('initial_qty', $item['qty']);
                    $vehicleStock->increment('leftover_qty', $item['qty']);
                } else {
                    KanvasVehicleStock::create([
                        'sales_id' => $request->sales_id,
                        'product_id' => $item['product_id'],
                        'initial_qty' => $item['qty'],
                        'sold_qty' => 0,
                        'leftover_qty' => $item['qty'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('kanvas.loading.index')->with('success', 'Loading Barang ke Kanvas Berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}

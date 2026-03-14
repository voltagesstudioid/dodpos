<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\GulaLoading::query()
            ->with(['sales', 'vehicle', 'user', 'items.product'])
            ->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('loading_number', 'like', '%' . $q . '%')
                    ->orWhere('notes', 'like', '%' . $q . '%')
                    ->orWhereHas('vehicle', fn ($v) => $v->where('license_plate', 'like', '%' . $q . '%'))
                    ->orWhereHas('sales', fn ($u) => $u->where('name', 'like', '%' . $q . '%'))
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', '%' . $q . '%'));
            });
        }

        $allowedStatuses = ['loaded', 'returned'];
        if ($request->filled('status') && in_array($request->status, $allowedStatuses, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $totalCount = (clone $query)->count();
        $loadedCount = (clone $query)->where('status', 'loaded')->count();
        $returnedCount = (clone $query)->where('status', 'returned')->count();
        $todayCount = (clone $query)->whereDate('date', today())->count();

        $loadings = $query->paginate(15)->withQueryString();

        return view('gula.loading.index', compact(
            'loadings',
            'totalCount',
            'loadedCount',
            'returnedCount',
            'todayCount',
        ));
    }

    public function create()
    {
        $products = \App\Models\GulaProduct::where('is_active', true)->with('warehouseStocks')->get();
        $sales = \App\Models\User::where('role', 'sales')->get();
        $vehicles = \App\Models\Vehicle::where('status', 'available')->get();
        
        return view('gula.loading.create', compact('products', 'sales', 'vehicles'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'sales_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:gula_products,id',
            'products.*.qty_karung' => 'nullable|numeric|min:0',
            'products.*.qty_eceran' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $loadingDate = \Carbon\Carbon::parse($request->date);
            $loadingNo = 'GLD-' . $loadingDate->format('Ymd') . '-' . strtoupper(Str::random(4));

            $loading = \App\Models\GulaLoading::create([
                'loading_number' => $loadingNo,
                'date' => $request->date,
                'user_id' => Auth::id(),
                'sales_id' => $request->sales_id,
                'vehicle_id' => $request->vehicle_id,
                'status' => 'loaded',
                'notes' => $request->notes,
            ]);

            foreach ($request->products as $item) {
                $qtyKrg = $item['qty_karung'] ?? 0;
                $qtyEcr = $item['qty_eceran'] ?? 0;

                if ($qtyKrg == 0 && $qtyEcr == 0) continue;

                $product = \App\Models\GulaProduct::findOrFail($item['id']);
                $warehouse = $product->warehouseStocks()->first();

                // Validation
                if (!$warehouse || $warehouse->qty_karung < $qtyKrg || $warehouse->qty_eceran < $qtyEcr) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'products' => 'Stok Gudang ' . $product->name . ' tidak mencukupi untuk loading muatan ini.'
                    ]);
                }

                // Create Loading Items detail
                $loading->items()->create([
                    'gula_product_id' => $product->id,
                    'qty_karung' => $qtyKrg,
                    'qty_eceran' => $qtyEcr
                ]);

                // Kurangi Stok Gudang Utama
                $warehouse->decrement('qty_karung', $qtyKrg);
                $warehouse->decrement('qty_eceran', $qtyEcr);

                // Tambahkan Stok Fisik ke Kendaraan (Realtime Sales)
                $vehicleStock = \App\Models\GulaVehicleStock::firstOrCreate(
                    [
                        'vehicle_id' => $request->vehicle_id,
                        'sales_id' => $request->sales_id,
                        'gula_product_id' => $product->id
                    ]
                );
                
                $vehicleStock->increment('qty_karung', $qtyKrg);
                $vehicleStock->increment('qty_eceran', $qtyEcr);
            }
        });

        return redirect()->route('gula.loading.index')->with('success', 'Surat Jalan Gula beserta Muatan Armada Sales berhasil disimpan. Stok kendaraan bertambah otomatis.');
    }
}

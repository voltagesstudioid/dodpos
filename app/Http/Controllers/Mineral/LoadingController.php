<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralLoading;
use App\Models\MineralLoadingItem;
use App\Models\MineralProduct;
use App\Models\MineralWarehouseStock;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoadingController extends Controller
{
    public function index()
    {
        $loadings = MineralLoading::with(['sales', 'admin'])->latest()->paginate(15);
        return view('mineral.loading.index', compact('loadings'));
    }

    public function create()
    {
        $salesList = User::where('role', 'sales_mineral')->get();
        $products = MineralProduct::with('warehouseStocks')->get();
        return view('mineral.loading.create', compact('salesList', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sales_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:mineral_products,id',
            'items.*.qty_dus' => 'nullable|integer|min:0'
        ]);

        \DB::transaction(function() use ($request) {
            $loading = MineralLoading::create([
                'admin_id' => auth()->id(),
                'sales_id' => $request->sales_id,
                'date' => Carbon::today(),
                'status' => 'verified' // Langsung verified karena diinput admin
            ]);

            foreach ($request->items as $item) {
                if (empty($item['qty_dus']) || $item['qty_dus'] <= 0) continue;

                // Create loading item
                MineralLoadingItem::create([
                    'loading_id' => $loading->id,
                    'product_id' => $item['product_id'],
                    'qty_dus' => $item['qty_dus']
                ]);

                // Deduct warehouse
                $stock = MineralWarehouseStock::where('product_id', $item['product_id'])->first();
                if ($stock) {
                    $stock->decrement('qty_dus', $item['qty_dus']);
                }

                // Add to vehicle
                $vehicleStock = \App\Models\MineralVehicleStock::firstOrCreate(
                    ['sales_id' => $request->sales_id, 'product_id' => $item['product_id']],
                    ['initial_qty' => 0, 'sold_qty' => 0, 'leftover_qty' => 0]
                );
                
                $vehicleStock->initial_qty += $item['qty_dus'];
                $vehicleStock->save();

                // Log mutation
                \App\Models\MineralWarehouseMutation::create([
                    'product_id' => $item['product_id'],
                    'type' => 'out_loading',
                    'qty_dus' => $item['qty_dus'],
                    'user_id' => auth()->id(),
                    'notes' => 'Surat Jalan #' . $loading->id . ' ke Sales ' . User::find($request->sales_id)->name
                ]);
            }
        });

        return redirect()->route('mineral.loading.index')->with('success', 'Loading / Surat Jalan berhasil dibuat.');
    }

    public function show(MineralLoading $loading)
    {
        $loading->load(['sales', 'admin', 'items.product']);
        return view('mineral.loading.show', compact('loading'));
    }
}

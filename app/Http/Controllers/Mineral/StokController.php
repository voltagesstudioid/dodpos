<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralProduct;
use App\Models\MineralWarehouseStock;
use App\Models\MineralWarehouseMutation;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index()
    {
        $products = MineralProduct::with('warehouseStocks')->get();
        $mutations = MineralWarehouseMutation::with(['product', 'user'])->latest()->paginate(15);
        
        return view('mineral.stok.index', compact('products', 'mutations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:mineral_products,id',
            'type' => 'required|in:in,out_damage',
            'qty_dus' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        \DB::transaction(function() use ($request) {
            MineralWarehouseMutation::create([
                'product_id' => $request->product_id,
                'type' => $request->type,
                'qty_dus' => $request->qty_dus,
                'user_id' => auth()->id(),
                'notes' => $request->notes
            ]);

            $stock = MineralWarehouseStock::firstOrCreate(
                ['product_id' => $request->product_id],
                ['qty_dus' => 0]
            );

            if ($request->type == 'in') {
                $stock->increment('qty_dus', $request->qty_dus);
            } else {
                $stock->decrement('qty_dus', $request->qty_dus);
            }
        });

        return redirect()->route('mineral.stok.index')->with('success', 'Stok berhasil dicatat.');
    }
}

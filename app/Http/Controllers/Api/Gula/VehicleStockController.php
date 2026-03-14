<?php

namespace App\Http\Controllers\Api\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VehicleStockController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = $request->user();
        
        // Ambil stok di kendaraan berdasarkan ID sales yang sedang login
        $stocks = \App\Models\GulaVehicleStock::where('sales_id', $user->id)
            ->with(['product' => function ($q) {
                // Return necessary master data with stock
                $q->select('id', 'name', 'type', 'base_price', 'price_karungan', 'price_eceran', 'qty_per_karung');
            }])
            ->get();

        // Format mapping response API
        $formatted = $stocks->map(function ($stock) {
            return [
                'vehicle_stock_id' => $stock->id,
                'vehicle_id' => $stock->vehicle_id,
                'product_id' => $stock->product->id,
                'product_name' => $stock->product->name,
                'product_type' => $stock->product->type,
                'qty_karung' => $stock->qty_karung,
                'qty_eceran' => $stock->qty_eceran,
                'conversion_rate' => $stock->product->qty_per_karung,
                'prices' => [
                    'base' => $stock->product->base_price,
                    'karungan' => $stock->product->price_karungan,
                    'eceran' => $stock->product->price_eceran,
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted,
            'message' => 'Berhasil mengambil informasi stok berjalan di armada.'
        ]);
    }
}

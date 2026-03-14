<?php

namespace App\Http\Controllers\Api\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralVehicleStock;
use Illuminate\Http\Request;

class VehicleStockController extends Controller
{
    /**
     * Menarik daftar stok air mineral khusus untuk Sales yang sedang login.
     * Hanya menampilkan stok yang dibawa di mobil (initial_qty > 0 atau ada sisa).
     */
    public function index(Request $request)
    {
        $salesId = $request->user()->id;

        $stocks = MineralVehicleStock::with('product')
            ->where('sales_id', $salesId)
            ->where(function ($query) {
                // Selama initial > 0 ATAU masih ada leftover, tampilkan di HP
                $query->where('initial_qty', '>', 0)
                      ->orWhere('leftover_qty', '>', 0);
            })
            ->get();

        $formatted = $stocks->map(function ($st) {
            return [
                'id' => $st->id,
                'product_id' => $st->product_id,
                'product_name' => $st->product->name,
                'price_cash' => $st->product->price_cash,
                'price_tempo' => $st->product->price_tempo,
                'initial_qty' => $st->initial_qty,
                'sold_qty' => $st->sold_qty,
                'leftover_qty' => $st->leftover_qty,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $formatted
        ]);
    }
}

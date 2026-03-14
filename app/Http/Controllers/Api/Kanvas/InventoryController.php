<?php

namespace App\Http\Controllers\Api\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasVehicleStock;

class InventoryController extends Controller
{
    /**
     * Get all currently available stocks in Van (Real-time).
     * Dioptimalkan (eager loading) karena record bisa ratusan.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Tarik sisa stok > 0, urut abjad
        $stocks = KanvasVehicleStock::with(['product' => function($q) {
                $q->select('id', 'name', 'barcode', 'unit', 'price_cash', 'price_tempo', 'qty_per_carton');
            }])
            ->where('sales_id', $user->id)
            ->where('leftover_qty', '>', 0)
            ->get();

        $formatted = $stocks->map(function ($st) {
            return [
                'id' => $st->id,
                'product_id' => $st->product->id,
                'name' => $st->product->name,
                'barcode' => $st->product->barcode,
                'unit' => $st->product->unit,
                'price_cash' => $st->product->price_cash,
                'price_tempo' => $st->product->price_tempo,
                'qty_per_carton' => $st->product->qty_per_carton,
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

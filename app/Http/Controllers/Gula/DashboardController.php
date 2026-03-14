<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products = \App\Models\GulaProduct::with('warehouseStocks')->get();
        // Aggregating global view for widgets
        $globalKarung = 0;
        $globalEceran = 0;
        $globalBal = 0;

        foreach ($products as $product) {
            $globalKarung += $product->warehouseStocks->sum('qty_karung');
            $globalBal += $product->warehouseStocks->sum('qty_bal');
            $globalEceran += $product->warehouseStocks->sum('qty_eceran');
        }

        // Active vehicles currently carrying sugar
        $activeVehicles = \App\Models\GulaVehicleStock::with(['vehicle', 'sales'])
            ->where(function ($q) {
                $q->where('qty_karung', '>', 0)
                    ->orWhere('qty_bal', '>', 0)
                    ->orWhere('qty_eceran', '>', 0);
            })
            ->get();

        $armadaAktifCount = $activeVehicles->groupBy('vehicle_id')->count();

        return view('gula.dashboard', compact(
            'products',
            'globalKarung',
            'globalBal',
            'globalEceran',
            'activeVehicles',
            'armadaAktifCount',
        ));
    }
}

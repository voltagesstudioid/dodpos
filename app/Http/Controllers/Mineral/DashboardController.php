<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralProduct;
use App\Models\MineralWarehouseStock;
use App\Models\MineralTransactionItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $products = MineralProduct::all();
        
        $stocks = MineralWarehouseStock::with('product')->get();
        
        // Terjual hari ini
        $today = Carbon::today();
        $soldToday = MineralTransactionItem::whereHas('transaction', function($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->sum('qty_dus');

        return view('mineral.dashboard', compact('products', 'stocks', 'soldToday'));
    }
}

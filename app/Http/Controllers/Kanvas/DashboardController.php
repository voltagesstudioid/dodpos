<?php

namespace App\Http\Controllers\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasVehicleStock;
use App\Models\KanvasSetoran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total nilai rupiah produk Kanvas yang sedang on-hand di mobil saat ini
        $onHandStocks = KanvasVehicleStock::with('product')
                            ->where('leftover_qty', '>', 0)
                            ->get();

        $totalNilaiBarangDiJalan = 0;
        foreach ($onHandStocks as $stok) {
            $totalNilaiBarangDiJalan += $stok->leftover_qty * $stok->product->price_cash;
        }

        $armadaAktifCount = KanvasVehicleStock::where('leftover_qty', '>', 0)
            ->distinct('sales_id')
            ->count('sales_id');

        $skuAktifCount = KanvasVehicleStock::where('leftover_qty', '>', 0)
            ->distinct('product_id')
            ->count('product_id');

        $todaySetorans = KanvasSetoran::whereDate('created_at', Carbon::today())
            ->where('status', 'verified')
            ->get();
        $totalSetoranMasuk = (float) $todaySetorans->sum('actual_cash');
        $setoranTerverifikasiCount = $todaySetorans->count();

        $targetPercent = 67;

        return view('kanvas.dashboard', compact(
            'totalNilaiBarangDiJalan',
            'totalSetoranMasuk',
            'armadaAktifCount',
            'skuAktifCount',
            'setoranTerverifikasiCount',
            'targetPercent',
        ));
    }
}

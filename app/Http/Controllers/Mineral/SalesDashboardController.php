<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralSales;
use App\Models\MineralPelanggan;
use App\Models\MineralPenjualan;
use App\Models\MineralLoading;
use App\Models\MineralSetoran;
use App\Models\MineralKunjungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalesDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Get sales profile
        $salesProfile = MineralSales::where('user_id', Auth::id())->first();

        if (!$salesProfile) {
            return view('pasgar.sales-dashboard.no-profile');
        }

        // Personal stats hari ini
        $stats = [
            'penjualan_hari_ini' => MineralPenjualan::where('sales_id', $salesProfile->id)
                ->whereDate('tanggal_jual', $today)->sum('total'),
            'transaksi_hari_ini' => MineralPenjualan::where('sales_id', $salesProfile->id)
                ->whereDate('tanggal_jual', $today)->count(),
            'kunjungan_hari_ini' => MineralKunjungan::where('sales_id', $salesProfile->id)
                ->whereDate('waktu_checkin', $today)->count(),
            'kunjungan_aktif' => MineralKunjungan::where('sales_id', $salesProfile->id)
                ->where('status', 'checkin')->count(),
        ];

        // Stats bulan ini
        $statsBulanIni = [
            'total_penjualan' => MineralPenjualan::where('sales_id', $salesProfile->id)
                ->where('tanggal_jual', '>=', $thisMonth)->sum('total'),
            'total_transaksi' => MineralPenjualan::where('sales_id', $salesProfile->id)
                ->where('tanggal_jual', '>=', $thisMonth)->count(),
        ];

        // Loading hari ini
        $loadingHariIni = MineralLoading::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today)
            ->with('produk')
            ->get();

        // Setoran status
        $setoranHariIni = MineralSetoran::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Kunjungan terakhir
        $kunjunganTerakhir = MineralKunjungan::where('sales_id', $salesProfile->id)
            ->with('pelanggan')
            ->orderBy('waktu_checkin', 'desc')
            ->take(5)
            ->get();

        // Penjualan terakhir
        $penjualanTerakhir = MineralPenjualan::where('sales_id', $salesProfile->id)
            ->with(['pelanggan', 'produk'])
            ->orderBy('tanggal_jual', 'desc')
            ->take(5)
            ->get();

        return view('mineral.sales-dashboard.index', compact(
            'salesProfile',
            'stats',
            'statsBulanIni',
            'loadingHariIni',
            'setoranHariIni',
            'kunjunganTerakhir',
            'penjualanTerakhir'
        ));
    }
}

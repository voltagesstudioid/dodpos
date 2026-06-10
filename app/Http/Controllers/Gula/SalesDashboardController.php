<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaSales;
use App\Models\GulaPelanggan;
use App\Models\GulaPenjualan;
use App\Models\GulaLoading;
use App\Models\GulaSetoran;
use App\Models\GulaKunjungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalesDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Get sales profile
        $salesProfile = GulaSales::where('user_id', Auth::id())->first();

        if (!$salesProfile) {
            return view('pasgar.sales-dashboard.no-profile');
        }

        // Personal stats hari ini
        $stats = [
            'penjualan_hari_ini' => GulaPenjualan::where('sales_id', $salesProfile->id)
                ->whereDate('tanggal_jual', $today)->sum('total'),
            'transaksi_hari_ini' => GulaPenjualan::where('sales_id', $salesProfile->id)
                ->whereDate('tanggal_jual', $today)->count(),
            'kunjungan_hari_ini' => GulaKunjungan::where('sales_id', $salesProfile->id)
                ->whereDate('waktu_checkin', $today)->count(),
            'kunjungan_aktif' => GulaKunjungan::where('sales_id', $salesProfile->id)
                ->where('status', 'checkin')->count(),
        ];

        // Stats bulan ini
        $statsBulanIni = [
            'total_penjualan' => GulaPenjualan::where('sales_id', $salesProfile->id)
                ->where('tanggal_jual', '>=', $thisMonth)->sum('total'),
            'total_transaksi' => GulaPenjualan::where('sales_id', $salesProfile->id)
                ->where('tanggal_jual', '>=', $thisMonth)->count(),
        ];

        // Loading hari ini
        $loadingHariIni = GulaLoading::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today)
            ->with('produk')
            ->get();

        // Setoran status
        $setoranHariIni = GulaSetoran::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today)
            ->first();

        // Kunjungan terakhir
        $kunjunganTerakhir = GulaKunjungan::where('sales_id', $salesProfile->id)
            ->with('pelanggan')
            ->orderBy('waktu_checkin', 'desc')
            ->take(5)
            ->get();

        // Penjualan terakhir
        $penjualanTerakhir = GulaPenjualan::where('sales_id', $salesProfile->id)
            ->with(['pelanggan', 'produk'])
            ->orderBy('tanggal_jual', 'desc')
            ->take(5)
            ->get();

        return view('gula.sales-dashboard.index', compact(
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

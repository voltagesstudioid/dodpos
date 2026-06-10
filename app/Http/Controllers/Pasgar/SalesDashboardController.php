<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarSales;
use App\Models\PasgarLoading;
use App\Models\PasgarPenjualan;
use App\Models\PasgarSetoran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalesDashboardController extends Controller
{
    public function index()
    {
        $salesProfile = PasgarSales::where('user_id', Auth::id())->first();
        if (!$salesProfile) {
            return view('pasgar.sales-dashboard.no-profile');
        }

        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        // Today's sales
        $todayPenjualan = PasgarPenjualan::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today);
        $todayStats = [
            'penjualan' => (clone $todayPenjualan)->sum('total'),
            'transaksi' => (clone $todayPenjualan)->count(),
        ];

        // Monthly sales
        $monthlyPenjualan = PasgarPenjualan::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', '>=', $monthStart);
        $monthlyStats = [
            'penjualan' => (clone $monthlyPenjualan)->sum('total'),
            'transaksi' => (clone $monthlyPenjualan)->count(),
        ];

        // Active loadings (in progress, not yet completed/opnamed)
        $activeLoadings = PasgarLoading::with('items')
            ->where('sales_id', $salesProfile->id)
            ->whereIn('status', ['pending', 'preparing', 'ready', 'picked_up', 'loaded'])
            ->latest()
            ->get();

        // Loading hari ini (for today section)
        $loadingHariIni = PasgarLoading::with('items')
            ->where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today)
            ->latest()
            ->get();

        // Setoran status
        $setoranStatus = [
            'pending' => PasgarSetoran::where('sales_id', $salesProfile->id)
                ->where('status', 'pending')->count(),
            'terverifikasi' => PasgarSetoran::where('sales_id', $salesProfile->id)
                ->where('status', 'terverifikasi')
                ->whereDate('tanggal', '>=', $monthStart)->count(),
        ];

        // Recent penjualan
        $recentPenjualan = PasgarPenjualan::where('sales_id', $salesProfile->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Recent setoran
        $recentSetoran = PasgarSetoran::where('sales_id', $salesProfile->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        return view('pasgar.sales-dashboard.index', compact(
            'salesProfile',
            'todayStats',
            'monthlyStats',
            'activeLoadings',
            'loadingHariIni',
            'setoranStatus',
            'recentPenjualan',
            'recentSetoran'
        ));
    }
}

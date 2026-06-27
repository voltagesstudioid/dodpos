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
        $salesProfile = PasgarSales::with('regional')->where('user_id', Auth::id())->first();

        if (!$salesProfile) {
            return view('pasgar.sales-dashboard.no-profile');
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $monthStart = Carbon::now()->startOfMonth();

        // Today's sales
        $todayPenjualan = PasgarPenjualan::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today);
        $todayStats = [
            'penjualan' => (clone $todayPenjualan)->sum('total'),
            'transaksi' => (clone $todayPenjualan)->count(),
        ];

        // Yesterday's sales (for trend comparison)
        $yesterdayPenjualan = PasgarPenjualan::where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $yesterday);
        $yesterdayStats = [
            'penjualan' => (clone $yesterdayPenjualan)->sum('total'),
            'transaksi' => (clone $yesterdayPenjualan)->count(),
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
        $activeLoadingsCount = $activeLoadings->count();

        // Loading hari ini
        $loadingHariIni = PasgarLoading::with('items')
            ->where('sales_id', $salesProfile->id)
            ->whereDate('tanggal', $today)
            ->latest()
            ->get();

        // Setoran stats
        $setoranStatus = [
            'pending' => PasgarSetoran::where('sales_id', $salesProfile->id)
                ->where('status', 'pending')->count(),
            'terverifikasi' => PasgarSetoran::where('sales_id', $salesProfile->id)
                ->where('status', 'terverifikasi')
                ->whereDate('tanggal', '>=', $monthStart)->count(),
        ];

        // Recent records
        $recentPenjualan = PasgarPenjualan::where('sales_id', $salesProfile->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        $recentSetoran = PasgarSetoran::where('sales_id', $salesProfile->id)
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Target progress
        $targetPercentage = $salesProfile->target_harian > 0
            ? min(100, round(($todayStats['penjualan'] / $salesProfile->target_harian) * 100))
            : 0;

        // Sales trend (today vs yesterday)
        $trend = $yesterdayStats['penjualan'] > 0
            ? round((($todayStats['penjualan'] - $yesterdayStats['penjualan']) / $yesterdayStats['penjualan']) * 100)
            : ($todayStats['penjualan'] > 0 ? 100 : 0);

        return view('pasgar.sales-dashboard.index', compact(
            'salesProfile',
            'todayStats',
            'yesterdayStats',
            'monthlyStats',
            'activeLoadings',
            'activeLoadingsCount',
            'loadingHariIni',
            'setoranStatus',
            'recentPenjualan',
            'recentSetoran',
            'targetPercentage',
            'trend',
        ));
    }
}

<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakSales;
use App\Models\MinyakProduk;
use App\Models\MinyakPenjualan;
use App\Models\MinyakLoading;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'harian');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $salesId = $request->input('sales_id');

        // Determine date range based on period
        if ($tanggalDari && $tanggalSampai) {
            $dari = Carbon::parse($tanggalDari);
            $sampai = Carbon::parse($tanggalSampai);
        } elseif ($periode == 'mingguan') {
            $dari = Carbon::now()->subDays(6);
            $sampai = Carbon::today();
        } elseif ($periode == 'bulanan') {
            $dari = Carbon::now()->startOfMonth();
            $sampai = Carbon::today();
        } else {
            $dari = Carbon::today();
            $sampai = Carbon::today();
        }

        // Base query for penjualan
        $penjualanQuery = MinyakPenjualan::whereBetween('tanggal_jual', [$dari, $sampai]);
        if ($salesId) {
            $penjualanQuery->where('sales_id', $salesId);
        }

        // Summary stats
        $totalPenjualan = (clone $penjualanQuery)->sum('total');
        $totalTransaksi = (clone $penjualanQuery)->count();
        $totalHutang = (clone $penjualanQuery)->where('tipe_bayar', 'hutang')->sum('hutang');
        $totalTunai = (clone $penjualanQuery)->where('tipe_bayar', 'tunai')->sum('total');
        $totalVolume = (clone $penjualanQuery)->sum('jumlah');

        // Sales performance
        $salesPerformance = MinyakSales::aktif()
            ->when($salesId, fn($q) => $q->where('id', $salesId))
            ->withSum(['penjualans' => function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_jual', [$dari, $sampai]);
            }], 'total')
            ->withCount(['penjualans' => function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_jual', [$dari, $sampai]);
            }])
            ->orderByDesc('penjualans_sum_total')
            ->get();

        // Product performance
        $produkPerformance = MinyakProduk::where('status', 'aktif')
            ->withSum(['penjualans' => function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_jual', [$dari, $sampai]);
            }], 'total')
            ->withSum(['penjualans' => function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_jual', [$dari, $sampai]);
            }], 'jumlah')
            ->withCount(['penjualans' => function ($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_jual', [$dari, $sampai]);
            }])
            ->orderByDesc('penjualans_sum_total')
            ->get();

        // Daily trend data
        $dailyTrend = [];
        $current = $dari->copy();
        while ($current->lte($sampai)) {
            $dayQuery = MinyakPenjualan::whereDate('tanggal_jual', $current);
            if ($salesId) $dayQuery->where('sales_id', $salesId);
            $dailyTrend[] = [
                'tanggal' => $current->format('d M'),
                'total' => (clone $dayQuery)->sum('total'),
                'transaksi' => (clone $dayQuery)->count(),
            ];
            $current->addDay();
        }

        // Payment type breakdown
        $tipeBayarStats = [
            'tunai' => (clone $penjualanQuery)->where('tipe_bayar', 'tunai')->count(),
            'hutang' => (clone $penjualanQuery)->where('tipe_bayar', 'hutang')->count(),
            'transfer' => (clone $penjualanQuery)->where('tipe_bayar', 'transfer')->count(),
        ];

        // Sales list for filter
        $salesList = MinyakSales::where('status', 'aktif')->orderBy('nama')->get();

        // Loading summary
        $loadingQuery = MinyakLoading::whereBetween('tanggal', [$dari, $sampai]);
        if ($salesId) $loadingQuery->where('sales_id', $salesId);
        $totalLoading = (clone $loadingQuery)->sum('jumlah_loading');

        return view('minyak.laporan.index', compact(
            'periode', 'dari', 'sampai', 'salesId', 'salesList',
            'totalPenjualan', 'totalTransaksi', 'totalHutang', 'totalTunai', 'totalVolume',
            'salesPerformance', 'produkPerformance', 'dailyTrend',
            'tipeBayarStats', 'totalLoading'
        ));
    }
}

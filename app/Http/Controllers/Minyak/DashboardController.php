<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakSales;
use App\Models\MinyakPelanggan;
use App\Models\MinyakProduk;
use App\Models\MinyakPenjualan;
use App\Models\MinyakLoading;
use App\Models\MinyakHutang;
use App\Models\MinyakSetoran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Statistik hari ini
        $stats = [
            'penjualan_hari_ini' => MinyakPenjualan::whereDate('tanggal_jual', $today)->sum('total'),
            'transaksi_hari_ini' => MinyakPenjualan::whereDate('tanggal_jual', $today)->count(),
            'setoran_hari_ini' => MinyakSetoran::whereDate('tanggal', $today)->where('status', 'terverifikasi')->sum('total_setor'),
            'loading_hari_ini' => MinyakLoading::whereDate('tanggal', $today)->sum('jumlah_loading'),
        ];

        // Statistik bulan ini
        $statsBulanIni = [
            'total_penjualan' => MinyakPenjualan::where('tanggal_jual', '>=', $thisMonth)->sum('total'),
            'total_transaksi' => MinyakPenjualan::where('tanggal_jual', '>=', $thisMonth)->count(),
            'total_hutang_baru' => MinyakPenjualan::where('tanggal_jual', '>=', $thisMonth)->where('tipe_bayar', 'hutang')->sum('hutang'),
        ];

        // Data master
        $master = [
            'total_sales' => MinyakSales::where('status', 'aktif')->count(),
            'total_pelanggan' => MinyakPelanggan::where('status', 'aktif')->count(),
            'total_produk' => MinyakProduk::where('status', 'aktif')->count(),
            'stok_rendah' => MinyakProduk::whereColumn('stok_gudang', '<=', 'stok_minimum')->count(),
        ];

        // Hutang overdue
        $hutangOverdue = MinyakHutang::overdue()->count();
        $totalHutang = MinyakHutang::sum('sisa');

        // Top sales bulan ini
        $topSales = MinyakSales::aktif()
            ->withSum(['penjualans' => function ($q) use ($thisMonth) {
                $q->where('tanggal_jual', '>=', $thisMonth);
            }], 'total')
            ->orderByDesc('penjualans_sum_total')
            ->take(5)
            ->get();

        // Penjualan 7 hari terakhir untuk chart
        $penjualanChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $penjualanChart[] = [
                'tanggal' => $date->format('d M'),
                'total' => MinyakPenjualan::whereDate('tanggal_jual', $date)->sum('total'),
            ];
        }

        // Setoran pending
        $setoranPending = MinyakSetoran::where('status', 'pending')
            ->with('sales')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        return view('minyak.dashboard.index', compact(
            'stats',
            'statsBulanIni',
            'master',
            'hutangOverdue',
            'totalHutang',
            'topSales',
            'penjualanChart',
            'setoranPending'
        ));
    }

    public function kunjungan()
    {
        return view('minyak.kunjungan.index');
    }

    public function laporan()
    {
        return view('minyak.laporan.index');
    }

    public function rekonsiliasi()
    {
        return view('minyak.rekonsiliasi.index');
    }
}

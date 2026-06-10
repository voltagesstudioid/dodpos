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
use App\Models\MinyakKunjungan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Sales users see their own dedicated dashboard
        $user = Auth::user();
        if ($user && str_starts_with(strtolower($user->role ?? ''), 'sales_')) {
            return $this->salesDashboard();
        }

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
        MinyakHutang::markAllOverdue();
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

        // Stok di Jalan — sisa stok per sales yang masih di tangki kendaraan
        $stokDiJalan = MinyakLoading::where('sisa_stok', '>', 0)
            ->where('status', '!=', 'selesai')
            ->with(['sales', 'produk'])
            ->get()
            ->groupBy('sales_id')
            ->map(function ($items) {
                $sales = $items->first()->sales;
                return [
                    'sales' => $sales,
                    'total_sisa' => $items->sum('sisa_stok'),
                    'detail' => $items->map(fn ($i) => [
                        'produk' => $i->produk->nama ?? '-',
                        'sisa' => $i->sisa_stok,
                    ]),
                ];
            });

        $totalStokDiJalan = MinyakLoading::where('sisa_stok', '>', 0)
            ->where('status', '!=', 'selesai')
            ->sum('sisa_stok');

        return view('minyak.dashboard.index', compact(
            'stats',
            'statsBulanIni',
            'master',
            'hutangOverdue',
            'totalHutang',
            'topSales',
            'penjualanChart',
            'setoranPending',
            'stokDiJalan',
            'totalStokDiJalan'
        ));
    }

    public function kunjungan()
    {
        return view('minyak.kunjungan.index');
    }

    public function laporan(Request $request)
    {
        $periode = $request->input('periode', 'harian'); // harian, mingguan, bulanan
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

        // Sales performance (group by sales)
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

        // Product performance (group by produk)
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

    public function rekonsiliasi()
    {
        return view('minyak.rekonsiliasi.index');
    }

    /**
     * Sales-specific dashboard — shows only the logged-in sales person's data.
     */
    public function salesDashboard()
    {
        $user = Auth::user();
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            abort(403, 'Profil sales tidak ditemukan.');
        }

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Sales profile
        $salesProfile = $sales;

        // Today's stats (own data)
        $penjualanHariIni = MinyakPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('status', '!=', 'batal');

        $stats = [
            'penjualan_hari_ini' => (clone $penjualanHariIni)->sum('total'),
            'transaksi_hari_ini' => (clone $penjualanHariIni)->count(),
            'volume_hari_ini' => (clone $penjualanHariIni)->sum('jumlah'),
            'tunai_hari_ini' => (clone $penjualanHariIni)->where('tipe_bayar', 'tunai')->sum('total'),
        ];

        // Target harian progress
        $targetHarian = $sales->target_harian ?? 0;
        $progressHarian = $targetHarian > 0 ? round(($stats['penjualan_hari_ini'] / $targetHarian) * 100, 1) : 0;

        // Month stats (own data)
        $statsBulan = [
            'total_penjualan' => MinyakPenjualan::where('sales_id', $sales->id)
                ->where('tanggal_jual', '>=', $thisMonth)->sum('total'),
            'total_transaksi' => MinyakPenjualan::where('sales_id', $sales->id)
                ->where('tanggal_jual', '>=', $thisMonth)->count(),
            'total_hutang_baru' => MinyakPenjualan::where('sales_id', $sales->id)
                ->where('tanggal_jual', '>=', $thisMonth)->where('tipe_bayar', 'hutang')->sum('hutang'),
        ];

        // Today's loading (all products)
        $stokKendaraan = MinyakLoading::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)
            ->with('produk')
            ->get();
        $totalLoadingHariIni = $stokKendaraan->sum('jumlah_loading');

        // Recent penjualan (own last 10)
        $recentPenjualan = MinyakPenjualan::where('sales_id', $sales->id)
            ->with(['pelanggan', 'produk'])
            ->orderBy('tanggal_jual', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Hutang pending (own customers, through penjualan)
        $hutangPending = MinyakHutang::whereHas('penjualan', function ($q) use ($sales) {
                $q->where('sales_id', $sales->id);
            })
            ->where('status', '!=', 'lunas')
            ->with('pelanggan')
            ->orderBy('jatuh_tempo', 'asc')
            ->take(5)
            ->get();
        $totalHutangPending = MinyakHutang::whereHas('penjualan', function ($q) use ($sales) {
                $q->where('sales_id', $sales->id);
            })
            ->where('status', '!=', 'lunas')->sum('sisa');

        // Setoran status
        $setoranHariIni = MinyakSetoran::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)->first();

        // Kunjungan hari ini
        $kunjunganHariIni = MinyakKunjungan::where('sales_id', $sales->id)
            ->whereDate('waktu_checkin', $today)->count();

        // Penjualan 7 hari terakhir untuk chart
        $penjualanChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $penjualanChart[] = [
                'tanggal' => $date->format('d M'),
                'total' => MinyakPenjualan::where('sales_id', $sales->id)
                    ->whereDate('tanggal_jual', $date)->sum('total'),
            ];
        }

        return view('minyak.dashboard.sales', compact(
            'salesProfile', 'stats', 'targetHarian', 'progressHarian',
            'statsBulan', 'totalLoadingHariIni', 'stokKendaraan',
            'recentPenjualan', 'hutangPending', 'totalHutangPending',
            'setoranHariIni', 'kunjunganHariIni', 'penjualanChart'
        ));
    }
}

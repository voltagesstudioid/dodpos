<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralSales;
use App\Models\MineralPelanggan;
use App\Models\MineralProduk;
use App\Models\MineralPenjualan;
use App\Models\MineralLoading;
use App\Models\MineralHutang;
use App\Models\MineralSetoran;
use App\Models\MineralKunjungan;
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
            'penjualan_hari_ini' => MineralPenjualan::whereDate('tanggal_jual', $today)->sum('total'),
            'transaksi_hari_ini' => MineralPenjualan::whereDate('tanggal_jual', $today)->count(),
            'setoran_hari_ini' => MineralSetoran::whereDate('tanggal', $today)->where('status', 'terverifikasi')->sum('total_setor'),
            'loading_hari_ini' => MineralLoading::whereDate('tanggal', $today)->sum('jumlah_loading'),
        ];

        // Statistik bulan ini
        $statsBulanIni = [
            'total_penjualan' => MineralPenjualan::where('tanggal_jual', '>=', $thisMonth)->sum('total'),
            'total_transaksi' => MineralPenjualan::where('tanggal_jual', '>=', $thisMonth)->count(),
            'total_hutang_baru' => MineralPenjualan::where('tanggal_jual', '>=', $thisMonth)->where('tipe_bayar', 'hutang')->sum('hutang'),
        ];

        // Data master
        $master = [
            'total_sales' => MineralSales::where('status', 'aktif')->count(),
            'total_pelanggan' => MineralPelanggan::where('status', 'aktif')->count(),
            'total_produk' => MineralProduk::where('status', 'aktif')->count(),
            'stok_rendah' => MineralProduk::whereColumn('stok_gudang', '<=', 'stok_minimum')->count(),
        ];

        // Hutang overdue
        MineralHutang::markAllOverdue();
        $hutangOverdue = MineralHutang::overdue()->count();
        $totalHutang = MineralHutang::sum('sisa');

        // Top sales bulan ini
        $topSales = MineralSales::aktif()
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
                'total' => MineralPenjualan::whereDate('tanggal_jual', $date)->sum('total'),
            ];
        }

        // Setoran pending
        $setoranPending = MineralSetoran::where('status', 'pending')
            ->with('sales')
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Stok di Jalan — sisa stok per sales yang masih di tangki kendaraan
        $stokDiJalan = MineralLoading::where('sisa_stok', '>', 0)
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

        $totalStokDiJalan = MineralLoading::where('sisa_stok', '>', 0)
            ->where('status', '!=', 'selesai')
            ->sum('sisa_stok');

        return view('mineral.dashboard.index', compact(
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

    /**
     * Sales-specific dashboard — shows only the logged-in sales person's data.
     */
    private function salesDashboard()
    {
        $user = Auth::user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            abort(403, 'Profil sales tidak ditemukan.');
        }

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Sales profile
        $salesProfile = $sales;

        // Today's stats (own data)
        $penjualanHariIni = MineralPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('status', '!=', 'batal');

        $stats = [
            'penjualan_hari_ini' => (clone $penjualanHariIni)->sum('total'),
            'transaksi_hari_ini' => (clone $penjualanHariIni)->count(),
            'kunjungan_hari_ini' => MineralKunjungan::where('sales_id', $sales->id)
                ->whereDate('waktu_checkin', $today)->count(),
            'kunjungan_aktif' => MineralKunjungan::where('sales_id', $sales->id)
                ->whereDate('waktu_checkin', $today)
                ->where('status', 'checkin')->count(),
        ];

        // Month stats (own data)
        $statsBulanIni = [
            'total_penjualan' => MineralPenjualan::where('sales_id', $sales->id)
                ->where('tanggal_jual', '>=', $thisMonth)->sum('total'),
            'total_transaksi' => MineralPenjualan::where('sales_id', $sales->id)
                ->where('tanggal_jual', '>=', $thisMonth)->count(),
        ];

        // Today's loading (all products)
        $loadingHariIni = MineralLoading::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)
            ->with('produk')
            ->get();

        // Setoran status
        $setoranHariIni = MineralSetoran::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)->first();

        // Kunjungan terakhir
        $kunjunganTerakhir = MineralKunjungan::where('sales_id', $sales->id)
            ->with('pelanggan')
            ->orderBy('waktu_checkin', 'desc')
            ->take(5)
            ->get();

        // Penjualan terakhir
        $penjualanTerakhir = MineralPenjualan::where('sales_id', $sales->id)
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

<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralPenjualan;
use App\Models\MineralSales;
use App\Models\MineralProduk;
use App\Models\MineralSetoran;
use App\Models\MineralHutang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());
        $salesId = $request->input('sales_id');

        // Summary stats
        $penjualanQuery = MineralPenjualan::whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
            ->where('status', '!=', 'batal');

        if ($salesId) {
            $penjualanQuery->where('sales_id', $salesId);
        }

        $summary = [
            'total_penjualan' => $penjualanQuery->sum('total'),
            'jumlah_transaksi' => $penjualanQuery->count(),
            'total_tunai' => (clone $penjualanQuery)->where('tipe_bayar', 'tunai')->sum('total'),
            'total_transfer' => (clone $penjualanQuery)->where('tipe_bayar', 'transfer')->sum('total'),
            'total_hutang' => (clone $penjualanQuery)->where('tipe_bayar', 'hutang')->sum('total'),
        ];

        // Daily chart data
        $dailyData = MineralPenjualan::whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
            ->where('status', '!=', 'batal')
            ->when($salesId, fn($q) => $q->where('sales_id', $salesId))
            ->selectRaw('DATE(tanggal_jual) as tanggal, COUNT(*) as jumlah, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Sales performance
        $salesPerformance = MineralSales::where('status', 'aktif')
            ->withCount(['penjualans as total_penjualan' => function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
            }])
            ->withSum(['penjualans as omzet' => function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
            }], 'total')
            ->get();

        // Product performance
        $productPerformance = MineralProduk::where('status', 'aktif')
            ->withCount(['penjualans as terjual' => function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
            }])
            ->withSum(['penjualans as omzet' => function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
            }], 'total')
            ->orderByDesc('terjual')
            ->get();

        // Setoran summary
        $setoranSummary = MineralSetoran::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->when($salesId, fn($q) => $q->where('sales_id', $salesId))
            ->selectRaw('status, COUNT(*) as jumlah, SUM(total_setor) as total')
            ->groupBy('status')
            ->get();

        // Hutang summary
        $hutangSummary = [
            'total_hutang' => MineralHutang::where('status', '!=', 'lunas')->sum('sisa'),
            'jumlah_pelanggan' => MineralHutang::where('status', '!=', 'lunas')->distinct('pelanggan_id')->count(),
            'hutang_baru' => MineralHutang::whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])->sum('total_hutang'),
        ];

        $salesList = MineralSales::where('status', 'aktif')->orderBy('nama')->get();

        return view('mineral.laporan.index', compact(
            'summary',
            'dailyData',
            'salesPerformance',
            'productPerformance',
            'setoranSummary',
            'hutangSummary',
            'salesList',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }
}

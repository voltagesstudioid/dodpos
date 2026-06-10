<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaPenjualan;
use App\Models\GulaSales;
use App\Models\GulaProduk;
use App\Models\GulaSetoran;
use App\Models\GulaHutang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LaporanController extends Controller
{
    /**
     * Build the shared report data used by both screen view and print view.
     */
    private function buildReportData(string $tanggalMulai, string $tanggalSelesai, ?int $salesId): array
    {
        // --- Base penjualan query ---
        $baseQuery = GulaPenjualan::whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
            ->where('status', '!=', 'batal');

        if ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }

        // --- KPI Summary ---
        $totalPenjualan   = (clone $baseQuery)->sum('total');
        $jumlahTransaksi  = (clone $baseQuery)->count();
        $totalTunai       = (clone $baseQuery)->where('tipe_bayar', 'tunai')->sum('total');
        $totalTransfer    = (clone $baseQuery)->where('tipe_bayar', 'transfer')->sum('total');
        $penjualanHutang  = (clone $baseQuery)->where('tipe_bayar', 'hutang')->sum('total');
        // Actual debt created (from hutang field, not total field)
        $hutangFieldSum   = (clone $baseQuery)->where('tipe_bayar', 'hutang')->sum('hutang');
        // Bayar di tempat from hutang transactions
        $bayarHutang      = (clone $baseQuery)->where('tipe_bayar', 'hutang')->sum('bayar');

        $summary = [
            'total_penjualan'   => $totalPenjualan,
            'jumlah_transaksi'  => $jumlahTransaksi,
            'total_tunai'       => $totalTunai,
            'total_transfer'    => $totalTransfer,
            'penjualan_hutang'  => $penjualanHutang,
            'hutang_field'      => $hutangFieldSum,
            'bayar_hutang'      => $bayarHutang,
        ];

        // --- Daily chart data with zero-fill for missing dates ---
        $rawDaily = (clone $baseQuery)
            ->selectRaw('DATE(tanggal_jual) as tanggal, COUNT(*) as jumlah, SUM(total) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $period = CarbonPeriod::create($tanggalMulai, $tanggalSelesai);
        $dailyData = collect();
        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $dailyData->push((object) [
                'tanggal' => $key,
                'jumlah'  => $rawDaily->has($key) ? $rawDaily[$key]->jumlah : 0,
                'total'   => $rawDaily->has($key) ? $rawDaily[$key]->total : 0,
            ]);
        }

        // --- Sales performance (respects sales_id filter) ---
        $salesQuery = GulaSales::where('status', 'aktif');
        if ($salesId) {
            $salesQuery->where('id', $salesId);
        }
        $salesPerformance = $salesQuery
            ->withCount(['penjualans as total_penjualan' => function ($q) use ($tanggalMulai, $tanggalSelesai, $salesId) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
                if ($salesId) $q->where('sales_id', $salesId);
            }])
            ->withSum(['penjualans as omzet' => function ($q) use ($tanggalMulai, $tanggalSelesai, $salesId) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
                if ($salesId) $q->where('sales_id', $salesId);
            }], 'total')
            ->get();

        // --- Product performance (respects sales_id filter) ---
        $productPerformance = GulaProduk::where('status', 'aktif')
            ->withCount(['penjualans as terjual' => function ($q) use ($tanggalMulai, $tanggalSelesai, $salesId) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
                if ($salesId) $q->where('sales_id', $salesId);
            }])
            ->withSum(['penjualans as omzet' => function ($q) use ($tanggalMulai, $tanggalSelesai, $salesId) {
                $q->whereBetween('tanggal_jual', [$tanggalMulai, $tanggalSelesai])
                  ->where('status', '!=', 'batal');
                if ($salesId) $q->where('sales_id', $salesId);
            }], 'total')
            ->orderByDesc('terjual')
            ->get();

        // --- Setoran summary (respects sales_id filter) ---
        $setoranSummary = GulaSetoran::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->when($salesId, fn($q) => $q->where('sales_id', $salesId))
            ->selectRaw('status, COUNT(*) as jumlah, COALESCE(SUM(total_setor),0) as total_setor, COALESCE(SUM(selisih),0) as total_selisih')
            ->groupBy('status')
            ->get();

        // --- Hutang summary (respects sales_id filter) ---
        $hutangQuery = GulaHutang::where('status', '!=', 'lunas');
        if ($salesId) {
            $hutangQuery->whereHas('penjualan', fn($q) => $q->where('sales_id', $salesId));
        }
        $hutangSummary = [
            'total_hutang'      => (clone $hutangQuery)->sum('sisa'),
            'jumlah_pelanggan'  => (clone $hutangQuery)->distinct('pelanggan_id')->count(),
            'hutang_baru'       => GulaHutang::whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
                ->when($salesId, fn($q) => $q->whereHas('penjualan', fn($q2) => $q2->where('sales_id', $salesId)))
                ->sum('total_hutang'),
        ];

        // --- Sales list for filter dropdown ---
        $salesList = GulaSales::where('status', 'aktif')->orderBy('nama')->get();

        // --- Top transactions for detail table ---
        $topTransactions = (clone $baseQuery)
            ->with(['pelanggan', 'produk', 'sales'])
            ->orderBy('tanggal_jual', 'desc')
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        return compact(
            'summary', 'dailyData', 'salesPerformance', 'productPerformance',
            'setoranSummary', 'hutangSummary', 'salesList', 'topTransactions',
            'tanggalMulai', 'tanggalSelesai'
        );
    }

    public function index(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());
        $salesId        = $request->input('sales_id') ? (int) $request->input('sales_id') : null;

        $data = $this->buildReportData($tanggalMulai, $tanggalSelesai, $salesId);

        return view('gula.laporan.index', $data);
    }

    public function print(Request $request)
    {
        $tanggalMulai   = $request->input('tanggal_mulai', now()->startOfMonth()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());
        $salesId        = $request->input('sales_id') ? (int) $request->input('sales_id') : null;

        $data = $this->buildReportData($tanggalMulai, $tanggalSelesai, $salesId);

        return view('gula.laporan.print', $data);
    }
}

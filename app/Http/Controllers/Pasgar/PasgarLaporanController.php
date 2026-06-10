<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarLoading;
use App\Models\PasgarPenjualan;
use App\Models\PasgarSales;
use App\Models\PasgarSetoran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasgarLaporanController extends Controller
{
    /**
     * Laporan Penjualan — sales transaction report with breakdown per sales person.
     */
    public function penjualan(Request $request)
    {
        $isPrint = $request->boolean('print');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', now()->format('Y-m-d'));
        $salesId  = $request->input('sales_id');

        // Base query
        $baseQuery = PasgarPenjualan::whereDate('tanggal', '>=', $dateFrom)
            ->whereDate('tanggal', '<=', $dateTo);

        if ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }

        // Summary stats (DB-level)
        $totalPenjualan   = (clone $baseQuery)->sum('total');
        $totalTransaksi   = (clone $baseQuery)->count();
        $totalTunai       = (clone $baseQuery)->where('metode_bayar', 'tunai')->sum('total');
        $totalTransfer    = (clone $baseQuery)->whereIn('metode_bayar', ['transfer', 'qris'])->sum('total');

        // Per-sales breakdown
        $bySales = (clone $baseQuery)
            ->select('sales_id',
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(total) as total_penjualan'),
                DB::raw("SUM(CASE WHEN metode_bayar = 'tunai' THEN total ELSE 0 END) as total_tunai"),
                DB::raw("SUM(CASE WHEN metode_bayar IN ('transfer','qris') THEN total ELSE 0 END) as total_transfer")
            )
            ->groupBy('sales_id')
            ->orderByDesc('total_penjualan')
            ->get();

        // Attach sales names
        $salesIds = $bySales->pluck('sales_id');
        $salesMap = PasgarSales::whereIn('id', $salesIds)->pluck('nama', 'id');
        foreach ($bySales as $row) {
            $row->nama_sales = $salesMap[$row->sales_id] ?? '-';
        }

        // Detail transactions (paginated or full for print)
        $detailQuery = (clone $baseQuery)->with('sales:id,nama,kode_sales')->latest('tanggal');
        if ($isPrint) {
            $details = $detailQuery->get();
        } else {
            $details = $detailQuery->paginate(25)->withQueryString();
        }

        $summary = compact('totalPenjualan', 'totalTransaksi', 'totalTunai', 'totalTransfer');
        $allSales = PasgarSales::where('status', 'aktif')->orderBy('nama')->get(['id', 'nama', 'kode_sales']);

        return view('pasgar.laporan.penjualan', compact(
            'isPrint', 'dateFrom', 'dateTo', 'salesId',
            'summary', 'bySales', 'details', 'allSales'
        ));
    }

    /**
     * Laporan Setoran — deposit verification report with per-sales breakdown.
     */
    public function setoran(Request $request)
    {
        $isPrint = $request->boolean('print');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', now()->format('Y-m-d'));
        $salesId  = $request->input('sales_id');
        $status   = $request->input('status');

        $baseQuery = PasgarSetoran::whereDate('tanggal', '>=', $dateFrom)
            ->whereDate('tanggal', '<=', $dateTo);

        if ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }
        if ($status) {
            $baseQuery->where('status', $status);
        }

        // Summary stats
        $totalSetor      = (clone $baseQuery)->where('status', 'terverifikasi')->sum('total_setor');
        $totalPenjualan  = (clone $baseQuery)->sum('total_penjualan');
        $totalTunai      = (clone $baseQuery)->sum('total_tunai');
        $totalTransfer   = (clone $baseQuery)->sum('total_transfer');
        $totalSelisih    = (clone $baseQuery)->where('status', 'terverifikasi')->sum('selisih');
        $countPending    = (clone $baseQuery)->where('status', 'pending')->count();
        $countVerified   = (clone $baseQuery)->where('status', 'terverifikasi')->count();
        $countRejected   = (clone $baseQuery)->where('status', 'ditolak')->count();

        // Per-sales breakdown
        $bySales = (clone $baseQuery)
            ->select('sales_id',
                DB::raw('COUNT(*) as jumlah_setoran'),
                DB::raw('SUM(total_penjualan) as total_penjualan'),
                DB::raw('SUM(total_setor) as total_setor'),
                DB::raw('SUM(selisih) as total_selisih'),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN status = 'terverifikasi' THEN 1 ELSE 0 END) as terverifikasi"),
                DB::raw("SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak")
            )
            ->groupBy('sales_id')
            ->orderByDesc('total_setor')
            ->get();

        $salesIds = $bySales->pluck('sales_id');
        $salesMap = PasgarSales::whereIn('id', $salesIds)->pluck('nama', 'id');
        foreach ($bySales as $row) {
            $row->nama_sales = $salesMap[$row->sales_id] ?? '-';
        }

        // Detail
        $detailQuery = (clone $baseQuery)->with('sales:id,nama,kode_sales')->latest('tanggal');
        if ($isPrint) {
            $details = $detailQuery->get();
        } else {
            $details = $detailQuery->paginate(25)->withQueryString();
        }

        $summary = compact('totalSetor', 'totalPenjualan', 'totalTunai', 'totalTransfer', 'totalSelisih', 'countPending', 'countVerified', 'countRejected');
        $allSales = PasgarSales::where('status', 'aktif')->orderBy('nama')->get(['id', 'nama', 'kode_sales']);

        return view('pasgar.laporan.setoran', compact(
            'isPrint', 'dateFrom', 'dateTo', 'salesId', 'status',
            'summary', 'bySales', 'details', 'allSales'
        ));
    }

}

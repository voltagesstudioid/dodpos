<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarPenjualan;
use App\Models\PasgarSales;
use App\Models\PasgarSetoran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasgarLaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $isPrint = $request->boolean('print');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', now()->format('Y-m-d'));
        $salesId  = $request->input('sales_id');

        $baseQuery = PasgarPenjualan::whereDate('tanggal', '>=', $dateFrom)
            ->whereDate('tanggal', '<=', $dateTo);

        if ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }

        $totalPenjualan = (clone $baseQuery)->sum('total');
        $totalTransaksi = (clone $baseQuery)->count();
        $totalTunai     = (clone $baseQuery)->where('metode_bayar', 'tunai')->sum('total');
        $totalTransfer  = (clone $baseQuery)->whereIn('metode_bayar', ['transfer', 'qris'])->sum('total');

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

        $salesIds = $bySales->pluck('sales_id');
        $salesMap = PasgarSales::whereIn('id', $salesIds)->pluck('nama', 'id');
        foreach ($bySales as $row) {
            $row->nama_sales = $salesMap[$row->sales_id] ?? '-';
        }

        $detailQuery = (clone $baseQuery)->with('sales:id,nama,kode_sales')->latest('tanggal');
        if ($isPrint) {
            $details = $detailQuery->get();
        } else {
            $details = $detailQuery->paginate(25)->withQueryString();
        }

        $rataRata = $totalTransaksi > 0 ? round($totalPenjualan / $totalTransaksi) : 0;

        $summary = compact('totalPenjualan', 'totalTransaksi', 'totalTunai', 'totalTransfer', 'rataRata');
        $allSales = PasgarSales::where('status', 'aktif')->orderBy('nama')->get(['id', 'nama', 'kode_sales']);

        return view('pasgar.laporan.penjualan', compact(
            'isPrint', 'dateFrom', 'dateTo', 'salesId',
            'summary', 'bySales', 'details', 'allSales'
        ));
    }

    public function setoran(Request $request)
    {
        $isPrint = $request->boolean('print');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('date_to', now()->format('Y-m-d'));
        $salesId  = $request->input('sales_id');
        $status   = $request->input('status');

        // Base query applies date range (and optional sales filter)
        $baseQuery = PasgarSetoran::whereDate('tanggal', '>=', $dateFrom)
            ->whereDate('tanggal', '<=', $dateTo);

        if ($salesId) {
            $baseQuery->where('sales_id', $salesId);
        }

        // Summary stats across all statuses (unfiltered by status)
        $totalSetorAll     = (clone $baseQuery)->sum('total_setor');
        $totalPenjualanAll = (clone $baseQuery)->sum('total_penjualan');
        $totalTunaiAll     = (clone $baseQuery)->sum('total_tunai');
        $totalTransferAll  = (clone $baseQuery)->sum('total_transfer');
        $totalCountAll     = (clone $baseQuery)->count();
        $countPendingAll   = (clone $baseQuery)->where('status', 'pending')->count();
        $countVerifiedAll  = (clone $baseQuery)->where('status', 'terverifikasi')->count();
        $countRejectedAll  = (clone $baseQuery)->where('status', 'ditolak')->count();

        // Verified-only stats (for the "verified" summary cards)
        $verifiedQuery = (clone $baseQuery)->where('status', 'terverifikasi');
        $totalSetorVerified   = (clone $verifiedQuery)->sum('total_setor');
        $totalSelisihVerified = (clone $verifiedQuery)->sum('selisih');
        $countVerified        = (clone $verifiedQuery)->count();

        // Apply status filter for detail queries
        $detailQuery = (clone $baseQuery);
        if ($status) {
            $detailQuery->where('status', $status);
        }

        // Per-sales breakdown (respects all filters including status)
        $bySales = (clone $detailQuery)
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

        // Detail transactions
        $detailsQuery = (clone $detailQuery)->with('sales:id,nama,kode_sales')->latest('tanggal');
        if ($isPrint) {
            $details = $detailsQuery->get();
        } else {
            $details = $detailsQuery->paginate(25)->withQueryString();
        }

        $summary = [
            'totalSetor'       => $totalSetorAll,
            'totalSetorVerified' => $totalSetorVerified,
            'totalPenjualan'   => $totalPenjualanAll,
            'totalTunai'       => $totalTunaiAll,
            'totalTransfer'    => $totalTransferAll,
            'totalSelisih'     => $totalSelisihVerified,
            'totalCount'       => $totalCountAll,
            'countPending'     => $countPendingAll,
            'countVerified'    => $countVerifiedAll,
            'countRejected'    => $countRejectedAll,
        ];

        $allSales = PasgarSales::where('status', 'aktif')->orderBy('nama')->get(['id', 'nama', 'kode_sales']);

        return view('pasgar.laporan.setoran', compact(
            'isPrint', 'dateFrom', 'dateTo', 'salesId', 'status',
            'summary', 'bySales', 'details', 'allSales'
        ));
    }
}

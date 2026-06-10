<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaKunjungan;
use App\Models\GulaSales;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());
        $salesId = $request->input('sales_id');
        $status = $request->input('status');

        // Base query
        $query = GulaKunjungan::query()
            ->with(['sales', 'pelanggan'])
            ->whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai);

        // Filters
        if ($salesId) {
            $query->where('sales_id', $salesId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $kunjungans = $query->orderBy('waktu_checkin', 'desc')->paginate(20)->withQueryString();

        // Statistics (recompute with same filter scope)
        $baseQuery = GulaKunjungan::query()
            ->whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai)
            ->when($salesId, fn($q) => $q->where('sales_id', $salesId));

        $totalKunjungan = (clone $baseQuery)->count();
        $kunjunganSelesai = (clone $baseQuery)->whereNotNull('waktu_checkout')->count();
        $kunjunganBertransaksi = (clone $baseQuery)->where('ada_penjualan', true)->count();

        // Average duration in minutes from completed visits
        $avgDurasi = 0;
        $completedVisits = (clone $baseQuery)->whereNotNull('waktu_checkout')->get();
        if ($completedVisits->count() > 0) {
            $totalMinutes = $completedVisits->sum(function ($k) {
                return $k->waktu_checkin->diffInMinutes($k->waktu_checkout);
            });
            $avgDurasi = round($totalMinutes / $completedVisits->count());
        }

        $stats = [
            'total_kunjungan' => $totalKunjungan,
            'kunjungan_selesai' => $kunjunganSelesai,
            'kunjungan_bertransaksi' => $kunjunganBertransaksi,
            'durasi_rata_rata' => $avgDurasi,
        ];

        // Sales list for filter
        $salesList = GulaSales::where('status', 'aktif')->orderBy('nama')->get();

        // Kunjungan by sales for chart
        $kunjunganBySales = GulaKunjungan::whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai)
            ->selectRaw('sales_id, COUNT(*) as total')
            ->groupBy('sales_id')
            ->with('sales')
            ->get();

        return view('gula.kunjungan.index', compact(
            'kunjungans',
            'stats',
            'salesList',
            'kunjunganBySales',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    public function show(GulaKunjungan $kunjungan)
    {
        $kunjungan->load(['sales', 'pelanggan']);
        return view('gula.kunjungan.show', compact('kunjungan'));
    }
}

<?php

namespace App\Http\Controllers\Api\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralSetoran;
use App\Models\MineralTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SetoranController extends Controller
{
    /**
     * Hitung total tagihan penjualan hari ini (Cash & Tempo) untuk modal form "Closing"
     */
    public function summary(Request $request)
    {
        $salesId = $request->user()->id;
        $today = Carbon::today();

        // Cari transaksi tunai hari ini
        $cashTotal = MineralTransaction::where('sales_id', $salesId)
            ->whereDate('created_at', $today)
            ->where('payment_method', 'cash')
            ->sum('total_amount');

        // Cari transaksi piutang/tempo hari ini
        $tempoTotal = MineralTransaction::where('sales_id', $salesId)
            ->whereDate('created_at', $today)
            ->where('payment_method', 'tempo')
            ->sum('total_amount');

        return response()->json([
            'status' => 'success',
            'data' => [
                'expected_cash' => $cashTotal,
                'expected_tempo' => $tempoTotal,
                'total_sales_value' => $cashTotal + $tempoTotal
            ]
        ]);
    }

    /**
     * Push form setoran akhir (Uang Tunai Real) ke Admin Web.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'expected_cash' => 'required|numeric|min:0',
            'expected_tempo' => 'required|numeric|min:0',
            'actual_cash' => 'required|numeric|min:0' // Uang tunai yang benar dihitung Sales di tangan
        ]);

        $salesId = $request->user()->id;

        // Pastikan belum closing hari ini
        $alreadySubmitted = MineralSetoran::where('sales_id', $salesId)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadySubmitted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah merekam Setoran Shift untuk hari ini.'
            ], 422);
        }

        $setoran = MineralSetoran::create([
            'sales_id' => $salesId,
            'expected_cash' => $request->expected_cash,
            'expected_tempo' => $request->expected_tempo,
            'actual_cash' => $request->actual_cash,
            'status' => 'pending' // Menunggu disetujui SPV di Web Admin
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan Rekonsiliasi (Setoran) berhasi dikirim ke Admin Pusat.',
            'data' => $setoran
        ]);
    }
}

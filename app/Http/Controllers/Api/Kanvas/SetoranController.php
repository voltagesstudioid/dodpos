<?php

namespace App\Http\Controllers\Api\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasSetoran;
use App\Models\KanvasTransaction;
use Carbon\Carbon;

class SetoranController extends Controller
{
    /**
     * Hitung rekap nilai (Cash vs Piutang) sebelum final closing sore
     */
    public function summary()
    {
        $user = auth()->user();

        // Cari transaksi hari ini
        $transactions = KanvasTransaction::where('sales_id', $user->id)
                                         ->whereDate('created_at', Carbon::today())
                                         ->get();

        $totalCash = $transactions->where('payment_method', 'cash')->sum('grand_total');
        // DP / Uang Muka transaksi tempo
        $dpTempo = $transactions->where('payment_method', 'tempo')->sum('paid_amount');
        
        $expectedCash = $totalCash + $dpTempo; // Uang riil seharusnya ditangan
        
        // Sisa yang jadi piutang toko
        $expectedTempo = $transactions->where('payment_method', 'tempo')
                                      ->sum(function($t) {
                                          return $t->grand_total - $t->paid_amount;
                                      });

        return response()->json([
            'status' => 'success',
            'data' => [
                'expected_cash' => $expectedCash,
                'expected_tempo' => $expectedTempo,
                'transactions_count' => $transactions->count()
            ]
        ]);
    }

    /**
     * Final Submit Rekonsiliasi (Sales Pulang & Setor di Kasir)
     */
    public function submit(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric'
        ]);

        $user = auth()->user();
        
        $summary = $this->summary()->getData(true)['data'];

        $setoran = KanvasSetoran::create([
            'sales_id' => $user->id,
            'expected_cash' => $summary['expected_cash'],
            'actual_cash' => $request->actual_cash,
            'expected_tempo' => $summary['expected_tempo'],
            'status' => 'pending', 
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan Closing Berhasil Disubmit. Tunggu Verifikasi Admin/Kasir.',
            'data' => $setoran
        ]);
    }
}

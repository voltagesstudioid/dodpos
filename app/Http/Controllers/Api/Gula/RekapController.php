<?php

namespace App\Http\Controllers\Api\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    // Mengambil snapshot rekap sebelum submit
    public function summary(\Illuminate\Http\Request $request)
    {
        $user = $request->user();
        $date = now()->toDateString();

        // 1. Total Transaksi Tunai Hari Ini (Uang Cash di Tangan)
        $totalCash = \App\Models\GulaTransaction::where('sales_id', $user->id)
            ->whereDate('date', $date)
            ->where('payment_method', 'cash')
            ->sum('grand_total');

        // 2. Total Piutang Tempo Hari Ini (Tagihan baru)
        $totalPiutangBaru = \App\Models\GulaTransaction::where('sales_id', $user->id)
            ->whereDate('date', $date)
            ->where('payment_method', 'tempo')
            ->sum('grand_total');
            
        // 3. (Optional) Pembayaran Piutang Lama yang ditagih hari ini
        // Jika ada fitur penagihan piutang, tambahkan di sini ke Total Cash
        $totalBayarPiutang = \App\Models\CustomerCredit::where('sales_id', $user->id)
            ->whereDate('updated_at', $date)
            ->whereColumn('updated_at', '!=', 'created_at') // Hanya yang diupdate (dibayar)
            ->where('status', 'paid')
            ->sum('amount');
        
        $totalUangFisik = $totalCash + $totalBayarPiutang;

        // 4. Sisa Barang Fisik di Mobil saat ini
        $sisaStok = \App\Models\GulaVehicleStock::where('sales_id', $user->id)
            ->with(['product' => function($q) {
                $q->select('id', 'name');
            }])
            ->get()->map(function($stock) {
                return [
                    'product_name' => $stock->product->name,
                    'qty_karung' => $stock->qty_karung,
                    'qty_eceran' => $stock->qty_eceran,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'date' => $date,
                'total_cash_from_sales' => $totalCash,
                'total_cash_from_credits' => $totalBayarPiutang,
                'expected_physical_cash' => $totalUangFisik,
                'total_new_piutang' => $totalPiutangBaru,
                'remaining_stocks' => $sisaStok
            ]
        ]);
    }

    // Submit Rekap ke Admin untuk divalidasi
    public function submit(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $user = $request->user();
        $date = now()->toDateString();
        
        // Cek apakah sudah setoran hari ini (hindari double submit)
        $existing = \App\Models\GulaSetoran::where('sales_id', $user->id)
            ->whereDate('date', $date)
            ->first();
            
        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda telah melakukan laporan closing setoran hari ini.'
            ], 400);
        }

        // Hitung total piutang
        $totalPiutangBaru = \App\Models\GulaTransaction::where('sales_id', $user->id)
            ->whereDate('date', $date)
            ->where('payment_method', 'tempo')
            ->sum('grand_total');

        // Buat record Setoran 
        // Admin nanti akan memvalidasi actual cash VS expected cash
        $setoran = \App\Models\GulaSetoran::create([
            'date' => now(),
            'sales_id' => $user->id,
            'total_cash' => $request->actual_cash,
            'total_piutang' => $totalPiutangBaru,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Setoran Akhir Hari berhasi dikirim ke Admin Web.',
            'data' => $setoran
        ]);
    }
}

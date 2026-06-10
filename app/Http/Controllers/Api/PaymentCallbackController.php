<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MineralPenjualan;
use App\Models\MinyakPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    /**
     * Handle simulated payment gateway callback
     */
    public function handleCallback(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required|string',
        ]);

        $noFaktur = $request->input('no_faktur');
        Log::info('Payment callback received for invoice: ' . $noFaktur);

        $verified = false;
        $division = '';

        if (str_starts_with($noFaktur, 'FKM')) {
            $penjualan = MineralPenjualan::where('no_faktur', $noFaktur)->first();
            if ($penjualan) {
                $penjualan->update([
                    'status' => 'terverifikasi',
                    'verified_at' => now(),
                ]);
                $verified = true;
                $division = 'mineral';
            }
        } elseif (str_starts_with($noFaktur, 'FKT')) {
            $penjualan = MinyakPenjualan::where('no_faktur', $noFaktur)->first();
            if ($penjualan) {
                $penjualan->update([
                    'status' => 'terverifikasi',
                    'verified_at' => now(),
                ]);
                $verified = true;
                $division = 'minyak';
            }
        }

        if ($verified) {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diverifikasi secara otomatis.',
                'data' => [
                    'no_faktur' => $noFaktur,
                    'division' => $division,
                    'status' => 'terverifikasi'
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Transaksi tidak ditemukan atau format faktur tidak valid.'
        ], 404);
    }

    /**
     * Polling endpoint to check transaction status
     */
    public function checkStatus(Request $request)
    {
        $noFaktur = $request->query('no_faktur');

        if (!$noFaktur) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter no_faktur diperlukan.'
            ], 400);
        }

        $status = 'unknown';

        if (str_starts_with($noFaktur, 'FKM')) {
            $penjualan = MineralPenjualan::where('no_faktur', $noFaktur)->first();
            if ($penjualan) {
                $status = $penjualan->status;
            }
        } elseif (str_starts_with($noFaktur, 'FKT')) {
            $penjualan = MinyakPenjualan::where('no_faktur', $noFaktur)->first();
            if ($penjualan) {
                $status = $penjualan->status;
            }
        }

        return response()->json([
            'success' => true,
            'no_faktur' => $noFaktur,
            'status' => $status
        ]);
    }
}

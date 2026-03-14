<?php

namespace App\Http\Controllers\Api\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:gula_products,id',
            'unit_type' => 'required|in:karung,eceran',
            'qty' => 'required|numeric|min:0.5',
            'reason' => 'required|string|max:500'
        ]);

        $user = $request->user();

        // Catat Retur Gula
        $retur = \App\Models\GulaReturn::create([
            'date' => now(),
            'sales_id' => $user->id,
            'customer_id' => $request->customer_id,
            'gula_product_id' => $request->product_id,
            'unit_type' => $request->unit_type,
            'qty' => $request->qty,
            'reason' => $request->reason,
            'status' => 'pending' // Menunggu approval dari admin saat setoran
        ]);

        // Catatan: Pada kasus gula, barang rusak diserahkan fisik kembali ke admin saat setoran,
        // jadi kita belum perlu otomatis menambah stok retur ke gudang/armada di titik API ini.

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan Retur Barang Rusak berhasil dikirim.',
            'data' => $retur
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Minyak;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * GET /api/minyak/transaksi
     * Daftar transaksi hari ini milik sales yang login
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $user    = $request->user();

        $transaksi = DB::table('minyak_transaksis as mt')
            ->leftJoin('customers as c', 'c.id', '=', 'mt.customer_id')
            ->where('mt.user_id', $user->id)
            ->whereDate('mt.tanggal', $tanggal)
            ->select('mt.*', 'c.name as customer_name')
            ->orderByDesc('mt.created_at')
            ->get();

        $totalQty   = $transaksi->sum('qty');
        $totalHarga = $transaksi->sum('total_harga');

        return response()->json([
            'status'      => 'success',
            'tanggal'     => $tanggal,
            'data'        => $transaksi,
            'total_qty'   => $totalQty,
            'total_harga' => $totalHarga,
        ]);
    }

    /**
     * POST /api/minyak/transaksi
     * Catat penjualan minyak baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'  => 'nullable|exists:customers,id',
            'nama_warung'  => 'nullable|string|max:200',
            'qty'          => 'required|numeric|min:0.1',
            'satuan'       => 'required|in:liter,kg',
            'harga_satuan' => 'required|numeric|min:0',
            'catatan'      => 'nullable|string|max:500',
        ]);

        $totalHarga = $request->qty * $request->harga_satuan;

        $id = DB::table('minyak_transaksis')->insertGetId([
            'user_id'      => $request->user()->id,
            'customer_id'  => $request->customer_id,
            'nama_warung'  => $request->nama_warung,
            'tanggal'      => today()->format('Y-m-d'),
            'qty'          => $request->qty,
            'satuan'       => $request->satuan,
            'harga_satuan' => $request->harga_satuan,
            'total_harga'  => $totalHarga,
            'catatan'      => $request->catatan,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi berhasil disimpan.',
            'id'      => $id,
            'total'   => $totalHarga,
        ], 201);
    }

    /**
     * DELETE /api/minyak/transaksi/{id}
     * Hapus transaksi (hanya milik sendiri)
     */
    public function destroy(Request $request, $id)
    {
        $deleted = DB::table('minyak_transaksis')
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->delete();

        if (!$deleted) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi tidak ditemukan.'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Transaksi dihapus.']);
    }

    /**
     * GET /api/minyak/rekap
     * Rekap setoran harian sales
     */
    public function rekap(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));
        $user    = $request->user();

        $transaksi = DB::table('minyak_transaksis as mt')
            ->leftJoin('customers as c', 'c.id', '=', 'mt.customer_id')
            ->where('mt.user_id', $user->id)
            ->whereDate('mt.tanggal', $tanggal)
            ->select('mt.*', 'c.name as customer_name')
            ->orderBy('mt.created_at')
            ->get();

        $totalQty    = $transaksi->sum('qty');
        $totalHarga  = $transaksi->sum('total_harga');
        $jumlahToko  = $transaksi->pluck('customer_id')->filter()->unique()->count()
                     + $transaksi->whereNull('customer_id')->count();

        return response()->json([
            'status'       => 'success',
            'tanggal'      => $tanggal,
            'sales_name'   => $user->name,
            'total_qty'    => $totalQty,
            'total_harga'  => $totalHarga,
            'jumlah_toko'  => $jumlahToko,
            'transaksi'    => $transaksi,
        ]);
    }
}

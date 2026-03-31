<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Sales Dashboard - Mobile Optimized
     */
    public function dashboard()
    {
        return view('sales.dashboard');
    }

    /**
     * Show sales menu
     */
    public function menu()
    {
        return view('sales.menu');
    }

    /**
     * Step 2: Select Produk for penjualan
     */
    public function selectProduk()
    {
        return view('sales.penjualan.produk');
    }

    /**
     * Store penjualan
     */
    public function storePenjualan(Request $request)
    {
        // Process the sale - API will handle this
        return redirect()->route('sales.dashboard')->with('success', 'Penjualan tersimpan');
    }

    /**
     * Create penjualan form
     */
    public function createPenjualan()
    {
        return view('sales.penjualan.create');
    }

    /**
     * List penjualan history
     */
    public function listPenjualan()
    {
        return view('sales.penjualan.list');
    }

    /**
     * Loading/stok kendaraan
     */
    public function loading()
    {
        return view('sales.loading');
    }

    /**
     * Hutang list
     */
    public function hutang()
    {
        return view('sales.hutang');
    }

    /**
     * Bayar hutang
     */
    public function bayarHutang(Request $request, $id)
    {
        return redirect()->route('sales.hutang')->with('success', 'Pembayaran hutang tersimpan');
    }

    /**
     * Setoran form
     */
    public function setoran()
    {
        return view('sales.setoran');
    }

    /**
     * Store setoran
     */
    public function storeSetoran(Request $request)
    {
        return redirect()->route('sales.dashboard')->with('success', 'Setoran berhasil disimpan');
    }

    /**
     * Kunjungan form
     */
    public function createKunjungan()
    {
        return view('sales.kunjungan.create');
    }

    /**
     * List kunjungan
     */
    public function listKunjungan()
    {
        return view('sales.kunjungan.list');
    }

    /**
     * Store kunjungan
     */
    public function storeKunjungan(Request $request)
    {
        return redirect()->route('sales.dashboard')->with('success', 'Kunjungan tercatat');
    }

    /**
     * Pelanggan list
     */
    public function pelanggan()
    {
        return view('sales.pelanggan');
    }

    /**
     * Sync data offline
     */
    public function sync()
    {
        return response()->json([
            'success' => true,
            'message' => 'Sync endpoint ready'
        ]);
    }
}

<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard Tangki & Penjualan Minyak
     * Menampilkan ringkasan penjualan harian minyak dari seluruh armada sales.
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', today()->format('Y-m-d'));

        // ── Total terjual per produk hari ini dari semua sales ────────────
        // Menggunakan stock_movements bertipe 'out' dari gudang kendaraan
        $penjualanHariIni = DB::table('stock_movements as sm')
            ->join('products as p', 'p.id', '=', 'sm.product_id')
            ->join('units as u', 'u.id', '=', 'p.unit_id')
            ->join('warehouses as w', 'w.id', '=', 'sm.warehouse_id')
            ->where('sm.type', 'out')
            ->where('sm.source_type', 'sale')
            ->whereDate('sm.created_at', $tanggal)
            ->select(
                'p.id',
                'p.name',
                'u.name as unit',
                DB::raw('SUM(sm.quantity) as total_qty'),
                DB::raw('COUNT(DISTINCT sm.warehouse_id) as jumlah_armada')
            )
            ->groupBy('p.id', 'p.name', 'u.name')
            ->orderByDesc('total_qty')
            ->get();

        // ── Stok minyak di semua kendaraan (gudang virtual) ──────────────
        $stokKendaraan = DB::table('product_stocks as ps')
            ->join('products as p', 'p.id', '=', 'ps.product_id')
            ->join('units as u', 'u.id', '=', 'p.unit_id')
            ->join('warehouses as w', 'w.id', '=', 'ps.warehouse_id')
            ->whereExists(function ($q) {
                $q->from('vehicles')->whereColumn('vehicles.warehouse_id', 'ps.warehouse_id');
            })
            ->where('ps.stock', '>', 0)
            ->select(
                'w.name as kendaraan',
                'p.name as produk',
                'u.name as satuan',
                'ps.stock'
            )
            ->orderBy('w.name')
            ->orderByDesc('ps.stock')
            ->get();

        // ── Ringkasan per kendaraan ───────────────────────────────────────
        $armada = DB::table('vehicles as v')
            ->join('warehouses as w', 'w.id', '=', 'v.warehouse_id')
            ->leftJoin('product_stocks as ps', 'ps.warehouse_id', '=', 'v.warehouse_id')
            ->leftJoin('products as p', 'p.id', '=', 'ps.product_id')
            ->select(
                'v.id',
                'v.license_plate as kendaraan',
                'w.name as gudang_virtual',
                DB::raw('COUNT(DISTINCT ps.product_id) as jenis_produk'),
                DB::raw('COALESCE(SUM(ps.stock), 0) as total_stok')
            )
            ->groupBy('v.id', 'v.license_plate', 'w.name')
            ->get();

        // ── Total penjualan hari ini (semua produk) ───────────────────────
        $totalTerjualHariIni = DB::table('stock_movements')
            ->where('type', 'out')
            ->where('source_type', 'sale')
            ->whereDate('created_at', $tanggal)
            ->sum('quantity');

        return view('minyak.dashboard', compact(
            'tanggal',
            'penjualanHariIni',
            'stokKendaraan',
            'armada',
            'totalTerjualHariIni'
        ));
    }
}

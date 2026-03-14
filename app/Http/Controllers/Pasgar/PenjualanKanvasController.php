<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\PasgarMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PenjualanKanvasController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua user dengan role pasgar
        $pasgarUserIds = User::where('role', 'pasgar')->pluck('id');

        $query = SalesOrder::with(['customer', 'user', 'items.product'])
            ->whereIn('user_id', $pasgarUserIds)
            ->latest('order_date');

        // Filter tanggal
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');
        $query->whereDate('order_date', '>=', $dateFrom)
              ->whereDate('order_date', '<=', $dateTo);

        // Filter anggota
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter tipe order (hanya jika kolom sudah ada)
        $hasOrderType = Schema::hasColumn('sales_orders', 'order_type');
        if ($hasOrderType && $request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        $orders = $query->paginate(20)->withQueryString();

        // Summary
        $summaryQuery = SalesOrder::whereIn('user_id', $pasgarUserIds)
            ->whereDate('order_date', '>=', $dateFrom)
            ->whereDate('order_date', '<=', $dateTo);

        if ($request->user_id) {
            $summaryQuery->where('user_id', $request->user_id);
        }

        $totalOrders  = $summaryQuery->count();
        $totalAmount  = $summaryQuery->sum('total_amount');

        // Hanya query order_type jika kolom sudah ada di database
        if (!isset($hasOrderType)) {
            $hasOrderType = Schema::hasColumn('sales_orders', 'order_type');
        }
        $totalCanvas   = $hasOrderType ? (clone $summaryQuery)->where('order_type', 'canvas')->count() : 0;
        $totalPreorder = $hasOrderType ? (clone $summaryQuery)->where('order_type', 'preorder')->count() : 0;

        // Daftar anggota untuk filter
        $pasgarUsers = User::where('role', 'pasgar')->orderBy('name')->get();

        return view('pasgar.penjualan.index', compact(
            'orders', 'pasgarUsers', 'dateFrom', 'dateTo',
            'totalOrders', 'totalAmount', 'totalCanvas', 'totalPreorder'
        ));
    }

    public function show(SalesOrder $order)
    {
        $order->load(['customer', 'user', 'items.product.unit']);
        return view('pasgar.penjualan.show', compact('order'));
    }
}

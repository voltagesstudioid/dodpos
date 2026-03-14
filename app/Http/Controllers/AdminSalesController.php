<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;

// You can add more models here as needed for dashboard metrics, e.g., Transaction, Product

class AdminSalesController extends Controller
{
    /**
     * Display the Admin Sales Dashboard.
     */
    public function index()
    {
        // Fetch some basic metrics for the dashboard
        $totalSalesOrders = SalesOrder::count();
        $pendingSalesOrders = SalesOrder::whereIn('status', ['confirmed', 'processing'])->count();
        $completedSalesOrders = SalesOrder::where('status', 'completed')->count();

        // You can add logic to calculate today's revenue, active cashiers, etc.
        // For now, we'll pass basic metrics.

        $recentOrders = SalesOrder::with(['customer', 'user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin_sales.dashboard', compact(
            'totalSalesOrders',
            'pendingSalesOrders',
            'completedSalesOrders',
            'recentOrders'
        ));
    }
}

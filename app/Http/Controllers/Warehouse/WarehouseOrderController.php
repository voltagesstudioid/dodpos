<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseOrderController extends Controller
{
    /**
     * Dashboard admin gudang - lihat pesanan yang perlu dikemas
     */
    public function index(Request $request)
    {
        $warehouseId = $request->user()->warehouse_id ?? $request->get('warehouse_id');
        
        // Auto-backfill for legacy transactions that were missing source_warehouse_id
        $missingTxs = Transaction::where('status', 'completed')
            ->where('delivery_status', 'pending')
            ->whereNull('source_warehouse_id')
            ->get();
            
        foreach ($missingTxs as $t) {
            $firstDetail = $t->details()->first();
            if ($firstDetail && $firstDetail->warehouse_id) {
                $t->source_warehouse_id = $firstDetail->warehouse_id;
                $t->save();
            }
        }

        $query = Transaction::with(['details.product', 'user', 'customer', 'sourceWarehouse'])
            ->where('status', 'completed')
            ->where('source_warehouse_id', '!=', null);
            
        // Filter by warehouse if user has assigned warehouse
        if ($warehouseId) {
            $query->where('source_warehouse_id', $warehouseId);
        }
        
        // Filter by delivery status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('delivery_status', $status);
        }
        
        $orders = $query->latest()->paginate(20)->withQueryString();
        
        // Count by status
        $baseCountQuery = Transaction::where('status', 'completed')
            ->whereNotNull('source_warehouse_id')
            ->when($warehouseId, fn($q) => $q->where('source_warehouse_id', $warehouseId));

        $counts = [
            'pending' => (clone $baseCountQuery)->where('delivery_status', 'pending')->count(),
            'packing' => (clone $baseCountQuery)->where('delivery_status', 'packing')->count(),
            'packed' => (clone $baseCountQuery)->where('delivery_status', 'packed')->count(),
            'in_transit' => (clone $baseCountQuery)->where('delivery_status', 'in_transit')->count(),
            'delivered' => (clone $baseCountQuery)->where('delivery_status', 'delivered')->count(),
        ];
        
        $warehouses = Warehouse::where('active', true)->get();
        
        return view('warehouse.orders.index', compact('orders', 'counts', 'warehouses', 'status', 'warehouseId'));
    }
    
    /**
     * Detail pesanan untuk packing
     */
    public function show(Transaction $order)
    {
        $order->load(['details.product', 'details.warehouse', 'user', 'customer', 'sourceWarehouse', 
                      'packedBy', 'checkedBy', 'deliveredBy', 'additionalTransactions.details.product']);
        
        // Get all items from parent and additional transactions
        $allDetails = collect($order->details);
        foreach ($order->additionalTransactions as $addTrans) {
            $allDetails = $allDetails->merge($addTrans->details);
        }
        
        // Group by warehouse
        $itemsByWarehouse = $allDetails->groupBy('warehouse_id');
        
        return view('warehouse.orders.show', compact('order', 'allDetails', 'itemsByWarehouse'));
    }
    
    /**
     * Mulai packing - update status
     */
    public function startPacking(Transaction $order)
    {
        if ($order->delivery_status !== 'pending') {
            return back()->with('error', 'Pesanan sudah dalam proses packing atau selesai.');
        }
        
        $order->update([
            'delivery_status' => 'packing',
            'packed_by' => Auth::id(),
        ]);
        
        return redirect()->route('warehouse.orders.show', $order)
            ->with('success', 'Packing dimulai. Silakan kemas barang sesuai pesanan.');
    }
    
    /**
     * Selesai packing - siap cross-check
     */
    public function finishPacking(Transaction $order)
    {
        if ($order->delivery_status !== 'packing') {
            return back()->with('error', 'Pesanan belum dalam proses packing.');
        }
        
        $order->update([
            'delivery_status' => 'packed',
            'packed_at' => now(),
        ]);
        
        return redirect()->route('warehouse.orders.show', $order)
            ->with('success', 'Packing selesai. Menunggu cross-check admin.');
    }
    
    /**
     * Cross-check oleh admin - konfirmasi barang sesuai
     */
    public function crossCheck(Request $request, Transaction $order)
    {
        if ($order->delivery_status !== 'packed') {
            return back()->with('error', 'Pesanan belum siap untuk cross-check.');
        }
        
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
            'items_checked' => 'required|array',
            'items_checked.*' => 'boolean',
        ]);
        
        $order->update([
            'delivery_status' => 'in_transit',
            'checked_by' => Auth::id(),
            'checked_at' => now(),
            'delivery_notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()->route('warehouse.orders.show', $order)
            ->with('success', 'Cross-check berhasil. Barang siap diantar ke grosir.');
    }
    
    /**
     * Konfirmasi pengiriman - barang diterima di grosir
     */
    public function confirmDelivery(Request $request, Transaction $order)
    {
        if ($order->delivery_status !== 'in_transit') {
            return back()->with('error', 'Pesanan belum dalam pengiriman.');
        }
        
        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);
        
        $order->update([
            'delivery_status' => 'delivered',
            'delivered_by' => Auth::id(),
            'delivered_at' => now(),
            'delivery_notes' => ($order->delivery_notes ? $order->delivery_notes . "\n" : '') . 
                "[Delivery] " . now()->format('d/m/Y H:i') . " - " . ($validated['notes'] ?? 'Barang diterima di grosir'),
        ]);
        
        return redirect()->route('warehouse.orders.show', $order)
            ->with('success', 'Pengiriman berhasil dikonfirmasi. Barang telah diterima di grosir.');
    }
}

<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\PosPickOrder;
use App\Models\PosPickOrderItem;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosPickOrderController extends Controller
{
    /**
     * Display list of pick orders for warehouse admin
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $search = $request->input('search');

        $pickOrders = PosPickOrder::with(['transaction', 'warehouse', 'requester', 'items.product'])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pick_number', 'like', "%{$search}%")
                        ->orWhereHas('transaction', function ($tq) use ($search) {
                            $tq->where('invoice_number', 'like', "%{$search}%");
                        })
                        ->orWhereHas('requester', function ($rq) use ($search) {
                            $rq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Count for badges
        $counts = [
            'pending' => PosPickOrder::where('status', 'pending')->count(),
            'processing' => PosPickOrder::where('status', 'processing')->count(),
            'ready' => PosPickOrder::where('status', 'ready')->count(),
            'completed' => PosPickOrder::where('status', 'completed')->count(),
        ];

        return view('gudang.pos_pick_orders.index', compact('pickOrders', 'status', 'counts'));
    }

    /**
     * Show pick order detail for warehouse processing
     */
    public function show(PosPickOrder $pickOrder)
    {
        $pickOrder->load(['transaction.details.product', 'warehouse', 'requester', 'processor', 'confirmer', 'items.product']);

        // Check stock availability for each item
        $stockChecks = [];
        foreach ($pickOrder->items as $item) {
            $availableStock = ProductStock::where('warehouse_id', $pickOrder->warehouse_id)
                ->where('product_id', $item->product_id)
                ->sum('stock');

            $stockChecks[$item->product_id] = [
                'available' => $availableStock,
                'requested' => $item->quantity,
                'sufficient' => $availableStock >= $item->quantity,
            ];
        }

        return view('gudang.pos_pick_orders.show', compact('pickOrder', 'stockChecks'));
    }

    /**
     * Start processing pick order
     */
    public function process(PosPickOrder $pickOrder)
    {
        abort_if($pickOrder->status !== 'pending', 403, 'Pick order sudah diproses');

        $pickOrder->update([
            'status' => 'processing',
            'processed_by' => Auth::id(),
        ]);

        return redirect()->route('gudang.pos_pick.show', $pickOrder)
            ->with('success', 'Pick order sedang diproses. Silakan siapkan barang.');
    }

    /**
     * Mark pick order as ready (items prepared)
     */
    public function markReady(Request $request, PosPickOrder $pickOrder)
    {
        abort_if(!in_array($pickOrder->status, ['pending', 'processing']), 403, 'Status tidak valid');

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $pickOrder->update([
            'status' => 'ready',
            'processed_by' => $pickOrder->processed_by ?? Auth::id(),
            'ready_at' => now(),
            'notes' => $validated['notes'] ?? $pickOrder->notes,
        ]);

        return redirect()->route('gudang.pos_pick.show', $pickOrder)
            ->with('success', 'Barang sudah siap! Kasir dapat mengambil.');
    }

    /**
     * Complete pick order (when cashier confirms receipt)
     * Note: Stock is already deducted by the POS transaction, so we only update status here.
     */
    public function complete(PosPickOrder $pickOrder)
    {
        abort_if($pickOrder->status !== 'ready', 403, 'Barang belum siap');

        try {
            DB::beginTransaction();

            $pickOrder->update([
                'status' => 'completed',
                'confirmed_by' => Auth::id(),
                'confirmed_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('gudang.pos_pick.index')
                ->with('success', 'Pick order selesai.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Get pending count for AJAX/badge updates
     */
    public function pendingCount()
    {
        $count = PosPickOrder::whereIn('status', ['pending', 'processing'])->count();
        return response()->json(['count' => $count]);
    }
}

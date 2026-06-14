<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\PosPickOrder;
use App\Models\PosPickOrderItem;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Support\WarehouseConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosPickOrderController extends Controller
{
    private function guardWarehouseAccess(PosPickOrder $pickOrder): void
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));

        // Supervisor is read-only — cannot process pick orders
        if ($role === 'supervisor') {
            abort(403, 'Supervisor hanya dapat memantau persiapan barang, tidak dapat memproses.');
        }

        $userWhId = WarehouseConfig::getAllowedId($role);
        if ($userWhId && $pickOrder->warehouse_id !== $userWhId) {
            abort(403, 'Anda tidak berhak mengelola pick order dari gudang lain.');
        }
    }

    /**
     * Display list of pick orders for warehouse admin
     */
    public function index(Request $request)
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        $userWhId = WarehouseConfig::getAllowedId($role);

        $status = $request->input('status', 'pending');
        $search = $request->input('search');
        $warehouseFilter = $request->input('warehouse');

        // For supervisor: load warehouse list for filter dropdown
        $warehouses = collect();
        if ($role === 'supervisor') {
            $warehouses = Warehouse::where('active', true)->orderBy('name')->get();
        }

        $pickOrders = PosPickOrder::with(['transaction.customer', 'warehouse', 'requester', 'items.product'])
            ->when($userWhId, fn ($q) => $q->where('warehouse_id', $userWhId))
            ->when($role === 'supervisor' && $warehouseFilter, fn ($q) => $q->where('warehouse_id', $warehouseFilter))
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('pick_number', 'like', "%{$search}%")
                        ->orWhereHas('transaction', function ($tq) use ($search) {
                            $tq->where('invoice_number', 'like', "%{$search}%")
                                ->orWhereHas('customer', function ($cq) use ($search) {
                                    $cq->where('name', 'like', "%{$search}%");
                                });
                        })
                        ->orWhereHas('requester', function ($rq) use ($search) {
                            $rq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Count for badges (warehouse-scoped)
        $baseCountQuery = PosPickOrder::query()
            ->when($userWhId, fn ($q) => $q->where('warehouse_id', $userWhId))
            ->when($role === 'supervisor' && $warehouseFilter, fn ($q) => $q->where('warehouse_id', $warehouseFilter));
        $counts = [
            'pending'    => (clone $baseCountQuery)->where('status', 'pending')->count(),
            'processing' => (clone $baseCountQuery)->where('status', 'processing')->count(),
            'ready'      => (clone $baseCountQuery)->where('status', 'ready')->count(),
            'completed'  => (clone $baseCountQuery)->where('status', 'completed')->count(),
            'cancelled'  => (clone $baseCountQuery)->where('status', 'cancelled')->count(),
        ];

        return view('gudang.pos_pick_orders.index', compact('pickOrders', 'status', 'counts', 'role', 'warehouses', 'warehouseFilter'));
    }

    /**
     * Show pick order detail for warehouse processing
     */
    public function show(PosPickOrder $pickOrder)
    {
        $pickOrder->load(['transaction.customer', 'transaction.details.product', 'warehouse', 'requester', 'processor', 'confirmer', 'items.product']);

        $role = strtolower((string) (Auth::user()?->role ?? ''));
        $isSupervisor = $role === 'supervisor';

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

        return view('gudang.pos_pick_orders.show', compact('pickOrder', 'stockChecks', 'isSupervisor'));
    }

    /**
     * Start processing pick order
     */
    public function process(PosPickOrder $pickOrder)
    {
        $this->guardWarehouseAccess($pickOrder);
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
        $this->guardWarehouseAccess($pickOrder);
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
        $this->guardWarehouseAccess($pickOrder);
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
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        $userWhId = WarehouseConfig::getAllowedId($role);

        $count = PosPickOrder::whereIn('status', ['pending', 'processing'])
            ->when($userWhId, fn ($q) => $q->where('warehouse_id', $userWhId))
            ->count();

        return response()->json(['count' => $count]);
    }
}

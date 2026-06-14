<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRequest;
use App\Models\ProductUnitConversion;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Support\WarehouseConfig;
use Illuminate\Http\Request;

/**
 * ProductRequestController
 * 
 * Handles goods requests (PO and Transfer) for Admin3 and Admin4.
 * Supervisor can only view/monitor all requests.
 * 
 * Business Rules:
 * - Admin3 manages Gudang Utama (main warehouse) → receives stock TO Gudang Utama
 * - Admin4 manages Gudang Cabang (branch warehouse) → receives stock TO Gudang Cabang
 * - Transfer: FROM other warehouse TO own warehouse (auto-assigned)
 * - PO: Request to purchase new stock (warehouse auto-assigned to own)
 * - All requests are auto-approved by the system on creation
 * - Supervisor can view all but cannot create
 */
class ProductRequestController extends Controller
{
    /**
     * Display paginated list of requests with stats and filters.
     */
    public function index(Request $request)
    {
        $role = strtolower(auth()->user()->role);
        $userWhId = WarehouseConfig::getAllowedId($role);

        $query = $this->buildScopedQuery($role, $userWhId);
        $stats = $this->calculateStats($role, $userWhId);

        $this->applyFilters($query, $request);

        $filteredCount = (clone $query)->count();
        $requests = $query->paginate(15)->withQueryString();

        return view('gudang.request.index', array_merge($stats, [
            'requests'      => $requests,
            'role'          => $role,
            'userWhId'      => $userWhId,
            'filteredCount' => $filteredCount,
        ]));
    }

    /**
     * Show create form (Admin3/Admin4 only).
     */
    public function create()
    {
        $this->guardSupervisor();

        $products   = Product::with(['unit', 'unitConversions.unit'])->orderBy('name')->get();
        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();
        $units      = Unit::orderBy('name')->get();

        return view('gudang.request.create', compact('products', 'warehouses', 'units'));
    }

    /**
     * Store new request with auto-approve (Admin3/Admin4 only).
     * 
     * Warehouse assignment is automatic based on role:
     * - Admin3: to_warehouse = Gudang Utama (own), from_warehouse = Gudang Cabang (for transfer)
     * - Admin4: to_warehouse = Gudang Cabang (own), from_warehouse = Gudang Utama (for transfer)
     */
    public function store(\App\Http\Requests\StoreProductRequestRequest $request)
    {
        $this->guardSupervisor();

        $data = $request->validated();
        $role = strtolower(auth()->user()->role);

        // Creator info
        $data['user_id']  = auth()->id();
        $data['quantity'] = (float) ($data['quantity'] ?? 0);

        // Auto-approve by system
        $data['status']      = 'approved';
        $data['approved_by'] = auth()->id();
        $data['approved_at'] = now();

        // Unit conversion factor
        $data['conversion_factor'] = $this->resolveConversionFactor(
            $data['product_id'],
            $data['unit_id'] ?? null
        );

        // Auto-assign warehouses based on role
        $this->assignWarehouses($data, $role);

        // Generate reference number
        $data['transfer_reference'] = $this->generateReference($data['type']);

        ProductRequest::create($data);

        $typeLabel = $data['type'] === 'transfer' ? 'Transfer' : 'Purchase Order';
        return redirect()
            ->route('gudang.request.index')
            ->with('success', "{$typeLabel} berhasil dibuat ({$data['transfer_reference']}).");
    }

    /**
     * Delete a request.
     * - Supervisor: can delete any request
     * - Admin: can delete own requests OR requests involving their warehouse
     */
    public function destroy(ProductRequest $productRequest)
    {
        $role     = strtolower(auth()->user()->role);
        $userWhId = WarehouseConfig::getAllowedId($role);

        // Supervisor can delete any request
        if ($role === 'supervisor') {
            $productRequest->delete();
            return back()->with('success', 'Permintaan berhasil dihapus.');
        }

        // Admin can delete: own requests OR requests involving their warehouse
        $isOwner             = $productRequest->user_id === auth()->id();
        $involvesMyWarehouse = $userWhId && (
            $productRequest->from_warehouse_id == $userWhId ||
            $productRequest->to_warehouse_id == $userWhId
        );

        if (!$isOwner && !$involvesMyWarehouse) {
            abort(403, 'Anda tidak berhak menghapus permintaan ini.');
        }

        $productRequest->delete();

        return back()->with('success', 'Permintaan berhasil dihapus.');
    }

    // ═══════════════════════════════════════════════════════════
    //  PRIVATE HELPERS
    // ═══════════════════════════════════════════════════════════

    /**
     * Build query with role-based visibility.
     * - Supervisor: ALL requests
     * - Admin: own requests + requests involving their warehouse
     */
    private function buildScopedQuery(string $role, ?int $userWhId)
    {
        $query = ProductRequest::with([
            'user', 'product.unit', 'fromWarehouse', 'toWarehouse', 'unit'
        ])->latest();

        if ($role !== 'supervisor') {
            $query->where(function ($q) use ($userWhId) {
                $q->where('user_id', auth()->id());
                if ($userWhId) {
                    $q->orWhere('to_warehouse_id', $userWhId)
                      ->orWhere('from_warehouse_id', $userWhId);
                }
            });
        }

        return $query;
    }

    /**
     * Calculate global stats (unaffected by filters).
     */
    private function calculateStats(string $role, ?int $userWhId): array
    {
        $query = ProductRequest::query();

        if ($role !== 'supervisor') {
            $query->where(function ($q) use ($userWhId) {
                $q->where('user_id', auth()->id());
                if ($userWhId) {
                    $q->orWhere('to_warehouse_id', $userWhId)
                      ->orWhere('from_warehouse_id', $userWhId);
                }
            });
        }

        return [
            'totalCount'     => (clone $query)->count(),
            'pendingCount'   => (clone $query)->where('status', 'pending')->count(),
            'approvedCount'  => (clone $query)->where('status', 'approved')->count(),
            'rejectedCount'  => (clone $query)->where('status', 'rejected')->count(),
            'completedCount' => (clone $query)->where('status', 'completed')->count(),
        ];
    }

    /**
     * Apply search and filter conditions.
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('status', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%")
                    ->orWhere('transfer_reference', 'like', "%{$q}%")
                    ->orWhereHas('product', fn($p) => $p->where('name', 'like', "%{$q}%")->orWhere('sku', 'like', "%{$q}%"))
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")->orWhere('role', 'like', "%{$q}%"))
                    ->orWhereHas('fromWarehouse', fn($w) => $w->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('toWarehouse', fn($w) => $w->where('name', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
    }

    /**
     * Auto-assign warehouses based on role and type.
     * 
     * Admin3 (Gudang Utama):
     *   - Transfer: FROM Gudang Cabang → TO Gudang Utama
     *   - PO: to_warehouse = Gudang Utama
     * 
     * Admin4 (Gudang Cabang):
     *   - Transfer: FROM Gudang Utama → TO Gudang Cabang
     *   - PO: to_warehouse = Gudang Cabang
     */
    private function assignWarehouses(array &$data, string $role): void
    {
        $ownWh = WarehouseConfig::getAllowedId($role);

        if (($data['type'] ?? '') === 'transfer') {
            $otherWh = ($role === 'admin3')
                ? WarehouseConfig::getBranchId()
                : WarehouseConfig::getMainId();

            $data['from_warehouse_id'] = $otherWh;  // source = other admin's warehouse
            $data['to_warehouse_id']   = $ownWh;    // destination = own warehouse
        } else {
            // PO: purchasing new stock for own warehouse
            $data['from_warehouse_id'] = null;
            $data['to_warehouse_id']   = $ownWh;
        }
    }

    /**
     * Generate unique reference number.
     */
    private function generateReference(string $type): string
    {
        $prefix = $type === 'transfer' ? 'TR' : 'PO';
        return $prefix . '-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }

    /**
     * Resolve unit conversion factor.
     */
    private function resolveConversionFactor(int $productId, ?int $unitId): float
    {
        if (!$unitId) {
            return 1;
        }

        $conversion = ProductUnitConversion::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->first();

        return $conversion ? (float) $conversion->conversion_factor : 1;
    }

    /**
     * Block supervisor from create/store actions.
     */
    private function guardSupervisor(): void
    {
        if (strtolower(auth()->user()->role) === 'supervisor') {
            abort(403, 'Supervisor hanya dapat memantau permintaan.');
        }
    }
}

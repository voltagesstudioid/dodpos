<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockOpnameItem;
use App\Models\StockOpnameSession;
use App\Models\Warehouse;
use App\Support\SearchSanitizer;
use App\Support\WarehouseConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpnameSessionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        $allowedWarehouseId = WarehouseConfig::getAllowedId($role);

        $query = StockOpnameSession::with(['warehouse', 'creator', 'approver'])
            ->orderByDesc('created_at');

        if ($allowedWarehouseId !== null) {
            $query->where('warehouse_id', $allowedWarehouseId);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $sanitizedQ = SearchSanitizer::sanitize($q);
            $query->where(function ($sub) use ($sanitizedQ) {
                $sub->where('reference_number', 'like', '%'.$sanitizedQ.'%')
                    ->orWhereHas('creator', function ($u) use ($sanitizedQ) {
                        $u->where('name', 'like', '%'.$sanitizedQ.'%');
                    });
            });
        }

        $sessions = $query->paginate(15)->withQueryString();

        return view('gudang.opname_sessions.index', compact('sessions', 'role'));
    }

    public function create()
    {
        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        $whQuery = Warehouse::where('active', true);
        $allowedWarehouseId = WarehouseConfig::getAllowedId($role);
        if ($allowedWarehouseId !== null) {
            $whQuery->where('id', $allowedWarehouseId);
        }
        $warehouses = $whQuery->orderBy('name')->get();

        return view('gudang.opname_sessions.create', compact('warehouses', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'notes' => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));
        $warehouseId = (int) $request->warehouse_id;

        if (! WarehouseConfig::canAccess($role, $warehouseId)) {
            return back()->withInput()->with('error', 'Anda tidak memiliki akses untuk melakukan opname di gudang ini.');
        }

        $session = StockOpnameSession::create([
            'warehouse_id' => $warehouseId,
            'created_by' => Auth::id(),
            'status' => 'draft',
            'notes' => $request->notes,
        ]);

        return redirect()->route('gudang.opname_sessions.edit', $session)->with('success', 'Sesi opname dibuat. Silakan input item.');
    }

    public function edit(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);

        $session->load(['warehouse', 'creator', 'approver', 'items.product']);
        $products = Product::orderBy('name')->get();

        $systemQtyMap = ProductStock::query()
            ->where('warehouse_id', $session->warehouse_id)
            ->selectRaw('product_id, SUM(stock) as qty')
            ->groupBy('product_id')
            ->pluck('qty', 'product_id');

        return view('gudang.opname_sessions.edit', compact('session', 'products', 'systemQtyMap'));
    }

    public function addItem(Request $request, StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);
        if ($session->status !== 'draft') {
            return back()->with('error', 'Sesi ini sudah dikirim / diproses dan tidak bisa diubah.');
        }

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'scan_code' => 'nullable|string|max:120',
            'physical_qty' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $productId = $request->product_id;
        $scan = trim((string) $request->scan_code);
        if (! $productId && $scan !== '') {
            $productId = Product::query()
                ->where('sku', $scan)
                ->orWhere('barcode', $scan)
                ->value('id');
        }

        if (! $productId) {
            return back()->withInput()->with('error', 'Produk tidak ditemukan. Pilih produk atau scan SKU/Barcode.');
        }

        $productId = (int) $productId;
        $physicalQty = (int) $request->physical_qty;
        $systemQty = (int) ProductStock::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $session->warehouse_id)
            ->sum('stock');
        $diff = $physicalQty - $systemQty;

        try {
            DB::beginTransaction();

            $item = StockOpnameItem::query()
                ->where('session_id', $session->id)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->system_qty = $systemQty;
                $item->physical_qty = $physicalQty;
                $item->difference_qty = $diff;
                $item->notes = $request->notes;
                $item->save();
            } else {
                StockOpnameItem::create([
                    'session_id' => $session->id,
                    'product_id' => $productId,
                    'system_qty' => $systemQty,
                    'physical_qty' => $physicalQty,
                    'difference_qty' => $diff,
                    'notes' => $request->notes,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menyimpan item: '.$e->getMessage());
        }

        return redirect()->route('gudang.opname_sessions.edit', $session)->with('success', 'Item opname tersimpan.');
    }

    public function deleteItem(StockOpnameSession $session, StockOpnameItem $item)
    {
        $this->authorizeSessionAccess($session);
        if ($session->status !== 'draft') {
            return back()->with('error', 'Sesi ini sudah dikirim / diproses dan tidak bisa diubah.');
        }
        if ((int) $item->session_id !== (int) $session->id) {
            abort(404);
        }

        $item->delete();

        return back()->with('success', 'Item dihapus.');
    }

    public function submit(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);
        if ($session->status !== 'draft') {
            return back()->with('error', 'Sesi ini sudah dikirim / diproses.');
        }
        if ($session->items()->count() === 0) {
            return back()->with('error', 'Minimal 1 item wajib diinput sebelum submit.');
        }

        try {
            DB::beginTransaction();

            $session = StockOpnameSession::whereKey($session->id)->lockForUpdate()->first();
            if (! $session || $session->status !== 'draft') {
                DB::rollBack();

                return back()->with('error', 'Sesi sudah diproses oleh user lain.');
            }

            $items = $session->items()->lockForUpdate()->get();
            foreach ($items as $item) {
                $systemQty = (int) ProductStock::query()
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $session->warehouse_id)
                    ->sum('stock');
                $physicalQty = (int) $item->physical_qty;

                $item->system_qty = $systemQty;
                $item->difference_qty = $physicalQty - $systemQty;
                $item->save();
            }

            $session->status = 'submitted';
            $session->submitted_at = now();
            $session->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal submit sesi opname: '.$e->getMessage());
        }

        return redirect()->route('gudang.opname_sessions.index')->with('success', 'Sesi opname berhasil dikirim untuk approval Supervisor.');
    }

    private function authorizeSessionAccess(StockOpnameSession $session): void
    {
        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        $allowedWarehouseId = WarehouseConfig::getAllowedId($role);

        if ($allowedWarehouseId && (int) $session->warehouse_id !== $allowedWarehouseId) {
            abort(403);
        }
        if ($role !== 'supervisor' && (int) $session->created_by !== (int) Auth::id()) {
            abort(403);
        }
    }
}

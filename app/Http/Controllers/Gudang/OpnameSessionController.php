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

        // Base query for stats (before search/status filter)
        $baseQuery = StockOpnameSession::query();
        if ($allowedWarehouseId !== null) {
            $baseQuery->where('warehouse_id', $allowedWarehouseId);
        }
        if ($role !== 'supervisor') {
            $baseQuery->where('created_by', Auth::id());
        }

        // Stats from full dataset (unfiltered)
        $totalSessions = (clone $baseQuery)->count();
        $draftCount = (clone $baseQuery)->where('status', 'draft')->count();
        $submittedCount = (clone $baseQuery)->where('status', 'submitted')->count();
        $approvedCount = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedCount = (clone $baseQuery)->where('status', 'rejected')->count();

        // Sortable columns
        $allowedSorts = ['created_at', 'warehouse_id', 'status', 'reference_number'];
        $sort = in_array($request->input('sort'), $allowedSorts, true) ? $request->input('sort') : 'created_at';
        $dir = $request->input('dir') === 'asc' ? 'asc' : 'desc';

        // Main query with filters
        $query = StockOpnameSession::with(['warehouse', 'creator', 'approver'])
            ->orderBy($sort, $dir);

        if ($allowedWarehouseId !== null) {
            $query->where('warehouse_id', $allowedWarehouseId);
        }
        if ($role !== 'supervisor') {
            $query->where('created_by', Auth::id());
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

        return view('gudang.opname_sessions.index', compact(
            'sessions', 'role', 'totalSessions', 'draftCount', 'submittedCount', 'approvedCount', 'rejectedCount', 'sort', 'dir'
        ));
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
            'deadline_at' => 'nullable|date|after:today',
        ]);

        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));
        $warehouseId = (int) $request->warehouse_id;

        if (! WarehouseConfig::canAccess($role, $warehouseId)) {
            return back()->withInput()->with('error', 'Anda tidak memiliki akses untuk melakukan opname di gudang ini.');
        }

        // Cegah duplikat: cek apakah ada sesi draft di gudang yang sama oleh user ini
        $existingDraft = StockOpnameSession::where('warehouse_id', $warehouseId)
            ->where('created_by', Auth::id())
            ->where('status', 'draft')
            ->first();

        if ($existingDraft) {
            return back()->withInput()->with('error', 'Anda sudah memiliki sesi draft di gudang ini. Silakan lanjutkan sesi yang sudah ada atau batalkan terlebih dahulu.')
                ->with('existing_session_id', $existingDraft->id);
        }

        $session = StockOpnameSession::create([
            'warehouse_id' => $warehouseId,
            'created_by' => Auth::id(),
            'status' => 'draft',
            'notes' => $request->notes,
            'deadline_at' => $request->deadline_at,
        ]);

        return redirect()->route('gudang.opname_sessions.edit', $session)->with('success', 'Sesi opname dibuat. Silakan input item.');
    }

    public function edit(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);

        $session->load(['warehouse', 'creator', 'approver', 'items.product.unitConversions.unit']);
        $products = Product::with(['unitConversions.unit'])->orderBy('name')->get();

        $role = strtolower((string) (Auth::user()?->role ?? ''));

        if ($role === 'supervisor') {
            $systemQtyMap = ProductStock::query()
                ->where('warehouse_id', $session->warehouse_id)
                ->selectRaw('product_id, SUM(stock) as qty')
                ->groupBy('product_id')
                ->pluck('qty', 'product_id');
        } else {
            $systemQtyMap = collect();
        }

        // Produk yang belum dihitung di sesi ini
        $countedProductIds = $session->items->pluck('product_id')->toArray();
        $uncountedProducts = $products->filter(function ($p) use ($countedProductIds) {
            return !in_array($p->id, $countedProductIds);
        })->values();

        // Build map konversi satuan per produk (untuk JS)
        $unitMap = [];
        foreach ($products as $p) {
            $conversions = [];
            foreach ($p->unitConversions as $uc) {
                $conversions[] = [
                    'unit_name' => $uc->unit?->abbreviation ?? $uc->unit?->name ?? '-',
                    'factor' => (float) $uc->conversion_factor,
                    'is_base' => (bool) $uc->is_base_unit,
                ];
            }
            $unitMap[$p->id] = $conversions;
        }

        // Summary stats
        $items = $session->items;
        $summary = (object) [
            'totalItems'    => $items->count(),
            'totalSystem'   => (int) $items->sum('system_qty'),
            'totalPhysical' => (int) $items->sum('physical_qty'),
            'totalDiff'     => (int) $items->sum('difference_qty'),
            'diffPlus'      => $items->where('difference_qty', '>', 0)->count(),
            'diffMinus'     => $items->where('difference_qty', '<', 0)->count(),
            'diffZero'      => $items->where('difference_qty', '=', 0)->count(),
        ];

        return view('gudang.opname_sessions.edit', compact('session', 'products', 'systemQtyMap', 'uncountedProducts', 'unitMap', 'summary'));
    }

    public function addItem(Request $request, StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);
        if (!in_array($session->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Sesi ini sudah dikirim / diproses dan tidak bisa diubah.');
        }

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'scan_code' => 'nullable|string|max:120',
            'physical_qty' => 'required|numeric|min:0',
            'counted_unit' => 'nullable|string|max:255',
            'counted_qty' => 'nullable|numeric|min:0',
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

        // Konversi dari satuan yang diinput ke base unit
        $enteredQty = (float) $request->physical_qty; // sudah dalam base unit (dihitung di JS)
        $countedUnit = $request->counted_unit;
        $countedQty = $request->counted_qty ? (float) $request->counted_qty : $enteredQty;

        // JS sudah mengkonversi ke base unit sebelum kirim, jadi enteredQty sudah final
        $physicalQtyBase = (int) round($enteredQty);

        $systemQty = (int) ProductStock::query()
            ->where('product_id', $productId)
            ->where('warehouse_id', $session->warehouse_id)
            ->sum('stock');
        $diff = $physicalQtyBase - $systemQty;

        try {
            DB::beginTransaction();

            $item = StockOpnameItem::query()
                ->where('session_id', $session->id)
                ->where('product_id', $productId)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $item->system_qty = $systemQty;
                $item->physical_qty = $physicalQtyBase;
                $item->counted_unit = $countedUnit;
                $item->counted_qty = $countedQty;
                $item->difference_qty = $diff;
                $item->notes = $request->notes;
                $item->counted_at = now();
                $item->save();
            } else {
                StockOpnameItem::create([
                    'session_id' => $session->id,
                    'product_id' => $productId,
                    'system_qty' => $systemQty,
                    'physical_qty' => $physicalQtyBase,
                    'counted_unit' => $countedUnit,
                    'counted_qty' => $countedQty,
                    'difference_qty' => $diff,
                    'notes' => $request->notes,
                    'counted_at' => now(),
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
        if (!in_array($session->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Sesi ini sudah dikirim / diproses dan tidak bisa diubah.');
        }
        if ((int) $item->session_id !== (int) $session->id) {
            abort(404);
        }

        $item->delete();

        return back()->with('success', 'Item dihapus.');
    }

    // generateSales() removed — use generateFromSales() which is more complete

    public function submit(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);
        if (!in_array($session->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Sesi ini sudah dikirim / diproses.');
        }
        if ($session->items()->count() === 0) {
            return back()->with('error', 'Minimal 1 item wajib diinput sebelum submit.');
        }

        try {
            DB::beginTransaction();

            $session = StockOpnameSession::whereKey($session->id)->lockForUpdate()->first();
            if (! $session || !in_array($session->status, ['draft', 'rejected'])) {
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

    public function reviseToDraft(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);
        if ($session->status !== 'rejected') {
            return back()->with('error', 'Hanya sesi yang ditolak (rejected) yang bisa direvisi.');
        }

        $session->status = 'draft';
        $session->submitted_at = null;
        $session->approved_by = null;
        $session->approved_at = null;
        $session->approval_notes = null;
        $session->save();

        return redirect()->route('gudang.opname_sessions.edit', $session)
            ->with('success', 'Sesi opname dikembalikan ke draft. Silakan revisi dan submit ulang.');
    }

    public function cancel(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);
        if (!in_array($session->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Hanya sesi draft atau rejected yang bisa dibatalkan.');
        }

        $session->status = 'cancelled';
        $session->save();

        return redirect()->route('gudang.opname_sessions.index')
            ->with('success', 'Sesi opname dibatalkan.');
    }

    public function generateFromSales(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);
        if (!in_array($session->status, ['draft', 'rejected'])) {
            return back()->with('error', 'Sesi ini sudah dikirim / diproses dan tidak bisa diubah.');
        }

        // Cari produk yang laku terjual hari ini beserta total qty terjual
        $salesData = \App\Models\TransactionDetail::where('warehouse_id', $session->warehouse_id)
            ->whereHas('transaction', function($q) {
                $q->whereDate('created_at', today());
                $q->whereIn('status', ['completed', 'delivered']);
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        if ($salesData->isEmpty()) {
            return back()->with('error', 'Tidak ada transaksi penjualan terekam hari ini untuk gudang ini.');
        }

        $addedCount = 0;
        try {
            DB::beginTransaction();

            foreach ($salesData as $productId => $sale) {
                // Cek apakah item sudah dimasukkan di sesi ini
                $exists = $session->items()->where('product_id', $productId)->exists();
                if (!$exists) {
                    $systemQty = (int) \App\Models\ProductStock::query()
                        ->where('product_id', $productId)
                        ->where('warehouse_id', $session->warehouse_id)
                        ->sum('stock');

                    // Ambil nama satuan produk sebagai referensi
                    $product = \App\Models\Product::with('unit')->find($productId);
                    $unitName = $product?->unit?->abbreviation ?? $product?->unit?->name ?? null;
                    $qtySold  = (float) $sale->total_sold;

                    $notesRef = 'Terjual hari ini: ' . number_format($qtySold, 0)
                        . ($unitName ? ' ' . $unitName : '')
                        . ' | Tarik otomatis dari penjualan';

                    \App\Models\StockOpnameItem::create([
                        'session_id'    => $session->id,
                        'product_id'    => $productId,
                        'system_qty'    => $systemQty,
                        'physical_qty'  => 0,
                        'counted_unit'  => $unitName,
                        'counted_qty'   => null,
                        'difference_qty'=> 0 - $systemQty,
                        'notes'         => $notesRef,
                        'counted_at'    => null,
                    ]);
                    $addedCount++;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menarik data penjualan: ' . $e->getMessage());
        }

        if ($addedCount > 0) {
            return back()->with('success', "Berhasil menambahkan {$addedCount} jenis produk dari data penjualan hari ini. Kolom Satuan terisi otomatis — silakan lengkapi Qty Fisiknya.");
        } else {
            return back()->with('success', 'Semua barang yang laku hari ini sudah ada di dalam daftar.');
        }
    }

    public function print(StockOpnameSession $session)
    {
        $this->authorizeSessionAccess($session);

        $session->load(['warehouse', 'creator', 'approver', 'items.product.unitConversions.unit']);

        $systemQtyMap = ProductStock::query()
            ->where('warehouse_id', $session->warehouse_id)
            ->selectRaw('product_id, SUM(stock) as qty')
            ->groupBy('product_id')
            ->pluck('qty', 'product_id');

        return view('gudang.opname_sessions.print', compact('session', 'systemQtyMap'));
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

<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarLoading;
use App\Models\PasgarLoadingItem;
use App\Models\PasgarSales;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasgarLoadingController extends Controller
{
    /**
     * List all loadings with optional status filter.
     */
    public function index(Request $request)
    {
        $query = PasgarLoading::with(['sales', 'warehouse', 'items.product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sales users only see their own loadings
        $user = Auth::user();
        if ($user && (str_starts_with(strtolower($user->role ?? ''), 'sales_') || ($user->role ?? '') === 'sales')) {
            $salesProfile = PasgarSales::where('user_id', $user->id)->first();
            if ($salesProfile) {
                $query->where('sales_id', $salesProfile->id);
            }
        }

        $loadings = $query->paginate(15);

        return view('pasgar.loading.index', compact('loadings'));
    }

    // Warehouse IDs (constant references)
    private const WH_GUDANG = 1;
    private const WH_GROSIR = 2;

    /**
     * Show form for sales to request goods.
     * Supports both create (new) and edit (existing pending loading) modes.
     */
    public function create()
    {
        return $this->buildCreateView();
    }

    /**
     * Show edit form for a pending loading request.
     */
    public function edit($id)
    {
        $loading = PasgarLoading::with('items.unitConversion')->findOrFail($id);
        if ($loading->status !== 'pending') {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Hanya loading yang masih pending yang bisa diedit.');
        }

        // Verify ownership
        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile || $loading->sales_id !== $salesProfile->id) {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Anda tidak berhak mengedit loading ini.');
        }

        return $this->buildCreateView($loading);
    }

    /**
     * Build the create/edit view with shared product data.
     */
    private function buildCreateView(?PasgarLoading $loading = null)
    {
        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile) {
            return redirect()->route('pasgar.loading.index')->with('error', 'Profil sales tidak ditemukan.');
        }

        $products = Product::with(['category', 'unit', 'unitConversions.unit'])->orderBy('name')->get();

        // Build per-product stock by warehouse
        $stockMap = ProductStock::whereIn('warehouse_id', [self::WH_GUDANG, self::WH_GROSIR])
            ->get()
            ->groupBy('product_id')
            ->map(function ($rows) {
                $map = [];
                foreach ($rows as $r) {
                    $map[$r->warehouse_id] = (int) $r->stock;
                }
                return $map;
            });

        // Pre-compute product data for JS search
        $productsJson = $products->map(function ($p) use ($stockMap) {
            $pStock = $stockMap[$p->id] ?? [];
            $stockGudang = $pStock[self::WH_GUDANG] ?? 0;
            $stockGrosir = $pStock[self::WH_GROSIR] ?? 0;
            $conversions = $p->unitConversions->map(function ($uc) {
                return [
                    'id' => $uc->id,
                    'unit_name' => $uc->unit?->name ?? '',
                    'conversion_factor' => (int) $uc->conversion_factor,
                    'is_base' => (bool) $uc->is_base_unit,
                    'price' => (float) ($uc->sell_price_grosir ?: $uc->sell_price_ecer ?: 0),
                ];
            })->values();
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku ?? '',
                'barcode' => $p->barcode ?? '',
                'price' => (float)($p->price ?? 0),
                'stock_gudang' => $stockGudang,
                'stock_grosir' => $stockGrosir,
                'category' => $p->category?->name ?? '',
                'unit' => $p->unit?->name ?? '',
                'conversions' => $conversions,
            ];
        })->values()->toJson();

        // Pre-compute existing items JSON for edit mode
        $existingItemsJson = '[]';
        if ($loading) {
            $existingItemsJson = $loading->items->map(function ($item) {
                $conv = $item->unitConversion;
                $convData = null;
                if ($conv) {
                    $convData = [
                        'id' => $conv->id,
                        'unit_name' => $conv->unit?->name ?? '',
                        'conversion_factor' => (int) $conv->conversion_factor,
                        'is_base' => (bool) $conv->is_base_unit,
                        'price' => (float) ($conv->sell_price_grosir ?: $conv->sell_price_ecer ?: 0),
                    ];
                }
                return [
                    'product_id' => $item->product_id,
                    'sumber' => $item->sumber,
                    'qty' => (int) $item->qty_diminta,
                    'unit_conversion_id' => $item->unit_conversion_id,
                    'conversion' => $convData,
                ];
            })->values()->toJson();
        }

        return view('pasgar.loading.create', compact(
            'products', 'salesProfile', 'productsJson', 'loading', 'existingItemsJson'
        ));
    }

    /**
     * Store new loading request (sales submits goods request).
     * Auto-determines sumber (gudang/grosir) per item based on stock availability.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_diminta' => 'required|integer|min:1|max:9999',
            'items.*.sumber' => 'required|in:gudang,grosir',
            'items.*.unit_conversion_id' => 'nullable|integer|exists:product_unit_conversions,id',
        ], [
            'items.required' => 'Tambahkan minimal 1 barang.',
            'items.min' => 'Tambahkan minimal 1 barang.',
            'items.*.product_id.required' => 'Pilih produk.',
            'items.*.product_id.exists' => 'Produk tidak valid.',
            'items.*.qty_diminta.required' => 'Masukkan jumlah.',
            'items.*.qty_diminta.min' => 'Jumlah minimal 1.',
            'items.*.sumber.required' => 'Pilih sumber barang.',
            'items.*.sumber.in' => 'Sumber harus gudang atau grosir.',
        ]);

        // Check for duplicate products
        $productIds = array_column($validated['items'], 'product_id');
        if (count($productIds) !== count(array_unique($productIds))) {
            return back()->withErrors(['items' => 'Tidak boleh ada produk yang sama dalam daftar permintaan.'])->withInput();
        }

        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile) {
            return redirect()->route('pasgar.loading.index')->with('error', 'Profil sales tidak ditemukan.');
        }

        DB::transaction(function () use ($validated, $salesProfile) {
            // Determine loading-level sumber from item sources
            $itemSumberList = array_column($validated['items'], 'sumber');
            $loadingSumber = PasgarLoading::computeSumberFromItems($itemSumberList);
            $primaryWarehouseId = $loadingSumber === 'grosir' ? self::WH_GROSIR : self::WH_GUDANG;

            $loading = PasgarLoading::create([
                'nomor_loading' => PasgarLoading::generateNomor(),
                'sales_id' => $salesProfile->id,
                'sumber' => $loadingSumber,
                'warehouse_id' => $primaryWarehouseId,
                'tanggal' => $validated['tanggal'],
                'status' => 'pending',
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $pid = $item['product_id'];
                $qty = $item['qty_diminta'];
                $sumber = $item['sumber']; // gudang or grosir (user-selected)
                $warehouseId = $sumber === 'grosir' ? self::WH_GROSIR : self::WH_GUDANG;

                $convId = $item['unit_conversion_id'] ?? null;

                PasgarLoadingItem::create([
                    'loading_id' => $loading->id,
                    'product_id' => $pid,
                    'sumber' => $sumber,
                    'warehouse_id' => $warehouseId,
                    'unit_conversion_id' => $convId,
                    'qty_diminta' => $qty,
                    'qty_disetujui' => 0,
                    'qty_dikirim' => 0,
                    'qty_terjual' => 0,
                    'qty_sisa' => 0,
                ]);
            }
        });

        return redirect()->route('pasgar.loading.index')
            ->with('success', 'Permintaan loading berhasil diajukan.');
    }

    /**
     * Update an existing pending loading request.
     * Replaces all items with the submitted list.
     */
    public function update(Request $request, $id)
    {
        $loading = PasgarLoading::findOrFail($id);
        if ($loading->status !== 'pending') {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Hanya loading pending yang bisa diedit.');
        }

        // Verify ownership
        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile || $loading->sales_id !== $salesProfile->id) {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Anda tidak berhak mengedit loading ini.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_diminta' => 'required|integer|min:1|max:9999',
            'items.*.sumber' => 'required|in:gudang,grosir',
            'items.*.unit_conversion_id' => 'nullable|integer|exists:product_unit_conversions,id',
        ], [
            'items.required' => 'Tambahkan minimal 1 barang.',
            'items.min' => 'Tambahkan minimal 1 barang.',
            'items.*.product_id.required' => 'Pilih produk.',
            'items.*.product_id.exists' => 'Produk tidak valid.',
            'items.*.qty_diminta.required' => 'Masukkan jumlah.',
            'items.*.qty_diminta.min' => 'Jumlah minimal 1.',
            'items.*.sumber.required' => 'Pilih sumber barang.',
            'items.*.sumber.in' => 'Sumber harus gudang atau grosir.',
        ]);

        $productIds = array_column($validated['items'], 'product_id');
        if (count($productIds) !== count(array_unique($productIds))) {
            return back()->withErrors(['items' => 'Tidak boleh ada produk yang sama dalam daftar permintaan.'])->withInput();
        }

        DB::transaction(function () use ($loading, $validated) {
            // Recompute loading-level sumber from new item sources
            $itemSumberList = array_column($validated['items'], 'sumber');
            $loadingSumber = PasgarLoading::computeSumberFromItems($itemSumberList);
            $primaryWarehouseId = $loadingSumber === 'grosir' ? self::WH_GROSIR : self::WH_GUDANG;

            $loading->update([
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
                'sumber' => $loadingSumber,
                'warehouse_id' => $primaryWarehouseId,
            ]);

            // Delete all existing items and replace
            $loading->items()->delete();

            foreach ($validated['items'] as $item) {
                $sumber = $item['sumber'];
                $warehouseId = $sumber === 'grosir' ? self::WH_GROSIR : self::WH_GUDANG;

                PasgarLoadingItem::create([
                    'loading_id' => $loading->id,
                    'product_id' => $item['product_id'],
                    'sumber' => $sumber,
                    'warehouse_id' => $warehouseId,
                    'unit_conversion_id' => $item['unit_conversion_id'] ?? null,
                    'qty_diminta' => $item['qty_diminta'],
                    'qty_disetujui' => 0,
                    'qty_dikirim' => 0,
                    'qty_terjual' => 0,
                    'qty_sisa' => 0,
                ]);
            }
        });

        return redirect()->route('pasgar.loading.show', $id)
            ->with('success', 'Permintaan loading berhasil diperbarui.');
    }

    /**
     * Show loading detail with workflow timeline.
     */
    public function show($id)
    {
        $loading = PasgarLoading::with([
            'sales', 'warehouse', 'approver', 'preparer', 'confirmer', 'pickedUpByUser', 'loadedByUser',
            'items.product', 'items.warehouse', 'items.unitConversion.unit', 'penjualans', 'setoran', 'opname',
        ])->findOrFail($id);

        return view('pasgar.loading.show', compact('loading'));
    }

    /**
     * Admin approves/rejects the loading request.
     * Updates qty_disetujui for each item.
     */
    public function approve(Request $request, $id)
    {
        \Illuminate\Support\Facades\Log::info('PasgarLoadingController@approve called', [
            'id' => $id,
            'input' => $request->all(),
            'user_id' => Auth::id(),
        ]);

        $loading = PasgarLoading::findOrFail($id);
        if ($loading->status !== 'pending') {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Loading sudah diproses.');
        }

        $action = $request->input('action', 'approve');
        if (!in_array($action, ['approve', 'reject'])) {
            return back()->with('error', 'Aksi tidak valid.');
        }

        try {
            DB::beginTransaction();

            if ($action === 'reject') {
                $loading->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'approved_at' => now(),
                    'catatan' => $request->input('catatan') ?: $loading->catatan,
                ]);
                DB::commit();
                return redirect()->route('pasgar.loading.show', $id)->with('success', 'Loading ditolak.');
            }

            $loading->update([
                'status' => 'preparing',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $items = $request->input('items', []);
            foreach ($items as $itemId => $data) {
                $item = PasgarLoadingItem::where('loading_id', $loading->id)->find($itemId);
                if ($item) {
                    $item->update(['qty_disetujui' => (int) ($data['qty_disetujui'] ?? 0)]);
                }
            }

            DB::commit();
            return redirect()->route('pasgar.loading.show', $id)->with('success', 'Loading disetujui, mulai persiapan barang.');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('PasgarLoadingController@approve failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin confirms goods are prepared and ready for pickup.
     * Updates qty_dikirim for each item.
     */
    public function confirmReady(Request $request, $id)
    {
        $loading = PasgarLoading::findOrFail($id);
        if ($loading->status !== 'preparing') {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Loading belum dalam tahap persiapan.');
        }

        try {
            DB::beginTransaction();

            $loading->update([
                'status' => 'ready',
                'prepared_by' => Auth::id(),
                'prepared_at' => now(),
                'confirmed_by' => Auth::id(),
                'ready_at' => now(),
            ]);

            $items = $request->input('items', []);
            if (!empty($items)) {
                foreach ($items as $itemId => $data) {
                    $item = PasgarLoadingItem::where('loading_id', $loading->id)->find($itemId);
                    if ($item) {
                        $qtyDikirim = (int) ($data['qty_dikirim'] ?? $item->qty_disetujui);
                        $item->update([
                            'qty_dikirim' => $qtyDikirim,
                            'qty_sisa' => $qtyDikirim,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('pasgar.loading.show', $id)
                ->with('success', 'Barang sudah siap untuk dijemput sales.');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('PasgarLoadingController@confirmReady failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Sales picks up the goods and cross-checks.
     * Deducts stock from each item's source warehouse.
     */
    public function pickup(Request $request, $id)
    {
        $loading = PasgarLoading::with('items.unitConversion')->findOrFail($id);
        if ($loading->status !== 'ready') {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Barang belum siap untuk dijemput.');
        }

        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile || $loading->sales_id !== $salesProfile->id) {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Anda tidak berhak menjemput loading ini.');
        }

        try {
            DB::beginTransaction();

            $autoNotes = [];
            foreach ($loading->items as $item) {
                $inputQty = $request->input("items.{$item->id}.qty_diterima");
                if ($inputQty !== null && $inputQty < $item->qty_dikirim) {
                    $unitName = $item->unitConversion?->unit?->name ?? 'pcs';
                    $autoNotes[] = "[-] {$item->product->name}: Sistem tercatat {$item->qty_dikirim}, aktual diterima {$inputQty} {$unitName}.";
                    $item->qty_dikirim = (int) $inputQty;
                    $item->qty_sisa = (int) $inputQty;
                    $item->save();
                }

                $qty = $item->qty_dikirim ?: $item->qty_disetujui;
                if ($qty <= 0) continue;

                $convFactor = (float) ($item->unitConversion?->conversion_factor ?: 1);
                $convFactor = max(0.0001, $convFactor);
                $baseQty = (int) round($qty * $convFactor);
                $warehouseId = $item->warehouse_id ?? 1;

                $productStock = ProductStock::where('product_id', $item->product_id)
                    ->where('warehouse_id', $warehouseId)
                    ->where('stock', '>=', $baseQty)
                    ->lockForUpdate()
                    ->first();

                if (!$productStock) {
                    throw new \RuntimeException("Stok {$item->product->name} tidak mencukupi.");
                }

                $productStock->stock -= $baseQty;
                $productStock->save();

                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock = max(0, ($product->stock ?? 0) - $baseQty);
                    $product->save();
                }

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'warehouse_id' => $warehouseId,
                    'location_id' => $productStock->location_id,
                    'type' => 'out',
                    'source_type' => 'pasgar_loading',
                    'reference_number' => $loading->nomor_loading,
                    'quantity' => $baseQty,
                    'balance' => $productStock->stock,
                    'notes' => 'Loading Pasgar - ' . $loading->nomor_loading,
                    'user_id' => $user->id,
                ]);
            }

            $finalNotes = $request->input('cross_check_notes', '');
            if (count($autoNotes) > 0) {
                $finalNotes = "Penyesuaian Fisik:\n" . implode("\n", $autoNotes) . "\n\n" . $finalNotes;
            }

            $loading->update([
                'status' => 'picked_up',
                'picked_up_by' => $user->id,
                'picked_up_at' => now(),
                'cross_check_notes' => trim($finalNotes),
            ]);

            DB::commit();
            return redirect()->route('pasgar.loading.show', $id)
                ->with('success', 'Barang berhasil dijemput. Stok telah dikurangi.');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('PasgarLoadingController@pickup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Sales loads the goods into the vehicle for selling.
     */
    public function loadIntoVehicle(Request $request, $id)
    {
        $request->validate([
            'catatan_muat' => 'nullable|string|max:500',
        ]);

        $loading = PasgarLoading::findOrFail($id);
        if ($loading->status !== 'picked_up') {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Barang belum selesai cross-check untuk dimuat ke kendaraan.');
        }

        // Verify sales is the owner
        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile || $loading->sales_id !== $salesProfile->id) {
            return redirect()->route('pasgar.loading.show', $id)->with('error', 'Anda tidak berhak memuat barang ini.');
        }

        $loading->update([
            'status' => 'loaded',
            'loaded_by' => $user->id,
            'loaded_at' => now(),
        ]);

        // Append load note to cross_check_notes if provided
        if ($request->filled('catatan_muat')) {
            $note = $loading->cross_check_notes
                ? $loading->cross_check_notes . "\n[Cargo loaded]: " . $request->catatan_muat
                : "[Cargo loaded]: " . $request->catatan_muat;
            $loading->update(['cross_check_notes' => $note]);
        }

        return redirect()->route('pasgar.loading.show', $id)
            ->with('success', 'Barang berhasil dimuat ke kendaraan. Siap berjualan!');
    }

    /**
     * Print loading (Surat Jalan/Faktur)
     */
    public function print($id)
    {
        $loading = PasgarLoading::with(['sales', 'items.product', 'items.unitConversion.unit', 'warehouse'])->findOrFail($id);
        
        return view('pasgar.loading.print', compact('loading'));
    }
}

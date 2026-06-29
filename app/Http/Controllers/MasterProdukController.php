<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductUnitConversion;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Http\Requests\MasterProduk\StoreProductRequest;
use App\Http\Requests\MasterProduk\UpdateProductRequest;
use App\Http\Requests\MasterProduk\StoreCategoryRequest;
use App\Http\Requests\MasterProduk\UpdateCategoryRequest;
use App\Http\Requests\MasterProduk\StoreUnitRequest;
use App\Http\Requests\MasterProduk\UpdateUnitRequest;
use App\Http\Requests\MasterProduk\StoreAdjustmentRequest;
use App\Services\AuditService;
use App\Services\PriceService;
use App\Services\ReferenceNumberService;
use App\Support\SearchSanitizer;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MasterProdukController extends Controller
{
    private PriceService $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    public function index(Request $request): View
    {
        $tab = in_array($request->get('tab', 'produk'), ['produk', 'kategori', 'satuan', 'stok'])
            ? $request->get('tab', 'produk') : 'produk';

        $stats = [
            'total_produk'   => Product::count(),
            'total_kategori' => Category::count(),
            'total_satuan'   => Unit::count(),
            'total_stok'     => StockMovement::where('source_type', 'adjustment')->count(),
            'low_stock'      => Product::whereColumn('stock', '<=', 'min_stock')->where('min_stock', '>', 0)->count(),
        ];

        $categories = Category::orderBy('name')->get(['id', 'name']);
        $units = Unit::orderBy('name')->get(['id', 'name', 'abbreviation']);
        $warehouses = Warehouse::where('active', true)->orderBy('name')->get(['id', 'name']);

        return view('master.produk.index', compact('tab', 'stats', 'categories', 'units', 'warehouses'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $units = Unit::orderBy('name')->get(['id', 'name', 'abbreviation']);

        return view('master.produk.create', compact('categories', 'units'));
    }

    public function edit(Product $product): View
    {
        $product->load(['unitConversions.unit']);
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $units = Unit::orderBy('name')->get(['id', 'name', 'abbreviation']);

        return view('master.produk.edit', compact('product', 'categories', 'units'));
    }

    // ─────────── PRODUK ───────────

    public function searchProducts(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'unit', 'unitConversions.unit']);

        if ($search = $request->get('search')) {
            $s = SearchSanitizer::sanitize($search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('barcode', 'like', "%{$s}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('low_stock')) {
            $query->whereColumn('stock', '<=', 'min_stock')->where('min_stock', '>', 0);
        }

        $perPage = min((int) $request->get('per_page', 15), 100);
        $products = $query->latest()->paginate($perPage);
        $products->getCollection()->transform(function ($p) {
            $units = $p->unitConversions->map(fn ($uc) => [
                'id'                 => $uc->id,
                'unit_id'            => $uc->unit_id,
                'unit_name'          => $uc->unit?->name,
                'conversion_factor'  => (float) $uc->conversion_factor,
                'purchase_price'     => (float) $uc->purchase_price,
                'sell_price_ecer'    => (float) $uc->sell_price_ecer,
                'sell_price_grosir'  => (float) $uc->sell_price_grosir,
                'sell_price_jual1'   => (float) ($uc->sell_price_jual1 ?? 0),
                'sell_price_jual2'   => (float) ($uc->sell_price_jual2 ?? 0),
                'sell_price_jual3'   => (float) ($uc->sell_price_jual3 ?? 0),
                'sell_price_minimal' => (float) ($uc->sell_price_minimal ?? 0),
                'is_base_unit'       => (bool) $uc->is_base_unit,
            ]);

            return [
                'id'              => $p->id,
                'name'            => $p->name,
                'sku'             => $p->sku,
                'barcode'         => $p->barcode,
                'category_id'     => $p->category_id,
                'category'        => $p->category?->name ?? '-',
                'unit_id'         => $p->unit_id,
                'unit_name'       => $p->unit?->name ?? '-',
                'price'           => (float) $p->price,
                'purchase_price'  => (float) ($p->purchase_price ?? 0),
                'stock'           => (int) $p->stock,
                'min_stock'       => (int) $p->min_stock,
                'description'     => $p->description,
                'units'           => $units,
                'stock_breakdown' => ProductStock::where('product_id', $p->id)
                    ->where('stock', '>', 0)
                    ->with('warehouse')
                    ->get()
                    ->map(fn ($ps) => [
                        'warehouse_id' => $ps->warehouse_id,
                        'warehouse'    => $ps->warehouse?->name ?? '-',
                        'qty'          => (int) $ps->stock,
                    ]),
            ];
        });

        return response()->json([
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
            'stats' => [
                'total'     => $products->total(),
                'low_stock' => Product::whereColumn('stock', '<=', 'min_stock')->where('min_stock', '>', 0)->count(),
            ],
        ]);
    }

    public function storeProduct(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $sku = $request->sku ?: ReferenceNumberService::generateSku();

            $product = Product::create([
                'name'           => $request->name,
                'category_id'    => $request->category_id,
                'unit_id'        => $request->unit_id,
                'sku'            => $sku,
                'barcode'        => $request->barcode,
                'price'          => $request->price,
                'purchase_price' => $request->purchase_price ?? 0,
                'stock'          => $request->stock ?? 0,
                'min_stock'      => $request->min_stock ?? 0,
                'description'    => $request->description,
            ]);

            if ($request->has('units') && is_array($request->units)) {
                $this->saveUnitConversions($product, $request->units);
            }

            DB::commit();

            AuditService::log('product.create', 'Product', $product->id, [
                'sku' => $product->sku, 'name' => $product->name,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan.']);
            }
            
            return redirect()->route('master.produk')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menyimpan produk: ' . $e->getMessage()], 500);
            }
            
            return back()->withInput()->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
    }

    public function showProduct(Product $product): JsonResponse
    {
        $product->load(['category', 'unit', 'unitConversions.unit']);
        $units = $product->unitConversions->map(fn ($uc) => [
            'id'                 => $uc->id,
            'unit_id'            => $uc->unit_id,
            'unit_name'          => $uc->unit?->name,
            'conversion_factor'  => (float) $uc->conversion_factor,
            'purchase_price'     => (float) $uc->purchase_price,
            'sell_price_ecer'    => (float) $uc->sell_price_ecer,
            'sell_price_grosir'  => (float) $uc->sell_price_grosir,
            'sell_price_jual1'   => (float) ($uc->sell_price_jual1 ?? 0),
            'sell_price_jual2'   => (float) ($uc->sell_price_jual2 ?? 0),
            'sell_price_jual3'   => (float) ($uc->sell_price_jual3 ?? 0),
            'sell_price_minimal' => (float) ($uc->sell_price_minimal ?? 0),
            'is_base_unit'       => (bool) $uc->is_base_unit,
        ]);

        return response()->json([
            'id'              => $product->id,
            'name'            => $product->name,
            'sku'             => $product->sku,
            'barcode'         => $product->barcode,
            'category_id'     => $product->category_id,
            'unit_id'         => $product->unit_id,
            'price'           => (float) $product->price,
            'purchase_price'  => (float) ($product->purchase_price ?? 0),
            'min_stock'       => (int) $product->min_stock,
            'description'     => $product->description,
            'units'           => $units,
        ]);
    }

    public function updateProduct(UpdateProductRequest $request, Product $product)
    {
        if (ProductStock::where('product_id', $product->id)->where('stock', '>', 0)->exists() && $request->stock !== null) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Stok tidak bisa diubah melalui form ini. Gunakan fitur Penyesuaian Stok.'], 422);
            }
            return back()->withInput()->with('error', 'Stok tidak bisa diubah melalui form ini. Gunakan fitur Penyesuaian Stok.');
        }

        try {
            DB::beginTransaction();

            $product->update([
                'name'           => $request->name,
                'category_id'    => $request->category_id,
                'unit_id'        => $request->unit_id,
                'sku'            => $request->sku,
                'barcode'        => $request->barcode,
                'price'          => $request->price,
                'purchase_price' => $request->purchase_price ?? 0,
                'min_stock'      => $request->min_stock ?? 0,
                'description'    => $request->description,
            ]);

            $product->unitConversions()->delete();
            if ($request->has('units') && is_array($request->units)) {
                $this->saveUnitConversions($product, $request->units);
            }

            DB::commit();

            AuditService::log('product.update', 'Product', $product->id, [
                'sku' => $product->sku, 'name' => $product->name,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Produk berhasil diperbarui.']);
            }
            return redirect()->route('master.produk')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui produk: ' . $e->getMessage()], 500);
            }
            return back()->withInput()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage());
        }
    }

    public function destroyProduct(Product $product): JsonResponse
    {
        $stockCount = ProductStock::where('product_id', $product->id)->where('stock', '>', 0)->count();
        if ($stockCount > 0) {
            return response()->json(['success' => false, 'message' => "Produk '{$product->name}' tidak bisa dihapus karena masih memiliki stok di {$stockCount} gudang."], 422);
        }

        $movementCount = StockMovement::where('product_id', $product->id)->count();
        if ($movementCount > 0) {
            return response()->json(['success' => false, 'message' => "Produk '{$product->name}' tidak bisa dihapus karena sudah memiliki riwayat pergerakan stok."], 422);
        }

        try {
            $product->unitConversions()->delete();
            $product->delete();

            AuditService::log('product.delete', 'Product', $product->id, [
                'id' => $product->id, 'sku' => $product->sku, 'name' => $product->name,
            ]);

            return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus.']);
        } catch (QueryException $e) {
            if ((string) $e->getCode() === '23000') {
                return response()->json(['success' => false, 'message' => 'Produk tidak bisa dihapus karena masih terkait data transaksi.'], 422);
            }
            throw $e;
        }
    }

    // ─────────── KATEGORI ───────────

    public function searchCategories(Request $request): JsonResponse
    {
        $query = Category::withCount(['products as products_count' => fn ($q) => $q->withTrashed()]);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $perPage = min((int) $request->get('per_page', 15), 100);
        $categories = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
                'per_page'     => $categories->perPage(),
                'total'        => $categories->total(),
            ],
        ]);
    }

    public function storeCategory(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->only('name', 'description'));
        return response()->json(['success' => true, 'message' => 'Kategori berhasil ditambahkan.', 'data' => $category]);
    }

    public function updateCategory(UpdateCategoryRequest $request, Category $kategori): JsonResponse
    {
        $kategori->update($request->only('name', 'description'));
        return response()->json(['success' => true, 'message' => 'Kategori berhasil diperbarui.', 'data' => $kategori]);
    }

    public function destroyCategory(Category $kategori): JsonResponse
    {
        $hasProducts = Product::withTrashed()->where('category_id', $kategori->id)->exists();
        if ($hasProducts) {
            return response()->json(['success' => false, 'message' => 'Kategori tidak bisa dihapus karena masih digunakan oleh produk.'], 422);
        }

        try {
            $kategori->delete();
            return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
        } catch (QueryException $e) {
            if ((string) $e->getCode() === '23000') {
                return response()->json(['success' => false, 'message' => 'Kategori tidak bisa dihapus karena masih terkait data transaksi.'], 422);
            }
            throw $e;
        }
    }

    // ─────────── SATUAN ───────────

    public function searchUnits(Request $request): JsonResponse
    {
        $query = Unit::withCount(['products as products_count' => fn ($q) => $q->withTrashed()]);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('abbreviation', 'like', '%' . $search . '%');
            });
        }

        $perPage = min((int) $request->get('per_page', 15), 100);
        $units = $query->latest()->paginate($perPage);

        return response()->json([
            'data' => $units->items(),
            'pagination' => [
                'current_page' => $units->currentPage(),
                'last_page'    => $units->lastPage(),
                'per_page'     => $units->perPage(),
                'total'        => $units->total(),
            ],
        ]);
    }

    public function storeUnit(StoreUnitRequest $request): JsonResponse
    {
        $unit = Unit::create($request->only('name', 'abbreviation', 'description'));
        return response()->json(['success' => true, 'message' => 'Satuan berhasil ditambahkan.', 'data' => $unit]);
    }

    public function updateUnit(UpdateUnitRequest $request, Unit $satuan): JsonResponse
    {
        $satuan->update($request->only('name', 'abbreviation', 'description'));
        return response()->json(['success' => true, 'message' => 'Satuan berhasil diperbarui.', 'data' => $satuan]);
    }

    public function destroyUnit(Unit $satuan): JsonResponse
    {
        $productsCount = Product::withTrashed()->where('unit_id', $satuan->id)->count();
        if ($productsCount > 0) {
            return response()->json(['success' => false, 'message' => "Satuan tidak bisa dihapus karena masih digunakan oleh {$productsCount} produk."], 422);
        }

        $conversionsCount = ProductUnitConversion::where('unit_id', $satuan->id)->count();
        if ($conversionsCount > 0) {
            return response()->json(['success' => false, 'message' => "Satuan tidak bisa dihapus karena masih digunakan pada konversi satuan produk."], 422);
        }

        try {
            $satuan->delete();
            return response()->json(['success' => true, 'message' => 'Satuan berhasil dihapus.']);
        } catch (QueryException $e) {
            if ((string) $e->getCode() === '23000') {
                return response()->json(['success' => false, 'message' => 'Satuan tidak bisa dihapus karena masih terkait data transaksi.'], 422);
            }
            throw $e;
        }
    }

    // ─────────── STOK (PENYESUAIAN) ───────────

    public function searchAdjustments(Request $request): JsonResponse
    {
        $query = StockMovement::with(['product', 'warehouse', 'user'])
            ->where('source_type', 'adjustment');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('tipe')) {
            $query->where('type', $request->tipe);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $perPage = min((int) $request->get('per_page', 15), 100);
        $adjustments = $query->latest()->paginate($perPage);

        $adjustments->getCollection()->transform(fn ($m) => [
            'id'               => $m->id,
            'reference_number' => $m->reference_number,
            'product_name'     => $m->product?->name ?? '-',
            'product_id'       => $m->product_id,
            'warehouse_name'   => $m->warehouse?->name ?? '-',
            'type'             => $m->type,
            'quantity'         => (int) $m->quantity,
            'balance'          => (int) $m->balance,
            'notes'            => $m->notes,
            'user_name'        => $m->user?->name ?? '-',
            'created_at'       => $m->created_at?->format('d/m/Y H:i'),
        ]);

        $stats = [
            'total_masuk'  => StockMovement::where('source_type', 'adjustment')->where('type', 'in')->count(),
            'total_keluar' => StockMovement::where('source_type', 'adjustment')->where('type', 'out')->count(),
        ];

        return response()->json([
            'data' => $adjustments->items(),
            'pagination' => [
                'current_page' => $adjustments->currentPage(),
                'last_page'    => $adjustments->lastPage(),
                'per_page'     => $adjustments->perPage(),
                'total'        => $adjustments->total(),
            ],
            'stats' => $stats,
        ]);
    }

    public function storeAdjustment(StoreAdjustmentRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);
            $warehouse = Warehouse::findOrFail($request->warehouse_id);

            $baseUnitName = $product->unit?->abbreviation ?? 'pcs';
            $totalBaseQty = 0;
            $unitNotes = [];

            // Process multiple items (per satuan)
            $items = $request->input('items', []);
            if (empty($items) || !is_array($items)) {
                // Fallback: single input (old format)
                $conversionFactor = 1;
                $unitName = $baseUnitName;
                $inputQty = (float) $request->jumlah;

                if ($request->filled('unit_id')) {
                    $conversion = ProductUnitConversion::where('product_id', $product->id)
                        ->where('unit_id', $request->unit_id)->first();
                    if ($conversion) {
                        $conversionFactor = $conversion->conversion_factor ?: 1;
                        $unitName = $conversion->unit?->name ?? $unitName;
                    }
                }

                $baseQty = $inputQty * $conversionFactor;
                $totalBaseQty = $baseQty;
                if ($conversionFactor > 1) {
                    $unitNotes[] = "{$inputQty} {$unitName}";
                }
            } else {
                foreach ($items as $item) {
                    $qty = (float) ($item['jumlah'] ?? 0);
                    if ($qty <= 0) continue;
                    $factor = (float) ($item['factor'] ?? 1);
                    $unitId = $item['unit_id'] ?? null;
                    $unitName = $baseUnitName;

                    if ($unitId) {
                        $unit = Unit::find((int) $unitId);
                        if ($unit) $unitName = $unit->name;
                    }

                    $baseQty = $qty * $factor;
                    $totalBaseQty += $baseQty;
                    if ($factor > 1) {
                        $unitNotes[] = "{$qty} {$unitName}";
                    } else {
                        $unitNotes[] = "{$qty} {$unitName}";
                    }
                }
            }

            if ($totalBaseQty <= 0) {
                throw new \Exception('Jumlah minimal harus lebih dari 0.');
            }

            $productStock = ProductStock::firstOrCreate(
                [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'location_id' => null,
                    'batch_number' => null,
                    'expired_date' => null,
                ],
                ['stock' => 0]
            );

            $stokSebelum = (float) $productStock->stock;

            if ($request->tipe === 'masuk') {
                $stokSesudah = $stokSebelum + $totalBaseQty;
                $qty = $totalBaseQty;
                $type = 'in';
            } else {
                $stokSesudah = $totalBaseQty;
                $qty = $stokSesudah - $stokSebelum;
                $type = $qty >= 0 ? 'in' : 'out';
            }

            $productStock->update(['stock' => $stokSesudah]);

            $totalStock = ProductStock::where('product_id', $product->id)->sum('stock');
            $product->update(['stock' => $totalStock]);

            $refNumber = 'ADJ-' . strtoupper(substr(uniqid(), -8));

            $directionLabel = $request->tipe === 'masuk'
                ? '[Stok Masuk Manual] '
                : ($qty >= 0 ? '[Koreksi Stok +] ' : '[Koreksi Stok -] ');

            $unitNote = '';
            if (!empty($unitNotes)) {
                $unitNote = ' | Per-satuan: ' . implode(' + ', $unitNotes) . ' = ' . $totalBaseQty . ' ' . $baseUnitName;
            }

            StockMovement::create([
                'product_id'       => $product->id,
                'warehouse_id'     => $warehouse->id,
                'type'             => $type,
                'status'           => 'completed',
                'source_type'      => 'adjustment',
                'reference_number' => $refNumber,
                'quantity'         => abs($qty),
                'quantity_in_unit' => abs($qty),
                'balance'          => $stokSesudah,
                'notes'            => $directionLabel . ($request->keterangan ?? '') . $unitNote,
                'user_id'          => Auth::id(),
            ]);

            DB::commit();

            $totalDisplay = $totalBaseQty . ' ' . $baseUnitName;
            $label = $request->tipe === 'masuk'
                ? "Stok berhasil ditambahkan (+{$totalDisplay})"
                : "Koreksi stok berhasil (dari {$stokSebelum} → {$stokSesudah} {$baseUnitName})";

            return response()->json(['success' => true, 'message' => $label]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // ─────────── HELPERS ───────────

    private function saveUnitConversions(Product $product, array $units): void
    {
        $unitIds = [];
        foreach ($units as $u) {
            if (in_array($u['unit_id'], $unitIds)) {
                throw new \InvalidArgumentException('Satuan duplikat tidak diperbolehkan.');
            }
            $unitIds[] = $u['unit_id'];
        }

        $baseSet = false;
        $minFactor = PHP_FLOAT_MAX;
        $minIdx = 0;

        foreach ($units as $idx => $u) {
            if (!empty($u['is_base_unit'])) {
                $baseSet = true;
            }
            $factor = (float) $u['conversion_factor'];
            if ($factor > 0 && $factor < $minFactor) {
                $minFactor = $factor;
                $minIdx = $idx;
            }
        }

        foreach ($units as $idx => $u) {
            $isBase = !empty($u['is_base_unit']);
            if (!$baseSet && $idx === $minIdx) {
                $isBase = true;
            }

            ProductUnitConversion::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $u['unit_id']],
                [
                    'conversion_factor'  => (float) $u['conversion_factor'],
                    'purchase_price'     => (float) $u['purchase_price'],
                    'sell_price_ecer'    => (float) $u['sell_price_ecer'],
                    'sell_price_grosir'  => (float) $u['sell_price_grosir'],
                    'sell_price_jual1'   => (float) ($u['sell_price_jual1'] ?? 0),
                    'sell_price_jual2'   => (float) ($u['sell_price_jual2'] ?? 0),
                    'sell_price_jual3'   => (float) ($u['sell_price_jual3'] ?? 0),
                    'sell_price_minimal' => (float) ($u['sell_price_minimal'] ?? 0),
                    'is_base_unit'       => $isBase,
                ]
            );
        }
    }
}

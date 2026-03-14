<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'unit', 'unitConversions.unit']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('sku', 'like', '%'.$request->search.'%')
                    ->orWhere('barcode', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    private function generateSku(): string
    {
        $lastProduct = Product::where('sku', 'like', 'PRD-%')->orderBy('id', 'desc')->first();
        if (! $lastProduct) {
            return 'PRD-0001';
        }
        $number = (int) str_replace('PRD-', '', $lastProduct->sku);

        return 'PRD-'.str_pad($number + 1, 4, '0', STR_PAD_LEFT);
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $unitsData = $units->map(function ($u) {
            return ['id' => $u->id, 'name' => $u->name, 'abbr' => $u->abbreviation];
        })->values()->toArray();
        $nextSku = $this->generateSku();

        return view('products.create', compact('categories', 'units', 'unitsData', 'nextSku'));
    }

    public function store(Request $request)
    {
        // Auto-generate SKU if empty or already exists (collision prevention)
        if (empty($request->sku) || Product::where('sku', $request->sku)->exists()) {
            $request->merge(['sku' => $this->generateSku()]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',

            'sku' => 'required|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            // Unit conversions validation
            'units' => 'nullable|array',
            'units.*.unit_id' => 'required|exists:units,id',
            'units.*.conversion_factor' => 'required|integer|min:1',
            'units.*.purchase_price' => 'required|numeric|min:0',
            'units.*.sell_price_ecer' => 'required|numeric|min:0',
            'units.*.sell_price_grosir' => 'required|numeric|min:0',
            'units.*.sell_price_jual1' => 'nullable|numeric|min:0',
            'units.*.sell_price_jual2' => 'nullable|numeric|min:0',
            'units.*.sell_price_jual3' => 'nullable|numeric|min:0',
            'units.*.sell_price_minimal' => 'nullable|numeric|min:0',
            'units.*.is_base_unit' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create($request->only([
                'name', 'category_id', 'unit_id',
                'sku', 'barcode', 'price', 'purchase_price',
                'stock', 'min_stock', 'description',
            ]));

            // Save unit conversions
            if ($request->has('units') && is_array($request->units)) {
                $this->saveUnitConversions($product, $request->units);
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan produk: '.$e->getMessage())->withInput();
        }
    }

    public function show(Product $product)
    {
        $product->load(['category', 'unit', 'unitConversions.unit']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $product->load('unitConversions.unit');

        $unitsData = $units->map(function ($u) {
            return ['id' => $u->id, 'name' => $u->name, 'abbr' => $u->abbreviation];
        })->values()->toArray();

        $existingConversionsData = $product->unitConversions->map(function ($uc) {
            return [
                'unit_id' => $uc->unit_id,
                'conversion_factor' => $uc->conversion_factor,
                'purchase_price' => (float) $uc->purchase_price,
                'sell_price_ecer' => (float) $uc->sell_price_ecer,
                'sell_price_grosir' => (float) $uc->sell_price_grosir,
                'sell_price_jual1' => (float) ($uc->sell_price_jual1 ?? 0),
                'sell_price_jual2' => (float) ($uc->sell_price_jual2 ?? 0),
                'sell_price_jual3' => (float) ($uc->sell_price_jual3 ?? 0),
                'sell_price_minimal' => (float) ($uc->sell_price_minimal ?? 0),
                'is_base_unit' => (bool) $uc->is_base_unit,
            ];
        })->values()->toArray();

        return view('products.edit', compact('product', 'categories', 'units', 'unitsData', 'existingConversionsData'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',

            'sku' => 'required|string|unique:products,sku,'.$product->id,
            'barcode' => 'nullable|string|unique:products,barcode,'.$product->id,
            'price' => 'required|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'units' => 'nullable|array',
            'units.*.unit_id' => 'required|exists:units,id',
            'units.*.conversion_factor' => 'required|integer|min:1',
            'units.*.purchase_price' => 'required|numeric|min:0',
            'units.*.sell_price_ecer' => 'required|numeric|min:0',
            'units.*.sell_price_grosir' => 'required|numeric|min:0',
            'units.*.sell_price_jual1' => 'nullable|numeric|min:0',
            'units.*.sell_price_jual2' => 'nullable|numeric|min:0',
            'units.*.sell_price_jual3' => 'nullable|numeric|min:0',
            'units.*.sell_price_minimal' => 'nullable|numeric|min:0',
            'units.*.is_base_unit' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $product->update($request->only([
                'name', 'category_id', 'unit_id',
                'sku', 'barcode', 'price', 'purchase_price',
                'min_stock', 'description',
            ]));

            // Replace unit conversions
            $product->unitConversions()->delete();
            if ($request->has('units') && is_array($request->units)) {
                $this->saveUnitConversions($product, $request->units);
            }

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal memperbarui produk: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function importForm()
    {
        $categories = Category::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('products.import', compact('categories', 'units'));
    }

    public function downloadTemplate(): StreamedResponse
    {
        $filename = 'template-produk-dodpos.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['name', 'category', 'unit', 'sku', 'barcode', 'price', 'purchase_price', 'stock', 'min_stock', 'description'], ';');
            fputcsv($out, ['Indomie Goreng', 'Sembako', 'pcs', '', '8999999999999', '3500', '3000', '0', '5', ''], ';');
            fputcsv($out, ['Gula Pasir', 'Sembako', 'kg', '', '', '16000', '15000', '10', '5', ''], ';');
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:20480',
            'mode' => 'nullable|in:create_only,upsert_by_sku',
        ]);

        $mode = $request->input('mode', 'upsert_by_sku');
        $file = $request->file('file')->getRealPath();

        $created = 0;
        $updated = 0;
        $errors = 0;
        $skipped = 0;
        $rowErrors = [];

        if (! is_readable($file)) {
            return back()->with('error', 'File tidak bisa dibaca.');
        }

        $handle = fopen($file, 'r');
        if ($handle === false) {
            return back()->with('error', 'Gagal membuka file untuk diproses.');
        }

        $firstLine = '';
        while (($line = fgets($handle)) !== false) {
            $firstLine = trim($line);
            if ($firstLine !== '') {
                break;
            }
        }
        rewind($handle);

        $delimiter = $this->detectCsvDelimiter($firstLine);
        $header = fgetcsv($handle, 0, $delimiter);
        if (! $header) {
            fclose($handle);

            return back()->with('error', 'Header CSV tidak ditemukan.');
        }

        if (isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $header[0]);
        }

        $aliasToCanonical = $this->csvHeaderAliases();

        $map = [];
        foreach ($header as $i => $h) {
            $key = $this->normalizeCsvKey((string) $h);
            $canonical = $aliasToCanonical[$key] ?? $key;
            $map[$canonical] = $i;
        }

        foreach (['name', 'category', 'price'] as $required) {
            if (! array_key_exists($required, $map)) {
                fclose($handle);

                return back()->with('error', "Kolom wajib tidak ditemukan di header CSV: {$required}");
            }
        }

        DB::beginTransaction();
        try {
            $lineNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $lineNumber++;
                try {
                    $get = function (string $key, string $default = '') use ($map, $row): string {
                        if (! array_key_exists($key, $map)) {
                            return $default;
                        }

                        return (string) ($row[$map[$key]] ?? $default);
                    };

                    $name = trim($get('name'));
                    if ($name === '') {
                        $skipped++;

                        continue;
                    }

                    $categoryName = trim($get('category'));
                    if ($categoryName === '') {
                        throw new \RuntimeException('Kategori wajib diisi.');
                    }

                    $unitAbbrOrName = trim($get('unit'));
                    $sku = trim($get('sku'));
                    $barcode = trim($get('barcode'));
                    $priceRaw = $get('price');
                    $price = $this->parseCsvNumber($priceRaw);
                    if ($price === null || $price < 0) {
                        throw new \RuntimeException('Harga jual (price) wajib diisi dan harus >= 0.');
                    }

                    $purchaseRaw = $get('purchase_price');
                    $purchasePrice = $this->parseCsvNumber($purchaseRaw) ?? 0;
                    $stockRaw = $get('stock', '0');
                    $stock = (int) round($this->parseCsvNumber($stockRaw) ?? 0);
                    $minStockRaw = $get('min_stock', '5');
                    $minStock = (int) round($this->parseCsvNumber($minStockRaw) ?? 5);
                    $description = trim($get('description'));

                    $category = null;
                    if ($categoryName !== '') {
                        $category = Category::firstOrCreate(['name' => $categoryName], ['description' => null]);
                    }

                    $unit = null;
                    if ($unitAbbrOrName !== '') {
                        $unit = Unit::where('abbreviation', $unitAbbrOrName)->first()
                             ?: Unit::where('name', $unitAbbrOrName)->first();
                    }

                    if ($sku === '') {
                        $sku = $this->generateSku();
                    }

                    $existing = Product::where('sku', $sku)->first();
                    if ($existing && $mode === 'create_only') {
                        $skipped++;

                        continue;
                    }

                    $payload = [
                        'name' => $name,
                        'category_id' => $category?->id ?? null,
                        'unit_id' => $unit?->id ?? null,
                        'sku' => $sku,
                        'barcode' => $barcode !== '' ? $barcode : null,
                        'price' => $price,
                        'purchase_price' => max(0, (float) $purchasePrice),
                        'stock' => max(0, $stock),
                        'min_stock' => max(0, $minStock),
                        'description' => $description !== '' ? $description : null,
                    ];

                    if ($existing) {
                        if ($mode === 'upsert_by_sku') {
                            $existing->update($payload);
                            if ($unit) {
                                ProductUnitConversion::updateOrCreate(
                                    ['product_id' => $existing->id, 'unit_id' => $unit->id],
                                    [
                                        'conversion_factor' => 1,
                                        'purchase_price' => max(0, (float) $purchasePrice),
                                        'sell_price_ecer' => $price,
                                        'sell_price_grosir' => $price,
                                        'sell_price_jual1' => 0,
                                        'sell_price_jual2' => 0,
                                        'sell_price_jual3' => 0,
                                        'sell_price_minimal' => 0,
                                        'is_base_unit' => true,
                                    ]
                                );
                            }
                            $updated++;
                        }
                    } else {
                        $p = Product::create($payload);
                        if ($unit) {
                            ProductUnitConversion::updateOrCreate(
                                ['product_id' => $p->id, 'unit_id' => $unit->id],
                                [
                                    'conversion_factor' => 1,
                                    'purchase_price' => max(0, (float) $purchasePrice),
                                    'sell_price_ecer' => $price,
                                    'sell_price_grosir' => $price,
                                    'sell_price_jual1' => 0,
                                    'sell_price_jual2' => 0,
                                    'sell_price_jual3' => 0,
                                    'sell_price_minimal' => 0,
                                    'is_base_unit' => true,
                                ]
                            );
                        }
                        $created++;
                    }
                } catch (\Throwable $e) {
                    $errors++;
                    if (count($rowErrors) < 20) {
                        $rowErrors[] = "Baris {$lineNumber}: ".$e->getMessage();
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);

            return back()->with('error', 'Gagal mengimpor: '.$e->getMessage());
        }

        fclose($handle);

        $summary = ['created' => $created, 'updated' => $updated, 'skipped' => $skipped, 'errors' => $errors];
        if ($errors > 0) {
            return redirect()
                ->route('products.import')
                ->with('error', 'Import selesai, tapi ada beberapa baris yang gagal.')
                ->with('import_summary', $summary)
                ->with('import_errors', $rowErrors);
        }

        return redirect()->route('products.index')->with('success', "Import selesai. Tambah: {$created}, Update: {$updated}, Lewat: {$skipped}.");
    }

    private function detectCsvDelimiter(string $sample): string
    {
        $candidates = [',', ';', "\t", '|'];
        $best = ',';
        $bestScore = -1;
        foreach ($candidates as $d) {
            $score = substr_count($sample, $d);
            if ($score > $bestScore) {
                $best = $d;
                $bestScore = $score;
            }
        }

        return $best;
    }

    private function normalizeCsvKey(string $key): string
    {
        $k = trim(mb_strtolower($key));
        $k = str_replace(["\u{00A0}", "\t"], ' ', $k);
        $k = preg_replace('/\s+/', '_', $k);
        $k = preg_replace('/[^a-z0-9_]/', '', (string) $k);

        return (string) $k;
    }

    private function csvHeaderAliases(): array
    {
        $map = [];
        $aliases = [
            'name' => ['name', 'nama', 'nama_produk', 'nama_barang', 'produk', 'product', 'product_name'],
            'category' => ['category', 'kategori', 'kategori_produk', 'kategori_barang'],
            'unit' => ['unit', 'satuan', 'uom', 'satuan_barang'],
            'sku' => ['sku', 'kode_sku', 'kode_barang', 'kode'],
            'barcode' => ['barcode', 'ean', 'barcode_ean'],
            'price' => ['price', 'harga', 'harga_jual', 'harga_jual_ecer', 'harga_ecer', 'jual'],
            'purchase_price' => ['purchase_price', 'harga_beli', 'modal', 'hpp', 'cost'],
            'stock' => ['stock', 'stok', 'qty', 'quantity', 'jumlah'],
            'min_stock' => ['min_stock', 'minstok', 'minimum_stock', 'stok_minimum', 'min'],
            'description' => ['description', 'deskripsi', 'keterangan', 'catatan', 'note', 'notes'],
        ];
        foreach ($aliases as $canonical => $keys) {
            foreach ($keys as $k) {
                $map[$this->normalizeCsvKey($k)] = $canonical;
            }
        }

        return $map;
    }

    private function parseCsvNumber(string $raw): ?float
    {
        $s = trim($raw);
        if ($s === '') {
            return null;
        }
        $s = str_replace(['Rp', 'rp', ' ', "\u{00A0}"], '', $s);
        $s = preg_replace('/[^0-9.,\-]/', '', (string) $s);
        if ($s === '' || $s === '-' || $s === '.' || $s === ',') {
            return null;
        }

        $hasDot = str_contains($s, '.');
        $hasComma = str_contains($s, ',');
        if ($hasDot && $hasComma) {
            $s = str_replace('.', '', $s);
            $s = str_replace(',', '.', $s);
        } elseif ($hasComma && ! $hasDot) {
            $s = str_replace(',', '.', $s);
        } else {
            $s = str_replace(',', '', $s);
        }

        if (! is_numeric($s)) {
            return null;
        }

        return (float) $s;
    }

    /**
     * Save unit conversions from the form array.
     * Ensures exactly one is_base_unit = true (defaults to the smallest conversion_factor).
     */
    private function saveUnitConversions(Product $product, array $units): void
    {
        // Determine which row is the base unit (from checkbox, or fallback to row with factor=1 or min factor)
        $baseSet = false;
        $minFactor = PHP_INT_MAX;
        $minIdx = 0;

        foreach ($units as $idx => $u) {
            if (isset($u['is_base_unit']) && $u['is_base_unit']) {
                $baseSet = true;
            }
            if ((int) $u['conversion_factor'] < $minFactor) {
                $minFactor = (int) $u['conversion_factor'];
                $minIdx = $idx;
            }
        }

        foreach ($units as $idx => $u) {
            $isBase = isset($u['is_base_unit']) && $u['is_base_unit'];
            if (! $baseSet && $idx === $minIdx) {
                $isBase = true;
            }

            ProductUnitConversion::updateOrCreate(
                ['product_id' => $product->id, 'unit_id' => $u['unit_id']],
                [
                    'conversion_factor' => (int) $u['conversion_factor'],
                    'purchase_price' => (float) $u['purchase_price'],
                    'sell_price_ecer' => (float) $u['sell_price_ecer'],
                    'sell_price_grosir' => (float) $u['sell_price_grosir'],
                    'sell_price_jual1' => (float) ($u['sell_price_jual1'] ?? 0),
                    'sell_price_jual2' => (float) ($u['sell_price_jual2'] ?? 0),
                    'sell_price_jual3' => (float) ($u['sell_price_jual3'] ?? 0),
                    'sell_price_minimal' => (float) ($u['sell_price_minimal'] ?? 0),
                    'is_base_unit' => $isBase,
                ]
            );
        }
    }
}

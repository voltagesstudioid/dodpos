<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarLoading;
use App\Models\PasgarLoadingItem;
use App\Models\PasgarPelanggan;
use App\Models\PasgarPenjualan;
use App\Models\PasgarPenjualanItem;
use App\Models\PasgarSales;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasgarPenjualanController extends Controller
{
    /**
     * List all penjualan with optional status filter.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSalesRole = str_starts_with(strtolower($user->role ?? ''), 'sales_') || ($user->role ?? '') === 'sales';

        $search = $request->input('search');
        $metodeBayar = $request->input('metode_bayar');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = PasgarPenjualan::with(['sales', 'pelanggan', 'items'])->latest('tanggal');

        // Sales users only see their own
        $salesProfile = null;
        if ($isSalesRole) {
            $salesProfile = PasgarSales::where('user_id', $user->id)->first();
            if ($salesProfile) {
                $query->where('sales_id', $salesProfile->id);
            }
        }

        // Filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_transaksi', 'like', "%{$search}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', fn($pq) => $pq->where('nama_toko', 'like', "%{$search}%"))
                  ->orWhereHas('sales', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
            });
        }
        if ($metodeBayar) {
            $query->where('metode_bayar', $metodeBayar);
        }
        if ($dateFrom) {
            $query->where('tanggal', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('tanggal', '<=', $dateTo . ' 23:59:59');
        }

        $penjualans = $query->paginate(15)->withQueryString();

        // Stats (scoped same as main query)
        $statsQuery = PasgarPenjualan::query();
        if ($salesProfile) {
            $statsQuery->where('sales_id', $salesProfile->id);
        }

        $today = now()->toDateString();
        $stats = [
            'total_transaksi' => (clone $statsQuery)->count(),
            'total_penjualan' => (clone $statsQuery)->sum('total'),
            'total_hari_ini' => (clone $statsQuery)->whereDate('tanggal', $today)->sum('total'),
            'count_hari_ini' => (clone $statsQuery)->whereDate('tanggal', $today)->count(),
        ];

        return view('pasgar.penjualan.index', compact(
            'penjualans', 'stats', 'isSalesRole',
            'search', 'metodeBayar', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Show form to create a new penjualan from loaded stock.
     */
    public function create()
    {
        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile) {
            return redirect()->route('pasgar.penjualan.index')->with('error', 'Profil sales tidak ditemukan.');
        }

        // Find loaded loading with remaining stock
        $loading = PasgarLoading::with(['items.product.unitConversions.unit', 'items.unitConversion'])
            ->where('sales_id', $salesProfile->id)
            ->where('status', 'loaded')
            ->latest('loaded_at')
            ->first();

        if (!$loading) {
            return redirect()->route('pasgar.loading.index')
                ->with('error', 'Belum ada loading yang dimuat ke kendaraan. Muat barang terlebih dahulu.');
        }

        // Filter items with remaining stock
        $availableItems = $loading->items->filter(fn($item) => $item->qty_sisa > 0)->values();

        if ($availableItems->isEmpty()) {
            return redirect()->route('pasgar.loading.show', $loading->id)
                ->with('error', 'Semua stok sudah terjual. Tidak ada barang tersisa untuk dijual.');
        }

        // Build available items JSON for the form
        $itemsJson = $availableItems->map(function ($item) {
            $product = $item->product;
            $conv = $item->unitConversion;
            // Get all conversions for the product (for price lookup)
            $allConvs = $product->unitConversions->map(function ($uc) {
                $minimal = (float) $uc->sell_price_minimal;
                if ($minimal > 0) {
                    $minPrice = $minimal;
                } else {
                    $ecer = (float) $uc->sell_price_ecer;
                    $jual1 = (float) $uc->sell_price_jual1;
                    $jual2 = (float) $uc->sell_price_jual2;
                    $jual3 = (float) $uc->sell_price_jual3;
                    $grosir = (float) $uc->sell_price_grosir;
                    $prices = array_filter([$ecer, $jual1, $jual2, $jual3, $grosir], fn($p) => $p > 0);
                    $minPrice = empty($prices) ? 0 : min($prices);
                }

                return [
                    'id' => $uc->id,
                    'unit_name' => $uc->unit?->name ?? '',
                    'conversion_factor' => (int) $uc->conversion_factor,
                    'is_base' => (bool) $uc->is_base_unit,
                    'price' => (float) ($uc->sell_price_grosir ?: $uc->sell_price_ecer ?: 0),
                    'min_price' => $minPrice,
                    'ecer' => (float) $uc->sell_price_ecer,
                    'jual1' => (float) $uc->sell_price_jual1,
                    'jual2' => (float) $uc->sell_price_jual2,
                    'jual3' => (float) $uc->sell_price_jual3,
                ];
            })->values();

            // Determine current conversion
            $currentConv = null;
            if ($conv) {
                $minimal = (float) $conv->sell_price_minimal;
                if ($minimal > 0) {
                    $minPrice = $minimal;
                } else {
                    $ecer = (float) $conv->sell_price_ecer;
                    $jual1 = (float) $conv->sell_price_jual1;
                    $jual2 = (float) $conv->sell_price_jual2;
                    $jual3 = (float) $conv->sell_price_jual3;
                    $grosir = (float) $conv->sell_price_grosir;
                    $prices = array_filter([$ecer, $jual1, $jual2, $jual3, $grosir], fn($p) => $p > 0);
                    $minPrice = empty($prices) ? 0 : min($prices);
                }

                $currentConv = [
                    'id' => $conv->id,
                    'unit_name' => $conv->unit?->name ?? '',
                    'conversion_factor' => (int) $conv->conversion_factor,
                    'price' => (float) ($conv->sell_price_grosir ?: $conv->sell_price_ecer ?: 0),
                    'min_price' => $minPrice,
                    'ecer' => (float) $conv->sell_price_ecer,
                    'jual1' => (float) $conv->sell_price_jual1,
                    'jual2' => (float) $conv->sell_price_jual2,
                    'jual3' => (float) $conv->sell_price_jual3,
                ];
            }

            return [
                'loading_item_id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $product->name,
                'sku' => $product->sku ?? '',
                'category' => $product->category?->name ?? '',
                'qty_sisa' => (float) $item->qty_sisa,
                'unit_conversion_id' => $item->unit_conversion_id,
                'current_conversion' => $currentConv,
                'conversions' => $allConvs,
                'sumber' => $item->sumber,
            ];
        })->values()->toJson();

        // Get pelanggan list
        $pelanggans = PasgarPelanggan::aktif()->orderBy('nama_toko')->get();

        // Build pelanggan JSON for the form
        $pelanggansJson = $pelanggans->map(function ($pg) {
            return [
                'id' => $pg->id,
                'nama_toko' => $pg->nama_toko,
                'nama_pemilik' => $pg->nama_pemilik,
                'no_hp' => $pg->no_hp ?? '',
                'alamat' => $pg->alamat ?? '',
                'sisa_limit' => $pg->sisa_limit,
            ];
        })->values()->toJson();

        $isSalesRole = str_starts_with(strtolower($user->role ?? ''), 'sales_') || ($user->role ?? '') === 'sales';

        return view('pasgar.penjualan.create', compact(
            'loading', 'salesProfile', 'itemsJson', 'pelanggans', 'pelanggansJson', 'isSalesRole'
        ));
    }

    /**
     * Store a new penjualan transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loading_id' => 'required|exists:pasgar_loadings,id',
            'pelanggan_id' => 'nullable|exists:pasgar_pelanggan,id',
            'nama_pelanggan' => 'nullable|string|max:100',
            'telepon_pelanggan' => 'nullable|string|max:20',
            'alamat_pelanggan' => 'nullable|string|max:500',
            'metode_bayar' => 'required|in:tunai,transfer',
            'id_transaksi_transfer' => 'required_if:metode_bayar,transfer|nullable|string|max:100',
            'foto_bukti_transfer' => 'required_if:metode_bayar,transfer|image|mimes:jpeg,jpg,png,webp|max:4096',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.loading_item_id' => 'required|integer',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.unit_conversion_id' => 'nullable|integer|exists:product_unit_conversions,id',
            'items.*.qty' => 'required|integer|min:1|max:9999',
            'items.*.harga' => 'required|numeric|min:0',
        ], [
            'id_transaksi_transfer.required_if' => 'ID Transaksi wajib diisi bila menggunakan transfer.',
            'foto_bukti_transfer.required_if' => 'Foto bukti transfer wajib diunggah bila menggunakan transfer.',
            'items.required' => 'Tambahkan minimal 1 barang.',
            'items.min' => 'Tambahkan minimal 1 barang.',
            'items.*.qty.required' => 'Masukkan jumlah.',
            'items.*.qty.min' => 'Jumlah minimal 1.',
            'items.*.harga.required' => 'Masukkan harga.',
            'items.*.harga.min' => 'Harga tidak boleh negatif.',
        ]);

        $user = Auth::user();
        $isSalesRole = str_starts_with(strtolower($user->role ?? ''), 'sales_') || ($user->role ?? '') === 'sales';
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile) {
            return redirect()->route('pasgar.penjualan.index')->with('error', 'Profil sales tidak ditemukan.');
        }

        $loading = PasgarLoading::findOrFail($validated['loading_id']);
        if ($loading->status !== 'loaded' || $loading->sales_id !== $salesProfile->id) {
            return redirect()->route('pasgar.penjualan.index')->with('error', 'Loading tidak valid untuk penjualan.');
        }

        // Pre-compute subtotals
        $grandTotal = 0;
        $itemData = [];
        foreach ($validated['items'] as $item) {
            $qty = $item['qty'];
            $harga = (float) $item['harga'];
            $subtotal = $qty * $harga;
            $grandTotal += $subtotal;

            // Verify qty doesn't exceed remaining stock
            $loadingItem = PasgarLoadingItem::find($item['loading_item_id']);
            if (!$loadingItem || $loadingItem->loading_id != $loading->id) {
                return back()->withErrors(['items' => 'Item tidak valid.'])->withInput();
            }

            // --- Multi-UOM Calculation ---
            $convId = $item['unit_conversion_id'] ?? null;
            $convSold = null;
            if ($convId) {
                $convSold = \App\Models\ProductUnitConversion::find($convId);
            } else {
                $convSold = \App\Models\ProductUnitConversion::where('product_id', $item['product_id'])
                    ->where('is_base_unit', true)->first();
            }

            $convLoaded = $loadingItem->unitConversion;
            $factorSold = $convSold ? $convSold->conversion_factor : 1;
            $factorLoaded = $convLoaded ? $convLoaded->conversion_factor : 1;

            // Calculate quantity to deduct from loadingItem (in loaded unit)
            $deduction = ($qty * $factorSold) / $factorLoaded;

            // Use string comparison formatting to avoid floating point precision issues during display
            if (round($deduction, 3) > round($loadingItem->qty_sisa, 3)) {
                return back()->withErrors(['items' => "Qty melebihi stok sisa (Maksimal: " . (round($loadingItem->qty_sisa * $factorLoaded / $factorSold)) . " satuan yang dipilih)."])->withInput();
            }
            // --- End Multi-UOM Calculation ---

            // Calculate minimum allowed price
            $minPrice = 0;
            $convId = $item['unit_conversion_id'] ?? null;
            $conv = null;
            if ($convId) {
                $conv = \App\Models\ProductUnitConversion::find($convId);
            } else {
                $conv = \App\Models\ProductUnitConversion::where('product_id', $item['product_id'])
                    ->where('is_base_unit', true)->first();
            }

            if ($convSold) {
                $minimal = (float) $convSold->sell_price_minimal;
                if ($minimal > 0) {
                    $minPrice = $minimal;
                } else {
                    $ecer = (float) $convSold->sell_price_ecer;
                    $jual1 = (float) $convSold->sell_price_jual1;
                    $jual2 = (float) $convSold->sell_price_jual2;
                    $jual3 = (float) $convSold->sell_price_jual3;
                    $grosir = (float) $convSold->sell_price_grosir;
                    $prices = array_filter([$ecer, $jual1, $jual2, $jual3, $grosir], fn($p) => $p > 0);
                    $minPrice = empty($prices) ? 0 : min($prices);
                }
            }

            // Enforce minimum price for sales role
            if ($isSalesRole) {
                if ($harga < $minPrice) {
                    return back()->withErrors(['items' => "Harga " . ($loadingItem->product->name ?? 'Barang') . " tidak boleh di bawah harga minimal (Rp " . number_format($minPrice, 0, ',', '.') . ")."])->withInput();
                }
            }

            $subtotal = $qty * $harga;

            $itemData[] = [
                'loading_item_id' => $loadingItem->id,
                'product_id' => $item['product_id'],
                'unit_conversion_id' => $item['unit_conversion_id'] ?? null,
                'qty' => $qty,
                'deduction' => $deduction, // Pass the deduction factor to the transaction closure
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];
        }

        // Recalculate grand total from final item data (accounts for sales price override)
        $grandTotal = array_sum(array_column($itemData, 'subtotal'));

        // Limit method removed for Pasgar

        $uangMuka = 0;

        // Handle transfer proof upload
        $transferData = [
            'id_transaksi_transfer' => null,
            'foto_bukti_transfer' => null,
        ];
        if ($validated['metode_bayar'] === 'transfer') {
            $transferData['id_transaksi_transfer'] = $request->input('id_transaksi_transfer');
            if ($request->hasFile('foto_bukti_transfer')) {
                try {
                    $upload = FileUploadService::uploadImage(
                        $request->file('foto_bukti_transfer'),
                        'penjualan/pasgar/transfer',
                        'public',
                        ['max_width' => 1200, 'max_height' => 1200]
                    );
                    $transferData['foto_bukti_transfer'] = $upload['path'];
                } catch (\Throwable $e) {
                    return back()->withInput()->with('error', 'Gagal upload bukti transfer: ' . $e->getMessage());
                }
            }
        }

        DB::transaction(function () use ($loading, $salesProfile, $validated, $grandTotal, $itemData, $uangMuka, $transferData) {
            // Create penjualan
            $penjualan = PasgarPenjualan::create([
                'nomor_transaksi' => PasgarPenjualan::generateNomor(),
                'loading_id' => $loading->id,
                'sales_id' => $salesProfile->id,
                'pelanggan_id' => $validated['pelanggan_id'] ?? null,
                'nama_pelanggan' => $validated['nama_pelanggan'] ?? null,
                'telepon_pelanggan' => $validated['telepon_pelanggan'] ?? null,
                'alamat_pelanggan' => $validated['alamat_pelanggan'] ?? null,
                'tanggal' => now(),
                'total' => $grandTotal,
                'uang_muka' => $uangMuka,
                'metode_bayar' => $validated['metode_bayar'],
                'id_transaksi_transfer' => $transferData['id_transaksi_transfer'],
                'foto_bukti_transfer' => $transferData['foto_bukti_transfer'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Create items and deduct stock
            foreach ($itemData as $data) {
                PasgarPenjualanItem::create([
                    'penjualan_id' => $penjualan->id,
                    'product_id' => $data['product_id'],
                    'unit_conversion_id' => $data['unit_conversion_id'],
                    'qty' => $data['qty'],
                    'harga' => $data['harga'],
                    'subtotal' => $data['subtotal'],
                ]);

                // Deduct from loading item using the calculated fractional deduction
                $loadingItem = PasgarLoadingItem::find($data['loading_item_id']);
                $loadingItem->qty_terjual += $data['deduction'];
                $loadingItem->qty_sisa = max(0, $loadingItem->qty_sisa - $data['deduction']);
                $loadingItem->save();
            }

            // Check if all items are sold → auto-complete loading
            $allSold = $loading->items()->where('qty_sisa', '>', 0)->count() === 0;
            if ($allSold) {
                $loading->update(['status' => 'completed']);
            }

            // If limit, create hutang
            if ($validated['metode_bayar'] === 'limit') {
                \App\Models\PasgarHutang::create([
                    'pelanggan_id' => $validated['pelanggan_id'],
                    'penjualan_id' => $penjualan->id,
                    'total_hutang' => $grandTotal,
                    'dibayar' => 0,
                    'sisa' => $grandTotal,
                    'status' => 'belum_lunas',
                    'keterangan' => 'Penjualan menggunakan limit',
                ]);
            }
        });

        return redirect()->route('pasgar.penjualan.index')
            ->with('success', 'Transaksi penjualan berhasil disimpan.');
    }

    /**
     * Show penjualan detail.
     */
    public function show($id)
    {
        $penjualan = PasgarPenjualan::with([
            'sales', 'loading', 'pelanggan',
            'items.product', 'items.unitConversion.unit',
        ])->findOrFail($id);

        return view('pasgar.penjualan.show', compact('penjualan'));
    }

    /**
     * Print receipt for a penjualan transaction.
     */
    public function printStruk($id)
    {
        $penjualan = PasgarPenjualan::with([
            'sales', 'pelanggan', 'loading',
            'items.product', 'items.unitConversion.unit',
        ])->findOrFail($id);

        // Sales can only print their own transactions
        if (str_starts_with(strtolower(Auth::user()->role ?? ''), 'sales_') || (Auth::user()->role ?? '') === 'sales') {
            $profile = PasgarSales::where('user_id', Auth::id())->first();
            if ($profile && $penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        return view('pasgar.penjualan.print', compact('penjualan'));
    }
}

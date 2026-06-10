<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarLoading;
use App\Models\PasgarLoadingItem;
use App\Models\PasgarOpname;
use App\Models\PasgarOpnameItem;
use App\Models\PasgarSales;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasgarOpnameController extends Controller
{
    private const WH_GUDANG = 1;

    /**
     * List all opnames with optional filters.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSales = $user && (str_starts_with(strtolower($user->role ?? ''), 'sales_') || ($user->role ?? '') === 'sales');

        $query = PasgarOpname::with(['sales', 'loading', 'items.product'])->latest();

        // Sales scoped to own
        if ($isSales) {
            $salesProfile = PasgarSales::where('user_id', $user->id)->first();
            if ($salesProfile) {
                $query->where('sales_id', $salesProfile->id);
            }
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_opname', 'like', "%{$search}%")
                  ->orWhereHas('loading', fn($lq) => $lq->where('nomor_loading', 'like', "%{$search}%"))
                  ->orWhereHas('sales', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
            });
        }

        // KPI stats
        $baseQuery = PasgarOpname::query();
        if ($isSales && isset($salesProfile)) {
            $baseQuery->where('sales_id', $salesProfile->id);
        }
        $stats = [
            'pending' => (clone $baseQuery)->pending()->count(),
            'confirmed' => (clone $baseQuery)->confirmed()->count(),
            'total_returned' => PasgarOpnameItem::whereIn('opname_id', (clone $baseQuery)->pluck('id'))->sum('qty_fisik'),
        ];

        $opnames = $query->paginate(15)->withQueryString();

        return view('pasgar.opname.index', compact('opnames', 'stats'));
    }

    /**
     * Show create form with eligible loadings.
     */
    public function create()
    {
        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile) {
            return redirect()->route('pasgar.opname.index')->with('error', 'Profil sales tidak ditemukan.');
        }

        // Find loadings eligible for opname:
        // - status = 'loaded'
        // - has items with qty_sisa > 0
        // - no existing opname
        $eligibleLoadings = PasgarLoading::with(['items.product', 'items.warehouse', 'items.unitConversion.unit'])
            ->where('sales_id', $salesProfile->id)
            ->where('status', 'loaded')
            ->whereDoesntHave('opname')
            ->whereHas('items', fn($q) => $q->where('qty_sisa', '>', 0))
            ->orderBy('loaded_at', 'desc')
            ->get();

        // If loading_id passed via query param, pre-select it
        $selectedLoading = null;
        if (request()->filled('loading_id')) {
            $selectedLoading = $eligibleLoadings->find(request()->loading_id);
        }

        return view('pasgar.opname.create', compact('eligibleLoadings', 'selectedLoading'));
    }

    /**
     * Store new opname and return stock to warehouse.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loading_id' => 'required|exists:pasgar_loadings,id',
            'tanggal' => 'required|date',
            'catatan' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.loading_item_id' => 'required|integer',
            'items.*.qty_fisik' => 'required|integer|min:0|max:99999',
        ], [
            'items.required' => 'Minimal 1 item harus diopname.',
            'items.*.qty_fisik.required' => 'Masukkan jumlah fisik.',
            'items.*.qty_fisik.min' => 'Jumlah fisik tidak boleh negatif.',
        ]);

        $user = Auth::user();
        $salesProfile = PasgarSales::where('user_id', $user->id)->first();
        if (!$salesProfile) {
            return redirect()->route('pasgar.opname.index')->with('error', 'Profil sales tidak ditemukan.');
        }

        $loading = PasgarLoading::findOrFail($validated['loading_id']);

        // Validate loading eligibility
        if ($loading->status !== 'loaded' || $loading->sales_id !== $salesProfile->id) {
            return redirect()->route('pasgar.opname.index')->with('error', 'Loading tidak valid untuk opname.');
        }
        if ($loading->opname) {
            return redirect()->route('pasgar.opname.index')->with('error', 'Loading ini sudah memiliki opname.');
        }

        DB::transaction(function () use ($loading, $salesProfile, $validated, $user) {
            // Create opname record
            $opname = PasgarOpname::create([
                'nomor_opname' => PasgarOpname::generateNomor(),
                'loading_id' => $loading->id,
                'sales_id' => $salesProfile->id,
                'tanggal' => $validated['tanggal'],
                'catatan' => $validated['catatan'] ?? null,
                'status' => 'pending',
            ]);

            // Process each item
            foreach ($validated['items'] as $data) {
                $loadingItem = PasgarLoadingItem::with('product', 'unitConversion')->findOrFail($data['loading_item_id']);

                if ($loadingItem->loading_id != $loading->id) {
                    continue;
                }

                $qtySisaSistem = (int) $loadingItem->qty_sisa;
                $qtyFisik = (int) $data['qty_fisik'];
                $qtySelisih = $qtyFisik - $qtySisaSistem;
                $warehouseId = $loadingItem->warehouse_id ?? self::WH_GUDANG;

                // Apply unit conversion: e.g. 5 slop × factor 10 = 50 bungkus (base units)
                $convFactor = (int) ($loadingItem->unitConversion?->conversion_factor ?? 1);
                $baseQty = $qtyFisik * $convFactor;

                // Create opname item
                PasgarOpnameItem::create([
                    'opname_id' => $opname->id,
                    'loading_item_id' => $loadingItem->id,
                    'product_id' => $loadingItem->product_id,
                    'qty_sisa_sistem' => $qtySisaSistem,
                    'qty_fisik' => $qtyFisik,
                    'qty_selisih' => $qtySelisih,
                    'warehouse_id' => $warehouseId,
                ]);

                // Return stock to warehouse (only if qty_fisik > 0, in base units)
                if ($qtyFisik > 0) {
                    $product = $loadingItem->product;

                    // Add back to ProductStock (stored in base units)
                    $productStock = ProductStock::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'warehouse_id' => $warehouseId,
                            'location_id' => null,
                            'batch_number' => null,
                            'expired_date' => null,
                        ],
                        ['stock' => 0]
                    );
                    $productStock->stock += $baseQty;
                    $productStock->save();

                    // Update global product stock (stored in base units)
                    $product->stock += $baseQty;
                    $product->save();

                    // Record stock movement (return IN, in base units)
                    StockMovement::create([
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                        'location_id' => null,
                        'type' => 'in',
                        'status' => 'completed',
                        'source_type' => 'pasgar_opname',
                        'reference_number' => $opname->nomor_opname,
                        'quantity' => $baseQty,
                        'balance' => $productStock->stock,
                        'notes' => 'Retur barang tidak terjual - Opname ' . $opname->nomor_opname . ' (' . $qtyFisik . ' × ' . $convFactor . ')',
                        'user_id' => $user->id,
                    ]);
                }

                // Update loading item: zero out remaining (all returned)
                $loadingItem->qty_sisa = 0;
                $loadingItem->save();
            }

            // Mark loading as opnamed (closed)
            $loading->update(['status' => 'opnamed']);
        });

        return redirect()->route('pasgar.opname.index')
            ->with('success', 'Opname berhasil dibuat. Barang sisa telah dikembalikan ke stok gudang.');
    }

    /**
     * Show opname detail.
     */
    public function show($id)
    {
        $opname = PasgarOpname::with([
            'sales', 'loading', 'items.product', 'items.warehouse', 'items.loadingItem.unitConversion.unit', 'confirmer',
        ])->findOrFail($id);

        // Get related stock movements
        $stockMovements = StockMovement::where('source_type', 'pasgar_opname')
            ->where('reference_number', $opname->nomor_opname)
            ->with(['product', 'warehouse'])
            ->get();

        return view('pasgar.opname.show', compact('opname', 'stockMovements'));
    }

    /**
     * Supervisor confirms the opname.
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:confirm',
            'catatan' => 'nullable|string|max:500',
        ]);

        $opname = PasgarOpname::findOrFail($id);
        if ($opname->status !== 'pending') {
            return redirect()->route('pasgar.opname.show', $id)->with('error', 'Opname sudah diproses.');
        }

        $opname->update([
            'status' => 'confirmed',
            'confirmed_by' => Auth::id(),
            'confirmed_at' => now(),
            'catatan' => $request->catatan ?? $opname->catatan,
        ]);

        return redirect()->route('pasgar.opname.show', $id)
            ->with('success', 'Opname berhasil dikonfirmasi.');
    }
}

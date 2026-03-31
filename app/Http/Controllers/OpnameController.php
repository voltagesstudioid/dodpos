<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Support\SearchSanitizer;
use App\Support\WarehouseConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpnameController extends Controller
{
    /**
     * Display a listing of stock opname (adjustments).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        // Get allowed warehouse based on role (not hardcoded)
        $allowedWarehouseId = WarehouseConfig::getAllowedId($role);

        $query = StockMovement::with(['product', 'warehouse', 'location', 'user'])
            ->where('type', 'adjustment');

        if ($allowedWarehouseId !== null) {
            $query->where('warehouse_id', $allowedWarehouseId);
        }

        $query->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = SearchSanitizer::sanitize($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            });
        }

        $adjustments = $query->paginate(15);

        return view('gudang.opname.index', compact('adjustments'));
    }

    /**
     * Show the form for creating a new stock opname.
     */
    public function create()
    {
        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        $products = Product::orderBy('name')->get();
        $whQuery = Warehouse::where('active', true);

        // Use WarehouseConfig for proper authorization
        $allowedWarehouseId = WarehouseConfig::getAllowedId($role);
        if ($allowedWarehouseId !== null) {
            $whQuery->where('id', $allowedWarehouseId);
        }

        $warehouses = $whQuery->orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('gudang.opname.create', compact('products', 'warehouses', 'locations'));
    }

    public function systemQty(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
        ]);

        $systemQty = ProductStock::query()
            ->where('product_id', (int) $request->product_id)
            ->where('warehouse_id', (int) $request->warehouse_id)
            ->sum('stock');

        return response()->json(['system_qty' => (int) $systemQty]);
    }

    /**
     * Store a newly created stock opname in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string|max:100|unique:stock_movements,reference_number',
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'actual_qty' => 'nullable|integer|min:0',
            'difference' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $warehouseId = (int) $request->warehouse_id;

            $user = Auth::user();
            $role = strtolower((string) ($user?->role ?? ''));

            // Validate warehouse access using WarehouseConfig
            if (! WarehouseConfig::canAccess($role, $warehouseId)) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan opname di gudang ini.')->withInput();
            }

            // Find existing specific stock record or calculate total system qty for this specific wh
            $stockRecords = ProductStock::where('product_id', $product->id)
                ->where('warehouse_id', $warehouseId)
                ->get();

            $currentSystemQty = $stockRecords->sum('stock');
            $actualQty = $request->filled('actual_qty')
                ? (int) $request->actual_qty
                : null;

            if ($actualQty === null && $request->filled('difference')) {
                $actualQty = $currentSystemQty + (int) $request->difference;
            }

            if ($actualQty === null) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Masukkan Jumlah Aktual atau Selisih.')->withInput();
            }
            if ($actualQty < 0) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Jumlah aktual tidak boleh minus.')->withInput();
            }

            $difference = $actualQty - $currentSystemQty;

            if ($difference == 0) {
                return redirect()->back()->with('error', 'Stok fisik sama dengan stok sistem. Tidak ada penyesuaian yang diperlukan.');
            }

            // Adjust ProductStock records
            if ($difference > 0) {
                // If ACTUAL > SYSTEM (Found more physical stock)
                $targetStock = collect($stockRecords)->first();

                if ($targetStock) {
                    $targetStock->stock += $difference;
                    $targetStock->save();
                } else {
                    ProductStock::create([
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouseId,
                        'location_id' => null,
                        'stock' => $difference,
                    ]);
                }
            } else {
                // If ACTUAL < SYSTEM (Lost/Missing physical stock)
                $qtyToRemove = abs($difference);

                // FIFO deduction for the missing stock
                /** @var \App\Models\ProductStock $stock */
                foreach ($stockRecords->sortBy('expired_date')->sortBy('created_at') as $stock) {
                    if ($qtyToRemove <= 0) {
                        break;
                    }

                    if ($stock->stock <= $qtyToRemove) {
                        $qtyToRemove -= $stock->stock;
                        $stock->stock = 0;
                        $stock->save();
                    } else {
                        $stock->stock -= $qtyToRemove;
                        $qtyToRemove = 0;
                        $stock->save();
                    }
                }

                if ($qtyToRemove > 0) {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Gagal memotong stok. Selisih negatif melebihi stok yang ada pada lokasi ini.');
                }
            }

            // Record Movement
            StockMovement::create([
                'product_id' => $product->id,
                'warehouse_id' => $warehouseId,
                'location_id' => null,
                'type' => 'adjustment',
                'reference_number' => $request->reference_number,
                'quantity' => $difference, // Positive means added, negative means removed
                'notes' => $request->notes." (Sistem: $currentSystemQty, Aktual: $actualQty)",
                'user_id' => Auth::id() ?? 1,
            ]);

            // Update Global Product Stock
            $product->stock += $difference;

            // Re-check just to be safe
            if ($product->stock < 0) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Opname gagal, stok global produk menjadi minus.');
            }

            $product->save();

            DB::commit();

            return redirect()->route('gudang.opname')->with('success', 'Opname stok berhasil diproses. Selisih: '.$difference);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage())
                ->withInput();
        }
    }

    public function show(StockMovement $opname)
    {
        abort_if($opname->type !== 'adjustment', 404);
        $opname->load(['product', 'warehouse', 'location', 'user']);

        return view('gudang.opname.show', compact('opname'));
    }

    public function destroy(StockMovement $opname)
    {
        abort_if($opname->type !== 'adjustment', 404);

        $user = Auth::user();
        $role = strtolower((string) ($user?->role ?? ''));

        // Validate warehouse access using WarehouseConfig
        if (! WarehouseConfig::canAccess($role, $opname->warehouse_id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk membatalkan opname di gudang ini.');
        }

        try {
            DB::beginTransaction();

            $revertQty = -$opname->quantity;

            if ($revertQty > 0) {
                // Return stock that was deducted
                $stock = ProductStock::firstOrCreate([
                    'product_id' => $opname->product_id,
                    'warehouse_id' => $opname->warehouse_id,
                    'location_id' => null,
                    'batch_number' => null,
                    'expired_date' => null,
                ], ['stock' => 0]);
                $stock->stock += $revertQty;
                $stock->save();
            } elseif ($revertQty < 0) {
                // Deduct stock that was previously added
                $qtyToDeduct = abs($revertQty);
                $query = ProductStock::where('product_id', $opname->product_id)
                    ->where('warehouse_id', $opname->warehouse_id)
                    ->where('stock', '>', 0)
                    ->orderBy('expired_date', 'asc');

                $stocks = $query->get();

                /** @var \App\Models\ProductStock $stock */
                foreach ($stocks as $stock) {
                    if ($qtyToDeduct <= 0) {
                        break;
                    }
                    $deduct = min($stock->stock, $qtyToDeduct);
                    $stock->stock -= $deduct;
                    $stock->save();
                    $qtyToDeduct -= $deduct;
                }

                if ($qtyToDeduct > 0) {
                    DB::rollBack();

                    return back()->with('error', 'Gagal membatalkan opname karena stok tidak mencukupi untuk dipotong ulang.');
                }
            }

            // Global stock update
            $product = Product::find($opname->product_id);
            if ($product) {
                $product->stock += $revertQty;
                if ($product->stock < 0) {
                    DB::rollBack();

                    return back()->with('error', 'Gagal membatalkan opname karena stok global produk menjadi minus.');
                }
                $product->save();
            }

            $opname->delete();

            DB::commit();

            return redirect()->route('gudang.opname')->with('success', 'Data opname berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal membatalkan data: '.$e->getMessage());
        }
    }
}

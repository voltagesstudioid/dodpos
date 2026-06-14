<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $tipe       = $request->input('tipe');
        $warehouse_id = $request->input('warehouse_id');

        $records = StockMovement::with(['product', 'warehouse', 'user'])
            ->where('source_type', 'adjustment')
            ->when($search, function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            })
            ->when($tipe, fn($q) => $q->where('type', $tipe))
            ->when($warehouse_id, fn($q) => $q->where('warehouse_id', $warehouse_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();

        $stats = [
            'total_masuk'   => StockMovement::where('source_type', 'adjustment')->where('type', 'in')->whereMonth('created_at', now()->month)->sum('quantity'),
            'total_koreksi' => StockMovement::where('source_type', 'adjustment')->where('type', 'adjustment')->whereMonth('created_at', now()->month)->count(),
            'bulan'         => now()->translatedFormat('F Y'),
        ];

        return view('gudang.stock_adjustment.index', compact('records', 'warehouses', 'stats'));
    }

    public function create()
    {
        $products   = Product::with(['unit', 'unitConversions.unit'])->orderBy('name')->get();
        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();

        // Build per-warehouse stock map: { productId: { warehouseId: stock } }
        $warehouseStock = [];
        foreach (ProductStock::all() as $ps) {
            $warehouseStock[$ps->product_id][$ps->warehouse_id] = (float) $ps->stock;
        }

        return view('gudang.stock_adjustment.create', compact('products', 'warehouses', 'warehouseStock'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'   => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'tipe'         => 'required|in:masuk,koreksi',
            'jumlah'       => 'required|numeric|min:0.001',
            'unit_id'      => 'nullable|integer',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $product   = Product::findOrFail($validated['product_id']);
            $warehouse = Warehouse::findOrFail($validated['warehouse_id']);

            // Resolve unit conversion
            $conversionFactor = 1;
            $unitName = $product->unit?->abbreviation ?? 'pcs';
            $inputQty = (float) $validated['jumlah'];

            if (!empty($validated['unit_id'])) {
                $conversion = \App\Models\ProductUnitConversion::where('product_id', $product->id)
                    ->where('unit_id', $validated['unit_id'])
                    ->first();
                if ($conversion) {
                    $conversionFactor = $conversion->conversion_factor ?: 1;
                    $unitName = $conversion->unit?->name ?? $unitName;
                }
            }

            // Convert to base unit quantity
            $baseQty = $inputQty * $conversionFactor;

            // Get or create ProductStock entry
            $productStock = ProductStock::firstOrCreate(
                ['product_id' => $product->id, 'warehouse_id' => $warehouse->id],
                ['stock' => 0]
            );

            $stokSebelum = (float) $productStock->stock;

            if ($validated['tipe'] === 'masuk') {
                // Tambah stok
                $stokSesudah = $stokSebelum + $baseQty;
                $qty         = $baseQty;
                $type        = 'in';
            } else {
                // Koreksi: set ke jumlah yang diinput (dalam base unit)
                $stokSesudah = $baseQty;
                $qty         = $stokSesudah - $stokSebelum; // bisa negatif
                $type        = $qty >= 0 ? 'in' : 'out';
            }

            // Update ProductStock
            $productStock->update(['stock' => $stokSesudah]);

            // Update Product.stock (total gabungan)
            $totalStock = ProductStock::where('product_id', $product->id)->sum('stock');
            $product->update(['stock' => $totalStock]);

            // Buat StockMovement sebagai audit log
            $refNumber = 'ADJ-' . strtoupper(substr(uniqid(), -8));

            $directionLabel = $validated['tipe'] === 'masuk'
                ? '[Stok Masuk Manual] '
                : ($qty >= 0 ? '[Koreksi Stok +] ' : '[Koreksi Stok -] ');

            $unitNote = '';
            $baseUnitName = $product->unit?->abbreviation ?? 'pcs';
            if ($conversionFactor > 1) {
                $unitNote = " ({$inputQty} {$unitName} = {$baseQty} {$baseUnitName})";
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
                'notes'            => $directionLabel . ($validated['keterangan'] ?? '') . $unitNote,
                'user_id'          => Auth::id(),
            ]);

            DB::commit();

            $unitConvNote = ($conversionFactor > 1) ? " = {$baseQty} {$baseUnitName}" : '';
            $label = $validated['tipe'] === 'masuk'
                ? "Stok berhasil ditambahkan (+{$inputQty} {$unitName}{$unitConvNote})"
                : "Koreksi stok berhasil (dari {$stokSebelum} → {$stokSesudah} {$baseUnitName})";

            return redirect()->route('gudang.stock-adjustment.index')
                ->with('success', $label);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

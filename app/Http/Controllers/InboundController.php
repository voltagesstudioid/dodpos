<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Location;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\ProductUnitConversion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InboundController extends Controller
{
    // Source types for non-PO stock-in
    const SOURCE_TYPES = [
        'retur_pelanggan' => 'Retur dari Pelanggan',
        'stok_awal'       => 'Input Stok Awal',
        'koreksi'         => 'Koreksi / Temuan Stok',
        'transfer_masuk'  => 'Transfer Masuk dari Gudang Lain',
        'konsinyasi'      => 'Barang Konsinyasi / Titipan',
        'lainnya'         => 'Lainnya',
    ];

    public function index(Request $request)
    {
        $search = $request->input('search');
        $type   = $request->input('source_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $movements = StockMovement::with(['product', 'warehouse', 'location', 'user'])
            ->where('type', 'in')
            ->whereNull('purchase_order_id') // Only NON-PO inbounds
            ->when($search, fn($q) =>
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
            )
            ->when($type, fn($q) => $q->where('source_type', $type))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Stats
        $statsQuery = StockMovement::where('type', 'in')
            ->whereNull('purchase_order_id')
            ->when($type, fn($q) => $q->where('source_type', $type))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo));

        $totalQty = (clone $statsQuery)->sum('quantity');
        $totalTransactions = (clone $statsQuery)->count();

        // Get stats grouped by source type
        $statsBySource = (clone $statsQuery)
            ->select('source_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('source_type')
            ->get()
            ->keyBy('source_type')
            ->toArray();

        $sourceTypes = self::SOURCE_TYPES;
        return view('gudang.penerimaan.index', compact(
            'movements', 'sourceTypes', 'totalQty', 'totalTransactions', 'statsBySource'
        ));
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $type   = $request->input('source_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $movements = StockMovement::with(['product', 'warehouse', 'user'])
            ->where('type', 'in')
            ->whereNull('purchase_order_id')
            ->when($search, fn($q) =>
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
            )
            ->when($type, fn($q) => $q->where('source_type', $type))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->get();

        $filename = 'penerimaan-barang-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($movements) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'No. Referensi', 'Sumber', 'Produk', 'SKU', 'Gudang', 'Qty', 'Petugas', 'Catatan']);
            
            foreach ($movements as $m) {
                fputcsv($file, [
                    $m->created_at->format('d/m/Y H:i'),
                    $m->reference_number,
                    self::SOURCE_TYPES[$m->source_type] ?? 'Lainnya',
                    $m->product?->name ?? '-',
                    $m->product?->sku ?? '-',
                    $m->warehouse?->name ?? '-',
                    $m->quantity,
                    $m->user?->name ?? '-',
                    $m->notes ?? '-',
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function create()
    {
        $products = Product::with(['unit', 'unitConversions.unit'])->orderBy('name')->get();
        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();
        $locations  = Location::orderBy('name')->get();
        $sourceTypes = self::SOURCE_TYPES;

        return view('gudang.penerimaan.create', compact('products', 'warehouses', 'locations', 'sourceTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'source_type'      => 'required|in:' . implode(',', array_keys(self::SOURCE_TYPES)),
            'product_id'       => 'required|exists:products,id',
            'unit_id'          => 'nullable|exists:units,id',
            'warehouse_id'     => 'required|exists:warehouses,id',
            'reference_number' => 'required|string|max:100',
            'batch_number'     => 'nullable|string|max:100',
            'expired_date'     => 'nullable|date',
            'quantity'         => 'required|integer|min:1',
            'conversion_factor' => 'nullable|numeric|min:0.001',
            'notes'            => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $productId   = $request->product_id;
            $warehouseId = $request->warehouse_id;
            $unitId      = $request->unit_id;
            $quantityInUnit = (float) $request->quantity;

            // Get product with unit conversions
            $product = Product::with(['unit', 'unitConversions.unit'])->findOrFail($productId);

            // Calculate base quantity from unit conversion
            $conversionFactor = 1;
            if ($unitId) {
                // Check if it's the base unit
                $baseUnitId = $product->unit_id;
                if ((int) $unitId !== (int) $baseUnitId) {
                    $uc = $product->unitConversions->firstWhere('unit_id', $unitId);
                    if ($uc) {
                        $conversionFactor = (float) $uc->conversion_factor;
                    }
                }
            }
            $baseQty = (int) round($quantityInUnit * $conversionFactor);

            // 1. Cari atau buat record stok di product_stocks
            $stockRecord = ProductStock::firstOrCreate(
                [
                    'product_id'   => $productId,
                    'warehouse_id' => $warehouseId,
                    'location_id'  => null,
                    'batch_number' => $request->batch_number,
                    'expired_date' => $request->expired_date,
                ],
                ['stock' => 0]
            );

            // 2. Tambahkan stok (in base quantity)
            $stockRecord->stock += $baseQty;
            $stockRecord->save();

            // 3. Update total stok global di products
            $product->stock += $baseQty;
            $product->save();

            // 4. Catat pergerakan stok
            $sourceLabel = self::SOURCE_TYPES[$request->source_type] ?? $request->source_type;
            $unitName = $unitId ? Unit::find($unitId)?->name : ($product->unit?->name ?? 'satuan dasar');
            $notesWithUnit = "[{$sourceLabel}] Input: {$quantityInUnit} {$unitName} (= {$baseQty} satuan dasar). " . ($request->notes ?? '');

            StockMovement::create([
                'product_id'       => $productId,
                'warehouse_id'     => $warehouseId,
                'location_id'      => null,
                'type'             => 'in',
                'source_type'      => $request->source_type,
                'reference_number' => $request->reference_number,
                'batch_number'     => $request->batch_number,
                'expired_date'     => $request->expired_date,
                'quantity'         => $baseQty,
                'unit_id'          => $unitId,
                'conversion_factor'=> $conversionFactor,
                'quantity_in_unit' => $quantityInUnit,
                'balance'          => $stockRecord->stock,
                'notes'            => $notesWithUnit,
                'user_id'          => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('gudang.penerimaan')->with('success', "Barang berhasil diterima ({$sourceLabel}). Stok bertambah {$baseQty} satuan dasar (Input: {$quantityInUnit} {$unitName}).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function show(StockMovement $inbound)
    {
        abort_if($inbound->type !== 'in', 404);
        $inbound->load(['product', 'warehouse', 'location', 'user']);
        return view('gudang.penerimaan.show', compact('inbound'));
    }

    public function destroy(StockMovement $inbound)
    {
        abort_if($inbound->type !== 'in', 404);
        
        try {
            DB::beginTransaction();

            $query = ProductStock::where('product_id', $inbound->product_id)
                ->where('warehouse_id', $inbound->warehouse_id);
            
            if ($inbound->batch_number) {
                $query->where('batch_number', $inbound->batch_number);
            } else {
                $query->whereNull('batch_number');
            }

            if ($inbound->expired_date) {
                $query->where('expired_date', $inbound->expired_date);
            } else {
                $query->whereNull('expired_date');
            }

            $stockRecord = $query->first();

            if ($stockRecord) {
                $stockRecord->stock -= $inbound->quantity;
                $stockRecord->save();
            }

            $product = Product::find($inbound->product_id);
            if ($product) {
                $product->stock -= $inbound->quantity;
                $product->save();
            }

            $inbound->delete();

            DB::commit();
            return redirect()->route('gudang.penerimaan')->with('success', 'Data penerimaan berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

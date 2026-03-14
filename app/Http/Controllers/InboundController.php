<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Location;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        $movements = StockMovement::with(['product', 'warehouse', 'location', 'user'])
            ->where('type', 'in')
            ->whereNull('purchase_order_id') // Only NON-PO inbounds
            ->when($search, fn($q) =>
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('product', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
            )
            ->when($type, fn($q) => $q->where('source_type', $type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $sourceTypes = self::SOURCE_TYPES;
        return view('gudang.penerimaan.index', compact('movements', 'sourceTypes'));
    }

    public function create()
    {
        $products   = Product::orderBy('name')->get();
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
            'warehouse_id'     => 'required|exists:warehouses,id',
            'reference_number' => 'required|string|max:100',
            'batch_number'     => 'nullable|string|max:100',
            'expired_date'     => 'nullable|date',
            'quantity'         => 'required|integer|min:1',
            'notes'            => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $productId   = $request->product_id;
            $warehouseId = $request->warehouse_id;
            $qty         = $request->quantity;

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

            // 2. Tambahkan stok
            $stockRecord->stock += $qty;
            $stockRecord->save();

            // 3. Update total stok global di products
            $product = Product::findOrFail($productId);
            $product->stock += $qty;
            $product->save();

            // 4. Catat pergerakan stok
            $sourceLabel = self::SOURCE_TYPES[$request->source_type] ?? $request->source_type;
            StockMovement::create([
                'product_id'       => $productId,
                'warehouse_id'     => $warehouseId,
                'location_id'      => null,
                'type'             => 'in',
                'source_type'      => $request->source_type,
                'reference_number' => $request->reference_number,
                'batch_number'     => $request->batch_number,
                'expired_date'     => $request->expired_date,
                'quantity'         => $qty,
                'balance'          => $stockRecord->stock,
                'notes'            => "[{$sourceLabel}] " . ($request->notes ?? ''),
                'user_id'          => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('gudang.penerimaan')->with('success', "Barang berhasil diterima ({$sourceLabel}). Stok bertambah {$qty} unit.");
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

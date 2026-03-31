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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OutboundController extends Controller
{
    private function generateRef(string $prefix = 'OUT'): string
    {
        $date = date('Ymd');
        $base = "{$prefix}-{$date}";
        $last = StockMovement::where('type', 'out')
            ->where('reference_number', 'like', $base . '-%')
            ->orderBy('reference_number', 'desc')
            ->first();
        if (!$last) {
            return $base . '-001';
        }
        $lastNum = (int) substr($last->reference_number, -3);
        return $base . '-' . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
    public function index(Request $request)
    {
        $search = $request->input('search');

        $movements = StockMovement::with(['product', 'warehouse', 'location', 'user'])
            ->where('type', 'out')
            ->when($search, function ($query, $search) {
                return $query->where('reference_number', 'like', "%{$search}%")
                             ->orWhereHas('product', function($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('gudang.pengeluaran.index', compact('movements'));
    }

    public function create()
    {
        // Hanya tampilkan produk yang punya stok > 0 secara global atau spesifik
        $products = Product::with(['unit', 'unitConversions.unit'])->where('stock', '>', 0)->orderBy('name')->get();
        $warehouses = Warehouse::where('active', true)->orderBy('name')->get();
        // Option locations: bisa di fetch AJAX berdasarkan stok yang available di gudang yang dipilih.
        $locations = Location::orderBy('name')->get();

        return view('gudang.pengeluaran.create', compact('products', 'warehouses', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'unit_id' => 'nullable|exists:units,id',
            'reference_number' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $productId = $request->product_id;
            $warehouseId = $request->warehouse_id;
            $unitId = $request->unit_id;
            $quantityInUnit = (float) $request->quantity;
            $reference = $request->reference_number ?: $this->generateRef('OUT');

            // Get product with unit conversions
            $product = Product::with(['unit', 'unitConversions.unit'])->findOrFail($productId);

            // Calculate base quantity from unit conversion
            $conversionFactor = 1;
            if ($unitId) {
                $baseUnitId = $product->unit_id;
                if ((int) $unitId !== (int) $baseUnitId) {
                    $uc = $product->unitConversions->firstWhere('unit_id', $unitId);
                    if ($uc) {
                        $conversionFactor = (float) $uc->conversion_factor;
                    }
                }
            }
            $baseQty = (int) round($quantityInUnit * $conversionFactor);

            // 1. Cek ketersediaan stok di product_stocks
            // Asumsi: jika location tidak di set, kita cari stok di warehouse tersebut saja.
            // Untuk WMS murni, harusnya spesifik lokasinya juga. Di sini kita coba ambil yang cocok 
            // dengan kriteria yang diinput. Jika user tidak pilih lokasi, kita ambil global dari warehouse tersebut.
            
            $query = ProductStock::where('product_id', $productId)
                        ->where('warehouse_id', $warehouseId);
                        
            // Mengambil stok yang tersedia berdasarkan urutan expired (FIFO)
            // Lock rows during deduction to avoid race conditions with other processes
            $availableStocks = $query->where('stock', '>', 0)
                                     ->orderBy('expired_date', 'asc')
                                     ->orderBy('created_at', 'asc')
                                     ->lockForUpdate()
                                     ->get();
            
            $totalAvailable = $availableStocks->sum('stock');

            if ($totalAvailable < $baseQty) {
                return back()->withInput()->with('error', "Stok tidak mencukupi di gudang/lokasi terpilih. Stok tersedia: $totalAvailable (satuan dasar), dibutuhkan: $baseQty");
            }

            // 2. Kurangi stok (bisa memakan beberapa batch jika FIFO)
            $qtyRemaining = $baseQty;
            foreach($availableStocks as $stockRecord) {
                if ($qtyRemaining <= 0) break;
                
                $deduct = min($stockRecord->stock, $qtyRemaining);
                
                $stockRecord->stock -= $deduct;
                $stockRecord->save();
                
                $qtyRemaining -= $deduct;

                // 3. Catat pergerakan stok (Stock Movement) utk masing-masing record
                $unitName = $unitId ? Unit::find($unitId)?->name : ($product->unit?->name ?? 'satuan dasar');
                $notesWithUnit = "[Pengeluaran] Input: {$quantityInUnit} {$unitName} (= {$baseQty} satuan dasar). " . ($request->notes ?? '');

                StockMovement::create([
                    'product_id' => $productId,
                    'warehouse_id' => $stockRecord->warehouse_id,
                    'location_id' => null,
                    'type' => 'out',
                    'reference_number' => $reference,
                    'batch_number' => $stockRecord->batch_number,
                    'expired_date' => $stockRecord->expired_date,
                    'quantity' => $deduct,
                    'unit_id' => $unitId,
                    'conversion_factor' => $conversionFactor,
                    'quantity_in_unit' => $quantityInUnit,
                    'balance' => $stockRecord->stock,
                    'notes' => $notesWithUnit,
                    'user_id' => Auth::id(),
                ]);
            }

            // 4. Update total stok (global) di tabel products
            $product->stock -= $baseQty;
            $product->save();

            DB::commit();

            return redirect()->route('gudang.pengeluaran')->with('success', "Barang berhasil dikeluarkan. Stok berkurang {$baseQty} satuan dasar (Input: {$quantityInUnit} {$unitName}).");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    public function show(StockMovement $outbound)
    {
        abort_if($outbound->type !== 'out', 404);
        $outbound->load(['product', 'warehouse', 'location', 'user']);
        return view('gudang.pengeluaran.show', compact('outbound'));
    }

    public function destroy(StockMovement $outbound)
    {
        abort_if($outbound->type !== 'out', 404);

        try {
            DB::beginTransaction();

            // 1. Tambahkan kembali ke product_stocks
            $query = ProductStock::where('product_id', $outbound->product_id)
                ->where('warehouse_id', $outbound->warehouse_id);

            if ($outbound->batch_number) {
                $query->where('batch_number', $outbound->batch_number);
            } else {
                $query->whereNull('batch_number');
            }

            if ($outbound->expired_date) {
                $query->where('expired_date', $outbound->expired_date);
            } else {
                $query->whereNull('expired_date');
            }

            $stockRecord = $query->first();

            if ($stockRecord) {
                $stockRecord->stock += $outbound->quantity;
                $stockRecord->save();
            } else {
                // Jika stock record sudah terhapus, buat lagi
                ProductStock::create([
                    'product_id'   => $outbound->product_id,
                    'warehouse_id' => $outbound->warehouse_id,
                    'location_id'  => null,
                    'batch_number' => $outbound->batch_number,
                    'expired_date' => $outbound->expired_date,
                    'stock'        => $outbound->quantity,
                ]);
            }

            // 2. Tambahkan kembali ke products global stock
            $product = Product::find($outbound->product_id);
            if ($product) {
                $product->stock += $outbound->quantity;
                $product->save();
            }

            // 3. Hapus record StockMovement
            $outbound->delete();

            DB::commit();
            return redirect()->route('gudang.pengeluaran')->with('success', 'Data pengeluaran berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

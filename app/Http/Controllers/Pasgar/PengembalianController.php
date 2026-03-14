<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Vehicle;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        // Tampilkan transfer dengan tipe 'return' (dari kendaraan ke gudang utama)
        // Kita tandai dengan notes mengandung '[Pengembalian Pasgar]'
        $query = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'creator', 'items.product'])
            ->where('notes', 'like', '%[Pengembalian Pasgar]%')
            ->latest();

        if ($request->search) {
            $query->where('transfer_number', 'like', '%' . $request->search . '%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $returns = $query->paginate(15)->withQueryString();

        return view('pasgar.pengembalian.index', compact('returns'));
    }

    public function create()
    {
        // Kendaraan yang punya stok on-hand
        $vehicles = Vehicle::with('warehouse')
            ->whereHas('warehouse', function ($q) {
                $q->whereHas('productStocks', fn($s) => $s->where('stock', '>', 0));
            })
            ->get();

        $mainWarehouses = Warehouse::where('active', true)
            ->whereDoesntHave('vehicle')
            ->orderBy('name')
            ->get();

        return view('pasgar.pengembalian.create', compact('vehicles', 'mainWarehouses'));
    }

    /**
     * AJAX: ambil stok on-hand kendaraan
     */
    public function getVehicleStock(Request $request)
    {
        $vehicle = Vehicle::with('warehouse')->findOrFail($request->vehicle_id);
        if (!$vehicle->warehouse_id) {
            return response()->json([]);
        }

        $stocks = ProductStock::with(['product.unit'])
            ->where('warehouse_id', $vehicle->warehouse_id)
            ->where('stock', '>', 0)
            ->get()
            ->map(fn($s) => [
                'product_id'   => $s->product_id,
                'product_name' => $s->product->name,
                'unit'         => $s->product->unit?->name ?? 'pcs',
                'stock'        => $s->stock,
            ]);

        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'           => 'required|date',
            'vehicle_id'     => 'required|exists:vehicles,id',
            'to_warehouse_id'=> 'required|exists:warehouses,id',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $vehicle = Vehicle::with('warehouse')->findOrFail($request->vehicle_id);

        if (!$vehicle->warehouse_id) {
            return back()->with('error', 'Kendaraan tidak memiliki gudang virtual.');
        }

        try {
            DB::beginTransaction();

            $transfer = StockTransfer::create([
                'transfer_number'  => StockTransfer::generateNumber(),
                'date'             => $request->date,
                'from_warehouse_id'=> $vehicle->warehouse_id,
                'to_warehouse_id'  => $request->to_warehouse_id,
                'notes'            => '[Pengembalian Pasgar] ' . ($request->notes ?? ''),
                'status'           => 'pending',
                'created_by'       => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                // Validasi stok kendaraan
                $vehicleStock = ProductStock::where('warehouse_id', $vehicle->warehouse_id)
                    ->where('product_id', $item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$vehicleStock || $vehicleStock->stock < $item['quantity']) {
                    DB::rollBack();
                    $product = Product::find($item['product_id']);
                    return back()->with('error', "Stok {$product->name} di kendaraan tidak mencukupi. Tersedia: " . ($vehicleStock->stock ?? 0));
                }

                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                ]);

                // Kurangi stok kendaraan
                $vehicleStock->stock -= $item['quantity'];
                $vehicleStock->save();

                StockMovement::create([
                    'product_id'       => $item['product_id'],
                    'warehouse_id'     => $vehicle->warehouse_id,
                    'type'             => 'out',
                    'source_type'      => 'stock_transfer',
                    'reference_number' => $transfer->transfer_number,
                    'quantity'         => $item['quantity'],
                    'balance'          => $vehicleStock->stock,
                    'notes'            => '[Pengembalian Pasgar] Keluar dari: ' . $vehicle->warehouse->name,
                    'user_id'          => auth()->id(),
                ]);

                // Tambah stok gudang tujuan
                $destStock = ProductStock::firstOrCreate(
                    ['product_id' => $item['product_id'], 'warehouse_id' => $request->to_warehouse_id, 'location_id' => null],
                    ['stock' => 0]
                );
                $destStock->stock += $item['quantity'];
                $destStock->save();

                StockMovement::create([
                    'product_id'       => $item['product_id'],
                    'warehouse_id'     => $request->to_warehouse_id,
                    'type'             => 'in',
                    'source_type'      => 'stock_transfer',
                    'reference_number' => $transfer->transfer_number,
                    'quantity'         => $item['quantity'],
                    'balance'          => $destStock->stock,
                    'notes'            => '[Pengembalian Pasgar] Masuk ke gudang utama',
                    'user_id'          => auth()->id(),
                ]);

                // Update global product stock
                \App\Models\Product::where('id', $item['product_id'])
                    ->decrement('stock', 0); // no-op, stok global tidak berubah (hanya pindah lokasi)
            }

            $transfer->update(['status' => 'completed']);

            DB::commit();

            return redirect()->route('pasgar.pengembalian.index')
                ->with('success', 'Pengembalian sisa barang berhasil dicatat. Stok kendaraan telah dikurangi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage())->withInput();
        }
    }

    public function show(StockTransfer $pengembalian)
    {
        $pengembalian->load(['fromWarehouse', 'toWarehouse', 'creator', 'items.product.unit']);
        return view('pasgar.pengembalian.show', compact('pengembalian'));
    }
}

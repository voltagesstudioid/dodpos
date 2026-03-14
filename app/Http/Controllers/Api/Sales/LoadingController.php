<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadingController extends Controller
{
    /**
     * GET /api/sales/loadings
     * Daftar loading orders milik user yang login (by vehicle terkait)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil semua vehicle yang dimiliki user ini (via created_by)
        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse', 'items.product.unit'])
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = $transfers->map(function ($t) {
            return [
                'id'                => $t->id,
                'transfer_number'   => $t->transfer_number,
                'date'              => $t->date?->format('Y-m-d'),
                'from_warehouse'    => $t->fromWarehouse?->name,
                'to_warehouse'      => $t->toWarehouse?->name,
                'status'            => $t->status,
                'notes'             => $t->notes,
                'items_count'       => $t->items->count(),
                'created_at'        => $t->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    /**
     * POST /api/sales/loadings
     * Buat loading order baru (status: pending)
     */
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id'        => 'required|exists:vehicles,id',
            'notes'             => 'nullable|string',
            'items'             => 'required|array|min:1',
            'items.*.product_id'=> 'required|exists:products,id',
            'items.*.quantity'  => 'required|integer|min:1',
        ]);

        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        if (!$vehicle->warehouse_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kendaraan belum dikaitkan dengan gudang virtual.',
            ], 422);
        }

        DB::transaction(function () use ($request, $vehicle) {
            $transfer = StockTransfer::create([
                'transfer_number'   => StockTransfer::generateNumber(),
                'date'              => now()->format('Y-m-d'),
                'from_warehouse_id' => null,   // akan diisi admin saat Disiapkan
                'to_warehouse_id'   => $vehicle->warehouse_id,
                'notes'             => $request->notes,
                'status'            => 'pending',
                'created_by'        => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'notes'      => $item['notes'] ?? null,
                ]);
            }
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Order barang berhasil dibuat. Admin akan menyiapkan dari gudang yang tersedia.',
        ], 201);
    }

    /**
     * GET /api/sales/loadings/{id}
     * Detail loading order + items
     */
    public function show(Request $request, $id)
    {
        $transfer = StockTransfer::with([
            'fromWarehouse',
            'toWarehouse',
            'items.product.unit',
            'creator',
        ])->where('created_by', $request->user()->id)->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'              => $transfer->id,
                'transfer_number' => $transfer->transfer_number,
                'date'            => $transfer->date?->format('Y-m-d'),
                'from_warehouse'  => $transfer->fromWarehouse?->name,
                'to_warehouse'    => $transfer->toWarehouse?->name,
                'status'          => $transfer->status,
                'notes'           => $transfer->notes,
                'created_at'      => $transfer->created_at->format('Y-m-d H:i'),
                'items'           => collect($transfer->items)->map(fn($item) => [
                    'id'         => $item->id,
                    'product_id' => $item->product_id,
                    'name'       => $item->product->name,
                    'unit'       => $item->product->unit?->abbreviation ?? 'pcs',
                    'quantity'   => $item->quantity,
                    'notes'      => $item->notes,
                ]),
            ],
        ]);
    }

    /**
     * POST /api/sales/loadings/{id}/crosscheck
     * Sales konfirmasi cross check: bisa ubah qty aktual, lalu approve
     */
    public function crosscheck(Request $request, $id)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.id'         => 'required|exists:stock_transfer_items,id',
            'items.*.qty_actual' => 'required|integer|min:0',
        ]);

        $transfer = StockTransfer::with(['items.product', 'fromWarehouse', 'toWarehouse'])
            ->where('created_by', $request->user()->id)
            ->findOrFail($id);

        if ($transfer->status !== 'disiapkan') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Hanya loading dengan status "disiapkan" yang bisa di-crosscheck.',
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update qty tiap item dengan qty aktual dari cross check
            $actualMap = collect($request->items)->keyBy('id');

            foreach ($transfer->items as $item) {
                $actual = $actualMap->get($item->id);
                if ($actual) {
                    $item->update(['quantity' => $actual['qty_actual']]);
                }
            }

            // Reload items setelah update
            $transfer->refresh()->load(['items.product', 'fromWarehouse', 'toWarehouse']);

            // Proses mutasi stok: gudang asal → gudang kendaraan
            foreach ($transfer->items as $item) {
                if ($item->quantity <= 0) continue; // skip qty 0

                $sourceStock = ProductStock::where('product_id', $item->product_id)
                    ->where('warehouse_id', $transfer->from_warehouse_id)
                    ->lockForUpdate()
                    ->first();

                if (!$sourceStock || $sourceStock->stock < $item->quantity) {
                    throw new \Exception(
                        "Stok \"{$item->product->name}\" tidak mencukupi. " .
                        "Tersedia: " . ($sourceStock->stock ?? 0) . ", Dibutuhkan: {$item->quantity}"
                    );
                }

                // Kurangi stok gudang asal
                $sourceStock->stock -= $item->quantity;
                $sourceStock->save();

                StockMovement::create([
                    'product_id'       => $item->product_id,
                    'warehouse_id'     => $transfer->from_warehouse_id,
                    'type'             => 'out',
                    'source_type'      => 'stock_transfer',
                    'reference_number' => $transfer->transfer_number,
                    'quantity'         => $item->quantity,
                    'balance'          => $sourceStock->stock,
                    'notes'            => '[Loading Pasgar] CrossCheck → '.$transfer->toWarehouse->name,
                    'user_id'          => auth()->id(),
                ]);

                // Tambah stok gudang kendaraan
                $destStock = ProductStock::firstOrCreate(
                    ['product_id' => $item->product_id, 'warehouse_id' => $transfer->to_warehouse_id],
                    ['stock' => 0]
                );
                $destStock->stock += $item->quantity;
                $destStock->save();

                StockMovement::create([
                    'product_id'       => $item->product_id,
                    'warehouse_id'     => $transfer->to_warehouse_id,
                    'type'             => 'in',
                    'source_type'      => 'stock_transfer',
                    'reference_number' => $transfer->transfer_number,
                    'quantity'         => $item->quantity,
                    'balance'          => $destStock->stock,
                    'notes'            => '[Loading Pasgar] CrossCheck dari '.$transfer->fromWarehouse->name,
                    'user_id'          => auth()->id(),
                ]);
            }

            $transfer->update([
                'status'      => 'confirmed',
                'approved_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Cross check dikonfirmasi. Barang berhasil masuk ke kendaraan.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/sales/vehicle-stock
     * Stok barang di gudang kendaraan user
     */
    public function vehicleStock(Request $request)
    {
        $user = $request->user();

        // Ambil vehicle milik user ini
        $vehicle = Vehicle::where('user_id', $user->id)->with('warehouse')->first();

        if (!$vehicle || !$vehicle->warehouse_id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda belum dikaitkan dengan kendaraan / gudang virtual.',
            ], 404);
        }

        $stocks = ProductStock::with(['product.unit'])
            ->where('warehouse_id', $vehicle->warehouse_id)
            ->where('stock', '>', 0)
            ->get()
            ->map(fn($s) => [
                'product_id'   => $s->product_id,
                'name'         => $s->product->name,
                'unit'         => $s->product->unit?->abbreviation ?? 'pcs',
                'stock'        => $s->stock,
                'sell_price'   => $s->product->sell_price ?? 0,
            ]);

        return response()->json([
            'status'        => 'success',
            'vehicle'       => $vehicle->name ?? 'Kendaraan',
            'warehouse'     => $vehicle->warehouse->name,
            'data'          => $stocks,
        ]);
    }

    /**
     * GET /api/sales/warehouses
     * Daftar gudang (non-vehicle) untuk pilih gudang asal
     */
    public function warehouses()
    {
        $warehouses = Warehouse::whereDoesntHave('vehicle')
            ->where('active', true)
            ->get(['id', 'name']);

        return response()->json(['status' => 'success', 'data' => $warehouses]);
    }
}

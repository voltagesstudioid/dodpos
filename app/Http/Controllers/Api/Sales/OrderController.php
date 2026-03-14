<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreSalesOrderRequest;
use App\Http\Resources\Api\SalesOrderResource;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SalesOrder;
use App\Models\StockMovement;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = SalesOrder::with(['customer', 'items.product'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20); // Tambah pagination — jangan .get() semua sekaligus

        return response()->json([
            'status' => 'success',
            'data'   => SalesOrderResource::collection($orders),
        ]);
    }

    public function store(StoreSalesOrderRequest $request)
    {

        try {
            DB::beginTransaction();

            $isCanvas = $request->order_type === 'canvas';

            // ── Resolve vehicle warehouse untuk canvas order ──────────
            $vehicleWarehouseId = null;
            if ($isCanvas) {
                if (!$request->vehicle_id) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Canvas order membutuhkan vehicle_id.',
                    ], 422);
                }

                $vehicle = Vehicle::with('warehouse')->find($request->vehicle_id);
                if (!$vehicle || !$vehicle->warehouse_id) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Kendaraan tidak memiliki gudang yang terdaftar.',
                    ], 422);
                }
                $vehicleWarehouseId = $vehicle->warehouse_id;
            }

            // ── Validasi stok untuk canvas order (dari gudang kendaraan) ──
            if ($isCanvas) {
                foreach ($request->items as $item) {
                    // Cek stok di gudang kendaraan spesifik
                    $warehouseStock = ProductStock::where('product_id', $item['product_id'])
                        ->where('warehouse_id', $vehicleWarehouseId)
                        ->lockForUpdate()
                        ->sum('stock');

                    $product = Product::find($item['product_id']);

                    if ($warehouseStock < $item['quantity']) {
                        DB::rollBack();
                        return response()->json([
                            'status'  => 'error',
                            'message' => 'Stok produk "' . ($product->name ?? 'ID:'.$item['product_id']) .
                                         '" di kendaraan tidak mencukupi. Tersedia: ' . $warehouseStock .
                                         ', Dibutuhkan: ' . $item['quantity'],
                        ], 422);
                    }
                }
            }

            // ── Resolve harga dari DB (anti price manipulation) ────────
            $resolvedItems = [];
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $product = Product::with('unitConversions')->find($item['product_id']);
                if (!$product) {
                    DB::rollBack();
                    return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan.'], 422);
                }
                if ($isCanvas) {
                    $baseConv = $product->unitConversions->firstWhere('is_base_unit', true)
                        ?? $product->unitConversions->sortBy('conversion_factor')->first();

                    $serverPrice = $baseConv
                        ? (float) ($baseConv->sell_price_grosir > 0 ? $baseConv->sell_price_grosir : $baseConv->sell_price_ecer)
                        : (float) $product->price;
                } else {
                    $serverPrice = (float) $product->price;
                }
                $qty = (int) $item['quantity'];
                $resolvedItems[] = [
                    'product_id' => $item['product_id'],
                    'quantity'   => $qty,
                    'price'      => $serverPrice,
                    'subtotal'   => $serverPrice * $qty,
                ];
                $totalAmount += $serverPrice * $qty;
            }

            // ── Generate SO Number dengan DB lock (anti race condition) ──
            $soNumber = $this->generateSoNumber();

            $order = SalesOrder::create([
                'so_number'     => $soNumber,
                'customer_id'   => $request->customer_id,
                'user_id'       => $request->user()->id,
                'order_date'    => now()->toDateString(),
                'delivery_date' => ($isCanvas ? now() : now()->addDay())->toDateString(),
                'total_amount'  => $totalAmount,  // dari server
                'status'        => $isCanvas ? 'completed' : 'draft',
                'order_type'    => $request->order_type,
                'notes'         => $request->notes,
            ]);

            foreach ($resolvedItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],   // harga dari server
                    'subtotal'   => $item['subtotal'],
                ]);

                // ── Kurangi stok untuk canvas order dari gudang kendaraan ──
                if ($isCanvas) {
                    // Kurangi stok global
                    Product::where('id', $item['product_id'])
                        ->decrement('stock', $item['quantity']);

                    // Kurangi stok dari gudang kendaraan spesifik (FIFO)
                    $this->deductSpecificWarehouseStock(
                        $item['product_id'],
                        $item['quantity'],
                        $vehicleWarehouseId,
                        $order->so_number,
                        $request->user()->id
                    );
                }
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pesanan berhasil dibuat.',
                'data'    => new SalesOrderResource($order->load(['items.product', 'customer'])),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('OrderController::store — Gagal membuat order', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'user_id' => $request->user()?->id,
                'payload' => $request->except(['password']),
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal membuat pesanan. Silakan coba lagi.',
            ], 500);
        }
    }

    public function show($id)
    {
        $order = SalesOrder::with(['customer', 'items.product'])
            ->where('user_id', Auth::id()) // Pastikan hanya bisa lihat order sendiri
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => new SalesOrderResource($order),
        ]);
    }

    /**
     * Generate SO Number yang aman dari race condition menggunakan DB lock.
     */
    private function generateSoNumber(): string
    {
        // Gunakan advisory lock di database untuk mencegah race condition
        $prefix = 'SO-' . date('Ymd') . '-';

        $last = SalesOrder::where('so_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('so_number')
            ->first();

        $sequence = $last
            ? (int) substr($last->so_number, -4) + 1
            : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Kurangi stok dari gudang kendaraan spesifik secara FIFO.
     * Digunakan untuk canvas order agar stok diambil dari kendaraan yang benar.
     */
    private function deductSpecificWarehouseStock(
        int $productId,
        int $qty,
        int $warehouseId,
        string $reference,
        int $userId
    ): void {
        $remaining = $qty;

        // Ambil stok dari gudang kendaraan spesifik, FIFO by expired_date & created_at
        $stocks = ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('stock', '>', 0)
            ->orderBy('expired_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            $deduct = min($stock->stock, $remaining);
            $stock->stock -= $deduct;
            $stock->save();

            StockMovement::create([
                'product_id'       => $productId,
                'warehouse_id'     => $stock->warehouse_id,
                'location_id'      => null,
                'type'             => 'out',
                'source_type'      => 'sales_order',
                'reference_number' => $reference,
                'quantity'         => $deduct,
                'balance'          => $stock->stock,
                'notes'            => '[Canvas Order] ' . $reference,
                'user_id'          => $userId,
            ]);

            $remaining -= $deduct;
        }

        // Jika masih ada sisa yang belum terpotong, log sebagai warning
        if ($remaining > 0) {
            Log::warning('deductSpecificWarehouseStock: stok tidak cukup di gudang kendaraan', [
                'product_id'   => $productId,
                'warehouse_id' => $warehouseId,
                'requested'    => $qty,
                'shortfall'    => $remaining,
                'reference'    => $reference,
            ]);
        }
    }
}

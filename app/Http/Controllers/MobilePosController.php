<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MobilePosController extends Controller
{
    public function index()
    {
        return view('mobile_pos.index');
    }

    public function getVanStock(Request $request)
    {
        $user = $request->user();
        
        // Cek link user_id ke PasgarMember untuk mendapatkan Vehicle
        $member = \App\Models\PasgarMember::with('vehicle')->where('user_id', $user->id)->where('active', true)->first();
        
        if (!$member || !$member->vehicle || !$member->vehicle->warehouse_id) {
            return response()->json(['success' => true, 'van_stock' => []]);
        }

        $stocks = \App\Models\ProductStock::where('warehouse_id', $member->vehicle->warehouse_id)
            ->where('stock', '>', 0)
            ->get();

        // Rekap stok per produk (jika ada multi-batch, dijumlahkan)
        $mapped = [];
        foreach($stocks as $st) {
            if(!isset($mapped[$st->product_id])) {
                $mapped[$st->product_id] = 0;
            }
            $mapped[$st->product_id] += $st->stock;
        }

        return response()->json(['success' => true, 'van_stock' => $mapped]);
    }

    public function syncOfflineOrders(Request $request)
    {
        $orders = $request->input('orders', []);
        $successCount = 0;

        $user = $request->user();
        $member = \App\Models\PasgarMember::with('vehicle')->where('user_id', $user->id)->where('active', true)->first();
        $vehicleWarehouseId = null;
        if ($member && $member->vehicle && $member->vehicle->warehouse_id) {
            $vehicleWarehouseId = $member->vehicle->warehouse_id;
        }

        DB::beginTransaction();
        try {
            foreach($orders as $orderData) {
                // If already exists (using a custom offline_id field), skip
                // For simplicity, we just create new SO records here
                $so = SalesOrder::create([
                    'order_number' => 'SO-MB-' . strtoupper(substr(uniqid(), -6)),
                    'customer_id' => $orderData['customer_id'] ?: null,
                    'user_id' => $user->id,
                    'order_date' => date('Y-m-d', strtotime($orderData['created_at'])),
                    'status' => 'confirmed',
                    'total_amount' => $orderData['total'],
                    'notes' => 'Tersinkronisasi dari Offline Mobile POS (' . $orderData['id'] . ')',
                ]);

                foreach($orderData['items'] as $item) {
                    SalesOrderItem::create([
                        'sales_order_id' => $so->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['qty'],
                        'unit_price' => $item['harga_umum'], // or grosir based on logic
                        'subtotal' => $item['qty'] * $item['harga_umum'],
                    ]);

                    // Kurangi stok global
                    $product = \App\Models\Product::find($item['id']);
                    if ($product) {
                        $product->stock -= $item['qty'];
                        $product->save();
                    }

                    // Kurangi stok riil di kendaraan
                    if ($vehicleWarehouseId) {
                        $this->deductVehicleWarehouseStock($item['id'], $vehicleWarehouseId, $item['qty'], $so->id, $item['name'] ?? 'Barang ID:' . $item['id']);
                    }
                }
                $successCount++;
            }
            DB::commit();
            return response()->json(['success' => true, 'synced' => $successCount]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PWA Sync Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function deductVehicleWarehouseStock(int $productId, int $warehouseId, int $qty, int $transactionId, string $productName): void
    {
        $remaining = $qty;

        $stocks = \App\Models\ProductStock::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'asc') // FIFO
            ->lockForUpdate()
            ->get();

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            $deduct = min($stock->stock, $remaining);
            $stock->stock -= $deduct;
            $stock->save();
            
            \App\Models\StockMovement::create([
                'product_id'       => $productId,
                'warehouse_id'     => $warehouseId,
                'type'             => 'out',
                'reference_number' => 'POS-PWA-' . $transactionId,
                'quantity'         => $deduct,
                'balance'          => $stock->stock,
                'notes'            => '[POS Offline PWA] Transaksi Sinkronisasi #' . $transactionId,
                'user_id'          => \Illuminate\Support\Facades\Auth::id(),
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            throw new \Exception('Inkonsistensi data: Stok di Kendaraan untuk "' . $productName . '" tidak mencukupi saat proses sinkronisasi, kurang ' . $remaining . ' pcs.');
        }
    }
}

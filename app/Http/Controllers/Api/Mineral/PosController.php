<?php

namespace App\Http\Controllers\Api\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralTransaction;
use App\Models\MineralTransactionItem;
use App\Models\MineralVehicleStock;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * Endpoint kasir khusus Penjualan Air Mineral (Satuan Mutlak: Dus).
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|in:cash,tempo',
            'due_date' => 'required_if:payment_method,tempo|nullable|date',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:mineral_products,id',
            'items.*.qty_dus' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric' // Harga per dus yang disepakati/ditarik dari DB
        ]);

        $salesId = $request->user()->id;

        // Validasi ketersediaan stok mobil SEBELUM insert DB
        foreach ($request->items as $item) {
            $vehicleStock = MineralVehicleStock::where('sales_id', $salesId)
                ->where('product_id', $item['product_id'])
                ->first();

            if (!$vehicleStock || $vehicleStock->leftover_qty < $item['qty_dus']) {
                $prod = \App\Models\MineralProduct::find($item['product_id']);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Stok ' . ($prod->name ?? 'Produk') . ' di mobil tidak mencukupi. Sisa: ' . ($vehicleStock->leftover_qty ?? 0) . ' Dus'
                ], 422);
            }
        }

        try {
            \DB::beginTransaction();

            // 1. Catat Header Transaksi
            $transaction = MineralTransaction::create([
                'sales_id' => $salesId,
                'customer_id' => $request->customer_id,
                'receipt_number' => 'MIN-' . time() . '-' . rand(100, 999),
                'payment_method' => $request->payment_method,
                'due_date' => $request->payment_method == 'tempo' ? $request->due_date : null,
                'total_amount' => $request->total_amount,
                'status' => $request->payment_method == 'cash' ? 'paid' : 'unpaid',
                'notes' => $request->notes
            ]);

            // 2. Transaksi Items & Potong Stok Kendaraan
            foreach ($request->items as $item) {
                MineralTransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'qty_dus' => $item['qty_dus'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty_dus'] * $item['price']
                ]);

                // Update vehicle stock
                $vehicleStock = MineralVehicleStock::where('sales_id', $salesId)
                    ->where('product_id', $item['product_id'])
                    ->first();
                
                $vehicleStock->sold_qty += $item['qty_dus'];
                $vehicleStock->leftover_qty -= $item['qty_dus'];
                $vehicleStock->save();
            }

            // 3. (Opsional) Jika Temp, masuk ke CustomerCredit secara global
            if ($request->payment_method == 'tempo') {
                \App\Models\CustomerCredit::create([
                    'customer_id' => $request->customer_id,
                    'transaction_type' => 'mineral_pos',
                    'transaction_id' => $transaction->id,
                    'amount' => $request->total_amount,
                    'due_date' => $request->due_date,
                    'status' => 'unpaid',
                    'notes' => 'Piutang Mineral Nota #' . $transaction->receipt_number
                ]);
            }

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi penjualan mineral berhasil dicatat',
                'data' => $transaction->load('items.product')
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memproses transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function history(Request $request)
    {
        $limit = $request->query('limit', 20);
        $transactions = MineralTransaction::with(['customer', 'items.product'])
            ->where('sales_id', $request->user()->id)
            ->latest()
            ->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $transactions->items(),
            'meta' => [
                'current_page' => $transactions->currentPage(),
                'last_page' => $transactions->lastPage(),
                'total' => $transactions->total()
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Kanvas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KanvasTransaction;
use App\Models\KanvasTransactionItem;
use App\Models\KanvasVehicleStock;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PosController extends Controller
{
    /**
     * Store new transaction from Kanvas Mobile App.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_method' => 'required|in:cash,tempo',
            'due_date' => 'nullable|date',
            'total_amount' => 'required|numeric',
            'discount_amount' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:kanvas_products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();

            // Cek ketersediaan stok fisik di kendaraan
            foreach ($request->items as $item) {
                $stock = KanvasVehicleStock::where('sales_id', $user->id)
                                           ->where('product_id', $item['product_id'])
                                           ->first();
                if (!$stock || $stock->leftover_qty < $item['qty']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stok untuk produk ID ' . $item['product_id'] . ' tidak cukup di mobil Anda.'
                    ], 400);
                }
            }

            // Generate RM Receipt Number
            $receipt = 'INV-KVS-' . date('Ymd') . '-' . rand(1000, 9999);

            $discount = $request->discount_amount ?? 0;
            $grandTotal = $request->total_amount - $discount;

            $transaction = KanvasTransaction::create([
                'receipt_number' => $receipt,
                'sales_id' => $user->id,
                'customer_id' => $request->customer_id,
                'payment_method' => $request->payment_method,
                'due_date' => $request->payment_method === 'tempo' ? $request->due_date : null,
                'total_amount' => $request->total_amount,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'paid_amount' => $request->payment_method === 'cash' ? $grandTotal : ($request->paid_amount ?? 0),
                'status' => $request->payment_method === 'cash' ? 'paid' : 'unpaid',
            ]);

            foreach ($request->items as $item) {
                KanvasTransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);

                // Kurangi stok leftover
                KanvasVehicleStock::where('sales_id', $user->id)
                                  ->where('product_id', $item['product_id'])
                                  ->decrement('leftover_qty', $item['qty']);
                
                // Tambah sold qty
                KanvasVehicleStock::where('sales_id', $user->id)
                                  ->where('product_id', $item['product_id'])
                                  ->increment('sold_qty', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Berrhasil',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'receipt_number' => $transaction->receipt_number
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'System Error: ' . $e->getMessage()], 500);
        }
    }
}

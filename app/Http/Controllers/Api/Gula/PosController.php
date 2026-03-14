<?php

namespace App\Http\Controllers\Api\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'payment_method' => 'required|in:cash,tempo',
            'due_date' => 'required_if:payment_method,tempo|date|nullable',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:gula_products,id',
            'items.*.unit' => 'required|in:karung,eceran',
            'items.*.qty' => 'required|numeric|min:0.5',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $user = $request->user();

        try {
            DB::beginTransaction();

            $invoiceNo = 'INV-GL-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $grandTotal = 0;

            // Header Transaksi
            $transaction = \App\Models\GulaTransaction::create([
                'invoice_number' => $invoiceNo,
                'date' => now(),
                'sales_id' => $user->id,
                'customer_id' => $request->customer_id,
                'payment_method' => $request->payment_method,
                'due_date' => $request->payment_method === 'tempo' ? $request->due_date : null,
                'grand_total' => 0, // diupdate nanti
                'status' => $request->payment_method === 'cash' ? 'paid' : 'unpaid',
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['qty'] * $item['price'];
                $grandTotal += $subtotal;

                // 1. Catat Item Transaksi
                $transaction->items()->create([
                    'gula_product_id' => $item['product_id'],
                    'unit_type' => $item['unit'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // 2. Deduksi Stok di Kendaraan Sales
                $vehicleStock = \App\Models\GulaVehicleStock::where('sales_id', $user->id)
                    ->where('gula_product_id', $item['product_id'])
                    ->first();

                if (!$vehicleStock) {
                    throw new \Exception("Stok muatan untuk produk ini tidak ditemukan di kendaraan Anda.");
                }

                if ($item['unit'] === 'karung') {
                    if ($vehicleStock->qty_karung < $item['qty']) throw new \Exception("Stok Karungan tidak cukup!");
                    $vehicleStock->decrement('qty_karung', $item['qty']);
                } else {
                    if ($vehicleStock->qty_eceran < $item['qty']) throw new \Exception("Stok Eceran tidak cukup!");
                    $vehicleStock->decrement('qty_eceran', $item['qty']);
                }
            }

            // Update Total
            $transaction->update(['grand_total' => $grandTotal]);

            // Jika Tempo, catat di tabel Hutang Customer (CustomerCredit)
            if ($request->payment_method === 'tempo') {
                \App\Models\CustomerCredit::create([
                    'customer_id' => $request->customer_id,
                    'sales_id' => $user->id,
                    'amount' => $grandTotal,
                    'due_date' => $request->due_date,
                    'status' => 'unpaid',
                    'notes' => 'Piutang Gula ' . $invoiceNo
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Penjualan Gula berhasil dicatat.',
                'data' => $transaction->load('items')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function history(Request $request)
    {
        $transactions = \App\Models\GulaTransaction::where('sales_id', $request->user()->id)
            ->with(['customer', 'items.product'])
            ->latest()
            ->limit(30)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }
}

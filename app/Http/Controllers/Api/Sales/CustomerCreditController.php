<?php

namespace App\Http\Controllers\Api\Sales;

use App\Http\Controllers\Controller;
use App\Models\CustomerCredit;
use App\Models\CustomerCreditPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerCreditController extends Controller
{
    public function unpaid(Request $request)
    {
        $customers = \App\Models\Customer::where('current_debt', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'address', 'credit_limit', 'current_debt']);

        return response()->json([
            'status' => 'success',
            'data'   => $customers
        ]);
    }

    public function pay(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string|in:cash,transfer,qris,other',
            'notes'          => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $customer = \App\Models\Customer::lockForUpdate()->findOrFail($request->customer_id);

            if ($request->amount > $customer->current_debt) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Jumlah pembayaran melebihi total tagihan pelanggan saat ini.',
                ], 400);
            }

            // Alokasikan pembayaran ke CustomerCredit records secara FIFO
            $remaining = (float) $request->amount;
            $credits = CustomerCredit::where('customer_id', $customer->id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->orderBy('due_date', 'asc')
                ->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->get();

            foreach ($credits as $credit) {
                if ($remaining <= 0) break;

                $deduct = min($credit->remaining_amount, $remaining);

                // Catat pembayaran ke tabel customer_credit_payments
                CustomerCreditPayment::create([
                    'customer_credit_id' => $credit->id,
                    'payment_date'       => today(),
                    'amount'             => $deduct,
                    'payment_method'     => $request->payment_method,
                    'reference_number'   => 'PAY-' . strtoupper(uniqid()),
                    'notes'              => $request->notes ?? 'Pembayaran via Aplikasi Pasgar',
                    'created_by'         => Auth::id(),
                ]);

                // Update status & paid_amount CustomerCredit
                $credit->paid_amount += $deduct;
                $credit->status = ($credit->paid_amount >= $credit->amount) ? 'paid' : 'partial';
                $credit->save();

                $remaining -= $deduct;
            }

            // Update current_debt pelanggan
            $customer->refreshDebt();

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pembayaran tagihan berhasil dicatat.',
                'data'    => [
                    'customer_id'    => $customer->id,
                    'paid_amount'    => (float) $request->amount,
                    'remaining_debt' => (float) $customer->fresh()->current_debt,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CustomerCreditController::pay — Gagal mencatat pembayaran', [
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
                'user_id'     => Auth::id(),
                'customer_id' => $request->customer_id,
                'amount'      => $request->amount,
            ]);
            return response()->json([
                'status'  => 'error',
                'message' => 'Pembayaran gagal diproses. Silakan coba lagi.',
            ], 500);
        }
    }
}

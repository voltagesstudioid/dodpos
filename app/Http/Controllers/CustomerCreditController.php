<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\CustomerCreditPayment;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerCreditController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerCredit::with(['customer'])->latest();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('credit_number', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', fn($q2) => $q2->where('name', 'like', '%' . $search . '%'));
            });
        }
        if ($request->type)   $query->where('type', $request->type);
        if ($request->status) $query->where('status', $request->status);
        if ($request->customer_id) $query->where('customer_id', $request->customer_id);

        $credits   = $query->paginate(20)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        // Summary using DB-level aggregation (avoids loading all records)
        $activeQuery = CustomerCredit::whereIn('status', ['unpaid', 'partial']);
        $totalDebt    = (clone $activeQuery)->where('type', 'debt')->sum(DB::raw('amount - paid_amount'));
        $totalCredit  = (clone $activeQuery)->where('type', 'credit')->sum(DB::raw('amount - paid_amount'));
        $overdueCount = CustomerCredit::whereIn('status', ['unpaid', 'partial'])
                            ->where('due_date', '<', now())->count();

        return view('pelanggan.kredit.index', compact('credits', 'customers', 'totalDebt', 'totalCredit', 'overdueCount'));
    }

    /**
     * Tampilkan hutang terkonsolidasi per pelanggan
     */
    public function consolidated(Request $request)
    {
        $customers = Customer::where('is_active', true)
            ->where(function($q) {
                $q->where('current_debt', '>', 0)
                  ->orWhereHas('activeDebts');
            })
            ->withCount('activeDebts')
            ->orderBy('current_debt', 'desc')
            ->get();

        // Recalculate each customer's debt from actual records
        foreach ($customers as $c) {
            $c->calculated_debt = $c->activeDebts()->get()->sum(fn($d) => $d->remaining_amount);
        }

        $totalDebt = $customers->sum('calculated_debt');

        return view('pelanggan.kredit.consolidated', compact('customers', 'totalDebt'));
    }

    /**
     * Tampilkan detail hutang per pelanggan dengan fitur pembayaran
     */
    public function customerDebt(Customer $customer)
    {
        $debts = $customer->activeDebts()
            ->with(['payments'])
            ->orderBy('created_at', 'asc')
            ->get();

        $totalDebt = $customer->current_debt;
        $totalTransactions = $debts->count();

        return view('pelanggan.kredit.customer_debt', compact('customer', 'debts', 'totalDebt', 'totalTransactions'));
    }

    /**
     * Proses pembayaran hutang terkonsolidasi per pelanggan
     */
    public function payConsolidated(Request $request, Customer $customer)
    {
        $customerDebts = $customer->activeDebts()->orderBy('created_at', 'asc')->get();
        $totalDebt = $customerDebts->sum(fn($d) => $d->remaining_amount);

        if ($totalDebt <= 0) {
            return back()->with('error', 'Pelanggan tidak memiliki hutang.');
        }

        $totalDebtInt = (int) $totalDebt;

        $request->validate([
            'payment_date'     => 'required|date',
            'amount'           => 'required|numeric|min:1|max:' . $totalDebtInt,
            'payment_method'   => 'required|in:cash,transfer,qris,other',
            'reference_number' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Lock all active debts for this customer
            $debts = $customer->activeDebts()
                ->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->get();

            $remainingPayment = $request->amount;
            $paymentDetails = [];

            foreach ($debts as $debt) {
                if ($remainingPayment <= 0) break;

                $remainingDebt = (int) $debt->remaining_amount;
                if ($remainingDebt <= 0) continue;

                $payForThisDebt = min($remainingPayment, $remainingDebt);

                CustomerCreditPayment::create([
                    'customer_credit_id' => $debt->id,
                    'payment_date'       => $request->payment_date,
                    'amount'             => $payForThisDebt,
                    'payment_method'     => $request->payment_method,
                    'reference_number'   => $request->reference_number,
                    'notes'              => trim(($request->notes ?? '') . ' (Pembayaran terkonsolidasi)'),
                    'created_by'         => Auth::id(),
                ]);

                // Recalculate from actual payments
                $debt->recalculate();

                $paymentDetails[] = [
                    'credit_id' => $debt->id,
                    'amount' => $payForThisDebt,
                ];

                $remainingPayment -= $payForThisDebt;
            }

            // Refresh customer debt from actual records
            $customer->refreshDebt();

            AuditService::log(
                'customer_credit.pay_consolidated',
                'Customer',
                $customer->id,
                [
                    'total_amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'details' => $paymentDetails,
                    'remaining_debt' => $customer->current_debt,
                ],
                'info'
            );

            DB::commit();

            $msg = $customer->current_debt <= 0
                ? '✅ Lunas! Semua hutang pelanggan telah terlunasi.'
                : '💰 Pembayaran Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil. Sisa hutang: Rp ' . number_format((float) $customer->current_debt, 0, ',', '.');

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage())->withInput();
        }
    }

    public function create(Request $request)
    {
        $customers = Customer::where('is_active', true)->orderBy('name')->get();
        $type      = $request->type === 'credit' ? 'credit' : 'debt';
        return view('pelanggan.kredit.create', compact('customers', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'type'             => 'required|in:debt,credit',
            'transaction_date' => 'required|date',
            'due_date'         => 'nullable|date|after_or_equal:transaction_date',
            'amount'           => 'required|numeric|min:1',
            'description'      => 'required|string|max:255',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        if (!$customer->is_active) {
            return back()->with('error', 'Pelanggan "' . $customer->name . '" sudah tidak aktif.')->withInput();
        }

        // Credit limit check for debt type
        if ($request->type === 'debt' && $customer->credit_limit > 0) {
            $newTotal = (float) $customer->current_debt + (float) $request->amount;
            if ($newTotal > (float) $customer->credit_limit) {
                $remaining = max(0, (float) $customer->credit_limit - (float) $customer->current_debt);
                return back()->with('error',
                    'Melebihi batas kredit! Batas: Rp ' . number_format((float) $customer->credit_limit, 0, ',', '.') .
                    ' | Sisa: Rp ' . number_format($remaining, 0, ',', '.') .
                    ' | Diminta: Rp ' . number_format((float) $request->amount, 0, ',', '.')
                )->withInput();
            }
        }

        try {
            DB::beginTransaction();

            $credit = CustomerCredit::create([
                'credit_number'    => CustomerCredit::generateNumber($request->type),
                'customer_id'      => $customer->id,
                'type'             => $request->type,
                'transaction_date' => $request->transaction_date,
                'due_date'         => $request->due_date,
                'amount'           => $request->amount,
                'paid_amount'      => 0,
                'status'           => 'unpaid',
                'description'      => $request->description,
                'notes'            => $request->notes,
                'created_by'       => Auth::id(),
            ]);

            // Update customer's current_debt
            if ($request->type === 'debt') {
                $customer->increment('current_debt', $request->amount);
            }

            AuditService::log(
                'customer_credit.create',
                'CustomerCredit',
                $credit->id,
                [
                    'type' => $request->type,
                    'amount' => $request->amount,
                    'customer_id' => $customer->id,
                ],
                'info'
            );

            DB::commit();
            return redirect()->route('pelanggan.kredit.show', $credit)
                ->with('success', 'Catatan kredit berhasil dibuat: ' . $credit->credit_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(CustomerCredit $kredit)
    {
        $kredit->load(['customer', 'payments.createdBy', 'createdBy']);
        return view('pelanggan.kredit.show', compact('kredit'));
    }

    public function pay(Request $request, CustomerCredit $kredit)
    {
        if ($kredit->status === 'paid' || $kredit->remaining_amount <= 0) {
            return back()->with('error', 'Sudah lunas.');
        }

        $remaining = (int) $kredit->remaining_amount;

        $request->validate([
            'payment_date'     => 'required|date',
            'amount'           => 'required|numeric|min:1|max:' . $remaining,
            'payment_method'   => 'required|in:cash,transfer,qris,other',
            'reference_number' => 'nullable|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Lock for concurrent safety
            $kredit = CustomerCredit::where('id', $kredit->id)->lockForUpdate()->first();

            // Re-check remaining after lock
            $currentRemaining = (int) $kredit->remaining_amount;
            if ($request->amount > $currentRemaining) {
                DB::rollBack();
                return back()->with('error', 'Jumlah pembayaran melebihi sisa hutang (Rp ' . number_format($currentRemaining, 0, ',', '.') . ').');
            }

            CustomerCreditPayment::create([
                'customer_credit_id' => $kredit->id,
                'payment_date'       => $request->payment_date,
                'amount'             => $request->amount,
                'payment_method'     => $request->payment_method,
                'reference_number'   => $request->reference_number,
                'notes'              => $request->notes,
                'created_by'         => Auth::id(),
            ]);

            // Recalculate from actual payments (prevents rounding drift)
            $kredit->recalculate();

            // Refresh customer debt from all active records
            if ($kredit->type === 'debt') {
                $kredit->customer->refreshDebt();
            }

            AuditService::log(
                'customer_credit.pay',
                'CustomerCredit',
                $kredit->id,
                [
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'paid_amount' => $kredit->paid_amount,
                    'remaining' => $kredit->remaining_amount,
                ],
                'info'
            );

            DB::commit();

            $msg = $kredit->status === 'paid'
                ? '✅ Lunas! Hutang pelanggan telah terlunasi.'
                : 'Pembayaran Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dicatat. Sisa: Rp ' . number_format($kredit->remaining_amount, 0, ',', '.');

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(CustomerCredit $kredit)
    {
        if ($kredit->payments()->exists()) {
            return back()->with('error', 'Catatan yang sudah ada pembayaran tidak bisa dihapus.');
        }

        DB::beginTransaction();
        try {
            $customer = $kredit->customer;

            AuditService::log(
                'customer_credit.delete',
                'CustomerCredit',
                $kredit->id,
                [
                    'type' => $kredit->type,
                    'amount' => $kredit->amount,
                    'customer_id' => $kredit->customer_id,
                    'credit_number' => $kredit->credit_number,
                ],
                'warning'
            );

            $kredit->delete();

            // Recalculate customer debt from actual records (prevents negative drift)
            if ($customer && $kredit->type === 'debt') {
                $customer->refreshDebt();
            }

            DB::commit();

            return redirect()->route('pelanggan.kredit.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pembayaran yang salah (dengan recalculate hutang)
     */
    public function deletePayment(CustomerCreditPayment $payment)
    {
        $kredit = $payment->customerCredit;

        if (!$kredit) {
            return back()->with('error', 'Data kredit tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            $paymentAmount = (float) $payment->amount;
            $kreditType = $kredit->type;

            AuditService::log(
                'customer_credit.delete_payment',
                'CustomerCreditPayment',
                $payment->id,
                [
                    'customer_credit_id' => $kredit->id,
                    'amount' => $paymentAmount,
                    'payment_date' => $payment->payment_date->format('Y-m-d'),
                    'credit_number' => $kredit->credit_number,
                ],
                'warning'
            );

            $payment->delete();

            // Recalculate debt from remaining payments
            $kredit->recalculate();

            // Restore customer current_debt if type=debt
            if ($kreditType === 'debt') {
                $kredit->customer->refreshDebt();
            }

            DB::commit();

            return back()->with('success', 'Pembayaran Rp ' . number_format($paymentAmount, 0, ',', '.') . ' berhasil dihapus. Sisa hutang telah dihitung ulang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}

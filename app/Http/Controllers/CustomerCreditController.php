<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\CustomerCreditPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerCreditController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerCredit::with(['customer'])->latest();

        if ($request->search) {
            $query->where('credit_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->type)   $query->where('type', $request->type);
        if ($request->status) $query->where('status', $request->status);
        if ($request->customer_id) $query->where('customer_id', $request->customer_id);

        $credits   = $query->paginate(20)->withQueryString();
        $customers = Customer::orderBy('name')->get();

        // Summary
        $totalDebt      = CustomerCredit::where('type', 'debt')->whereIn('status', ['unpaid', 'partial'])
                            ->sum(DB::raw('amount - paid_amount'));
        $totalCredit    = CustomerCredit::where('type', 'credit')->whereIn('status', ['unpaid', 'partial'])
                            ->sum(DB::raw('amount - paid_amount'));
        $overdueCount   = CustomerCredit::whereIn('status', ['unpaid', 'partial'])
                            ->where('due_date', '<', now())->count();

        return view('pelanggan.kredit.index', compact('credits', 'customers', 'totalDebt', 'totalCredit', 'overdueCount'));
    }

    /**
     * Tampilkan hutang terkonsolidasi per pelanggan
     */
    public function consolidated(Request $request)
    {
        $customers = Customer::where('current_debt', '>', 0)
            ->withCount(['activeDebts'])
            ->withSum('activeDebts', DB::raw('amount - paid_amount'))
            ->orderBy('current_debt', 'desc')
            ->get();

        $totalDebt = Customer::sum('current_debt');

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
        if ($customer->current_debt <= 0) {
            return back()->with('error', 'Pelanggan tidak memiliki hutang.');
        }

        $request->validate([
            'payment_date'     => 'required|date',
            'amount'           => 'required|numeric|min:1|max:' . $customer->current_debt,
            'payment_method'   => 'required|in:cash,transfer,qris,other',
            'reference_number' => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $paymentAmount = $request->amount;
            $remainingPayment = $paymentAmount;

            // Ambil semua hutang yang belum lunas, urutkan dari yang paling lama
            $debts = $customer->activeDebts()->orderBy('created_at', 'asc')->get();

            foreach ($debts as $debt) {
                if ($remainingPayment <= 0) break;

                $remainingDebt = $debt->amount - $debt->paid_amount;
                $payForThisDebt = min($remainingPayment, $remainingDebt);

                // Catat pembayaran untuk hutang ini
                CustomerCreditPayment::create([
                    'customer_credit_id' => $debt->id,
                    'payment_date'       => $request->payment_date,
                    'amount'             => $payForThisDebt,
                    'payment_method'     => $request->payment_method,
                    'reference_number' => $request->reference_number,
                    'notes'              => $request->notes . ' (Pembayaran terkonsolidasi)',
                    'created_by'         => Auth::id(),
                ]);

                // Update status hutang
                $newPaid = $debt->paid_amount + $payForThisDebt;
                $newStatus = $newPaid >= $debt->amount ? 'paid' : 'partial';
                $debt->update(['paid_amount' => $newPaid, 'status' => $newStatus]);

                $remainingPayment -= $payForThisDebt;
            }

            // Update total hutang customer
            $customer->refreshDebt();

            DB::commit();

            $msg = $customer->current_debt == 0
                ? '✅ Lunas! Semua hutang pelanggan telah terlunasi.'
                : '💰 Pembayaran Rp ' . number_format($paymentAmount, 0, ',', '.') . ' berhasil. Sisa hutang: Rp ' . number_format($customer->current_debt, 0, ',', '.');

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
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
            'description'      => 'required|string',
            'notes'            => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $credit = CustomerCredit::create([
                'credit_number'    => CustomerCredit::generateNumber($request->type),
                'customer_id'      => $request->customer_id,
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
                $credit->customer->increment('current_debt', $request->amount);
            }

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
        if ($kredit->status === 'paid') {
            return back()->with('error', 'Sudah lunas.');
        }

        $request->validate([
            'payment_date'     => 'required|date',
            'amount'           => 'required|numeric|min:1|max:' . $kredit->remaining_amount,
            'payment_method'   => 'required|in:cash,transfer,qris,other',
            'reference_number' => 'nullable|string',
            'notes'            => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            CustomerCreditPayment::create([
                'customer_credit_id' => $kredit->id,
                'payment_date'       => $request->payment_date,
                'amount'             => $request->amount,
                'payment_method'     => $request->payment_method,
                'reference_number'   => $request->reference_number,
                'notes'              => $request->notes,
                'created_by'         => Auth::id(),
            ]);

            $newPaid   = $kredit->paid_amount + $request->amount;
            $newStatus = $newPaid >= $kredit->amount ? 'paid' : 'partial';
            $kredit->update(['paid_amount' => $newPaid, 'status' => $newStatus]);

            // Update customer debt
            if ($kredit->type === 'debt') {
                $kredit->customer->decrement('current_debt', $request->amount);
            }

            DB::commit();
            $msg = $newStatus === 'paid' ? '✅ Lunas! Hutang pelanggan telah terlunasi.' : 'Pembayaran Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dicatat.';
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
        if ($kredit->type === 'debt') {
            $kredit->customer->decrement('current_debt', $kredit->amount);
        }
        $kredit->delete();
        return redirect()->route('pelanggan.kredit.index')->with('success', 'Data dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\CustomerCreditPayment;
use App\Models\Transaction;
use App\Support\SearchSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $sanitized = SearchSanitizer::sanitize($request->search);
            $query->where(function ($q) use ($sanitized) {
                $q->where('name', 'like', "%{$sanitized}%")
                  ->orWhere('phone', 'like', "%{$sanitized}%")
                  ->orWhere('email', 'like', "%{$sanitized}%");
            });
        }

        if ($request->has('active') && $request->active !== '') {
            $query->where('is_active', $request->active);
        }

        $customers = $query->orderBy('name')->paginate(20)->withQueryString();

        // Stat cards (for the current filter scope)
        $statQuery = Customer::query();
        if ($request->filled('category') && $request->category !== 'all') {
            $statQuery->where('category', $request->category);
        }
        $totalCustomers = (clone $statQuery)->count();
        $activeCustomers = (clone $statQuery)->where('is_active', true)->count();
        $totalDebt = (clone $statQuery)->where('current_debt', '>', 0)->sum('current_debt');
        $withCredit = (clone $statQuery)->where('credit_limit', '>', 0)->count();

        return view('pelanggan.index', compact(
            'customers', 'totalCustomers', 'activeCustomers', 'totalDebt', 'withCredit'
        ));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:100',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
        ];
        if (Auth::user() && Auth::user()->role === 'supervisor') {
            $rules['credit_limit'] = 'nullable|numeric|min:0';
        }
        $request->validate($rules);

        $creditLimit = 0;
        if (Auth::user() && Auth::user()->role === 'supervisor') {
            $creditLimit = (float) preg_replace('/[^0-9.]/', '', $request->credit_limit ?? 0);
        }

        Customer::create([
            'name'         => strtoupper($request->name),
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'credit_limit' => $creditLimit,
            'current_debt' => 0,
            'category'     => $request->category ?? 'eceran',
            'is_active'    => true,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $pelanggan)
    {
        $activeDebts   = $pelanggan->credits()->where('type', 'debt')->whereIn('status', ['unpaid', 'partial'])->with('payments')->get();
        $recentCredits = $pelanggan->credits()->with('payments')->latest()->take(20)->get();

        // Riwayat pembelian dari POS (transaksi pelanggan ini)
        $purchaseHistory = Transaction::with(['user', 'details.product'])
            ->where('customer_id', $pelanggan->id)
            ->whereNull('parent_transaction_id')
            ->latest()
            ->take(50)
            ->get();

        $totalPurchase = Transaction::where('customer_id', $pelanggan->id)
            ->whereNull('parent_transaction_id')
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalTransactions = Transaction::where('customer_id', $pelanggan->id)
            ->whereNull('parent_transaction_id')
            ->where('status', 'completed')
            ->count();

        return view('pelanggan.show', compact('pelanggan', 'activeDebts', 'recentCredits', 'purchaseHistory', 'totalPurchase', 'totalTransactions'));
    }

    public function edit(Customer $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Customer $pelanggan)
    {
        $rules = [
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:100',
            'address'      => 'nullable|string',
            'category'     => 'required|in:eceran,grosir,pos,pasgar,minyak',
            'notes'        => 'nullable|string',
        ];
        if (Auth::user() && Auth::user()->role === 'supervisor') {
            $rules['credit_limit'] = 'nullable|numeric|min:0';
        }
        $request->validate($rules);

        $payload = $request->only('phone', 'email', 'address', 'category', 'notes');
        $payload['name'] = strtoupper($request->name);
        if (Auth::user() && Auth::user()->role === 'supervisor') {
            $payload['credit_limit'] = (float) preg_replace('/[^0-9.]/', '', $request->credit_limit ?? 0);
        }
        $pelanggan->update($payload);
        return redirect()->route('pelanggan.show', $pelanggan)->with('success', 'Data pelanggan diperbarui.');
    }

    public function destroy(Customer $pelanggan)
    {
        if ($pelanggan->current_debt > 0) {
            return back()->with('error', 'Pelanggan masih memiliki hutang. Selesaikan dulu sebelum menghapus.');
        }

        $activeCredits = $pelanggan->credits()
            ->where('type', 'credit')
            ->whereIn('status', ['unpaid', 'partial'])
            ->exists();

        if ($activeCredits) {
            return back()->with('error', 'Pelanggan masih memiliki piutang aktif. Selesaikan dulu sebelum menghapus.');
        }

        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan dihapus.');
    }
}

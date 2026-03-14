<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\CustomerCreditPayment;
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
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->has('active') && $request->active !== '') {
            $query->where('is_active', $request->active);
        }
        
        $customers = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('pelanggan.index', compact('customers'));
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
            $creditLimit = $request->credit_limit ?? 0;
        }

        Customer::create([
            'name'         => $request->name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'credit_limit' => $creditLimit,
            'current_debt' => 0,
            'category'     => 'pos',
            'is_active'    => true,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $pelanggan)
    {
        $pelanggan->load(['credits.payments']);
        $activeDebts   = $pelanggan->credits()->where('type', 'debt')->whereIn('status', ['unpaid', 'partial'])->get();
        $recentCredits = $pelanggan->credits()->latest()->take(20)->get();
        return view('pelanggan.show', compact('pelanggan', 'activeDebts', 'recentCredits'));
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
            'notes'        => 'nullable|string',
        ];
        if (Auth::user() && Auth::user()->role === 'supervisor') {
            $rules['credit_limit'] = 'nullable|numeric|min:0';
        }
        $request->validate($rules);

        $payload = $request->only('name', 'phone', 'email', 'address', 'notes');
        if (Auth::user() && Auth::user()->role === 'supervisor') {
            $payload['credit_limit'] = $request->credit_limit ?? 0;
        }
        $pelanggan->update($payload);
        return redirect()->route('pelanggan.show', $pelanggan)->with('success', 'Data pelanggan diperbarui.');
    }

    public function destroy(Customer $pelanggan)
    {
        if ($pelanggan->current_debt > 0) {
            return back()->with('error', 'Pelanggan masih memiliki hutang. Selesaikan dulu sebelum menghapus.');
        }
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan dihapus.');
    }
}

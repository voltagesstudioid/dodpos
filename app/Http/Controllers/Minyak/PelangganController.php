<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::where('category', 'minyak');
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
        }
        
        $customers = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('minyak.pelanggan.index', compact('customers'));
    }

    public function create()
    {
        return view('minyak.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:100',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);

        Customer::create([
            'name'         => $request->name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'credit_limit' => 0,
            'current_debt' => 0,
            'category'     => 'minyak',
            'is_active'    => true,
            'notes'        => $request->notes,
        ]);

        return redirect()->route('minyak.pelanggan.index')->with('success', 'Pelanggan Minyak berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pelanggan = Customer::findOrFail($id);
        return view('minyak.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Customer::findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:100',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:100',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);

        $pelanggan->update($request->only('name', 'phone', 'email', 'address', 'notes'));
        return redirect()->route('minyak.pelanggan.index')->with('success', 'Data Pelanggan Minyak diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Customer::findOrFail($id);
        
        if ($pelanggan->current_debt > 0) {
            return back()->with('error', 'Pelanggan masih memiliki hutang. Selesaikan dulu sebelum menghapus.');
        }

        $pelanggan->delete();
        return redirect()->route('minyak.pelanggan.index')->with('success', 'Pelanggan Minyak dihapus.');
    }
}

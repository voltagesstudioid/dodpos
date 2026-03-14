<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('active', $request->status);
        }
        $suppliers = $query->latest()->paginate(15)->withQueryString();
        return view('master.supplier.index', compact('suppliers'));
    }

    private function generateCode(): string
    {
        $last = Supplier::where('code', 'like', 'SUP-%')->orderBy('id', 'desc')->first();
        if (!$last) return 'SUP-0001';
        $number = (int) str_replace('SUP-', '', $last->code);
        return 'SUP-' . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
    }

    public function create()
    {
        $nextCode = $this->generateCode();
        return view('master.supplier.create', compact('nextCode'));
    }

    public function store(Request $request)
    {
        if (empty($request->code) || Supplier::where('code', $request->code)->exists()) {
            $request->merge(['code' => $this->generateCode()]);
        }

        $request->validate([
            'name'              => 'required|string|max:255',
            'code'              => 'nullable|string|max:50|unique:suppliers,code',
            'contact_person'    => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'city'              => 'nullable|string|max:100',
            'address'           => 'nullable|string',
            'npwp'              => 'nullable|string|max:50',
            'bank_name'         => 'nullable|string|max:100',
            'bank_account'      => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'term_days'         => 'nullable|integer|min:0',
            'notes'             => 'nullable|string',
        ]);
        Supplier::create($request->merge(['active' => $request->has('active')])->all());
        return redirect()->route('master.supplier')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('master.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'code'              => 'nullable|string|max:50|unique:suppliers,code,' . $supplier->id,
            'contact_person'    => 'nullable|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'city'              => 'nullable|string|max:100',
            'address'           => 'nullable|string',
            'npwp'              => 'nullable|string|max:50',
            'bank_name'         => 'nullable|string|max:100',
            'bank_account'      => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'term_days'         => 'nullable|integer|min:0',
            'notes'             => 'nullable|string',
        ]);
        $supplier->update($request->merge(['active' => $request->has('active')])->all());
        return redirect()->route('master.supplier')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('master.supplier')->with('success', 'Supplier berhasil dihapus.');
    }
}

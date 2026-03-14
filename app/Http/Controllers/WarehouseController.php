<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }
        $warehouses = $query->latest()->paginate(15)->withQueryString();
        return view('master.gudang.index', compact('warehouses'));
    }

    private function generateCode(): string
    {
        $last = Warehouse::where('code', 'like', 'WH-%')->orderBy('id', 'desc')->first();
        if (!$last) return 'WH-001';
        $number = (int) str_replace('WH-', '', $last->code);
        return 'WH-' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
    }

    public function create()
    {
        $nextCode = $this->generateCode();
        return view('master.gudang.create', compact('nextCode'));
    }

    public function store(Request $request)
    {
        if (empty($request->code) || Warehouse::where('code', $request->code)->exists()) {
            $request->merge(['code' => $this->generateCode()]);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:20|unique:warehouses,code',
            'address'     => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'pic'         => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        Warehouse::create($request->merge(['active' => $request->has('active')])->all());
        return redirect()->route('master.gudang')->with('success', 'Gudang berhasil ditambahkan.');
    }

    public function edit(Warehouse $gudang)
    {
        return view('master.gudang.edit', compact('gudang'));
    }

    public function update(Request $request, Warehouse $gudang)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'nullable|string|max:20|unique:warehouses,code,' . $gudang->id,
            'address'     => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
            'pic'         => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        $gudang->update($request->merge(['active' => $request->has('active')])->all());
        return redirect()->route('master.gudang')->with('success', 'Gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $gudang)
    {
        $hasStock = \App\Models\ProductStock::where('warehouse_id', $gudang->id)
            ->where('stock', '>', 0)
            ->exists();

        if ($hasStock) {
            return back()->with('error', 'Gudang tidak bisa dinonaktifkan karena masih ada stok di gudang tersebut.');
        }

        $gudang->update(['active' => false]);
        return redirect()->route('master.gudang')->with('success', 'Gudang dinonaktifkan. Data historis tetap aman.');
    }
}

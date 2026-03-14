<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\PurchaseReturnItem;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::withCount(['products as products_count' => function ($q) {
            $q->withTrashed();
        }]);
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('abbreviation', 'like', '%' . $request->search . '%');
        }
        $units = $query->latest()->paginate(15)->withQueryString();
        return view('master.satuan.index', compact('units'));
    }

    public function create()
    {
        return view('master.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255|unique:units,name',
            'abbreviation' => 'required|string|max:20|unique:units,abbreviation',
            'description'  => 'nullable|string',
        ]);
        Unit::create($request->only('name', 'abbreviation', 'description'));
        return redirect()->route('master.satuan')->with('success', 'Satuan berhasil ditambahkan.');
    }

    public function edit(Unit $satuan)
    {
        return view('master.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, Unit $satuan)
    {
        $request->validate([
            'name'         => 'required|string|max:255|unique:units,name,' . $satuan->id,
            'abbreviation' => 'required|string|max:20|unique:units,abbreviation,' . $satuan->id,
            'description'  => 'nullable|string',
        ]);
        $satuan->update($request->only('name', 'abbreviation', 'description'));
        return redirect()->route('master.satuan')->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroy(Unit $satuan)
    {
        $productsCount = Product::withTrashed()->where('unit_id', $satuan->id)->count();
        if ($productsCount > 0) {
            return back()->with('error', 'Satuan tidak bisa dihapus karena masih digunakan oleh ' . $productsCount . ' produk.');
        }

        $conversionsCount = ProductUnitConversion::where('unit_id', $satuan->id)->count();
        if ($conversionsCount > 0) {
            return back()->with('error', 'Satuan tidak bisa dihapus karena masih digunakan pada ' . $conversionsCount . ' konversi satuan produk.');
        }

        $purchaseReturnItemsCount = PurchaseReturnItem::where('unit_id', $satuan->id)->count();
        if ($purchaseReturnItemsCount > 0) {
            return back()->with('error', 'Satuan tidak bisa dihapus karena sudah digunakan pada retur pembelian (' . $purchaseReturnItemsCount . ' item).');
        }

        try {
            $satuan->delete();
            return redirect()->route('master.satuan')->with('success', 'Satuan berhasil dihapus.');
        } catch (QueryException $e) {
            if ((string) $e->getCode() === '23000') {
                return back()->with('error', 'Satuan tidak bisa dihapus karena masih terkait data transaksi.');
            }
            throw $e;
        }
    }
}

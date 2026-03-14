<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $brands = $query->latest()->paginate(15)->withQueryString();
        return view('master.merek.index', compact('brands'));
    }

    public function create()
    {
        return view('master.merek.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
        ]);
        Brand::create($request->only('name', 'description'));
        return redirect()->route('master.merek')->with('success', 'Merek berhasil ditambahkan.');
    }

    public function edit(Brand $merek)
    {
        return view('master.merek.edit', compact('merek'));
    }

    public function update(Request $request, Brand $merek)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:brands,name,' . $merek->id,
            'description' => 'nullable|string',
        ]);
        $merek->update($request->only('name', 'description'));
        return redirect()->route('master.merek')->with('success', 'Merek berhasil diperbarui.');
    }

    public function destroy(Brand $merek)
    {
        if ($merek->products()->count() > 0) {
            return back()->with('error', 'Merek tidak bisa dihapus karena masih digunakan oleh produk.');
        }
        $merek->delete();
        return redirect()->route('master.merek')->with('success', 'Merek berhasil dihapus.');
    }
}

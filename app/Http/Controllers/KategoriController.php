<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount(['products as products_count' => function ($q) {
            $q->withTrashed();
        }]);
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $kategoris = $query->latest()->paginate(15)->withQueryString();
        return view('master.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('master.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);
        Category::create($request->only('name', 'description'));
        return redirect()->route('master.kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $kategori)
    {
        return view('master.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Category $kategori)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $kategori->id,
            'description' => 'nullable|string',
        ]);
        $kategori->update($request->only('name', 'description'));
        return redirect()->route('master.kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $kategori)
    {
        $hasProducts = Product::withTrashed()->where('category_id', $kategori->id)->exists();
        if ($hasProducts) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh produk.');
        }
        try {
            $kategori->delete();
            return redirect()->route('master.kategori')->with('success', 'Kategori berhasil dihapus.');
        } catch (QueryException $e) {
            if ((string) $e->getCode() === '23000') {
                return back()->with('error', 'Kategori tidak bisa dihapus karena masih terkait data transaksi.');
            }
            throw $e;
        }
    }
}

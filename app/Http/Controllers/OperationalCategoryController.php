<?php

namespace App\Http\Controllers;

use App\Models\OperationalCategory;
use Illuminate\Http\Request;

class OperationalCategoryController extends Controller
{
    public function index()
    {
        $categories = OperationalCategory::all();
        return view('operasional.kategori.index', compact('categories'));
    }

    public function create()
    {
        return view('operasional.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string']);
        OperationalCategory::create($request->all());
        return redirect()->route('operasional.kategori.index')->with('success', 'Kategori operasional berhasil ditambahkan.');
    }

    public function edit(OperationalCategory $kategori)
    {
        return view('operasional.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, OperationalCategory $kategori)
    {
        $request->validate(['name' => 'required|string|max:255', 'description' => 'nullable|string']);
        $kategori->update($request->all());
        return redirect()->route('operasional.kategori.index')->with('success', 'Kategori operasional berhasil diperbarui.');
    }

    public function destroy(OperationalCategory $kategori)
    {
        $kategori->delete();
        return redirect()->route('operasional.kategori.index')->with('success', 'Kategori operasional berhasil dihapus.');
    }
}

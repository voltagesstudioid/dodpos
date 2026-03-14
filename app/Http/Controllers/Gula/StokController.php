<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\GulaProduct::query()
            ->with('warehouseStocks')
            ->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where('name', 'like', '%' . $q . '%');
        }

        if ($request->filled('active')) {
            if ($request->active === '1') {
                $query->where('is_active', true);
            } elseif ($request->active === '0') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $all = (clone $query)->get();
        $totalProducts = $all->count();
        $totalKarung = 0;
        $totalBal = 0;
        $totalEceran = 0;

        foreach ($all as $product) {
            $stock = $product->warehouseStocks->first();
            $totalKarung += (int) ($stock->qty_karung ?? 0);
            $totalBal += (int) ($stock->qty_bal ?? 0);
            $totalEceran += (int) ($stock->qty_eceran ?? 0);
        }

        $products = $query->paginate(15)->withQueryString();

        return view('gula.stok.index', compact(
            'products',
            'totalProducts',
            'totalKarung',
            'totalBal',
            'totalEceran',
        ));
    }

    public function create()
    {
        return view('gula.stok.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:karungan,eceran',
            'base_price' => 'required|numeric|min:0',
            'price_karungan' => 'nullable|numeric|min:0',
            'price_eceran' => 'nullable|numeric|min:0',
            'qty_per_karung' => 'required|integer|min:1',
            'initial_qty_karung' => 'nullable|numeric|min:0',
            'initial_qty_eceran' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $product = \App\Models\GulaProduct::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'base_price' => $validated['base_price'],
                'price_karungan' => $validated['price_karungan'],
                'price_eceran' => $validated['price_eceran'],
                'qty_per_karung' => $validated['qty_per_karung'],
                'is_active' => true,
            ]);

            $product->warehouseStocks()->create([
                'qty_karung' => $validated['initial_qty_karung'] ?? 0,
                'qty_bal' => 0,
                'qty_eceran' => $validated['initial_qty_eceran'] ?? 0,
            ]);
        });

        return redirect()->route('gula.stok.index')->with('success', 'Master Produk Gula dan Stok berhasil ditambahkan.');
    }

    public function edit(\App\Models\GulaProduct $stok)
    {
        return view('gula.stok.edit', compact('stok'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\GulaProduct $stok)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'price_karungan' => 'nullable|numeric|min:0',
            'price_eceran' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        
        $stok->update($validated);
        return redirect()->route('gula.stok.index')->with('success', 'Produk Gula berhasil diupdate.');
    }

    // Adjustment Tambah Manual (Penerimaan Pabrik)
    public function destroy(\App\Models\GulaProduct $stok)
    {
        $stok->delete();
        return redirect()->route('gula.stok.index')->with('success', 'Produk dihapus.');
    }
}

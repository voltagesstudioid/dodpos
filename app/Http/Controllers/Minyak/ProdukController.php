<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakProduk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis');
        $status = $request->input('status');

        $produks = MinyakProduk::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_produk', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('jenis', 'like', "%{$search}%");
                });
            })
            ->when($jenis, function ($query) use ($jenis) {
                $query->where('jenis', $jenis);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => MinyakProduk::count(),
            'aktif' => MinyakProduk::where('status', 'aktif')->count(),
            'stok_rendah' => MinyakProduk::whereColumn('stok_gudang', '<=', 'stok_minimum')->count(),
        ];

        return view('minyak.produk.index', compact('produks', 'stats'));
    }

    public function create()
    {
        return view('minyak.produk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:0',
            'harga_modal' => 'nullable|numeric|min:0',
            'stok_gudang' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['kode_produk'] = MinyakProduk::generateKode();

        MinyakProduk::create($validated);

        return redirect()->route('minyak.produk.index')
            ->with('success', 'Data Produk berhasil ditambahkan.');
    }

    public function show(MinyakProduk $produk)
    {
        $produk->load(['loadings.sales', 'penjualans.pelanggan']);
        
        return view('minyak.produk.show', compact('produk'));
    }

    public function edit(MinyakProduk $produk)
    {
        return view('minyak.produk.edit', compact('produk'));
    }

    public function update(Request $request, MinyakProduk $produk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:0',
            'harga_modal' => 'nullable|numeric|min:0',
            'stok_gudang' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $produk->update($validated);

        return redirect()->route('minyak.produk.index')
            ->with('success', 'Data Produk berhasil diperbarui.');
    }

    public function destroy(MinyakProduk $produk)
    {
        $produk->delete();

        return redirect()->route('minyak.produk.index')
            ->with('success', 'Data Produk berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaProduk;
use App\Models\GulaJenis;
use App\Models\GulaSatuan;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis');
        $status = $request->input('status');

        $produks = GulaProduk::query()
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
            'total' => GulaProduk::count(),
            'aktif' => GulaProduk::where('status', 'aktif')->count(),
            'stok_rendah' => GulaProduk::whereColumn('stok_gudang', '<=', 'stok_minimum')->count(),
        ];

        return view('gula.produk.index', compact('produks', 'stats'));
    }

    public function create()
    {
        $jenisList = GulaJenis::aktif()->orderBy('nama')->get();
        $satuanList = GulaSatuan::aktif()->orderBy('nama')->get();
        return view('gula.produk.create', compact('jenisList', 'satuanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'nullable|string|max:50',
            'jenis_id' => 'nullable|exists:gula_jenis,id',
            'satuan' => 'required|string|max:20',
            'satuan_id' => 'nullable|exists:gula_satuan,id',
            'harga_jual' => 'required|numeric|min:0',
            'harga_modal' => 'nullable|numeric|min:0',
            'stok_gudang' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['kode_produk'] = GulaProduk::generateKode();

        // Resolve jenis/satuan name from ID if provided
        if (!empty($validated['jenis_id'])) {
            $validated['jenis'] = GulaJenis::find($validated['jenis_id'])?->nama;
        }
        if (!empty($validated['satuan_id'])) {
            $validated['satuan'] = GulaSatuan::find($validated['satuan_id'])?->nama;
        }

        GulaProduk::create($validated);

        return redirect()->route('gula.produk.index')
            ->with('success', 'Data Produk berhasil ditambahkan.');
    }

    public function show(GulaProduk $produk)
    {
        $produk->load(['loadings.sales', 'penjualans.pelanggan']);
        
        return view('gula.produk.show', compact('produk'));
    }

    public function edit(GulaProduk $produk)
    {
        $jenisList = GulaJenis::aktif()->orderBy('nama')->get();
        $satuanList = GulaSatuan::aktif()->orderBy('nama')->get();
        return view('gula.produk.edit', compact('produk', 'jenisList', 'satuanList'));
    }

    public function update(Request $request, GulaProduk $produk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis' => 'nullable|string|max:50',
            'jenis_id' => 'nullable|exists:gula_jenis,id',
            'satuan' => 'required|string|max:20',
            'satuan_id' => 'nullable|exists:gula_satuan,id',
            'harga_jual' => 'required|numeric|min:0',
            'harga_modal' => 'nullable|numeric|min:0',
            'stok_gudang' => 'nullable|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Resolve jenis/satuan name from ID if provided
        $updateData = $validated;
        if (!empty($validated['jenis_id'])) {
            $updateData['jenis'] = GulaJenis::find($validated['jenis_id'])?->nama;
        }
        if (!empty($validated['satuan_id'])) {
            $updateData['satuan'] = GulaSatuan::find($validated['satuan_id'])?->nama;
        }

        $produk->update($updateData);

        return redirect()->route('gula.produk.index')
            ->with('success', 'Data Produk berhasil diperbarui.');
    }

    public function destroy(GulaProduk $produk)
    {
        $produk->delete();

        return redirect()->route('gula.produk.index')
            ->with('success', 'Data Produk berhasil dihapus.');
    }
}

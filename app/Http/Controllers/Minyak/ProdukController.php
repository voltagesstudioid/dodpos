<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakProduk;
use App\Models\MinyakJenis;
use App\Models\MinyakSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }
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
        ];

        $jenisList = MinyakJenis::orderBy('urutan')->orderBy('nama')->get();
        $isSalesRole = $this->isSales();

        return view('minyak.produk.index', compact('produks', 'stats', 'jenisList', 'isSalesRole'));
    }

    public function create()
    {
        $jenisList = MinyakJenis::getAktifList();
        $satuanList = MinyakSatuan::getAktifList();
        return view('minyak.produk.create', compact('jenisList', 'satuanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:minyak_produk,nama',
            'jenis' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:1',
            'harga_modal' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if (!empty($validated['harga_modal']) && (float) $validated['harga_modal'] > 0) {
            if ((float) $validated['harga_jual'] < (float) $validated['harga_modal']) {
                return back()->withErrors(['harga_jual' => 'Harga jual tidak boleh lebih rendah dari harga modal (HPP).'])
                    ->withInput();
            }
        }

        $validated['kode_produk'] = MinyakProduk::generateKode();

        MinyakProduk::create($validated);

        return redirect()->route('minyak.produk.index')
            ->with('success', 'Data Produk berhasil ditambahkan.');
    }

    public function show(MinyakProduk $produk)
    {
        $produk->load(['penjualans.pelanggan']);
        
        return view('minyak.produk.show', compact('produk'));
    }

    public function edit(MinyakProduk $produk)
    {
        $jenisList = MinyakJenis::getAktifList();
        $satuanList = MinyakSatuan::getAktifList();
        return view('minyak.produk.edit', compact('produk', 'jenisList', 'satuanList'));
    }

    public function update(Request $request, MinyakProduk $produk)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:minyak_produk,nama,' . $produk->id,
            'jenis' => 'nullable|string|max:50',
            'satuan' => 'required|string|max:20',
            'harga_jual' => 'required|numeric|min:1',
            'harga_modal' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if (!empty($validated['harga_modal']) && (float) $validated['harga_modal'] > 0) {
            if ((float) $validated['harga_jual'] < (float) $validated['harga_modal']) {
                return back()->withErrors(['harga_jual' => 'Harga jual tidak boleh lebih rendah dari harga modal (HPP).'])
                    ->withInput();
            }
        }

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

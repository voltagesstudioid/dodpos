<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralProduk;
use App\Models\MineralJenis;
use App\Models\MineralSatuan;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $jenis = $request->input('jenis');
        $status = $request->input('status');

        $produks = MineralProduk::query()
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
            'total'       => MineralProduk::count(),
            'aktif'       => MineralProduk::where('status', 'aktif')->count(),
            'stok_rendah' => MineralProduk::whereColumn('stok_gudang', '<=', 'stok_minimum')
                            ->where('stok_minimum', '>', 0)
                            ->where('status', 'aktif')
                            ->count(),
        ];

        return view('mineral.produk.index', compact('produks', 'stats'));
    }

    public function create()
    {
        $jenisList = MineralJenis::getAktifList();
        $satuanList = MineralSatuan::getAktifList();
        return view('mineral.produk.create', compact('jenisList', 'satuanList'));
    }

    public function store(Request $request)
    {
        $activeJenis = MineralJenis::where('status', 'aktif')->pluck('nama')->toArray();
        $activeSatuan = MineralSatuan::where('status', 'aktif')->pluck('nama')->toArray();

        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:100', Rule::unique('mineral_produk', 'nama')->whereNull('deleted_at')],
            'jenis'       => ['nullable', 'string', Rule::in($activeJenis)],
            'satuan'      => ['required', 'string', Rule::in($activeSatuan)],
            'harga_jual'  => ['required', 'numeric', 'min:0'],
            'harga_modal' => ['nullable', 'numeric', 'min:0'],
            'stok_minimum' => ['nullable', 'integer', 'min:0'],
            'keterangan'   => ['nullable', 'string', 'max:500'],
            'status'       => ['required', 'in:aktif,nonaktif'],
        ]);

        // Apply safe defaults for nullable fields
        $validated['stok_gudang']  = 0;
        $validated['stok_minimum'] = $validated['stok_minimum'] ?? 10;
        $validated['harga_modal']  = $validated['harga_modal']  ?? 0;

        // Generate auto kode
        $validated['kode_produk'] = MineralProduk::generateKode();

        $produk = MineralProduk::create($validated);

        AuditService::log(
            'mineral_produk.create',
            'MineralProduk',
            $produk->id,
            ['nama' => $produk->nama, 'kode' => $produk->kode_produk],
            'info'
        );

        return redirect()->route('mineral.produk.index')
            ->with('success', 'Produk "' . $produk->nama . '" berhasil ditambahkan.');
    }

    public function show(MineralProduk $produk)
    {
        $produk->load(['loadings.sales', 'penjualans.pelanggan']);

        return view('mineral.produk.show', compact('produk'));
    }

    public function edit(MineralProduk $produk)
    {
        $jenisList = MineralJenis::getAktifList();
        $satuanList = MineralSatuan::getAktifList();
        return view('mineral.produk.edit', compact('produk', 'jenisList', 'satuanList'));
    }

    public function update(Request $request, MineralProduk $produk)
    {
        $activeJenis = MineralJenis::where('status', 'aktif')->pluck('nama')->toArray();
        $activeSatuan = MineralSatuan::where('status', 'aktif')->pluck('nama')->toArray();

        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:100', Rule::unique('mineral_produk', 'nama')->whereNull('deleted_at')->ignore($produk->id)],
            'jenis'       => ['nullable', 'string', Rule::in($activeJenis)],
            'satuan'      => ['required', 'string', Rule::in($activeSatuan)],
            'harga_jual'  => ['required', 'numeric', 'min:0'],
            'harga_modal' => ['nullable', 'numeric', 'min:0'],
            'stok_minimum' => ['nullable', 'integer', 'min:0'],
            'keterangan'   => ['nullable', 'string', 'max:500'],
            'status'       => ['required', 'in:aktif,nonaktif'],
        ]);

        // Apply safe defaults for nullable fields
        $validated['stok_minimum'] = $validated['stok_minimum'] ?? 10;
        $validated['harga_modal']  = $validated['harga_modal']  ?? 0;

        $produk->update($validated);

        AuditService::log(
            'mineral_produk.update',
            'MineralProduk',
            $produk->id,
            ['nama' => $produk->nama, 'kode' => $produk->kode_produk],
            'info'
        );

        return redirect()->route('mineral.produk.index')
            ->with('success', 'Produk "' . $produk->nama . '" berhasil diperbarui.');
    }

    public function destroy(MineralProduk $produk)
    {
        // Prevent deletion if product has related transactions
        $hasLoadings   = $produk->loadings()->exists();
        $hasPenjualan = $produk->penjualans()->exists();

        if ($hasLoadings || $hasPenjualan) {
            return back()->with('error', 'Produk "' . $produk->nama . '" tidak bisa dihapus karena sudah memiliki transaksi loading atau penjualan.');
        }

        $nama  = $produk->nama;
        $kode  = $produk->kode_produk;
        $produk->delete();

        AuditService::log(
            'mineral_produk.delete',
            'MineralProduk',
            $produk->id,
            ['nama' => $nama, 'kode' => $kode],
            'warning'
        );

        return redirect()->route('mineral.produk.index')
            ->with('success', 'Produk "' . $nama . '" berhasil dihapus.');
    }
}

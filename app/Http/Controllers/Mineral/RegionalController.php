<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralRegional;
use App\Models\MineralRegionalHarga;
use App\Models\MineralProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $regionals = MineralRegional::query()
            ->withCount(['sales', 'pelanggans'])
            ->when($search, function ($q) use ($search) {
                $q->where('kode_regional', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => MineralRegional::count(),
            'aktif' => MineralRegional::where('status', 'aktif')->count(),
        ];

        return view('mineral.regional.index', compact('regionals', 'stats'));
    }

    public function create()
    {
        $produks = MineralProduk::where('status', 'aktif')->orderBy('nama')->get();
        return view('mineral.regional.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $validated['kode_regional'] = MineralRegional::generateKode();

            $regional = MineralRegional::create([
                'kode_regional' => $validated['kode_regional'],
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'status' => $validated['status'],
            ]);

            // Save regional prices
            if (!empty($validated['harga'])) {
                foreach ($validated['harga'] as $produkId => $harga) {
                    if ($harga !== null && $harga !== '' && (float) $harga > 0) {
                        MineralRegionalHarga::create([
                            'regional_id' => $regional->id,
                            'produk_id' => $produkId,
                            'harga_jual' => $harga,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('mineral.regional.index')
                ->with('success', 'Regional berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan regional: ' . $e->getMessage());
        }
    }

    public function show(MineralRegional $regional)
    {
        $regional->load(['hargaProduk.produk', 'sales', 'pelanggans']);

        $produks = MineralProduk::where('status', 'aktif')->orderBy('nama')->get();

        return view('mineral.regional.show', compact('regional', 'produks'));
    }

    public function edit(MineralRegional $regional)
    {
        $regional->load('hargaProduk');
        $produks = MineralProduk::where('status', 'aktif')->orderBy('nama')->get();

        return view('mineral.regional.edit', compact('regional', 'produks'));
    }

    public function update(Request $request, MineralRegional $regional)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $regional->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'status' => $validated['status'],
            ]);

            // Delete existing harga and recreate
            MineralRegionalHarga::where('regional_id', $regional->id)->delete();

            if (!empty($validated['harga'])) {
                foreach ($validated['harga'] as $produkId => $harga) {
                    if ($harga !== null && $harga !== '' && (float) $harga > 0) {
                        MineralRegionalHarga::create([
                            'regional_id' => $regional->id,
                            'produk_id' => $produkId,
                            'harga_jual' => $harga,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('mineral.regional.index')
                ->with('success', 'Regional berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui regional: ' . $e->getMessage());
        }
    }

    public function destroy(MineralRegional $regional)
    {
        if ($regional->sales()->where('status', 'aktif')->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus regional yang masih memiliki sales aktif.');
        }

        $regional->delete();

        return redirect()->route('mineral.regional.index')
            ->with('success', 'Regional berhasil dihapus.');
    }
}

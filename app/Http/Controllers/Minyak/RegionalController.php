<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakRegional;
use App\Models\MinyakRegionalHarga;
use App\Models\MinyakProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegionalController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $regionals = MinyakRegional::query()
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
            'total' => MinyakRegional::count(),
            'aktif' => MinyakRegional::where('status', 'aktif')->count(),
        ];

        return view('minyak.regional.index', compact('regionals', 'stats'));
    }

    public function create()
    {
        $produks = MinyakProduk::where('status', 'aktif')->orderBy('nama')->get();
        return view('minyak.regional.create', compact('produks'));
    }

    public function store(Request $request)
    {
        if ($request->has('harga') && is_array($request->harga)) {
            $harga = $request->harga;
            foreach ($harga as $k => $v) {
                if ($v) $harga[$k] = str_replace(['.', ','], ['', '.'], $v);
            }
            $request->merge(['harga' => $harga]);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
            'harga' => 'nullable|array',
            'harga.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $validated['kode_regional'] = MinyakRegional::generateKode();

            $regional = MinyakRegional::create([
                'kode_regional' => $validated['kode_regional'],
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'status' => $validated['status'],
            ]);

            // Save regional prices
            if (!empty($validated['harga'])) {
                foreach ($validated['harga'] as $produkId => $harga) {
                    if ($harga !== null && $harga !== '' && (float) $harga > 0) {
                        MinyakRegionalHarga::create([
                            'regional_id' => $regional->id,
                            'produk_id' => $produkId,
                            'harga_jual' => $harga,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('minyak.regional.index')
                ->with('success', 'Regional berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Gagal menambahkan regional: ' . $e->getMessage());
        }
    }

    public function show(MinyakRegional $regional)
    {
        $regional->load(['hargaProduk.produk', 'sales', 'pelanggans']);

        $produks = MinyakProduk::where('status', 'aktif')->orderBy('nama')->get();

        return view('minyak.regional.show', compact('regional', 'produks'));
    }

    public function edit(MinyakRegional $regional)
    {
        $regional->load('hargaProduk');
        $produks = MinyakProduk::where('status', 'aktif')->orderBy('nama')->get();

        return view('minyak.regional.edit', compact('regional', 'produks'));
    }

    public function update(Request $request, MinyakRegional $regional)
    {
        if ($request->has('harga') && is_array($request->harga)) {
            $harga = $request->harga;
            foreach ($harga as $k => $v) {
                if ($v) $harga[$k] = str_replace(['.', ','], ['', '.'], $v);
            }
            $request->merge(['harga' => $harga]);
        }

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
            MinyakRegionalHarga::where('regional_id', $regional->id)->delete();

            if (!empty($validated['harga'])) {
                foreach ($validated['harga'] as $produkId => $harga) {
                    if ($harga !== null && $harga !== '' && (float) $harga > 0) {
                        MinyakRegionalHarga::create([
                            'regional_id' => $regional->id,
                            'produk_id' => $produkId,
                            'harga_jual' => $harga,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('minyak.regional.index')
                ->with('success', 'Regional berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Gagal memperbarui regional: ' . $e->getMessage());
        }
    }

    public function destroy(MinyakRegional $regional)
    {
        // Check if has active sales
        if ($regional->sales()->where('status', 'aktif')->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus regional yang masih memiliki sales aktif.');
        }

        // Check if has pelanggan
        if ($regional->pelanggans()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus regional yang masih memiliki pelanggan.');
        }

        $regional->delete();

        return redirect()->route('minyak.regional.index')
            ->with('success', 'Regional berhasil dihapus.');
    }
}

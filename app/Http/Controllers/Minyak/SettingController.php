<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakJenis;
use App\Models\MinyakSatuan;
use App\Models\MinyakProduk;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $jenisList = MinyakJenis::orderBy('urutan')->orderBy('nama')->get();
        $satuanList = MinyakSatuan::orderBy('urutan')->orderBy('nama')->get();

        return view('minyak.setting.index', compact('jenisList', 'satuanList'));
    }

    // ===================== JENIS =====================

    public function storeJenis(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:minyak_jenis,nama',
        ]);

        $maxUrutan = MinyakJenis::max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;
        $validated['status'] = 'aktif';

        MinyakJenis::create($validated);

        return redirect()->route('minyak.setting.index')
            ->with('success', 'Jenis produk "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function updateJenis(Request $request, MinyakJenis $jenis)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:minyak_jenis,nama,' . $jenis->id,
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jenis->update($validated);

        return redirect()->route('minyak.setting.index')
            ->with('success', 'Jenis produk berhasil diperbarui.');
    }

    public function destroyJenis(MinyakJenis $jenis)
    {
        // Check if used by any produk
        $usedCount = MinyakProduk::where('jenis', $jenis->nama)->count();
        if ($usedCount > 0) {
            return redirect()->route('minyak.setting.index')
                ->with('error', 'Jenis "' . $jenis->nama . '" tidak bisa dihapus karena masih digunakan oleh ' . $usedCount . ' produk.');
        }

        $jenis->delete();

        return redirect()->route('minyak.setting.index')
            ->with('success', 'Jenis produk berhasil dihapus.');
    }

    // ===================== SATUAN =====================

    public function storeSatuan(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:30|unique:minyak_satuan,nama',
            'singkatan' => 'nullable|string|max:10',
        ]);

        $maxUrutan = MinyakSatuan::max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;
        $validated['status'] = 'aktif';

        MinyakSatuan::create($validated);

        return redirect()->route('minyak.setting.index')
            ->with('success', 'Satuan "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function updateSatuan(Request $request, MinyakSatuan $satuan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:30|unique:minyak_satuan,nama,' . $satuan->id,
            'singkatan' => 'nullable|string|max:10',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $satuan->update($validated);

        return redirect()->route('minyak.setting.index')
            ->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroySatuan(MinyakSatuan $satuan)
    {
        // Check if used by any produk
        $usedCount = MinyakProduk::where('satuan', $satuan->nama)->count();
        if ($usedCount > 0) {
            return redirect()->route('minyak.setting.index')
                ->with('error', 'Satuan "' . $satuan->nama . '" tidak bisa dihapus karena masih digunakan oleh ' . $usedCount . ' produk.');
        }

        $satuan->delete();

        return redirect()->route('minyak.setting.index')
            ->with('success', 'Satuan berhasil dihapus.');
    }
}

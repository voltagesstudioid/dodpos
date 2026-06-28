<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralJenis;
use App\Models\MineralSatuan;
use App\Models\MineralProduk;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $jenisList = MineralJenis::orderBy('urutan')->orderBy('nama')->get();
        $satuanList = MineralSatuan::orderBy('urutan')->orderBy('nama')->get();

        return view('mineral.setting.index', compact('jenisList', 'satuanList'));
    }

    // ===================== JENIS =====================

    public function storeJenis(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:mineral_jenis,nama',
        ]);

        $maxUrutan = MineralJenis::max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;
        $validated['status'] = 'aktif';

        MineralJenis::create($validated);

        return redirect()->route('mineral.setting.index')
            ->with('success', 'Jenis produk "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function updateJenis(Request $request, MineralJenis $jenis)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:mineral_jenis,nama,' . $jenis->id,
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jenis->update($validated);

        return redirect()->route('mineral.setting.index')
            ->with('success', 'Jenis produk berhasil diperbarui.');
    }

    public function destroyJenis(MineralJenis $jenis)
    {
        $usedCount = MineralProduk::where('jenis', $jenis->nama)->count();
        if ($usedCount > 0) {
            return redirect()->route('mineral.setting.index')
                ->with('error', 'Jenis "' . $jenis->nama . '" tidak bisa dihapus karena masih digunakan oleh ' . $usedCount . ' produk.');
        }

        $jenis->delete();

        return redirect()->route('mineral.setting.index')
            ->with('success', 'Jenis produk berhasil dihapus.');
    }

    // ===================== SATUAN =====================

    public function storeSatuan(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:30|unique:mineral_satuan,nama',
            'singkatan' => 'nullable|string|max:10',
        ]);

        $maxUrutan = MineralSatuan::max('urutan') ?? 0;
        $validated['urutan'] = $maxUrutan + 1;
        $validated['status'] = 'aktif';

        MineralSatuan::create($validated);

        return redirect()->route('mineral.setting.index')
            ->with('success', 'Satuan "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function updateSatuan(Request $request, MineralSatuan $satuan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:30|unique:mineral_satuan,nama,' . $satuan->id,
            'singkatan' => 'nullable|string|max:10',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $oldNama = $satuan->nama;
        $satuan->update($validated);

        // Sync satuan string on all products referencing this satuan_id
        if ($oldNama !== $validated['nama']) {
            MineralProduk::where('satuan_id', $satuan->id)
                ->where('satuan', $oldNama)
                ->update(['satuan' => $validated['nama']]);
        }

        return redirect()->route('mineral.setting.index')
            ->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroySatuan(MineralSatuan $satuan)
    {
        $usedCount = MineralProduk::where('satuan_id', $satuan->id)->count();
        if ($usedCount > 0) {
            return redirect()->route('mineral.setting.index')
                ->with('error', 'Satuan "' . $satuan->nama . '" tidak bisa dihapus karena masih digunakan oleh ' . $usedCount . ' produk.');
        }

        $satuan->delete();

        return redirect()->route('mineral.setting.index')
            ->with('success', 'Satuan berhasil dihapus.');
    }
}

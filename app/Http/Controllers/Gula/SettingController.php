<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaJenis;
use App\Models\GulaSatuan;
use App\Models\GulaProduk;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $jenisList = GulaJenis::orderBy('nama')->get();
        $satuanList = GulaSatuan::orderBy('nama')->get();

        return view('gula.setting.index', compact('jenisList', 'satuanList'));
    }

    // ===================== JENIS =====================

    public function storeJenis(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:gula_jenis,nama',
        ]);

        $validated['status'] = 'aktif';

        try {
            GulaJenis::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Gagal menambahkan jenis: ' . $e->getMessage());
        }

        return redirect()->route('gula.setting.index')
            ->with('success', 'Jenis produk "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function updateJenis(Request $request, GulaJenis $jenis)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50|unique:gula_jenis,nama,' . $jenis->id,
            'status' => 'required|in:aktif,nonaktif',
        ]);

        try {
            $jenis->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Gagal memperbarui jenis: ' . $e->getMessage());
        }

        return redirect()->route('gula.setting.index')
            ->with('success', 'Jenis produk berhasil diperbarui.');
    }

    public function destroyJenis(GulaJenis $jenis)
    {
        $usedCount = $jenis->produks()->count();
        if ($usedCount > 0) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Jenis "' . $jenis->nama . '" tidak bisa dihapus karena masih digunakan oleh ' . $usedCount . ' produk.');
        }

        try {
            $jenis->delete();
        } catch (\Exception $e) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Gagal menghapus jenis: ' . $e->getMessage());
        }

        return redirect()->route('gula.setting.index')
            ->with('success', 'Jenis produk berhasil dihapus.');
    }

    // ===================== SATUAN =====================

    public function storeSatuan(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:30|unique:gula_satuan,nama',
            'singkatan' => 'nullable|string|max:10',
        ]);

        $validated['status'] = 'aktif';

        try {
            GulaSatuan::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Gagal menambahkan satuan: ' . $e->getMessage());
        }

        return redirect()->route('gula.setting.index')
            ->with('success', 'Satuan "' . $validated['nama'] . '" berhasil ditambahkan.');
    }

    public function updateSatuan(Request $request, GulaSatuan $satuan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:30|unique:gula_satuan,nama,' . $satuan->id,
            'singkatan' => 'nullable|string|max:10',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        try {
            $satuan->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Gagal memperbarui satuan: ' . $e->getMessage());
        }

        return redirect()->route('gula.setting.index')
            ->with('success', 'Satuan berhasil diperbarui.');
    }

    public function destroySatuan(GulaSatuan $satuan)
    {
        $usedCount = $satuan->produks()->count();
        if ($usedCount > 0) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Satuan "' . $satuan->nama . '" tidak bisa dihapus karena masih digunakan oleh ' . $usedCount . ' produk.');
        }

        try {
            $satuan->delete();
        } catch (\Exception $e) {
            return redirect()->route('gula.setting.index')
                ->with('error', 'Gagal menghapus satuan: ' . $e->getMessage());
        }

        return redirect()->route('gula.setting.index')
            ->with('success', 'Satuan berhasil dihapus.');
    }
}

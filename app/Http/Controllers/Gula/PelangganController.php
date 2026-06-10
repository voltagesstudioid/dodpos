<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaPelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $status = $request->input('status');

        $pelanggans = GulaPelanggan::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_pelanggan', 'like', "%{$search}%")
                        ->orWhere('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($tipe, function ($query) use ($tipe) {
                $query->where('tipe', $tipe);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => GulaPelanggan::count(),
            'aktif' => GulaPelanggan::where('status', 'aktif')->count(),
            'eceran' => GulaPelanggan::where('tipe', 'eceran')->count(),
            'grosir' => GulaPelanggan::where('tipe', 'grosir')->count(),
            'total_hutang' => GulaPelanggan::sum('total_hutang'),
        ];

        return view('gula.pelanggan.index', compact('pelanggans', 'stats'));
    }

    public function create()
    {
        return view('gula.pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kecamatan' => 'nullable|string|max:50',
            'kota' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tipe' => 'required|in:eceran,grosir,agen',
            'limit_hutang' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif,blacklist',
        ]);

        $validated['kode_pelanggan'] = GulaPelanggan::generateKode();
        $validated['total_hutang'] = 0;

        GulaPelanggan::create($validated);

        return redirect()->route('gula.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    public function show(GulaPelanggan $pelanggan)
    {
        $pelanggan->load(['penjualans.sales', 'hutangs.penjualan', 'kunjungans.sales']);
        
        return view('gula.pelanggan.show', compact('pelanggan'));
    }

    public function edit(GulaPelanggan $pelanggan)
    {
        return view('gula.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, GulaPelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kecamatan' => 'nullable|string|max:50',
            'kota' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tipe' => 'required|in:eceran,grosir,agen',
            'limit_hutang' => 'nullable|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif,blacklist',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('gula.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(GulaPelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('gula.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil dihapus.');
    }
}

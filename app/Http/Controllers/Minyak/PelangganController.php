<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakPelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $status = $request->input('status');

        $pelanggans = MinyakPelanggan::query()
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
            'total' => MinyakPelanggan::count(),
            'aktif' => MinyakPelanggan::where('status', 'aktif')->count(),
            'eceran' => MinyakPelanggan::where('tipe', 'eceran')->count(),
            'grosir' => MinyakPelanggan::where('tipe', 'grosir')->count(),
            'total_hutang' => MinyakPelanggan::sum('total_hutang'),
        ];

        return view('minyak.pelanggan.index', compact('pelanggans', 'stats'));
    }

    public function create()
    {
        return view('minyak.pelanggan.create');
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

        $validated['kode_pelanggan'] = MinyakPelanggan::generateKode();
        $validated['total_hutang'] = 0;

        MinyakPelanggan::create($validated);

        return redirect()->route('minyak.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    public function show(MinyakPelanggan $pelanggan)
    {
        $pelanggan->load(['penjualans.sales', 'hutangs.penjualan', 'kunjungans.sales']);
        
        return view('minyak.pelanggan.show', compact('pelanggan'));
    }

    public function edit(MinyakPelanggan $pelanggan)
    {
        return view('minyak.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, MinyakPelanggan $pelanggan)
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

        return redirect()->route('minyak.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(MinyakPelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('minyak.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil dihapus.');
    }
}

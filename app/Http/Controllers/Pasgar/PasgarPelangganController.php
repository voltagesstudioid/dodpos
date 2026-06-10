<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarPelanggan;
use App\Models\PasgarSales;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasgarPelangganController extends Controller
{
    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    /**
     * Get the logged-in user's PasgarSales profile.
     */
    private function getSalesProfile(): ?PasgarSales
    {
        return PasgarSales::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $status = $request->input('status');

        $isSalesRole = $this->isSales();

        $query = PasgarPelanggan::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('kode_pelanggan', 'like', "%{$search}%")
                        ->orWhere('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($tipe, fn ($q) => $q->where('tipe', $tipe))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('nama_toko', 'asc');

        $pelanggans = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => PasgarPelanggan::count(),
            'aktif' => PasgarPelanggan::where('status', 'aktif')->count(),
            'warung' => PasgarPelanggan::where('tipe', 'warung')->count(),
            'toko' => PasgarPelanggan::where('tipe', 'toko')->count(),
            'kios' => PasgarPelanggan::where('tipe', 'kios')->count(),
        ];

        return view('pasgar.pelanggan.index', compact(
            'pelanggans', 'stats', 'isSalesRole'
        ));
    }

    public function create()
    {
        $isSalesRole = $this->isSales();
        $salesProfile = $isSalesRole ? $this->getSalesProfile() : null;

        return view('pasgar.pelanggan.create', compact('isSalesRole', 'salesProfile'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_toko' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kecamatan' => 'nullable|string|max:50',
            'kota' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tipe' => 'required|in:warung,toko,kios',
            'foto_toko' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];

        if (!$this->isSales()) {
            $rules['status'] = 'required|in:aktif,nonaktif,blacklist';
        }

        $validated = $request->validate($rules);

        // Upload foto toko
        if ($request->hasFile('foto_toko')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('foto_toko'),
                    'pelanggan/pasgar',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $validated['foto_toko'] = $upload['path'];
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal upload foto: ' . $e->getMessage());
            }
        }

        $validated['kode_pelanggan'] = PasgarPelanggan::generateKode();

        // Force defaults for sales role
        if ($this->isSales()) {
            $validated['status'] = 'aktif';
        }

        PasgarPelanggan::create($validated);

        return redirect()->route('pasgar.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    public function show(PasgarPelanggan $pelanggan)
    {
        $pelanggan->load([
            'penjualans' => fn ($q) => $q->with(['sales', 'items.product'])->orderBy('tanggal', 'desc')->limit(20),
        ]);

        $isSalesRole = $this->isSales();

        return view('pasgar.pelanggan.show', compact('pelanggan', 'isSalesRole'));
    }

    public function edit(PasgarPelanggan $pelanggan)
    {
        $isSalesRole = $this->isSales();
        $salesProfile = $isSalesRole ? $this->getSalesProfile() : null;

        return view('pasgar.pelanggan.edit', compact('pelanggan', 'isSalesRole', 'salesProfile'));
    }

    public function update(Request $request, PasgarPelanggan $pelanggan)
    {
        $rules = [
            'nama_toko' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'kecamatan' => 'nullable|string|max:50',
            'kota' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'tipe' => 'required|in:warung,toko,kios',
            'foto_toko' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];

        if (!$this->isSales()) {
            $rules['status'] = 'required|in:aktif,nonaktif,blacklist';
        }

        $validated = $request->validate($rules);

        // Upload foto toko if provided
        if ($request->hasFile('foto_toko')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('foto_toko'),
                    'pelanggan/pasgar',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $validated['foto_toko'] = $upload['path'];
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal upload foto: ' . $e->getMessage());
            }
        }

        $pelanggan->update($validated);

        return redirect()->route('pasgar.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(PasgarPelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pasgar.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil dihapus.');
    }
}

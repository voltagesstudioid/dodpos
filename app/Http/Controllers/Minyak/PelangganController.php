<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakPelanggan;
use App\Models\MinyakRegional;
use App\Models\MinyakSales;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    private function getSalesProfile(): ?MinyakSales
    {
        return MinyakSales::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $status = $request->input('status');
        $regionalId = $request->input('regional_id');

        $isSalesRole = $this->isSales();
        $salesProfile = $isSalesRole ? $this->getSalesProfile() : null;

        $query = MinyakPelanggan::with(['regional'])
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
            ->when($isSalesRole, function ($q) use ($salesProfile) {
                if ($salesProfile && $salesProfile->regional_id) {
                    $q->where('regional_id', $salesProfile->regional_id);
                } else {
                    $q->whereNull('regional_id');
                }
            })
            ->when(!$isSalesRole && $regionalId, fn ($q) => $q->where('regional_id', $regionalId))
            ->orderBy('nama_toko', 'asc');

        $allPelanggans = $query->get();

        // Group by regional
        $grouped = $allPelanggans->groupBy(function ($p) {
            return $p->regional_id ? $p->regional->nama : 'Tanpa Regional';
        });

        // Sort groups: named regionals alphabetically, "Tanpa Regional" last
        $sortedKeys = $grouped->keys()->sort(function ($a, $b) {
            if ($a === 'Tanpa Regional') return 1;
            if ($b === 'Tanpa Regional') return -1;
            return strcmp($a, $b);
        });
        $grouped = $sortedKeys->mapWithKeys(fn ($key) => [$key => $grouped[$key]]);

        $regionals = MinyakRegional::where('status', 'aktif')->orderBy('nama')->get();

        // Stats scoped to sales' regional if applicable
        $statsQuery = MinyakPelanggan::query();
        if ($isSalesRole) {
            if ($salesProfile && $salesProfile->regional_id) {
                $statsQuery->where('regional_id', $salesProfile->regional_id);
            } else {
                $statsQuery->whereNull('regional_id');
            }
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'aktif' => (clone $statsQuery)->where('status', 'aktif')->count(),
            'eceran' => (clone $statsQuery)->where('tipe', 'eceran')->count(),
            'grosir' => (clone $statsQuery)->where('tipe', 'grosir')->count(),
        ];

        // Hide total_hutang from sales role
        if (!$isSalesRole) {
            $stats['total_hutang'] = MinyakPelanggan::sum('total_hutang');
        }

        // Per-regional stats for summary
        $regionalStats = MinyakRegional::where('status', 'aktif')
            ->withCount(['pelanggans as pelanggan_count' => fn ($q) => $q->where('status', 'aktif')])
            ->withSum(['pelanggans as hutang_sum' => fn ($q) => $q->where('status', 'aktif')], 'total_hutang')
            ->orderBy('nama')
            ->get();

        return view('minyak.pelanggan.index', compact('grouped', 'regionals', 'stats', 'regionalStats', 'isSalesRole'));
    }

    public function create()
    {
        $isSalesRole = $this->isSales();
        $regionals = MinyakRegional::aktif()->orderBy('nama')->get();
        $kotaList = MinyakPelanggan::distinct()->whereNotNull('kota')->orderBy('kota')->pluck('kota');
        $kecamatanList = MinyakPelanggan::distinct()->whereNotNull('kecamatan')->orderBy('kecamatan')->pluck('kecamatan');
        return view('minyak.pelanggan.create', compact('isSalesRole', 'regionals', 'kotaList', 'kecamatanList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'regional_id' => 'nullable|exists:minyak_regional,id',
            'nama_toko' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:50',
            'kota' => 'required|string|max:50',
            'provinsi' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'tipe' => 'required|in:eceran,grosir,agen',
        ];

        // Photo required for sales (supervisor needs it to assess credit limit)
        if ($this->isSales()) {
            $rules['foto_toko'] = 'required|image|mimes:jpeg,jpg,png,webp|max:4096';
            $rules['foto_toko_dalam'] = 'required|image|mimes:jpeg,jpg,png,webp|max:4096';
        } else {
            $rules['foto_toko'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096';
            $rules['foto_toko_dalam'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096';
            $rules['limit_hutang'] = 'nullable|numeric|min:0';
            $rules['status'] = 'required|in:aktif,nonaktif,blacklist';
        }

        $validated = $request->validate($rules);

        // Upload foto toko
        foreach (['foto_toko', 'foto_toko_dalam'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                try {
                    $upload = FileUploadService::uploadImage(
                        $request->file($fotoField),
                        'pelanggan/minyak',
                        'public',
                        ['max_width' => 1200, 'max_height' => 1200]
                    );
                    $validated[$fotoField] = $upload['path'];
                } catch (\Exception $e) {
                    return back()->withInput()->with('error', 'Gagal upload ' . $fotoField . ': ' . $e->getMessage());
                }
            }
        }

        $validated['kode_pelanggan'] = MinyakPelanggan::generateKode();
        $validated['total_hutang'] = 0;

        // Force defaults for sales role
        if ($this->isSales()) {
            $validated['status'] = 'aktif';
            $validated['limit_hutang'] = 0;

            // Auto-assign regional from sales profile
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id) {
                $validated['regional_id'] = $salesProfile->regional_id;
            }
        }

        MinyakPelanggan::create($validated);

        return redirect()->route('minyak.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    public function show(MinyakPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('minyak.pelanggan.index')->with('error', 'Anda tidak berhak melihat pelanggan ini.');
            }
        }

        $pelanggan->load(['penjualans.sales', 'hutangs.penjualan', 'kunjungans.sales']);
        $isSalesRole = $this->isSales();
        
        return view('minyak.pelanggan.show', compact('pelanggan', 'isSalesRole'));
    }

    public function edit(MinyakPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('minyak.pelanggan.index')->with('error', 'Anda tidak berhak mengedit pelanggan ini.');
            }
        }

        $regionals = MinyakRegional::aktif()->orderBy('nama')->get();
        $isSalesRole = $this->isSales();
        $kotaList = MinyakPelanggan::distinct()->whereNotNull('kota')->orderBy('kota')->pluck('kota');
        $kecamatanList = MinyakPelanggan::distinct()->whereNotNull('kecamatan')->orderBy('kecamatan')->pluck('kecamatan');
        return view('minyak.pelanggan.edit', compact('pelanggan', 'regionals', 'isSalesRole', 'kotaList', 'kecamatanList'));
    }

    public function update(Request $request, MinyakPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('minyak.pelanggan.index')->with('error', 'Anda tidak berhak mengedit pelanggan ini.');
            }
        }

        $rules = [
            'regional_id' => 'nullable|exists:minyak_regional,id',
            'nama_toko' => 'required|string|max:100',
            'nama_pemilik' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'alamat' => 'required|string',
            'kecamatan' => 'required|string|max:50',
            'kota' => 'required|string|max:50',
            'provinsi' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'tipe' => 'required|in:eceran,grosir,agen',
            'foto_toko' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            'foto_toko_dalam' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];

        if (!$this->isSales()) {
            $rules['limit_hutang'] = 'nullable|numeric|min:0';
            $rules['status'] = 'required|in:aktif,nonaktif,blacklist';
        }

        $validated = $request->validate($rules);

        // Upload foto if provided
        foreach (['foto_toko', 'foto_toko_dalam'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                try {
                    $upload = FileUploadService::uploadImage(
                        $request->file($fotoField),
                        'pelanggan/minyak',
                        'public',
                        ['max_width' => 1200, 'max_height' => 1200]
                    );
                    $validated[$fotoField] = $upload['path'];
                } catch (\Exception $e) {
                    return back()->withInput()->with('error', 'Gagal upload ' . $fotoField . ': ' . $e->getMessage());
                }
            }
        }

        $pelanggan->update($validated);

        return redirect()->route('minyak.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(MinyakPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('minyak.pelanggan.index')->with('error', 'Anda tidak berhak menghapus pelanggan ini.');
            }
        }

        $pelanggan->delete();

        return redirect()->route('minyak.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil dihapus.');
    }

}

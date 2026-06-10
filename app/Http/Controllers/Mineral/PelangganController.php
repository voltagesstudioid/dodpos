<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralPelanggan;
use App\Models\MineralRegional;
use App\Models\MineralSales;
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

    private function getSalesProfile(): ?MineralSales
    {
        return MineralSales::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $status = $request->input('status');
        $regionalId = $request->input('regional_id');

        $isSalesRole = $this->isSales();
        $salesProfile = $isSalesRole ? $this->getSalesProfile() : null;

        // If sales, force filter to their regional only
        if ($salesProfile && $salesProfile->regional_id) {
            $regionalId = $salesProfile->regional_id;
        }

        $query = MineralPelanggan::with('regional')
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
            ->when($regionalId, fn ($q) => $q->where('regional_id', $regionalId))
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

        $regionals = MineralRegional::where('status', 'aktif')->orderBy('nama')->get();

        // Stats scoped to sales' regional if applicable
        $statsQuery = MineralPelanggan::query();
        if ($salesProfile && $salesProfile->regional_id) {
            $statsQuery->where('regional_id', $salesProfile->regional_id);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'aktif' => (clone $statsQuery)->where('status', 'aktif')->count(),
            'eceran' => (clone $statsQuery)->where('tipe', 'eceran')->count(),
            'grosir' => (clone $statsQuery)->where('tipe', 'grosir')->count(),
            'total_hutang' => (clone $statsQuery)->sum('total_hutang'),
        ];

        // Per-regional stats for summary
        $regionalStats = MineralRegional::where('status', 'aktif')
            ->withCount(['pelanggans as pelanggan_count' => fn ($q) => $q->where('status', 'aktif')])
            ->withSum(['pelanggans as hutang_sum' => fn ($q) => $q->where('status', 'aktif')], 'total_hutang')
            ->orderBy('nama')
            ->get();

        return view('mineral.pelanggan.index', compact('grouped', 'regionals', 'stats', 'regionalStats', 'isSalesRole'));
    }

    public function create()
    {
        $regionals = MineralRegional::where('status', 'aktif')->orderBy('nama')->get();
        $isSalesRole = $this->isSales();
        return view('mineral.pelanggan.create', compact('regionals', 'isSalesRole'));
    }

    public function store(Request $request)
    {
        $rules = [
            'regional_id' => 'nullable|exists:mineral_regional,id',
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
        ];

        // Photo required for sales (supervisor needs it to assess credit limit)
        if ($this->isSales()) {
            $rules['foto_toko'] = 'required|image|mimes:jpeg,jpg,png,webp|max:4096';
        } else {
            $rules['foto_toko'] = 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096';
            $rules['limit_hutang'] = 'nullable|numeric|min:0';
            $rules['status'] = 'required|in:aktif,nonaktif,blacklist';
        }

        $validated = $request->validate($rules);

        // Upload foto toko
        if ($request->hasFile('foto_toko')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('foto_toko'),
                    'pelanggan/mineral',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $validated['foto_toko'] = $upload['path'];
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal upload foto: ' . $e->getMessage());
            }
        }

        $validated['kode_pelanggan'] = MineralPelanggan::generateKode();
        $validated['total_hutang'] = 0;
        $validated['limit_hutang'] = $validated['limit_hutang'] ?? 0;

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

        MineralPelanggan::create($validated);

        return redirect()->route('mineral.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    public function show(MineralPelanggan $pelanggan)
    {
        // Verify sales can only see their regional's pelanggan
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('mineral.pelanggan.index')->with('error', 'Anda tidak berhak melihat pelanggan ini.');
            }
        }

        $pelanggan->load([
            'penjualans' => fn ($q) => $q->with('produk')->orderBy('tanggal_jual', 'desc')->limit(20),
            'hutangs'    => fn ($q) => $q->orderBy('created_at', 'desc'),
            'kunjungans' => fn ($q) => $q->with('sales')->orderBy('waktu_checkin', 'desc')->limit(20),
        ]);

        $isSalesRole = $this->isSales();

        return view('mineral.pelanggan.show', compact('pelanggan', 'isSalesRole'));
    }

    public function edit(MineralPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('mineral.pelanggan.index')->with('error', 'Anda tidak berhak mengedit pelanggan ini.');
            }
        }

        $regionals = MineralRegional::where('status', 'aktif')->orderBy('nama')->get();
        $isSalesRole = $this->isSales();
        return view('mineral.pelanggan.edit', compact('pelanggan', 'regionals', 'isSalesRole'));
    }

    public function update(Request $request, MineralPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('mineral.pelanggan.index')->with('error', 'Anda tidak berhak mengedit pelanggan ini.');
            }
        }

        $rules = [
            'regional_id' => 'nullable|exists:mineral_regional,id',
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
            'foto_toko' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];

        $validated = $request->validate($rules);

        // Upload foto toko if provided
        if ($request->hasFile('foto_toko')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('foto_toko'),
                    'pelanggan/mineral',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $validated['foto_toko'] = $upload['path'];
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal upload foto: ' . $e->getMessage());
            }
        }

        $pelanggan->update($validated);

        return redirect()->route('mineral.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroy(MineralPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id && $pelanggan->regional_id !== $salesProfile->regional_id) {
                return redirect()->route('mineral.pelanggan.index')->with('error', 'Anda tidak berhak menghapus pelanggan ini.');
            }
        }

        $pelanggan->delete();

        return redirect()->route('mineral.pelanggan.index')
            ->with('success', 'Data Pelanggan berhasil dihapus.');
    }
}

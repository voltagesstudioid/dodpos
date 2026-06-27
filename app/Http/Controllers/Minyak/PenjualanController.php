<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakPenjualan;
use App\Models\MinyakSales;
use App\Models\MinyakPelanggan;
use App\Models\MinyakProduk;
use App\Models\MinyakLoading;
use App\Models\MinyakHutang;
use App\Models\MinyakKunjungan;
use App\Models\MinyakRegionalHarga;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    private function getSalesProfile()
    {
        return MinyakSales::where('user_id', Auth::id())->first();
    }

    /**
     * Find the active loading record for a sales+product combination.
     * Prioritize loading with sisa_stok > 0, fallback to most recent.
     */
    private function findLoadingRecord(int $salesId, int $produkId): ?MinyakLoading
    {
        // First try: loading with remaining stock
        $loading = MinyakLoading::where('sales_id', $salesId)
            ->where('produk_id', $produkId)
            ->where('sisa_stok', '>', 0)
            ->orderBy('tanggal', 'desc')
            ->first();

        if ($loading) return $loading;

        // Fallback: most recent loading for this sales+product
        return MinyakLoading::where('sales_id', $salesId)
            ->where('produk_id', $produkId)
            ->orderBy('tanggal', 'desc')
            ->first();
    }

    /**
     * Auto-update loading status to 'selesai' when all stock is sold.
     */
    private function updateLoadingStatus(MinyakLoading $loading): void
    {
        if ($loading->sisa_stok <= 0 && $loading->status !== 'selesai') {
            $loading->status = 'selesai';
            $loading->save();
        }
    }

    /**
     * Store nota photo from file upload (mobile) or base64 (desktop webcam).
     */
    private function storeNotaPhoto(Request $request): ?string
    {
        if ($request->hasFile('foto_nota')) {
            return $request->file('foto_nota')->store('nota-photos/' . now()->format('Y-m-d'), 'public');
        }

        if ($request->filled('foto_nota_base64')) {
            $base64 = $request->input('foto_nota_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $base64Data = substr($base64, strpos($base64, ',') + 1);
                $imageData = base64_decode($base64Data);
                if ($imageData !== false) {
                    $dir = 'nota-photos/' . now()->format('Y-m-d');
                    $filename = 'nota_' . uniqid() . '.jpg';
                    \Illuminate\Support\Facades\Storage::disk('public')->put($dir . '/' . $filename, $imageData);
                    return $dir . '/' . $filename;
                }
            }
        }

        return null;
    }

    /**
     * Store bukti transfer photo from file upload or base64.
     */
    private function storeTransferPhoto(Request $request): ?string
    {
        if ($request->hasFile('bukti_transfer')) {
            return $request->file('bukti_transfer')->store('transfer-photos/' . now()->format('Y-m-d'), 'public');
        }

        if ($request->filled('bukti_transfer_base64')) {
            $base64 = $request->input('bukti_transfer_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $base64Data = substr($base64, strpos($base64, ',') + 1);
                $imageData = base64_decode($base64Data);
                if ($imageData !== false) {
                    $dir = 'transfer-photos/' . now()->format('Y-m-d');
                    $filename = 'transfer_' . uniqid() . '.jpg';
                    \Illuminate\Support\Facades\Storage::disk('public')->put($dir . '/' . $filename, $imageData);
                    return $dir . '/' . $filename;
                }
            }
        }

        return null;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $pelanggan_id = $request->input('pelanggan_id');
        $status = $request->input('status');
        $tipe_bayar = $request->input('tipe_bayar');

        $query = MinyakPenjualan::with(['sales', 'pelanggan', 'produk']);

        // Sales users: always scope to own data
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->where('sales_id', $profile->id);
            }
            $sales = collect([$profile]);
        } else {
            $query->when($sales_id, function ($q) use ($sales_id) {
                $q->where('sales_id', $sales_id);
            });
            $sales = MinyakSales::aktif()->get();
        }

        $penjualans = $query
            ->when($search, function ($q) use ($search) {
                $q->where('no_faktur', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($q2) use ($search) {
                        $q2->where('nama_toko', 'like', "%{$search}%");
                    });
            })
            ->when($pelanggan_id, function ($q) use ($pelanggan_id) {
                $q->where('pelanggan_id', $pelanggan_id);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($tipe_bayar, function ($q) use ($tipe_bayar) {
                $q->where('tipe_bayar', $tipe_bayar);
            })
            ->orderBy('tanggal_jual', 'desc')
            ->paginate(15)
            ->withQueryString();

        $pelanggans = MinyakPelanggan::where('status', 'aktif')->get();

        // Stats scoped to visible data
        $baseQuery = MinyakPenjualan::query();
        if ($this->isSales() && $profile) {
            $baseQuery->where('sales_id', $profile->id);
        }

        $stats = [
            'total_hari_ini' => (clone $baseQuery)->whereDate('tanggal_jual', today())->where('status', '!=', 'batal')->sum('total'),
            'total_transaksi' => (clone $baseQuery)->whereDate('tanggal_jual', today())->where('status', '!=', 'batal')->count(),
            'total_tunai' => (clone $baseQuery)->whereDate('tanggal_jual', today())->where('status', '!=', 'batal')->where('tipe_bayar', 'tunai')->sum('total'),
            'total_hutang' => (clone $baseQuery)->whereDate('tanggal_jual', today())->where('status', '!=', 'batal')->where('tipe_bayar', 'hutang')->sum('hutang'),
            'transfer_pending' => !$this->isSales() ? (clone $baseQuery)->where('tipe_bayar', 'transfer')->where('status', 'pending')->count() : 0,
        ];

        $isSalesRole = $this->isSales();

        return view('minyak.penjualan.index', compact('penjualans', 'sales', 'pelanggans', 'stats', 'isSalesRole'));
    }

    public function create()
    {
        $isSalesRole = $this->isSales();

        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            $sales = collect([$profile]);
        } else {
            $sales = MinyakSales::aktif()->get();
        }

        $pelanggans = MinyakPelanggan::where('status', 'aktif')->get();
        $produks = MinyakProduk::where('status', 'aktif')->get();

        // Build regional price map for JS lookup
        $regionalPriceMap = [];
        foreach ($sales as $s) {
            if ($s && $s->regional_id) {
                $hargaItems = MinyakRegionalHarga::where('regional_id', $s->regional_id)->get();
                foreach ($hargaItems as $h) {
                    $regionalPriceMap[$s->id][$h->produk_id] = (float) $h->harga_jual;
                }
            }
        }

        return view('minyak.penjualan.create', compact('sales', 'pelanggans', 'produks', 'isSalesRole', 'regionalPriceMap'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal_jual' => 'required|date',
            'sales_id' => $this->isSales() ? 'nullable' : 'required|exists:minyak_sales,id',
            'pelanggan_id' => 'required|exists:minyak_pelanggan,id',
            'produk_id' => 'required|exists:minyak_produk,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tipe_bayar' => 'required|in:tunai,hutang,transfer',
            'no_bukti_transfer' => 'required_if:tipe_bayar,transfer|nullable|string|max:100',
            'bayar' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto_nota' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'foto_nota_base64' => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'bukti_transfer_base64' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Force own sales_id for sales role
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile) abort(403, 'Profil sales tidak ditemukan.');
            $validated['sales_id'] = $profile->id;
        }

        // Radius validation: sales must be within 20 meters of customer's registered location
        if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
            $pelanggan = MinyakPelanggan::find($validated['pelanggan_id']);
            if ($pelanggan && $pelanggan->latitude && $pelanggan->longitude) {
                $salesLat = (float) $validated['latitude'];
                $salesLng = (float) $validated['longitude'];
                $custLat  = (float) $pelanggan->latitude;
                $custLng  = (float) $pelanggan->longitude;

                // Haversine formula
                $R = 6371000; // Earth radius in meters
                $dLat = deg2rad($custLat - $salesLat);
                $dLon = deg2rad($custLng - $salesLng);
                $a = sin($dLat / 2) * sin($dLat / 2) +
                     cos(deg2rad($salesLat)) * cos(deg2rad($custLat)) *
                     sin($dLon / 2) * sin($dLon / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $distance = $R * $c;

                $maxRadius = 20; // meters
                if ($distance > $maxRadius) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Anda berada ' . round($distance) . ' meter dari lokasi pelanggan (maksimum ' . $maxRadius . ' meter). Pastikan Anda berada di dekat toko pelanggan.');
                }
            }
        }

        // Apply regional pricing if harga_satuan not explicitly set by user
        $salesProfile = MinyakSales::find($validated['sales_id']);
        if ($salesProfile && $salesProfile->regional_id) {
            $regionalHarga = MinyakRegionalHarga::where('regional_id', $salesProfile->regional_id)
                ->where('produk_id', $validated['produk_id'])
                ->first();
            if ($regionalHarga && $regionalHarga->harga_jual > 0) {
                // If the form sent the default product price, override with regional price
                $produk = MinyakProduk::find($validated['produk_id']);
                if ($produk && (float) $validated['harga_satuan'] === (float) $produk->harga_jual) {
                    $validated['harga_satuan'] = $regionalHarga->harga_jual;
                }
            }
        }

        // Validasi stok loading cukup
        $loading = $this->findLoadingRecord($validated['sales_id'], $validated['produk_id']);
        if (! $loading) {
            return redirect()->back()->withInput()
                ->with('error', 'Tidak ada loading untuk produk ini. Silakan buat loading terlebih dahulu.');
        }
        if ($loading->sisa_stok < $validated['jumlah']) {
            return redirect()->back()->withInput()
                ->with('error', 'Stok di kendaraan tidak cukup. Sisa stok: ' . number_format($loading->sisa_stok, 0, ',', '.') . ' ' . ($loading->produk->satuan ?? 'liter'));
        }

        $validated['no_faktur'] = MinyakPenjualan::generateFaktur();
        $validated['total'] = $validated['jumlah'] * $validated['harga_satuan'];
        
        if ($validated['tipe_bayar'] === 'tunai') {
            $validated['bayar'] = $validated['total'];
            $validated['kembali'] = $validated['bayar'] - $validated['total'];
            $validated['hutang'] = 0;
        } elseif ($validated['tipe_bayar'] === 'hutang') {
            $validated['bayar'] = $validated['bayar'] ?? 0;
            $validated['hutang'] = $validated['total'] - $validated['bayar'];
            $validated['kembali'] = 0;
        } else {
            $validated['bayar'] = $validated['total'];
            $validated['kembali'] = 0;
            $validated['hutang'] = 0;
        }

        $validated['status'] = 'pending';

        // Store foto nota (file upload or base64 from webcam)
        $validated['foto_nota'] = $this->storeNotaPhoto($request);
        unset($validated['foto_nota_base64']);

        // Store bukti transfer photo if tipe_bayar is transfer
        if (($validated['tipe_bayar'] ?? '') === 'transfer') {
            $validated['bukti_transfer'] = $this->storeTransferPhoto($request);
        }
        unset($validated['bukti_transfer_base64']);

        DB::beginTransaction();
        try {
            // Auto-create kunjungan record for visit tracking
            $kunjungan = MinyakKunjungan::create([
                'sales_id'         => $validated['sales_id'],
                'pelanggan_id'     => $validated['pelanggan_id'],
                'waktu_checkin'    => now(),
                'latitude_checkin' => $validated['latitude'] ?? null,
                'longitude_checkin'=> $validated['longitude'] ?? null,
                'foto_checkin'     => $validated['foto_nota'] ?? null,
                'catatan'          => 'Auto dari penjualan ' . $validated['no_faktur'],
                'status'           => 'checkin',
                'ada_penjualan'    => true,
            ]);
            $validated['kunjungan_id'] = $kunjungan->id;

            $penjualan = MinyakPenjualan::create($validated);

            // Kurangi stok loading sales (gunakan record yang sudah divalidasi)
            $loading->terjual += $validated['jumlah'];
            $loading->sisa_stok -= $validated['jumlah'];
            $loading->save();

            // Auto-update loading status jika semua stok terjual
            $this->updateLoadingStatus($loading);

            // Jika hutang, buat record hutang
            if ($validated['hutang'] > 0) {
                // Credit limit check
                $pelanggan = MinyakPelanggan::find($validated['pelanggan_id']);
                if (!$pelanggan || $pelanggan->limit_hutang <= 0) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', 'Pelanggan tidak memiliki limit kredit.');
                }
                $sisaLimit = (float) $pelanggan->limit_hutang - (float) $pelanggan->total_hutang;
                if ($validated['hutang'] > $sisaLimit) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', 'Hutang melebihi limit pelanggan. Sisa limit: Rp ' . number_format(max(0, $sisaLimit), 0, ',', '.'));
                }

                MinyakHutang::create([
                    'pelanggan_id' => $validated['pelanggan_id'],
                    'penjualan_id' => $penjualan->id,
                    'total_hutang' => $validated['hutang'],
                    'dibayar' => 0,
                    'sisa' => $validated['hutang'],
                    'jatuh_tempo' => now()->addDays(30),
                    'status' => 'belum_lunas',
                ]);

                // Update total hutang pelanggan
                $pelanggan = $pelanggan ?? MinyakPelanggan::find($validated['pelanggan_id']);
                $pelanggan->total_hutang = (float) $pelanggan->total_hutang + (float) $validated['hutang'];
                $pelanggan->save();
            }

            // Audit log
            AuditService::log('minyak_penjualan.create', 'MinyakPenjualan', $penjualan->id, [
                'no_faktur' => $penjualan->no_faktur,
                'sales_id' => $validated['sales_id'],
                'pelanggan_id' => $validated['pelanggan_id'],
                'produk_id' => $validated['produk_id'],
                'jumlah' => $validated['jumlah'],
                'total' => $validated['total'],
                'tipe_bayar' => $validated['tipe_bayar'],
            ]);

            DB::commit();

            return redirect()->route('minyak.penjualan.index')
                ->with('success', 'Penjualan berhasil ditambahkan.')
                ->with('auto_print', $penjualan->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(MinyakPenjualan $penjualan)
    {
        // Sales can only view own transactions
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $penjualan->load(['sales', 'pelanggan', 'produk', 'hutang.pembayarans']);
        $isSalesRole = $this->isSales();
        
        return view('minyak.penjualan.show', compact('penjualan', 'isSalesRole'));
    }

    public function edit(MinyakPenjualan $penjualan)
    {
        // Sales can only edit own transactions
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        // Only pending sales can be edited
        if ($penjualan->status !== 'pending') {
            return redirect()->route('minyak.penjualan.show', $penjualan)
                ->with('error', 'Penjualan yang sudah terverifikasi atau batal tidak dapat diedit.');
        }

        $isSalesRole = $this->isSales();

        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            $sales = collect([$profile]);
        } else {
            $sales = MinyakSales::aktif()->get();
        }

        $pelanggans = MinyakPelanggan::where('status', 'aktif')->get();
        $produks = MinyakProduk::where('status', 'aktif')->get();

        return view('minyak.penjualan.edit', compact('penjualan', 'sales', 'pelanggans', 'produks', 'isSalesRole'));
    }

    public function update(Request $request, MinyakPenjualan $penjualan)
    {
        // Sales can only edit own transactions
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        if ($penjualan->status !== 'pending') {
            return redirect()->back()->with('error', 'Penjualan yang sudah terverifikasi atau batal tidak dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal_jual' => 'required|date',
            'sales_id' => $this->isSales() ? 'nullable' : 'required|exists:minyak_sales,id',
            'pelanggan_id' => 'required|exists:minyak_pelanggan,id',
            'produk_id' => 'required|exists:minyak_produk,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tipe_bayar' => 'required|in:tunai,hutang,transfer',
            'no_bukti_transfer' => 'required_if:tipe_bayar,transfer|nullable|string|max:100',
            'bayar' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'foto_nota' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'foto_nota_base64' => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'bukti_transfer_base64' => 'nullable|string',
        ]);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            $validated['sales_id'] = $profile->id;
        }

        DB::beginTransaction();
        try {
            $oldJumlah = (int) $penjualan->jumlah;
            $oldProdukId = $penjualan->produk_id;
            $oldSalesId = $penjualan->sales_id;
            $oldHutang = (float) $penjualan->hutang;

            // Kembalikan stok loading lama
            $oldLoading = $this->findLoadingRecord($oldSalesId, $oldProdukId);
            if ($oldLoading) {
                $oldLoading->terjual = max(0, $oldLoading->terjual - $oldJumlah);
                $oldLoading->sisa_stok += $oldJumlah;
                $oldLoading->save();
            }

            // Validasi stok loading baru
            $newSalesId = $validated['sales_id'] ?? $oldSalesId;
            $newProdukId = $validated['produk_id'];
            $newJumlah = $validated['jumlah'];

            $newLoading = $this->findLoadingRecord($newSalesId, $newProdukId);
            if (! $newLoading) {
                DB::rollBack();
                return redirect()->back()->withInput()
                    ->with('error', 'Tidak ada loading untuk produk ini.');
            }
            if ($newLoading->sisa_stok < $newJumlah) {
                DB::rollBack();
                return redirect()->back()->withInput()
                    ->with('error', 'Stok di kendaraan tidak cukup. Sisa: ' . number_format($newLoading->sisa_stok, 0, ',', '.') . ' ' . ($newLoading->produk->satuan ?? 'liter'));
            }

            // Kurangi stok loading baru
            $newLoading->terjual += $newJumlah;
            $newLoading->sisa_stok -= $newJumlah;
            $newLoading->save();
            $this->updateLoadingStatus($newLoading);

            // Recalculate totals
            $validated['total'] = $newJumlah * $validated['harga_satuan'];

            if ($validated['tipe_bayar'] === 'tunai') {
                $validated['bayar'] = $validated['total'];
                $validated['kembali'] = 0;
                $validated['hutang'] = 0;
            } elseif ($validated['tipe_bayar'] === 'hutang') {
                $validated['bayar'] = $validated['bayar'] ?? 0;
                $validated['hutang'] = $validated['total'] - $validated['bayar'];
                $validated['kembali'] = 0;
            } else {
                $validated['bayar'] = $validated['total'];
                $validated['kembali'] = 0;
                $validated['hutang'] = 0;
            }

            // Handle foto nota update
            $newFoto = $this->storeNotaPhoto($request);
            if ($newFoto) {
                $validated['foto_nota'] = $newFoto;
            }
            unset($validated['foto_nota_base64']);

            // Handle bukti transfer update
            $newTransfer = $this->storeTransferPhoto($request);
            if ($newTransfer) {
                $validated['bukti_transfer'] = $newTransfer;
            }
            unset($validated['bukti_transfer_base64']);

            // Handle hutang changes
            $newHutang = (float) ($validated['hutang'] ?? 0);

            if ($oldHutang > 0) {
                // Old had hutang
                $existingHutang = MinyakHutang::where('penjualan_id', $penjualan->id)->first();
                if ($newHutang > 0) {
                    // Update existing hutang
                    if ($existingHutang) {
                        $diffHutang = $newHutang - (float) $existingHutang->total_hutang;
                        $existingHutang->total_hutang = $newHutang;
                        $existingHutang->sisa = max(0, $newHutang - (float) $existingHutang->dibayar);
                        $existingHutang->status = $existingHutang->sisa <= 0 ? 'lunas' : 'belum_lunas';
                        $existingHutang->save();

                        // Update pelanggan total_hutang
                        $pelanggan = MinyakPelanggan::find($validated['pelanggan_id']);
                        if ($pelanggan) {
                            $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang + $diffHutang);
                            $pelanggan->save();
                        }
                    }
                } else {
                    // Hutang removed — delete hutang record
                    if ($existingHutang) {
                        $pelanggan = MinyakPelanggan::find($penjualan->pelanggan_id);
                        if ($pelanggan) {
                            $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $existingHutang->total_hutang);
                            $pelanggan->save();
                        }
                        $existingHutang->delete();
                    }
                }
            } elseif ($newHutang > 0) {
                // New hutang added (old had none)
                // Credit limit check
                $pelanggan = MinyakPelanggan::find($validated['pelanggan_id']);
                if (!$pelanggan || $pelanggan->limit_hutang <= 0) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', 'Pelanggan tidak memiliki limit kredit.');
                }
                $sisaLimit = (float) $pelanggan->limit_hutang - (float) $pelanggan->total_hutang;
                if ($newHutang > $sisaLimit) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', 'Hutang melebihi limit pelanggan. Sisa limit: Rp ' . number_format(max(0, $sisaLimit), 0, ',', '.'));
                }

                MinyakHutang::create([
                    'pelanggan_id' => $validated['pelanggan_id'],
                    'penjualan_id' => $penjualan->id,
                    'total_hutang' => $newHutang,
                    'dibayar' => 0,
                    'sisa' => $newHutang,
                    'jatuh_tempo' => now()->addDays(30),
                    'status' => 'belum_lunas',
                ]);

                $pelanggan = $pelanggan ?? MinyakPelanggan::find($validated['pelanggan_id']);
                $pelanggan->total_hutang = (float) $pelanggan->total_hutang + $newHutang;
                $pelanggan->save();
            }

            $penjualan->update($validated);

            AuditService::log('minyak_penjualan.update', 'MinyakPenjualan', $penjualan->id, [
                'old_jumlah' => $oldJumlah,
                'new_jumlah' => $newJumlah,
                'old_produk_id' => $oldProdukId,
                'new_produk_id' => $newProdukId,
                'total' => $validated['total'],
            ]);

            DB::commit();

            return redirect()->route('minyak.penjualan.show', $penjualan)
                ->with('success', 'Penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function printStruk(MinyakPenjualan $penjualan)
    {
        // Sales can only print own transactions
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $penjualan->load(['sales', 'pelanggan', 'produk']);

        return view('minyak.penjualan.print', compact('penjualan'));
    }

    public function verify(MinyakPenjualan $penjualan)
    {
        // Supervisor-only: verify transfer proof before approving
        if ($this->isSales()) {
            abort(403, 'Hanya supervisor yang dapat memverifikasi penjualan.');
        }

        if ($penjualan->status === 'terverifikasi') {
            return redirect()->back()->with('error', 'Penjualan ini sudah diverifikasi sebelumnya.');
        }

        if ($penjualan->status === 'batal') {
            return redirect()->back()->with('error', 'Penjualan ini sudah dibatalkan.');
        }

        // Extra check for transfer: ensure bukti_transfer exists
        if ($penjualan->tipe_bayar === 'transfer' && ! $penjualan->bukti_transfer) {
            return redirect()->back()->with('error', 'Tidak dapat memverifikasi: foto bukti transfer belum diunggah.');
        }

        $penjualan->update([
            'status' => 'terverifikasi',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        AuditService::log('minyak_penjualan.verify', 'MinyakPenjualan', $penjualan->id, [
            'no_faktur' => $penjualan->no_faktur,
            'tipe_bayar' => $penjualan->tipe_bayar,
            'total' => $penjualan->total,
            'verified_by' => Auth::id(),
        ]);

        $message = $penjualan->tipe_bayar === 'transfer'
            ? 'Pembayaran transfer berhasil diverifikasi dan disetujui.'
            : 'Penjualan berhasil diverifikasi.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Soft cancel: set status to 'batal' instead of hard delete.
     * Returns stock to loading and handles hutang cleanup.
     */
    public function destroy(MinyakPenjualan $penjualan)
    {
        // Sales users cannot cancel transactions
        if ($this->isSales()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        if ($penjualan->status === 'terverifikasi') {
            return redirect()->back()
                ->with('error', 'Penjualan yang sudah diverifikasi tidak dapat dibatalkan.');
        }

        if ($penjualan->status === 'batal') {
            return redirect()->back()
                ->with('error', 'Penjualan ini sudah dibatalkan sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok ke loading yang tepat
            $loading = $this->findLoadingRecord($penjualan->sales_id, $penjualan->produk_id);
            if ($loading) {
                $loading->terjual = max(0, $loading->terjual - $penjualan->jumlah);
                $loading->sisa_stok += $penjualan->jumlah;
                // Reset status from 'selesai' back to 'proses' if stock returned
                if ($loading->status === 'selesai') {
                    $loading->status = 'proses';
                }
                $loading->save();
            }

            // Hapus hutang jika ada
            if ($penjualan->hutang) {
                $pelanggan = MinyakPelanggan::find($penjualan->pelanggan_id);
                if ($pelanggan) {
                    $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $penjualan->hutang);
                    $pelanggan->save();
                }
                
                $penjualan->hutang->delete();
            }

            // Soft cancel: set status to batal instead of deleting
            $penjualan->update([
                'status' => 'batal',
            ]);

            // Mark kunjungan as no longer having a sale
            if ($penjualan->kunjungan_id) {
                MinyakKunjungan::where('id', $penjualan->kunjungan_id)
                    ->update(['ada_penjualan' => false]);
            }

            AuditService::log('minyak_penjualan.cancel', 'MinyakPenjualan', $penjualan->id, [
                'no_faktur' => $penjualan->no_faktur,
                'jumlah' => $penjualan->jumlah,
                'total' => $penjualan->total,
                'reason' => 'Dibatalkan oleh supervisor',
            ], 'warning');

            DB::commit();

            return redirect()->route('minyak.penjualan.index')
                ->with('success', 'Penjualan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

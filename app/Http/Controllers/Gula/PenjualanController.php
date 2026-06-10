<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaPenjualan;
use App\Models\GulaSales;
use App\Models\GulaPelanggan;
use App\Models\GulaProduk;
use App\Models\GulaLoading;
use App\Models\GulaHutang;
use App\Models\GulaKunjungan;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $tipe_bayar = $request->input('tipe_bayar');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = GulaPenjualan::with(['sales', 'pelanggan', 'produk'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('no_faktur', 'like', "%{$search}%")
                       ->orWhereHas('pelanggan', fn($pq) => $pq->where('nama_toko', 'like', "%{$search}%"))
                       ->orWhereHas('sales', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
                });
            })
            ->when($sales_id, fn($q) => $q->where('sales_id', $sales_id))
            ->when($tipe_bayar, fn($q) => $q->where('tipe_bayar', $tipe_bayar))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($dateFrom, fn($q) => $q->where('tanggal_jual', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->where('tanggal_jual', '<=', $dateTo . ' 23:59:59'));

        $penjualans = $query->orderBy('tanggal_jual', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sales = GulaSales::aktif()->get();

        // Stats (global, not filtered)
        $today = now()->toDateString();
        $statsBase = GulaPenjualan::query();
        $stats = [
            'total_hari_ini'   => (clone $statsBase)->whereDate('tanggal_jual', $today)->sum('total'),
            'total_transaksi'  => (clone $statsBase)->whereDate('tanggal_jual', $today)->count(),
            'total_tunai'      => (clone $statsBase)->whereDate('tanggal_jual', $today)->where('tipe_bayar', 'tunai')->sum('total'),
            'total_hutang'     => (clone $statsBase)->whereDate('tanggal_jual', $today)->where('tipe_bayar', 'hutang')->sum('hutang'),
        ];

        return view('gula.penjualan.index', compact(
            'penjualans', 'sales', 'stats',
            'search', 'sales_id', 'tipe_bayar', 'status', 'dateFrom', 'dateTo'
        ));
    }

    public function create()
    {
        $sales = GulaSales::aktif()->get();
        $pelanggans = GulaPelanggan::where('status', 'aktif')->get();
        $produks = GulaProduk::where('status', 'aktif')->get();

        // Build vehicle stock map: sales_id -> produk_id -> sisa_stok
        $loadings = GulaLoading::where('sisa_stok', '>', 0)
            ->whereIn('sales_id', $sales->pluck('id'))
            ->get(['sales_id', 'produk_id', 'sisa_stok']);

        $vehicleStock = [];
        foreach ($loadings as $l) {
            $sid = $l->sales_id;
            $pid = $l->produk_id;
            if (!isset($vehicleStock[$sid][$pid])) {
                $vehicleStock[$sid][$pid] = 0;
            }
            $vehicleStock[$sid][$pid] += (int) $l->sisa_stok;
        }

        // Product catalog for JS (pre-serialized to avoid Blade @json closure issues)
        $produkJson = $produks->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'satuan' => $p->satuan ?? 'Unit',
                'harga_jual' => (float) $p->harga_jual,
                'stok_gudang' => (int) $p->stok_gudang,
            ];
        })->values()->toJson();

        // Customer data for searchable dropdown
        $pelangganJson = $pelanggans->map(function ($p) {
            return [
                'id' => $p->id,
                'nama_toko' => $p->nama_toko,
                'nama_pemilik' => $p->nama_pemilik,
                'no_hp' => $p->no_hp ?? '',
                'alamat' => $p->alamat ?? '',
            ];
        })->values()->toJson();

        return view('gula.penjualan.create', compact('sales', 'pelanggans', 'produks', 'vehicleStock', 'produkJson', 'pelangganJson'));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal_jual' => 'required|date',
            'sales_id' => 'required|exists:gula_sales,id',
            'pelanggan_id' => 'required|exists:gula_pelanggan,id',
            'produk_id' => 'required|exists:gula_produk,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tipe_bayar' => 'required|in:tunai,hutang,transfer',
            'bayar' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];

        // Transfer requires ID transaksi + foto bukti
        if ($request->input('tipe_bayar') === 'transfer') {
            $rules['transfer_ref'] = 'required|string|max:100';
            $rules['foto_bukti_transfer'] = 'required|image|mimes:jpeg,jpg,png,webp|max:4096';
        }

        $validated = $request->validate($rules);

        // Validate against vehicle stock (sisa_stok), not warehouse stock
        $vehicleStock = GulaLoading::where('sales_id', $validated['sales_id'])
            ->where('produk_id', $validated['produk_id'])
            ->where('sisa_stok', '>', 0)
            ->sum('sisa_stok');

        if ($validated['jumlah'] > $vehicleStock) {
            return redirect()->back()->withInput()
                ->with('error', 'Stok kendaraan tidak mencukupi. Sisa stok di kendaraan: ' . number_format($vehicleStock, 0, ',', '.'));
        }

        $validated['no_faktur'] = GulaPenjualan::generateFaktur();
        $validated['total'] = $validated['jumlah'] * $validated['harga_satuan'];
        
        if ($validated['tipe_bayar'] === 'tunai') {
            // For tunai, read from bayar_tunai field (separate from hutang's bayar)
            $bayarTunai = $request->input('bayar_tunai');
            $validated['bayar'] = $bayarTunai ? (float) $bayarTunai : $validated['total'];
            if ($validated['bayar'] < $validated['total']) {
                return redirect()->back()->withInput()
                    ->with('error', 'Uang tunai tidak mencukupi. Total: Rp ' . number_format($validated['total'], 0, ',', '.'));
            }
            $validated['kembali'] = $validated['bayar'] - $validated['total'];
            $validated['hutang'] = 0;
        } elseif ($validated['tipe_bayar'] === 'hutang') {
            $validated['bayar'] = $validated['bayar'] ?? 0;
            $validated['hutang'] = $validated['total'] - $validated['bayar'];
            $validated['kembali'] = 0;
        } else {
            // Transfer: bayar = total, status pending until supervisor verifies proof
            $validated['bayar'] = $validated['total'];
            $validated['kembali'] = 0;
            $validated['hutang'] = 0;
        }

        $validated['status'] = 'pending';

        // Upload transfer proof photo
        if ($validated['tipe_bayar'] === 'transfer' && $request->hasFile('foto_bukti_transfer')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('foto_bukti_transfer'),
                    'gula/transfer',
                    'public',
                    ['max_width' => 2000, 'max_height' => 2000, 'strip_exif' => true]
                );
                $validated['foto_bukti_transfer'] = $upload['path'];
            } catch (\Throwable $e) {
                return redirect()->back()->withInput()
                    ->with('error', 'Gagal upload foto bukti transfer: ' . $e->getMessage());
            }
        }

        DB::beginTransaction();
        try {
            // Auto-create kunjungan record for visit tracking
            $kunjungan = GulaKunjungan::create([
                'sales_id'         => $validated['sales_id'],
                'pelanggan_id'     => $validated['pelanggan_id'],
                'waktu_checkin'    => now(),
                'latitude_checkin' => $validated['latitude'] ?? null,
                'longitude_checkin'=> $validated['longitude'] ?? null,
                'catatan'          => 'Auto dari penjualan ' . $validated['no_faktur'],
                'status'           => 'checkin',
                'ada_penjualan'    => true,
            ]);
            $validated['kunjungan_id'] = $kunjungan->id;

            $penjualan = GulaPenjualan::create($validated);

            // Deduct from vehicle loading stock (FIFO: oldest loading first)
            $remaining = $validated['jumlah'];
            $loadings = GulaLoading::where('sales_id', $validated['sales_id'])
                ->where('produk_id', $validated['produk_id'])
                ->where('sisa_stok', '>', 0)
                ->orderBy('tanggal', 'asc')
                ->get();

            foreach ($loadings as $loading) {
                if ($remaining <= 0) break;
                $deduct = min($remaining, (int) $loading->sisa_stok);
                $loading->terjual += $deduct;
                $loading->sisa_stok -= $deduct;
                $loading->save();
                $remaining -= $deduct;
            }

            // Jika hutang, buat record hutang
            if ($validated['hutang'] > 0) {
                // Credit limit check
                $pelanggan = GulaPelanggan::find($validated['pelanggan_id']);
                if ($pelanggan && $pelanggan->limit_hutang > 0) {
                    $sisaLimit = (float) $pelanggan->limit_hutang - (float) $pelanggan->total_hutang;
                    if ($validated['hutang'] > $sisaLimit) {
                        DB::rollBack();
                        return redirect()->back()->withInput()
                            ->with('error', 'Hutang melebihi limit kredit pelanggan. Sisa limit: Rp ' . number_format(max(0, $sisaLimit), 0, ',', '.'));
                    }
                }

                GulaHutang::create([
                    'pelanggan_id' => $validated['pelanggan_id'],
                    'penjualan_id' => $penjualan->id,
                    'total_hutang' => $validated['hutang'],
                    'dibayar' => 0,
                    'sisa' => $validated['hutang'],
                    'jatuh_tempo' => now()->addDays(30),
                    'status' => 'belum_lunas',
                ]);

                // Update total hutang pelanggan
                $pelanggan = $pelanggan ?? GulaPelanggan::find($validated['pelanggan_id']);
                $pelanggan->total_hutang = (float) $pelanggan->total_hutang + (float) $validated['hutang'];
                $pelanggan->save();
            }

            DB::commit();

            return redirect()->route('gula.penjualan.index')
                ->with('success', 'Penjualan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(GulaPenjualan $penjualan)
    {
        $penjualan->load(['sales', 'pelanggan', 'produk', 'hutangRecord.pembayarans']);
        
        return view('gula.penjualan.show', compact('penjualan'));
    }

    public function verify(GulaPenjualan $penjualan)
    {
        $penjualan->update([
            'status' => 'terverifikasi',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Penjualan berhasil diverifikasi.');
    }

    public function destroy(GulaPenjualan $penjualan)
    {
        if ($penjualan->status === 'terverifikasi') {
            return redirect()->back()
                ->with('error', 'Penjualan yang sudah diverifikasi tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            // Restore stock in reverse FIFO (LIFO): newest loading first
            $remaining = $penjualan->jumlah;
            $loadings = GulaLoading::where('sales_id', $penjualan->sales_id)
                ->where('produk_id', $penjualan->produk_id)
                ->orderBy('tanggal', 'desc')
                ->get();

            foreach ($loadings as $loading) {
                if ($remaining <= 0) break;
                $restore = min($remaining, (int) $loading->terjual);
                $loading->terjual -= $restore;
                $loading->sisa_stok += $restore;
                $loading->save();
                $remaining -= $restore;
            }

            // Cleanup hutang record
            if ($penjualan->hutang > 0) {
                $pelanggan = GulaPelanggan::find($penjualan->pelanggan_id);
                if ($pelanggan) {
                    $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $penjualan->hutang);
                    $pelanggan->save();
                }
                if ($penjualan->hutangRecord) {
                    $penjualan->hutangRecord->delete();
                }
            }

            // Cleanup auto-created kunjungan
            if ($penjualan->kunjungan_id) {
                GulaKunjungan::where('id', $penjualan->kunjungan_id)->delete();
            }

            $penjualan->delete();
            DB::commit();

            return redirect()->route('gula.penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print thermal receipt for penjualan.
     */
    public function printStruk($id)
    {
        $penjualan = GulaPenjualan::with([
            'sales', 'pelanggan', 'produk', 'hutangRecord',
        ])->findOrFail($id);

        // Sales can only print their own transactions
        if (str_starts_with(strtolower(Auth::user()->role ?? ''), 'sales_') || (Auth::user()->role ?? '') === 'sales') {
            $profile = GulaSales::where('user_id', Auth::id())->first();
            if ($profile && $penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        return view('gula.penjualan.print', compact('penjualan'));
    }
}

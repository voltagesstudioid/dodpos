<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MineralHutang;
use App\Models\MineralHutangBayar;
use App\Models\MineralKunjungan;
use App\Models\MineralLoading;
use App\Models\MineralPelanggan;
use App\Models\MineralPenjualan;
use App\Models\MineralProduk;
use App\Models\MineralRegionalHarga;
use App\Models\MineralSales;
use App\Models\MineralSetoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MineralApiController extends Controller
{
    /**
     * Get dashboard data for logged in sales
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        // Today's sales stats
        $penjualanQuery = MineralPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('status', '!=', 'batal');

        $totalPenjualan = $penjualanQuery->sum('total');
        $jumlahTransaksi = $penjualanQuery->count();
        $targetTercapai = $sales->target_harian > 0
            ? round(($totalPenjualan / $sales->target_harian) * 100, 2)
            : 0;

        // Today's loading
        $loadingHariIni = MineralLoading::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)
            ->with('produk')
            ->get();

        $totalLoading = $loadingHariIni->sum('jumlah_loading');
        $totalTerjual = $loadingHariIni->sum('terjual');
        $totalSisa = $loadingHariIni->sum('sisa_stok');

        // Debt info
        $pelangganIds = MineralPelanggan::whereHas('hutangs', function ($q) {
            $q->where('status', '!=', 'lunas');
        })->pluck('id');

        $totalPiutang = MineralHutang::whereIn('pelanggan_id', $pelangganIds)
            ->where('status', '!=', 'lunas')
            ->sum('sisa');

        // Setoran status
        $setoranHariIni = MineralSetoran::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'hari_ini' => [
                    'penjualan_total' => (float) $totalPenjualan,
                    'jumlah_transaksi' => $jumlahTransaksi,
                    'target_tercapai_persen' => $targetTercapai,
                ],
                'loading_hari_ini' => [
                    'total_loading' => $totalLoading,
                    'sisa_stok' => $totalSisa,
                    'terjual' => $totalTerjual,
                    'detail' => $loadingHariIni->map(function ($l) {
                        return [
                            'id' => $l->id,
                            'produk' => [
                                'id' => $l->produk->id,
                                'nama' => $l->produk->nama,
                                'satuan' => $l->produk->satuan,
                            ],
                            'jumlah_loading' => $l->jumlah_loading,
                            'terjual' => $l->terjual,
                            'sisa_stok' => $l->sisa_stok,
                            'status' => $l->status,
                        ];
                    }),
                ],
                'hutang_info' => [
                    'jumlah_pelanggan_hutang' => $pelangganIds->count(),
                    'total_piutang_belum_dibayar' => (float) $totalPiutang,
                ],
                'setoran' => [
                    'status' => $setoranHariIni?->status ?? 'belum_setor',
                    'target_setoran' => (float) $totalPenjualan,
                ],
                'sales' => [
                    'id' => $sales->id,
                    'kode_sales' => $sales->kode_sales,
                    'nama' => $sales->nama,
                    'no_kendaraan' => $sales->no_kendaraan,
                    'jenis_kendaraan' => $sales->jenis_kendaraan,
                    'target_harian' => (float) $sales->target_harian,
                ],
                // CamelCase compatibility fields for PWA Mobile view
                'hariIni' => [
                    'penjualanTotal' => (float) $totalPenjualan,
                    'jumlahTransaksi' => $jumlahTransaksi,
                ],
                'target' => [
                    'persen' => $targetTercapai,
                    'terpenuhi' => (float) $totalPenjualan,
                    'total' => (float) $sales->target_harian,
                ],
                'ringkasanHutang' => [
                    'totalPiutang' => (float) $totalPiutang,
                    'jumlahPelanggan' => $pelangganIds->count(),
                ],
                'loadingHariIni' => [
                    'detail' => $loadingHariIni->map(function ($l) {
                        return [
                            'sisa' => $l->sisa_stok,
                        ];
                    }),
                ],
            ],
        ]);
    }

    /**
     * Get list of pelanggan
     */
    public function pelangganList(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $status = $request->input('status', 'aktif');
        $perPage = $request->input('per_page', 15);

        $query = MineralPelanggan::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sq) use ($search) {
                    $sq->where('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%")
                        ->orWhere('no_hp', 'like', "%{$search}%");
                });
            })
            ->when($tipe, function ($q) use ($tipe) {
                $q->where('tipe', $tipe);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            });

        $pelanggans = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $pelanggans->through(function ($p) {
                return [
                    'id' => $p->id,
                    'kode_pelanggan' => $p->kode_pelanggan,
                    'nama_toko' => $p->nama_toko,
                    'nama_pemilik' => $p->nama_pemilik,
                    'no_hp' => $p->no_hp,
                    'alamat' => $p->alamat,
                    'kecamatan' => $p->kecamatan,
                    'kota' => $p->kota,
                    'latitude' => (float) $p->latitude,
                    'longitude' => (float) $p->longitude,
                    'tipe' => $p->tipe,
                    'limit_hutang' => (float) $p->limit_hutang,
                    'total_hutang' => (float) $p->total_hutang,
                    'status' => $p->status,
                ];
            }),
        ]);
    }

    /**
     * Get pelanggan detail with transaction history
     */
    public function pelangganDetail($id)
    {
        $pelanggan = MineralPelanggan::findOrFail($id);

        $historyPenjualan = MineralPenjualan::where('pelanggan_id', $id)
            ->orderBy('tanggal_jual', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'no_faktur' => $p->no_faktur,
                    'tanggal_jual' => $p->tanggal_jual?->toDateTimeString(),
                    'total' => (float) $p->total,
                    'tipe_bayar' => $p->tipe_bayar,
                ];
            });

        $hutangAktif = MineralHutang::where('pelanggan_id', $id)
            ->where('status', '!=', 'lunas')
            ->get()
            ->map(function ($h) {
                return [
                    'id' => $h->id,
                    'total_hutang' => (float) $h->total_hutang,
                    'sisa' => (float) $h->sisa,
                    'jatuh_tempo' => $h->jatuh_tempo?->toDateString(),
                    'status' => $h->status,
                    'overdue' => $h->jatuh_tempo && $h->jatuh_tempo < now(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'pelanggan' => [
                    'id' => $pelanggan->id,
                    'kode_pelanggan' => $pelanggan->kode_pelanggan,
                    'nama_toko' => $pelanggan->nama_toko,
                    'nama_pemilik' => $pelanggan->nama_pemilik,
                    'no_hp' => $pelanggan->no_hp,
                    'alamat' => $pelanggan->alamat,
                    'kecamatan' => $pelanggan->kecamatan,
                    'kota' => $pelanggan->kota,
                    'latitude' => (float) $pelanggan->latitude,
                    'longitude' => (float) $pelanggan->longitude,
                    'tipe' => $pelanggan->tipe,
                    'limit_hutang' => (float) $pelanggan->limit_hutang,
                    'total_hutang' => (float) $pelanggan->total_hutang,
                    'status' => $pelanggan->status,
                ],
                'history_penjualan' => $historyPenjualan,
                'hutang_aktif' => $hutangAktif,
            ],
        ]);
    }

    /**
     * Get produk list with stock info for current sales
     */
    public function produkList(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        $produks = MineralProduk::where('status', 'aktif')->get();

        $data = $produks->map(function ($p) use ($sales) {
            $sisaStok = 0;
            if ($sales) {
                $sisaStok = (float) MineralLoading::where('sales_id', $sales->id)
                    ->where('produk_id', $p->id)
                    ->where('sisa_stok', '>', 0)
                    ->where('status_approval', 'approved')
                    ->sum('sisa_stok');
            }

            $hargaJual = (float) $p->harga_jual;
            if ($sales && $sales->regional_id) {
                $regionalHarga = MineralRegionalHarga::where('regional_id', $sales->regional_id)
                    ->where('produk_id', $p->id)
                    ->first();
                if ($regionalHarga && $regionalHarga->harga_jual > 0) {
                    $hargaJual = (float) $regionalHarga->harga_jual;
                }
            }

            return [
                'id' => $p->id,
                'kode_produk' => $p->kode_produk,
                'nama' => $p->nama,
                'jenis' => $p->jenis,
                'satuan' => $p->satuan,
                'harga_jual' => $hargaJual,
                'harga_modal' => (float) $p->harga_modal,
                'sisa_stok_sales' => $sisaStok,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get today's loading for current sales
     */
    public function loadingToday(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        $loadings = MineralLoading::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)
            ->with('produk')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $loadings->map(function ($l) {
                return [
                    'id' => $l->id,
                    'tanggal' => $l->tanggal?->toDateString(),
                    'produk' => [
                        'id' => $l->produk->id,
                        'kode_produk' => $l->produk->kode_produk,
                        'nama' => $l->produk->nama,
                        'satuan' => $l->produk->satuan,
                    ],
                    'jumlah_loading' => $l->jumlah_loading,
                    'terjual' => $l->terjual,
                    'sisa_stok' => $l->sisa_stok,
                    'status' => $l->status,
                ];
            }),
        ]);
    }

    /**
     * Find the active loading record for a sales+product combination.
     * Prioritize loading with sisa_stok > 0, fallback to most recent.
     */
    private function findLoadingRecord(int $salesId, int $produkId): ?MineralLoading
    {
        $loading = MineralLoading::where('sales_id', $salesId)
            ->where('produk_id', $produkId)
            ->where('sisa_stok', '>', 0)
            ->where('status_approval', 'approved')
            ->orderBy('tanggal', 'desc')
            ->first();

        if ($loading) return $loading;

        return MineralLoading::where('sales_id', $salesId)
            ->where('produk_id', $produkId)
            ->where('status_approval', 'approved')
            ->orderBy('tanggal', 'desc')
            ->first();
    }

    /**
     * Create new penjualan
     */
    public function storePenjualan(Request $request)
    {
        $request->validate([
            'tanggal_jual' => 'required|date',
            'pelanggan_id' => 'required|exists:mineral_pelanggan,id',
            'produk_id' => 'required|exists:mineral_produk,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tipe_bayar' => 'required|in:tunai,hutang,transfer',
            'no_bukti_transfer' => 'required_if:tipe_bayar,transfer|nullable|string|max:100',
            'bayar' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto_nota' => 'nullable|string',
            'bukti_transfer' => 'nullable|string',
        ]);

        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $pelanggan = MineralPelanggan::findOrFail($request->pelanggan_id);
        $produk = MineralProduk::findOrFail($request->produk_id);

        $hargaSatuan = (float) $request->harga_satuan;
        if ($sales->regional_id && $hargaSatuan === (float) $produk->harga_jual) {
            $regionalHarga = MineralRegionalHarga::where('regional_id', $sales->regional_id)
                ->where('produk_id', $produk->id)
                ->first();
            if ($regionalHarga && $regionalHarga->harga_jual > 0) {
                $hargaSatuan = (float) $regionalHarga->harga_jual;
            }
        }

        $total = $request->jumlah * $hargaSatuan;
        $bayar = $request->bayar ?? 0;

        if ($request->tipe_bayar === 'tunai') {
            $bayar = $total;
            $kembali = 0;
            $hutang = 0;
        } elseif ($request->tipe_bayar === 'hutang') {
            $kembali = 0;
            $hutang = $total - $bayar;
        } else {
            $bayar = $total;
            $kembali = 0;
            $hutang = 0;
        }

        $loading = $this->findLoadingRecord($sales->id, $request->produk_id);

        if (! $loading || $loading->sisa_stok < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi',
                'sisa_stok' => $loading?->sisa_stok ?? 0,
            ], 422);
        }

        if ($request->tipe_bayar === 'hutang') {
            if ($pelanggan->limit_hutang <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pelanggan tidak memiliki limit kredit',
                    'limit_hutang' => 0,
                    'total_hutang_saat_ini' => $pelanggan->total_hutang,
                ], 422);
            }

            $sisaLimit = (float) $pelanggan->limit_hutang - (float) $pelanggan->total_hutang;
            if ($hutang > $sisaLimit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Limit hutang melebihi batas',
                    'limit_hutang' => $pelanggan->limit_hutang,
                    'total_hutang_saat_ini' => $pelanggan->total_hutang,
                    'sisa_limit' => max(0, $sisaLimit),
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            $fotoNotaPath = null;
            if ($request->foto_nota) {
                $imageData = base64_decode($request->foto_nota);
                if ($imageData !== false) {
                    $dir = 'nota-photos/' . now()->format('Y-m-d');
                    $filename = 'webcam_' . uniqid() . '.jpg';
                    Storage::disk('public')->put($dir . '/' . $filename, $imageData);
                    $fotoNotaPath = $dir . '/' . $filename;
                }
            }

            $buktiTransferPath = null;
            if ($request->tipe_bayar === 'transfer' && $request->bukti_transfer) {
                $imageData = base64_decode($request->bukti_transfer);
                if ($imageData !== false) {
                    $dir = 'transfer-photos/' . now()->format('Y-m-d');
                    $filename = 'transfer_' . uniqid() . '.jpg';
                    Storage::disk('public')->put($dir . '/' . $filename, $imageData);
                    $buktiTransferPath = $dir . '/' . $filename;
                }
            }

            $kunjungan = MineralKunjungan::create([
                'sales_id'         => $sales->id,
                'pelanggan_id'     => $request->pelanggan_id,
                'waktu_checkin'    => now(),
                'latitude_checkin' => $request->latitude,
                'longitude_checkin'=> $request->longitude,
                'foto_checkin'     => $fotoNotaPath,
                'catatan'          => 'Auto dari penjualan API',
                'status'           => 'checkin',
                'ada_penjualan'    => true,
            ]);

            $penjualan = MineralPenjualan::create([
                'kunjungan_id' => $kunjungan->id,
                'no_faktur' => MineralPenjualan::generateFaktur(),
                'tanggal_jual' => $request->tanggal_jual,
                'sales_id' => $sales->id,
                'pelanggan_id' => $request->pelanggan_id,
                'produk_id' => $request->produk_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $hargaSatuan,
                'total' => $total,
                'tipe_bayar' => $request->tipe_bayar,
                'no_bukti_transfer' => $request->no_bukti_transfer ?? null,
                'bukti_transfer' => $buktiTransferPath,
                'bayar' => $bayar,
                'kembali' => $kembali,
                'hutang' => $hutang,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'foto_nota' => $fotoNotaPath,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
            ]);

            $loading->terjual += $request->jumlah;
            $loading->sisa_stok -= $request->jumlah;
            if ($loading->sisa_stok <= 0 && $loading->status !== 'selesai') {
                $loading->status = 'selesai';
            }
            $loading->save();

            if ($hutang > 0) {
                MineralHutang::create([
                    'pelanggan_id' => $request->pelanggan_id,
                    'penjualan_id' => $penjualan->id,
                    'total_hutang' => $hutang,
                    'dibayar' => 0,
                    'sisa' => $hutang,
                    'jatuh_tempo' => now()->addDays(30),
                    'status' => 'belum_lunas',
                ]);

                $pelanggan = MineralPelanggan::find($request->pelanggan_id);
                $pelanggan->total_hutang = (float) $pelanggan->total_hutang + (float) $hutang;
                $pelanggan->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $penjualan->id,
                    'no_faktur' => $penjualan->no_faktur,
                    'total' => (float) $total,
                    'kembalian' => (float) $kembali,
                    'status' => $penjualan->status,
                ],
                'message' => 'Penjualan berhasil dicatat',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk sync penjualan from offline mode
     */
    public function syncPenjualan(Request $request)
    {
        $request->validate([
            'penjualan' => 'required|array',
            'penjualan.*.local_id' => 'required|string',
            'penjualan.*.tanggal_jual' => 'required|date',
            'penjualan.*.pelanggan_id' => 'required|exists:mineral_pelanggan,id',
            'penjualan.*.produk_id' => 'required|exists:mineral_produk,id',
            'penjualan.*.jumlah' => 'required|integer|min:1',
            'penjualan.*.harga_satuan' => 'required|numeric|min:0',
            'penjualan.*.tipe_bayar' => 'required|in:tunai,hutang,transfer',
        ]);

        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $synced = [];
        $failed = [];

        foreach ($request->penjualan as $item) {
            DB::beginTransaction();
            try {
                $total = $item['jumlah'] * $item['harga_satuan'];
                $bayar = $item['bayar'] ?? 0;

                if ($item['tipe_bayar'] === 'tunai') {
                    $bayar = $total;
                    $kembali = 0;
                    $hutang = 0;
                } elseif ($item['tipe_bayar'] === 'hutang') {
                    $kembali = 0;
                    $hutang = $total - $bayar;
                } else {
                    $bayar = $total;
                    $kembali = 0;
                    $hutang = 0;
                }

                $loading = $this->findLoadingRecord($sales->id, $item['produk_id']);

                if (! $loading || $loading->sisa_stok < $item['jumlah']) {
                    DB::rollBack();
                    $failed[] = [
                        'local_id' => $item['local_id'],
                        'error' => 'Stok tidak mencukupi. Tersedia: ' . ($loading?->sisa_stok ?? 0),
                    ];
                    continue;
                }

                $pelanggan = MineralPelanggan::find($item['pelanggan_id']);
                if ($hutang > 0 && $pelanggan) {
                    $sisaLimit = (float) $pelanggan->limit_hutang - (float) $pelanggan->total_hutang;
                    if ($sisaLimit < $hutang) {
                        DB::rollBack();
                        $failed[] = [
                            'local_id' => $item['local_id'],
                            'error' => 'Limit hutang pelanggan tidak mencukupi',
                        ];
                        continue;
                    }
                }

                $kunjungan = MineralKunjungan::create([
                    'sales_id'         => $sales->id,
                    'pelanggan_id'     => $item['pelanggan_id'],
                    'waktu_checkin'    => now(),
                    'latitude_checkin' => $item['latitude'] ?? null,
                    'longitude_checkin'=> $item['longitude'] ?? null,
                    'catatan'          => 'Auto dari sync ' . $item['local_id'],
                    'status'           => 'checkin',
                    'ada_penjualan'    => true,
                ]);

                $penjualan = MineralPenjualan::create([
                    'kunjungan_id' => $kunjungan->id,
                    'no_faktur' => MineralPenjualan::generateFaktur(),
                    'tanggal_jual' => $item['tanggal_jual'],
                    'sales_id' => $sales->id,
                    'pelanggan_id' => $item['pelanggan_id'],
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $total,
                    'tipe_bayar' => $item['tipe_bayar'],
                    'no_bukti_transfer' => $item['no_bukti_transfer'] ?? null,
                    'bayar' => $bayar,
                    'kembali' => $kembali,
                    'hutang' => $hutang,
                    'latitude' => $item['latitude'] ?? null,
                    'longitude' => $item['longitude'] ?? null,
                    'keterangan' => $item['keterangan'] ?? null,
                    'status' => 'pending',
                ]);

                $loading->terjual += $item['jumlah'];
                $loading->sisa_stok -= $item['jumlah'];
                if ($loading->sisa_stok <= 0 && $loading->status !== 'selesai') {
                    $loading->status = 'selesai';
                }
                $loading->save();

                if ($hutang > 0 && $pelanggan) {
                    MineralHutang::create([
                        'pelanggan_id' => $item['pelanggan_id'],
                        'penjualan_id' => $penjualan->id,
                        'total_hutang' => $hutang,
                        'dibayar' => 0,
                        'sisa' => $hutang,
                        'jatuh_tempo' => now()->addDays(30),
                        'status' => 'belum_lunas',
                    ]);

                    $pelanggan->total_hutang = (float) $pelanggan->total_hutang + (float) $hutang;
                    $pelanggan->save();
                }

                DB::commit();

                $synced[] = [
                    'local_id' => $item['local_id'],
                    'server_id' => $penjualan->id,
                    'no_faktur' => $penjualan->no_faktur,
                    'status' => 'success',
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                $failed[] = [
                    'local_id' => $item['local_id'],
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'synced' => $synced,
                'failed' => $failed,
            ],
        ]);
    }

    /**
     * Get hutang list
     */
    public function hutangList(Request $request)
    {
        $pelangganId = $request->input('pelanggan_id');
        $status = $request->input('status');

        $query = MineralHutang::with(['pelanggan', 'penjualan']);

        if ($pelangganId) {
            $query->where('pelanggan_id', $pelangganId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $hutangs = $query->orderBy('jatuh_tempo', 'asc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $hutangs->through(function ($h) {
                return [
                    'id' => $h->id,
                    'pelanggan' => [
                        'id' => $h->pelanggan->id,
                        'nama_toko' => $h->pelanggan->nama_toko,
                        'nama_pemilik' => $h->pelanggan->nama_pemilik,
                    ],
                    'penjualan' => [
                        'id' => $h->penjualan->id,
                        'no_faktur' => $h->penjualan->no_faktur,
                        'tanggal_jual' => $h->penjualan->tanggal_jual?->toDateTimeString(),
                    ],
                    'total_hutang' => (float) $h->total_hutang,
                    'dibayar' => (float) $h->dibayar,
                    'sisa' => (float) $h->sisa,
                    'jatuh_tempo' => $h->jatuh_tempo?->toDateString(),
                    'status' => $h->status,
                    'overdue' => $h->jatuh_tempo && $h->jatuh_tempo < now(),
                ];
            }),
        ]);
    }

    /**
     * Pay hutang
     */
    public function bayarHutang(Request $request, $id)
    {
        $hutang = MineralHutang::findOrFail($id);

        if ($hutang->status === 'lunas' || $hutang->sisa <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Hutang ini sudah lunas',
            ], 422);
        }

        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'cara_bayar' => 'required|in:tunai,transfer',
            'id_transaksi' => 'required_if:cara_bayar,transfer|nullable|string|min:3|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $pendingTotal = $hutang->pembayarans()->where('status', 'pending')->sum('jumlah');
        $effectiveSisa = max(0, (float) $hutang->sisa - (float) $pendingTotal);

        if ($effectiveSisa <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sisa hutang sudah tercover oleh pembayaran pending',
            ], 422);
        }

        if ($request->jumlah > $effectiveSisa) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah pembayaran melebihi sisa hutang',
                'sisa_efektif' => (float) $effectiveSisa,
            ], 422);
        }

        DB::beginTransaction();
        try {
            $hutang = MineralHutang::where('id', $hutang->id)->lockForUpdate()->first();

            $needsConfirmation = $request->cara_bayar === 'transfer' || $request->jumlah >= 500000;
            $paymentStatus = $needsConfirmation ? 'pending' : 'confirmed';

            $payment = MineralHutangBayar::create([
                'hutang_id'     => $hutang->id,
                'tanggal_bayar' => now(),
                'jumlah'        => $request->jumlah,
                'cara_bayar'    => $request->cara_bayar,
                'id_transaksi'  => $request->id_transaksi ?? null,
                'keterangan'    => $request->keterangan,
                'created_by'    => Auth::id(),
                'status'        => $paymentStatus,
            ]);

            if ($paymentStatus === 'confirmed') {
                $payment->confirmed_by = Auth::id();
                $payment->confirmed_at = now();
                $payment->save();

                $totalDibayar = $hutang->pembayarans()->confirmed()->sum('jumlah');
                $hutang->dibayar = $totalDibayar;
                $hutang->sisa = max(0, (float) $hutang->total_hutang - (float) $totalDibayar);
                $hutang->status = $hutang->sisa <= 0 ? 'lunas' : 'belum_lunas';
                $hutang->save();

                $pelanggan = MineralPelanggan::find($hutang->pelanggan_id);
                if ($pelanggan) {
                    $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $request->jumlah);
                    $pelanggan->save();
                }
            }

            DB::commit();

            $message = $paymentStatus === 'pending'
                ? 'Pembayaran berhasil diajukan. Menunggu konfirmasi supervisor.'
                : 'Pembayaran hutang berhasil dicatat';

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'payment_id' => $payment->id,
                    'status' => $paymentStatus,
                    'jumlah' => (float) $request->jumlah,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get setoran info for today
     */
    public function setoranInfo(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        $penjualanTunai = MineralPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('tipe_bayar', 'tunai')
            ->where('status', '!=', 'batal')
            ->sum('total');

        $penjualanTransfer = MineralPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('tipe_bayar', 'transfer')
            ->where('status', '!=', 'batal')
            ->sum('total');

        $hutangDibayar = MineralHutangBayar::whereDate('tanggal_bayar', $today)
            ->whereHas('hutang.penjualan', function ($q) use ($sales) {
                $q->where('sales_id', $sales->id);
            })
            ->sum('jumlah');

        $jumlahTransaksi = MineralPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('status', '!=', 'batal')
            ->count();

        $setoranSebelumnya = MineralSetoran::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'tanggal' => $today,
                'total_penjualan_tunai' => (float) $penjualanTunai,
                'total_penjualan_transfer' => (float) $penjualanTransfer,
                'total_hutang_dibayar' => (float) $hutangDibayar,
                'jumlah_transaksi' => $jumlahTransaksi,
                'total_setor_target' => (float) ($penjualanTunai + $hutangDibayar),
                'setoran_sebelumnya' => $setoranSebelumnya ? [
                    'id' => $setoranSebelumnya->id,
                    'status' => $setoranSebelumnya->status,
                    'total_setor' => (float) $setoranSebelumnya->total_setor,
                ] : null,
            ],
        ]);
    }

    /**
     * Create setoran
     */
    public function storeSetoran(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'total_setor' => 'required|numeric|min:0',
            'catatan_sales' => 'nullable|string',
            'bukti_setor' => 'nullable|string',
        ]);

        $existing = MineralSetoran::where('sales_id', $sales->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Setoran untuk tanggal ini sudah ada',
            ], 422);
        }

        $tanggal = $request->tanggal;

        $penjualanQuery = MineralPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $tanggal)
            ->where('status', '!=', 'batal');

        $totalPenjualan = (clone $penjualanQuery)->sum('total');
        $jumlahTransaksi = (clone $penjualanQuery)->count();
        $totalTunai = (clone $penjualanQuery)->where('tipe_bayar', 'tunai')->sum('total');
        $totalTransfer = (clone $penjualanQuery)->where('tipe_bayar', 'transfer')->sum('total');
        $totalHutangBaru = (clone $penjualanQuery)->where('tipe_bayar', 'hutang')->sum('hutang');
        $jumlahHutangBaru = (clone $penjualanQuery)->where('tipe_bayar', 'hutang')->count();

        $hutangIds = MineralHutang::whereHas('penjualan', function ($q) use ($sales) {
            $q->where('sales_id', $sales->id);
        })->pluck('id');

        $debtPaymentTunai = MineralHutangBayar::whereIn('hutang_id', $hutangIds)
            ->whereDate('tanggal_bayar', $tanggal)
            ->where('status', 'confirmed')
            ->where('cara_bayar', 'tunai')
            ->sum('jumlah');

        $selisih = (float) $request->total_setor - ((float) $totalTunai + (float) $debtPaymentTunai);

        $buktiSetorPath = null;
        if ($request->bukti_setor) {
            $imageData = base64_decode($request->bukti_setor);
            if ($imageData !== false) {
                $dir = 'bukti-setor/mineral/' . now()->format('Y-m-d');
                $filename = 'setoran_' . uniqid() . '.jpg';
                Storage::disk('public')->put($dir . '/' . $filename, $imageData);
                $buktiSetorPath = $dir . '/' . $filename;
            }
        }

        DB::beginTransaction();
        try {
            $setoran = MineralSetoran::create([
                'tanggal' => $request->tanggal,
                'sales_id' => $sales->id,
                'total_penjualan' => $totalPenjualan,
                'total_tunai' => $totalTunai,
                'total_transfer' => $totalTransfer,
                'total_setor' => $request->total_setor,
                'selisih' => $selisih,
                'jumlah_transaksi' => $jumlahTransaksi,
                'jumlah_hutang_baru' => $jumlahHutangBaru,
                'total_hutang_baru' => $totalHutangBaru,
                'status' => 'pending',
                'catatan_sales' => $request->catatan_sales,
                'bukti_setor' => $buktiSetorPath,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan setoran: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $setoran->id,
                'status' => $setoran->status,
                'selisih' => (float) $selisih,
                'total_tunai' => (float) $totalTunai,
                'total_transfer' => (float) $totalTransfer,
            ],
            'message' => 'Setoran berhasil dicatat, menunggu verifikasi',
        ], 201);
    }

    /**
     * Create kunjungan
     */
    public function storeKunjungan(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $request->validate([
            'pelanggan_id' => 'required|exists:mineral_pelanggan,id',
            'waktu_kunjungan' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $kunjungan = MineralKunjungan::create([
            'sales_id' => $sales->id,
            'pelanggan_id' => $request->pelanggan_id,
            'waktu_checkin' => $request->waktu_kunjungan,
            'latitude_checkin' => $request->latitude,
            'longitude_checkin' => $request->longitude,
            'catatan' => $request->keterangan,
            'status' => 'checkin',
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $kunjungan->id,
            ],
            'message' => 'Kunjungan berhasil dicatat',
        ], 201);
    }

    /**
     * Get penjualan history for current sales
     */
    public function penjualanHistory(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        $tipeBayar = $request->input('tipe_bayar');
        $perPage = $request->input('per_page', 15);

        $query = MineralPenjualan::where('sales_id', $sales->id)
            ->with(['pelanggan', 'produk'])
            ->when($tanggalMulai, function ($q) use ($tanggalMulai) {
                $q->whereDate('tanggal_jual', '>=', $tanggalMulai);
            })
            ->when($tanggalSelesai, function ($q) use ($tanggalSelesai) {
                $q->whereDate('tanggal_jual', '<=', $tanggalSelesai);
            })
            ->when($tipeBayar, function ($q) use ($tipeBayar) {
                $q->where('tipe_bayar', $tipeBayar);
            })
            ->orderBy('tanggal_jual', 'desc');

        $penjualans = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $penjualans->through(function ($p) {
                return [
                    'id' => $p->id,
                    'no_faktur' => $p->no_faktur,
                    'tanggal_jual' => $p->tanggal_jual?->toDateTimeString(),
                    'pelanggan' => [
                        'id' => $p->pelanggan->id,
                        'nama_toko' => $p->pelanggan->nama_toko,
                    ],
                    'produk' => [
                        'id' => $p->produk->id,
                        'nama' => $p->produk->nama,
                    ],
                    'jumlah' => $p->jumlah,
                    'total' => (float) $p->total,
                    'tipe_bayar' => $p->tipe_bayar,
                    'status' => $p->status,
                ];
            }),
        ]);
    }

    /**
     * Get kunjungan target for today
     */
    public function kunjunganTarget(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        // Get all active pelanggan
        $totalPelanggan = MineralPelanggan::where('status', 'aktif')->count();

        // Get today's kunjungan
        $kunjunganHariIni = MineralKunjungan::where('sales_id', $sales->id)
            ->whereDate('waktu_checkin', $today)
            ->with('pelanggan')
            ->get();

        $sudahDikunjungi = $kunjunganHariIni->count();

        // Get list of pelanggan that haven't been visited
        $dikunjungiIds = $kunjunganHariIni->pluck('pelanggan_id')->toArray();
        $belumDikunjungi = MineralPelanggan::where('status', 'aktif')
            ->whereNotIn('id', $dikunjungiIds)
            ->select('id', 'kode_pelanggan', 'nama_toko', 'alamat', 'kecamatan', 'kota')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'target_kunjungan' => min($totalPelanggan, 20),
                'sudah_dikunjungi' => $sudahDikunjungi,
                'belum_dikunjungi' => $belumDikunjungi->count(),
                'list_dikunjungi' => $kunjunganHariIni->map(function ($k) {
                    return [
                        'id' => $k->id,
                        'pelanggan_id' => $k->pelanggan_id,
                        'nama_toko' => $k->pelanggan->nama_toko,
                        'waktu_kunjungan' => $k->waktu_checkin?->toDateTimeString(),
                    ];
                }),
                'list_belum_dikunjungi' => $belumDikunjungi,
            ],
        ]);
    }

    /**
     * Get all kunjungan history
     */
    public function kunjunganList(Request $request)
    {
        $user = $request->user();
        $sales = MineralSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $perPage = $request->input('per_page', 15);
        $kunjungans = MineralKunjungan::where('sales_id', $sales->id)
            ->with('pelanggan')
            ->orderBy('waktu_checkin', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $kunjungans->through(function ($k) {
                return [
                    'id' => $k->id,
                    'pelanggan' => [
                        'id' => $k->pelanggan?->id,
                        'nama_toko' => $k->pelanggan?->nama_toko,
                        'nama_pemilik' => $k->pelanggan?->nama_pemilik,
                    ],
                    'waktu_checkin' => $k->waktu_checkin?->toDateTimeString(),
                    'created_at' => $k->created_at?->toDateTimeString(),
                    'latitude' => (float) $k->latitude_checkin,
                    'longitude' => (float) $k->longitude_checkin,
                    'keterangan' => $k->catatan,
                    'foto' => $k->foto_checkin ? asset('storage/' . $k->foto_checkin) : null,
                ];
            }),
        ]);
    }
}

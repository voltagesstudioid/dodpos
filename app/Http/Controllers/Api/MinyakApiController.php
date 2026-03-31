<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MinyakHutang;
use App\Models\MinyakHutangBayar;
use App\Models\MinyakKunjungan;
use App\Models\MinyakLoading;
use App\Models\MinyakPelanggan;
use App\Models\MinyakPenjualan;
use App\Models\MinyakProduk;
use App\Models\MinyakSales;
use App\Models\MinyakSetoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MinyakApiController extends Controller
{
    /**
     * Get dashboard data for logged in sales
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        // Today's sales stats
        $penjualanQuery = MinyakPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('status', '!=', 'batal');

        $totalPenjualan = $penjualanQuery->sum('total');
        $jumlahTransaksi = $penjualanQuery->count();
        $targetTercapai = $sales->target_harian > 0
            ? round(($totalPenjualan / $sales->target_harian) * 100, 2)
            : 0;

        // Today's loading
        $loadingHariIni = MinyakLoading::where('sales_id', $sales->id)
            ->whereDate('tanggal', $today)
            ->with('produk')
            ->get();

        $totalLoading = $loadingHariIni->sum('jumlah_loading');
        $totalTerjual = $loadingHariIni->sum('terjual');
        $totalSisa = $loadingHariIni->sum('sisa_stok');

        // Debt info
        $pelangganIds = MinyakPelanggan::whereHas('hutangs', function ($q) {
            $q->where('status', '!=', 'lunas');
        })->pluck('id');

        $totalPiutang = MinyakHutang::whereIn('pelanggan_id', $pelangganIds)
            ->where('status', '!=', 'lunas')
            ->sum('sisa');

        // Setoran status
        $setoranHariIni = MinyakSetoran::where('sales_id', $sales->id)
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
            ],
        ]);
    }

    /**
     * Get list of pelanggan with search and filter
     */
    public function pelangganList(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $status = $request->input('status', 'aktif');
        $perPage = $request->input('per_page', 15);

        $query = MinyakPelanggan::query()
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
        $pelanggan = MinyakPelanggan::findOrFail($id);

        $historyPenjualan = MinyakPenjualan::where('pelanggan_id', $id)
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

        $hutangAktif = MinyakHutang::where('pelanggan_id', $id)
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
        $sales = MinyakSales::where('user_id', $user->id)->first();
        $today = now()->toDateString();

        $produks = MinyakProduk::where('status', 'aktif')->get();

        $data = $produks->map(function ($p) use ($sales, $today) {
            $sisaStok = 0;
            if ($sales) {
                $loading = MinyakLoading::where('sales_id', $sales->id)
                    ->where('produk_id', $p->id)
                    ->whereDate('tanggal', $today)
                    ->first();
                $sisaStok = $loading?->sisa_stok ?? 0;
            }

            return [
                'id' => $p->id,
                'kode_produk' => $p->kode_produk,
                'nama' => $p->nama,
                'jenis' => $p->jenis,
                'satuan' => $p->satuan,
                'harga_jual' => (float) $p->harga_jual,
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
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        $loadings = MinyakLoading::where('sales_id', $sales->id)
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
     * Create new penjualan
     */
    public function storePenjualan(Request $request)
    {
        $request->validate([
            'tanggal_jual' => 'required|date',
            'pelanggan_id' => 'required|exists:minyak_pelanggan,id',
            'produk_id' => 'required|exists:minyak_produk,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tipe_bayar' => 'required|in:tunai,hutang,transfer',
            'bayar' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'foto_nota' => 'nullable|string', // base64 encoded
        ]);

        $user = $request->user();
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $pelanggan = MinyakPelanggan::findOrFail($request->pelanggan_id);
        $produk = MinyakProduk::findOrFail($request->produk_id);

        $total = $request->jumlah * $request->harga_satuan;
        $bayar = $request->bayar ?? 0;
        $kembali = $request->tipe_bayar === 'tunai' ? max(0, $bayar - $total) : 0;
        $hutang = $request->tipe_bayar === 'hutang' ? $total : 0;

        // Validate stock
        $today = now()->toDateString();
        $loading = MinyakLoading::where('sales_id', $sales->id)
            ->where('produk_id', $request->produk_id)
            ->whereDate('tanggal', $today)
            ->first();

        if (! $loading || $loading->sisa_stok < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi',
                'sisa_stok' => $loading?->sisa_stok ?? 0,
            ], 422);
        }

        // Validate hutang limit
        if ($request->tipe_bayar === 'hutang') {
            $totalHutangBaru = $pelanggan->total_hutang + $total;
            if ($totalHutangBaru > $pelanggan->limit_hutang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Limit hutang melebihi batas',
                    'limit_hutang' => $pelanggan->limit_hutang,
                    'total_hutang_saat_ini' => $pelanggan->total_hutang,
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // Handle foto_nota
            $fotoNotaPath = null;
            if ($request->foto_nota) {
                $imageData = base64_decode($request->foto_nota);
                $filename = 'nota/' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $imageData);
                $fotoNotaPath = $filename;
            }

            // Create penjualan
            $penjualan = MinyakPenjualan::create([
                'no_faktur' => MinyakPenjualan::generateFaktur(),
                'tanggal_jual' => $request->tanggal_jual,
                'sales_id' => $sales->id,
                'pelanggan_id' => $request->pelanggan_id,
                'produk_id' => $request->produk_id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $request->harga_satuan,
                'total' => $total,
                'tipe_bayar' => $request->tipe_bayar,
                'bayar' => $bayar,
                'kembali' => $kembali,
                'hutang' => $hutang,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'foto_nota' => $fotoNotaPath,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
            ]);

            // Update loading stock
            $loading->terjual += $request->jumlah;
            $loading->sisa_stok -= $request->jumlah;
            $loading->save();

            // Create hutang record if needed
            if ($hutang > 0) {
                MinyakHutang::create([
                    'pelanggan_id' => $request->pelanggan_id,
                    'penjualan_id' => $penjualan->id,
                    'total_hutang' => $hutang,
                    'dibayar' => 0,
                    'sisa' => $hutang,
                    'jatuh_tempo' => now()->addDays(30),
                    'status' => 'belum_lunas',
                ]);

                // Update pelanggan total hutang
                $pelanggan->total_hutang += $hutang;
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
            'penjualan.*.pelanggan_id' => 'required|exists:minyak_pelanggan,id',
            'penjualan.*.produk_id' => 'required|exists:minyak_produk,id',
            'penjualan.*.jumlah' => 'required|integer|min:1',
            'penjualan.*.harga_satuan' => 'required|numeric|min:0',
            'penjualan.*.tipe_bayar' => 'required|in:tunai,hutang,transfer',
        ]);

        $user = $request->user();
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $synced = [];
        $failed = [];

        foreach ($request->penjualan as $item) {
            try {
                $total = $item['jumlah'] * $item['harga_satuan'];
                $bayar = $item['bayar'] ?? 0;
                $kembali = $item['tipe_bayar'] === 'tunai' ? max(0, $bayar - $total) : 0;
                $hutang = $item['tipe_bayar'] === 'hutang' ? $total : 0;

                $penjualan = MinyakPenjualan::create([
                    'no_faktur' => MinyakPenjualan::generateFaktur(),
                    'tanggal_jual' => $item['tanggal_jual'],
                    'sales_id' => $sales->id,
                    'pelanggan_id' => $item['pelanggan_id'],
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'total' => $total,
                    'tipe_bayar' => $item['tipe_bayar'],
                    'bayar' => $bayar,
                    'kembali' => $kembali,
                    'hutang' => $hutang,
                    'latitude' => $item['latitude'] ?? null,
                    'longitude' => $item['longitude'] ?? null,
                    'keterangan' => $item['keterangan'] ?? null,
                    'status' => 'pending',
                ]);

                // Update loading stock
                $today = now()->toDateString();
                $loading = MinyakLoading::where('sales_id', $sales->id)
                    ->where('produk_id', $item['produk_id'])
                    ->whereDate('tanggal', $today)
                    ->first();

                if ($loading) {
                    $loading->terjual += $item['jumlah'];
                    $loading->sisa_stok -= $item['jumlah'];
                    $loading->save();
                }

                // Create hutang if needed
                if ($hutang > 0) {
                    MinyakHutang::create([
                        'pelanggan_id' => $item['pelanggan_id'],
                        'penjualan_id' => $penjualan->id,
                        'total_hutang' => $hutang,
                        'dibayar' => 0,
                        'sisa' => $hutang,
                        'jatuh_tempo' => now()->addDays(30),
                        'status' => 'belum_lunas',
                    ]);

                    $pelanggan = MinyakPelanggan::find($item['pelanggan_id']);
                    $pelanggan->total_hutang += $hutang;
                    $pelanggan->save();
                }

                $synced[] = [
                    'local_id' => $item['local_id'],
                    'server_id' => $penjualan->id,
                    'no_faktur' => $penjualan->no_faktur,
                    'status' => 'success',
                ];
            } catch (\Exception $e) {
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

        $query = MinyakHutang::with(['pelanggan', 'penjualan']);

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
        $hutang = MinyakHutang::findOrFail($id);

        $request->validate([
            'jumlah' => 'required|numeric|min:1|max:' . $hutang->sisa,
            'cara_bayar' => 'required|in:tunai,transfer',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            MinyakHutangBayar::create([
                'hutang_id' => $hutang->id,
                'tanggal_bayar' => now(),
                'jumlah' => $request->jumlah,
                'cara_bayar' => $request->cara_bayar,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id(),
            ]);

            $hutang->dibayar += $request->jumlah;
            $hutang->sisa -= $request->jumlah;

            if ($hutang->sisa <= 0) {
                $hutang->status = 'lunas';
            }

            $hutang->save();

            $pelanggan = MinyakPelanggan::find($hutang->pelanggan_id);
            $pelanggan->total_hutang -= $request->jumlah;
            $pelanggan->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran hutang berhasil dicatat',
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
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        $penjualanTunai = MinyakPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('tipe_bayar', 'tunai')
            ->where('status', '!=', 'batal')
            ->sum('total');

        $penjualanTransfer = MinyakPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('tipe_bayar', 'transfer')
            ->where('status', '!=', 'batal')
            ->sum('total');

        $hutangDibayar = MinyakHutangBayar::whereDate('tanggal_bayar', $today)
            ->whereHas('hutang.penjualan', function ($q) use ($sales) {
                $q->where('sales_id', $sales->id);
            })
            ->sum('jumlah');

        $jumlahTransaksi = MinyakPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $today)
            ->where('status', '!=', 'batal')
            ->count();

        $setoranSebelumnya = MinyakSetoran::where('sales_id', $sales->id)
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
        $sales = MinyakSales::where('user_id', $user->id)->first();

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
            'bukti_setor' => 'nullable|string', // base64
        ]);

        // Check if already exists
        $existing = MinyakSetoran::where('sales_id', $sales->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Setoran untuk tanggal ini sudah ada',
            ], 422);
        }

        // Calculate totals
        $penjualanQuery = MinyakPenjualan::where('sales_id', $sales->id)
            ->whereDate('tanggal_jual', $request->tanggal)
            ->where('status', 'terverifikasi');

        $totalPenjualan = $penjualanQuery->sum('total');
        $jumlahTransaksi = $penjualanQuery->count();
        $totalHutangBaru = $penjualanQuery->where('tipe_bayar', 'hutang')->sum('hutang');
        $jumlahHutangBaru = $penjualanQuery->where('tipe_bayar', 'hutang')->count();

        $selisih = $request->total_setor - $totalPenjualan;

        // Handle bukti setor
        $buktiSetorPath = null;
        if ($request->bukti_setor) {
            $imageData = base64_decode($request->bukti_setor);
            $filename = 'setoran/' . uniqid() . '.jpg';
            Storage::disk('public')->put($filename, $imageData);
            $buktiSetorPath = $filename;
        }

        $setoran = MinyakSetoran::create([
            'tanggal' => $request->tanggal,
            'sales_id' => $sales->id,
            'total_penjualan' => $totalPenjualan,
            'total_setor' => $request->total_setor,
            'selisih' => $selisih,
            'jumlah_transaksi' => $jumlahTransaksi,
            'jumlah_hutang_baru' => $jumlahHutangBaru,
            'total_hutang_baru' => $totalHutangBaru,
            'status' => 'pending',
            'catatan_sales' => $request->catatan_sales,
            'bukti_setor' => $buktiSetorPath,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $setoran->id,
                'status' => $setoran->status,
                'selisih' => (float) $selisih,
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
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $request->validate([
            'pelanggan_id' => 'required|exists:minyak_pelanggan,id',
            'waktu_kunjungan' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
            'ada_penjualan' => 'boolean',
            'penjualan_id' => 'nullable|exists:minyak_penjualan,id',
        ]);

        $kunjungan = MinyakKunjungan::create([
            'sales_id' => $sales->id,
            'pelanggan_id' => $request->pelanggan_id,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'keterangan' => $request->keterangan,
            'ada_penjualan' => $request->ada_penjualan ?? false,
            'penjualan_id' => $request->penjualan_id,
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
        $sales = MinyakSales::where('user_id', $user->id)->first();

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

        $query = MinyakPenjualan::where('sales_id', $sales->id)
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
        $sales = MinyakSales::where('user_id', $user->id)->first();

        if (! $sales) {
            return response()->json([
                'success' => false,
                'message' => 'Sales profile not found',
            ], 404);
        }

        $today = now()->toDateString();

        // Get all active pelanggan
        $totalPelanggan = MinyakPelanggan::where('status', 'aktif')->count();

        // Get today's kunjungan
        $kunjunganHariIni = MinyakKunjungan::where('sales_id', $sales->id)
            ->whereDate('waktu_kunjungan', $today)
            ->with('pelanggan')
            ->get();

        $sudahDikunjungi = $kunjunganHariIni->count();

        // Get list of pelanggan that haven't been visited
        $dikunjungiIds = $kunjunganHariIni->pluck('pelanggan_id')->toArray();
        $belumDikunjungi = MinyakPelanggan::where('status', 'aktif')
            ->whereNotIn('id', $dikunjungiIds)
            ->select('id', 'kode_pelanggan', 'nama_toko', 'alamat', 'kecamatan', 'kota')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'target_kunjungan' => min($totalPelanggan, 20), // reasonable daily target
                'sudah_dikunjungi' => $sudahDikunjungi,
                'belum_dikunjungi' => $belumDikunjungi->count(),
                'list_dikunjungi' => $kunjunganHariIni->map(function ($k) {
                    return [
                        'id' => $k->id,
                        'pelanggan_id' => $k->pelanggan_id,
                        'nama_toko' => $k->pelanggan->nama_toko,
                        'waktu_kunjungan' => $k->waktu_kunjungan?->toDateTimeString(),
                        'ada_penjualan' => $k->ada_penjualan,
                    ];
                }),
                'list_belum_dikunjungi' => $belumDikunjungi,
            ],
        ]);
    }
}

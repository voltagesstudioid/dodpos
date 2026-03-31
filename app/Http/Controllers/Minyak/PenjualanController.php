<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakPenjualan;
use App\Models\MinyakSales;
use App\Models\MinyakPelanggan;
use App\Models\MinyakProduk;
use App\Models\MinyakLoading;
use App\Models\MinyakHutang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $pelanggan_id = $request->input('pelanggan_id');
        $status = $request->input('status');
        $tipe_bayar = $request->input('tipe_bayar');

        $penjualans = MinyakPenjualan::with(['sales', 'pelanggan', 'produk'])
            ->when($search, function ($query) use ($search) {
                $query->where('no_faktur', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($q) use ($search) {
                        $q->where('nama_toko', 'like', "%{$search}%");
                    });
            })
            ->when($sales_id, function ($query) use ($sales_id) {
                $query->where('sales_id', $sales_id);
            })
            ->when($pelanggan_id, function ($query) use ($pelanggan_id) {
                $query->where('pelanggan_id', $pelanggan_id);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($tipe_bayar, function ($query) use ($tipe_bayar) {
                $query->where('tipe_bayar', $tipe_bayar);
            })
            ->orderBy('tanggal_jual', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sales = MinyakSales::aktif()->get();
        $pelanggans = MinyakPelanggan::where('status', 'aktif')->get();

        $stats = [
            'total_hari_ini' => MinyakPenjualan::whereDate('tanggal_jual', today())->sum('total'),
            'total_transaksi' => MinyakPenjualan::whereDate('tanggal_jual', today())->count(),
            'total_tunai' => MinyakPenjualan::whereDate('tanggal_jual', today())->where('tipe_bayar', 'tunai')->sum('total'),
            'total_hutang' => MinyakPenjualan::whereDate('tanggal_jual', today())->where('tipe_bayar', 'hutang')->sum('hutang'),
        ];

        return view('minyak.penjualan.index', compact('penjualans', 'sales', 'pelanggans', 'stats'));
    }

    public function create()
    {
        $sales = MinyakSales::aktif()->get();
        $pelanggans = MinyakPelanggan::where('status', 'aktif')->get();
        $produks = MinyakProduk::where('status', 'aktif')->get();

        return view('minyak.penjualan.create', compact('sales', 'pelanggans', 'produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_jual' => 'required|date',
            'sales_id' => 'required|exists:minyak_sales,id',
            'pelanggan_id' => 'required|exists:minyak_pelanggan,id',
            'produk_id' => 'required|exists:minyak_produk,id',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tipe_bayar' => 'required|in:tunai,hutang,transfer',
            'bayar' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

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

        DB::beginTransaction();
        try {
            $penjualan = MinyakPenjualan::create($validated);

            // Update stok loading sales
            $loading = MinyakLoading::where('sales_id', $validated['sales_id'])
                ->where('produk_id', $validated['produk_id'])
                ->where('sisa_stok', '>', 0)
                ->first();

            if ($loading) {
                $loading->terjual += $validated['jumlah'];
                $loading->sisa_stok -= $validated['jumlah'];
                $loading->save();
            }

            // Jika hutang, buat record hutang
            if ($validated['hutang'] > 0) {
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
                $pelanggan = MinyakPelanggan::find($validated['pelanggan_id']);
                $pelanggan->total_hutang += $validated['hutang'];
                $pelanggan->save();
            }

            DB::commit();

            return redirect()->route('minyak.penjualan.index')
                ->with('success', 'Penjualan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(MinyakPenjualan $penjualan)
    {
        $penjualan->load(['sales', 'pelanggan', 'produk', 'hutang.pembayarans']);
        
        return view('minyak.penjualan.show', compact('penjualan'));
    }

    public function verify(MinyakPenjualan $penjualan)
    {
        $penjualan->update([
            'status' => 'terverifikasi',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Penjualan berhasil diverifikasi.');
    }

    public function destroy(MinyakPenjualan $penjualan)
    {
        if ($penjualan->status === 'terverifikasi') {
            return redirect()->back()
                ->with('error', 'Penjualan yang sudah diverifikasi tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok
            $loading = MinyakLoading::where('sales_id', $penjualan->sales_id)
                ->where('produk_id', $penjualan->produk_id)
                ->first();

            if ($loading) {
                $loading->terjual -= $penjualan->jumlah;
                $loading->sisa_stok += $penjualan->jumlah;
                $loading->save();
            }

            // Hapus hutang jika ada
            if ($penjualan->hutang) {
                $pelanggan = MinyakPelanggan::find($penjualan->pelanggan_id);
                $pelanggan->total_hutang -= $penjualan->hutang;
                $pelanggan->save();
                
                $penjualan->hutang->delete();
            }

            $penjualan->delete();
            DB::commit();

            return redirect()->route('minyak.penjualan.index')
                ->with('success', 'Penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

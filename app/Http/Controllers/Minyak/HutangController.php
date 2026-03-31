<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakHutang;
use App\Models\MinyakHutangBayar;
use App\Models\MinyakPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $pelanggan_id = $request->input('pelanggan_id');
        $status = $request->input('status');

        $hutangs = MinyakHutang::with(['pelanggan', 'penjualan'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('pelanggan', function ($q) use ($search) {
                    $q->where('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%");
                });
            })
            ->when($pelanggan_id, function ($query) use ($pelanggan_id) {
                $query->where('pelanggan_id', $pelanggan_id);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('jatuh_tempo', 'asc')
            ->paginate(15)
            ->withQueryString();

        $pelanggans = MinyakPelanggan::where('status', 'aktif')->get();

        $stats = [
            'total_hutang' => MinyakHutang::sum('sisa'),
            'belum_lunas' => MinyakHutang::where('status', 'belum_lunas')->count(),
            'overdue' => MinyakHutang::overdue()->count(),
            'lunas' => MinyakHutang::where('status', 'lunas')->count(),
        ];

        return view('minyak.hutang.index', compact('hutangs', 'pelanggans', 'stats'));
    }

    public function show(MinyakHutang $hutang)
    {
        $hutang->load(['pelanggan', 'penjualan.sales', 'pembayarans.creator']);
        
        return view('minyak.hutang.show', compact('hutang'));
    }

    public function bayar(Request $request, MinyakHutang $hutang)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:1|max:' . $hutang->sisa,
            'cara_bayar' => 'required|in:tunai,transfer',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $validated['hutang_id'] = $hutang->id;
            $validated['tanggal_bayar'] = now();
            $validated['created_by'] = Auth::id();

            MinyakHutangBayar::create($validated);

            // Update hutang
            $hutang->dibayar += $validated['jumlah'];
            $hutang->sisa -= $validated['jumlah'];
            
            if ($hutang->sisa <= 0) {
                $hutang->status = 'lunas';
            }
            
            $hutang->save();

            // Update total hutang pelanggan
            $pelanggan = MinyakPelanggan::find($hutang->pelanggan_id);
            $pelanggan->total_hutang -= $validated['jumlah'];
            $pelanggan->save();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Pembayaran hutang berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

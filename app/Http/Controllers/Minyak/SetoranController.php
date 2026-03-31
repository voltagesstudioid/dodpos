<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakSetoran;
use App\Models\MinyakSales;
use App\Models\MinyakPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $status = $request->input('status');
        $tanggal = $request->input('tanggal');

        $setorans = MinyakSetoran::with(['sales', 'verifier'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('sales', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($sales_id, function ($query) use ($sales_id) {
                $query->where('sales_id', $sales_id);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($tanggal, function ($query) use ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sales = MinyakSales::aktif()->get();

        $stats = [
            'total_pending' => MinyakSetoran::where('status', 'pending')->count(),
            'total_terverifikasi' => MinyakSetoran::where('status', 'terverifikasi')->count(),
            'total_setoran_hari_ini' => MinyakSetoran::whereDate('tanggal', today())->where('status', 'terverifikasi')->sum('total_setor'),
        ];

        return view('minyak.setoran.index', compact('setorans', 'sales', 'stats'));
    }

    public function create()
    {
        $sales = MinyakSales::aktif()->get();
        
        return view('minyak.setoran.create', compact('sales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sales_id' => 'required|exists:minyak_sales,id',
            'total_setor' => 'required|numeric|min:0',
            'catatan_sales' => 'nullable|string',
        ]);

        // Hitung data penjualan hari ini
        $penjualan = MinyakPenjualan::where('sales_id', $validated['sales_id'])
            ->whereDate('tanggal_jual', $validated['tanggal'])
            ->where('status', 'terverifikasi');

        $total_penjualan = $penjualan->sum('total');
        $jumlah_transaksi = $penjualan->count();
        $total_hutang_baru = $penjualan->where('tipe_bayar', 'hutang')->sum('hutang');
        $jumlah_hutang_baru = $penjualan->where('tipe_bayar', 'hutang')->count();

        $validated['total_penjualan'] = $total_penjualan;
        $validated['jumlah_transaksi'] = $jumlah_transaksi;
        $validated['total_hutang_baru'] = $total_hutang_baru;
        $validated['jumlah_hutang_baru'] = $jumlah_hutang_baru;
        $validated['selisih'] = $validated['total_setor'] - $total_penjualan;
        $validated['status'] = 'pending';

        MinyakSetoran::create($validated);

        return redirect()->route('minyak.setoran.index')
            ->with('success', 'Setoran berhasil ditambahkan.');
    }

    public function show(MinyakSetoran $setoran)
    {
        $setoran->load(['sales', 'verifier']);
        
        return view('minyak.setoran.show', compact('setoran'));
    }

    public function verify(Request $request, MinyakSetoran $setoran)
    {
        $validated = $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $setoran->update([
            'status' => $validated['status'],
            'catatan_verifikasi' => $validated['catatan_verifikasi'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $message = $validated['status'] === 'terverifikasi' 
            ? 'Setoran berhasil diverifikasi.' 
            : 'Setoran ditolak.';

        return redirect()->back()->with('success', $message);
    }
}

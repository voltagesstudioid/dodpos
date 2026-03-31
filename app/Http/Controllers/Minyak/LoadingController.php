<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakLoading;
use App\Models\MinyakSales;
use App\Models\MinyakProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $tanggal = $request->input('tanggal');

        $loadings = MinyakLoading::with(['sales', 'produk'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('sales', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                })->orWhereHas('produk', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($sales_id, function ($query) use ($sales_id) {
                $query->where('sales_id', $sales_id);
            })
            ->when($tanggal, function ($query) use ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sales = MinyakSales::aktif()->get();
        
        $stats = [
            'total_hari_ini' => MinyakLoading::whereDate('tanggal', today())->sum('jumlah_loading'),
            'total_sales' => MinyakLoading::whereDate('tanggal', today())->distinct('sales_id')->count(),
        ];

        return view('minyak.loading.index', compact('loadings', 'sales', 'stats'));
    }

    public function create()
    {
        $sales = MinyakSales::aktif()->get();
        $produks = MinyakProduk::where('status', 'aktif')->get();
        
        return view('minyak.loading.create', compact('sales', 'produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sales_id' => 'required|exists:minyak_sales,id',
            'produk_id' => 'required|exists:minyak_produk,id',
            'jumlah_loading' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $validated['sisa_stok'] = $validated['jumlah_loading'];
        $validated['terjual'] = 0;
        $validated['status'] = 'loading';
        $validated['created_by'] = Auth::id();

        // Update stok gudang
        $produk = MinyakProduk::find($validated['produk_id']);
        $produk->stok_gudang -= $validated['jumlah_loading'];
        $produk->save();

        MinyakLoading::create($validated);

        return redirect()->route('minyak.loading.index')
            ->with('success', 'Loading harian berhasil ditambahkan.');
    }

    public function show(MinyakLoading $loading)
    {
        $loading->load(['sales', 'produk', 'creator']);
        
        return view('minyak.loading.show', compact('loading'));
    }

    public function edit(MinyakLoading $loading)
    {
        $sales = MinyakSales::aktif()->get();
        $produks = MinyakProduk::where('status', 'aktif')->get();
        
        return view('minyak.loading.edit', compact('loading', 'sales', 'produks'));
    }

    public function update(Request $request, MinyakLoading $loading)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sales_id' => 'required|exists:minyak_sales,id',
            'produk_id' => 'required|exists:minyak_produk,id',
            'jumlah_loading' => 'required|integer|min:1',
            'status' => 'required|in:loading,proses,selesai',
            'keterangan' => 'nullable|string',
        ]);

        $loading->update($validated);

        return redirect()->route('minyak.loading.index')
            ->with('success', 'Loading harian berhasil diperbarui.');
    }

    public function destroy(MinyakLoading $loading)
    {
        // Kembalikan stok ke gudang
        $produk = MinyakProduk::find($loading->produk_id);
        $produk->stok_gudang += $loading->jumlah_loading;
        $produk->save();

        $loading->delete();

        return redirect()->route('minyak.loading.index')
            ->with('success', 'Loading harian berhasil dihapus.');
    }
}

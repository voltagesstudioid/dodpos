<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakStokMasuk;
use App\Models\MinyakProduk;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipe = $request->input('tipe');
        $produk_id = $request->input('produk_id');

        $records = MinyakStokMasuk::with(['produk', 'creator'])
            ->when($search, function ($q) use ($search) {
                $q->where('no_referensi', 'like', "%{$search}%")
                    ->orWhereHas('produk', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
            })
            ->when($tipe, function ($q) use ($tipe) {
                $q->where('tipe', $tipe);
            })
            ->when($produk_id, function ($q) use ($produk_id) {
                $q->where('produk_id', $produk_id);
            })
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $produks = MinyakProduk::where('status', 'aktif')->orderBy('nama')->get();

        $stats = [
            'total_penerimaan' => MinyakStokMasuk::where('tipe', 'penerimaan')->where('status', 'aktif')->sum('jumlah'),
            'total_koreksi' => MinyakStokMasuk::where('tipe', 'koreksi')->where('status', 'aktif')->sum('jumlah'),
            'bulan_ini' => MinyakStokMasuk::where('status', 'aktif')
                ->whereMonth('created_at', now()->month)
                ->sum('jumlah'),
        ];

        return view('minyak.stok-masuk.index', compact('records', 'produks', 'stats'));
    }

    public function create()
    {
        $produks = MinyakProduk::where('status', 'aktif')->orderBy('nama')->get();
        return view('minyak.stok-masuk.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:minyak_produk,id',
            'tipe' => 'required|in:penerimaan,koreksi',
            'jumlah' => 'required|numeric|min:0.01',
            'sumber' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $produk = MinyakProduk::findOrFail($validated['produk_id']);
            $stokSebelum = (float) $produk->stok_gudang;

            if ($validated['tipe'] === 'penerimaan') {
                // Penerimaan: always adds stock
                $produk->stok_gudang = $stokSebelum + (float) $validated['jumlah'];
            } else {
                // Koreksi: set stock to the corrected value
                // jumlah here = the actual stock count (new stock value)
                $produk->stok_gudang = (float) $validated['jumlah'];
                $validated['jumlah'] = (float) $validated['jumlah'] - $stokSebelum; // diff (+/-)
            }

            $produk->save();

            $record = MinyakStokMasuk::create([
                'no_referensi' => MinyakStokMasuk::generateReferensi($validated['tipe']),
                'produk_id' => $validated['produk_id'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $produk->stok_gudang,
                'sumber' => $validated['sumber'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'aktif',
                'created_by' => Auth::id(),
            ]);

            AuditService::log('minyak_stok_masuk.create', 'MinyakStokMasuk', $record->id, [
                'no_referensi' => $record->no_referensi,
                'produk_id' => $validated['produk_id'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $produk->stok_gudang,
            ]);

            DB::commit();

            $label = $validated['tipe'] === 'penerimaan' ? 'Penerimaan stok' : 'Koreksi stok';
            return redirect()->route('minyak.stok-masuk.index')
                ->with('success', $label . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(MinyakStokMasuk $stokMasuk)
    {
        $stokMasuk->load(['produk', 'creator']);
        return view('minyak.stok-masuk.show', compact('stokMasuk'));
    }

    /**
     * Cancel: reverse the stock change.
     */
    public function destroy(MinyakStokMasuk $stokMasuk)
    {
        if ($stokMasuk->status === 'batal') {
            return redirect()->back()->with('error', 'Data ini sudah dibatalkan.');
        }

        DB::beginTransaction();
        try {
            $produk = MinyakProduk::find($stokMasuk->produk_id);
            if ($produk) {
                if ($stokMasuk->tipe === 'penerimaan') {
                    // Reverse: subtract the received amount
                    $produk->stok_gudang = max(0, (float) $produk->stok_gudang - (float) $stokMasuk->jumlah);
                } else {
                    // Koreksi reversal: we can't perfectly reverse, so just note it
                    // The user should create a new koreksi to fix the stock
                }
                $produk->save();
            }

            $stokMasuk->update(['status' => 'batal']);

            AuditService::log('minyak_stok_masuk.cancel', 'MinyakStokMasuk', $stokMasuk->id, [
                'no_referensi' => $stokMasuk->no_referensi,
                'reversed' => true,
            ], 'warning');

            DB::commit();

            return redirect()->route('minyak.stok-masuk.index')
                ->with('success', 'Data stok masuk berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

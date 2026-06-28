<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralStokMasuk;
use App\Models\MineralProduk;
use App\Models\Vehicle;
use App\Models\VehicleStock;
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

        $records = MineralStokMasuk::with(['produk', 'creator', 'vehicle'])
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

        $produks = MineralProduk::where('status', 'aktif')->orderBy('nama')->get();

        $stats = [
            'total_penerimaan' => MineralStokMasuk::where('tipe', 'penerimaan')->where('status', 'aktif')->sum('jumlah'),
            'total_koreksi' => MineralStokMasuk::where('tipe', 'koreksi')->where('status', 'aktif')->sum('jumlah'),
            'bulan_ini' => MineralStokMasuk::where('status', 'aktif')
                ->whereMonth('created_at', now()->month)
                ->sum('jumlah'),
        ];

        return view('mineral.stok-masuk.index', compact('records', 'produks', 'stats'));
    }

    public function create()
    {
        $produks = MineralProduk::where('status', 'aktif')->orderBy('nama')->get();
        $vehicles = Vehicle::orderBy('license_plate')->get();
        return view('mineral.stok-masuk.create', compact('produks', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:mineral_produk,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'tipe' => 'required|in:penerimaan,koreksi',
            'jumlah' => 'required|numeric|min:0.01',
            'sumber' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $produk = MineralProduk::findOrFail($validated['produk_id']);
            $stokSebelum = (float) $produk->stok_gudang;

            // Update vehicle_stocks
            $vehicleStock = VehicleStock::firstOrNew([
                'vehicle_id' => $validated['vehicle_id'],
                'produk_id' => $validated['produk_id'],
            ]);

            $oldJumlah = (float) ($vehicleStock->exists ? $vehicleStock->jumlah : 0);

            if ($validated['tipe'] === 'penerimaan') {
                $vehicleStock->jumlah = $oldJumlah + (float) $validated['jumlah'];
            } else {
                // Koreksi: user enters the actual physical stock count in the vehicle
                $vehicleStock->jumlah = max(0, (float) $validated['jumlah']);
                $validated['jumlah'] = (float) $validated['jumlah'] - $oldJumlah;
            }

            $vehicleStock->save();

            // Sync stok_gudang total
            $produk->recalculateStokGudang();

            // Reload to get the actual stok_gudang after recalculation
            $produk->refresh();

            $record = MineralStokMasuk::create([
                'no_referensi' => MineralStokMasuk::generateReferensi($validated['tipe']),
                'produk_id' => $validated['produk_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $produk->stok_gudang,
                'sumber' => $validated['sumber'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'aktif',
                'created_by' => Auth::id(),
            ]);

            AuditService::log('mineral_stok_masuk.create', 'MineralStokMasuk', $record->id, [
                'no_referensi' => $record->no_referensi,
                'produk_id' => $validated['produk_id'],
                'vehicle_id' => $validated['vehicle_id'],
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $produk->stok_gudang,
            ]);

            DB::commit();

            $label = $validated['tipe'] === 'penerimaan' ? 'Penerimaan barang' : 'Koreksi stok';
            return redirect()->route('mineral.stok-masuk.index')
                ->with('success', $label . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(MineralStokMasuk $stokMasuk)
    {
        $stokMasuk->load(['produk', 'creator', 'vehicle']);
        return view('mineral.stok-masuk.show', compact('stokMasuk'));
    }

    /**
     * Cancel: reverse the stock change.
     */
    public function destroy(MineralStokMasuk $stokMasuk)
    {
        if ($stokMasuk->status === 'batal') {
            return redirect()->back()->with('error', 'Data ini sudah dibatalkan.');
        }

        DB::beginTransaction();
        try {
            if ($stokMasuk->tipe === 'penerimaan' && $stokMasuk->vehicle_id) {
                $vehicleStock = VehicleStock::where('vehicle_id', $stokMasuk->vehicle_id)
                    ->where('produk_id', $stokMasuk->produk_id)
                    ->first();

                if ($vehicleStock) {
                    $vehicleStock->jumlah = max(0, (float) $vehicleStock->jumlah - (float) $stokMasuk->jumlah);
                    $vehicleStock->save();
                }
            }

            // Sync stok_gudang total
            $produk = MineralProduk::find($stokMasuk->produk_id);
            if ($produk) {
                $produk->recalculateStokGudang();
            }

            $stokMasuk->update(['status' => 'batal']);

            AuditService::log('mineral_stok_masuk.cancel', 'MineralStokMasuk', $stokMasuk->id, [
                'no_referensi' => $stokMasuk->no_referensi,
                'reversed' => true,
            ], 'warning');

            DB::commit();

            return redirect()->route('mineral.stok-masuk.index')
                ->with('success', 'Data stok masuk berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

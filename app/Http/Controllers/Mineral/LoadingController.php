<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralLoading;
use App\Models\MineralSales;
use App\Models\MineralProduk;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $tanggal = $request->input('tanggal');

        $loadings = MineralLoading::with(['sales', 'produk'])
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

        $sales = MineralSales::aktif()->get();

        // Group today's loading totals by product satuan (unit)
        $totalPerUnit = MineralLoading::whereDate('mineral_loading.tanggal', today())
            ->join('mineral_produk', 'mineral_loading.produk_id', '=', 'mineral_produk.id')
            ->select('mineral_produk.satuan', DB::raw('SUM(mineral_loading.jumlah_loading) as total'))
            ->groupBy('mineral_produk.satuan')
            ->pluck('total', 'satuan')
            ->toArray();

        $stats = [
            'total_per_unit' => $totalPerUnit,
            'total_sales' => MineralLoading::whereDate('tanggal', today())->distinct('sales_id')->count(),
        ];

        return view('mineral.loading.index', compact('loadings', 'sales', 'stats'));
    }

    public function create()
    {
        $sales = MineralSales::aktif()->get();
        $produks = MineralProduk::where('status', 'aktif')->get();
        
        return view('mineral.loading.create', compact('sales', 'produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sales_id' => 'required|exists:mineral_sales,id',
            'produk_id' => 'required|exists:mineral_produk,id',
            'jumlah_loading' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        // Cek duplikasi: 1 sales + 1 produk + 1 tanggal = 1 loading record
        $existing = MineralLoading::where('sales_id', $validated['sales_id'])
            ->where('produk_id', $validated['produk_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()
                ->with('error', 'Loading untuk produk ini pada tanggal yang sama sudah ada. Silakan edit loading yang sudah ada.');
        }

        DB::beginTransaction();
        try {
            $produk = MineralProduk::find($validated['produk_id']);
            if ($produk->stok_gudang < $validated['jumlah_loading']) {
                DB::rollBack();
                return redirect()->back()->withInput()
                    ->with('error', 'Stok gudang tidak mencukupi. Stok tersedia: ' . number_format($produk->stok_gudang, 2) . ' ' . $produk->satuan);
            }

            $validated['sisa_stok'] = $validated['jumlah_loading'];
            $validated['terjual'] = 0;
            $validated['status'] = 'loading';
            $validated['created_by'] = Auth::id();

            $produk->stok_gudang -= $validated['jumlah_loading'];
            $produk->save();

            $loading = MineralLoading::create($validated);

            AuditService::logInventory('loading.create', 'MineralLoading', $loading->id, [
                'produk' => $produk->nama,
                'sales_id' => $validated['sales_id'],
                'jumlah_loading' => $validated['jumlah_loading'],
                'stok_gudang_sebelum' => $produk->stok_gudang + $validated['jumlah_loading'],
                'stok_gudang_sesudah' => $produk->stok_gudang,
            ]);

            DB::commit();

            return redirect()->route('mineral.loading.index')
                ->with('success', 'Loading harian berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(MineralLoading $loading)
    {
        $loading->load(['sales', 'produk', 'creator']);
        
        return view('mineral.loading.show', compact('loading'));
    }

    public function edit(MineralLoading $loading)
    {
        $sales = MineralSales::aktif()->get();
        $produks = MineralProduk::where('status', 'aktif')->get();
        
        return view('mineral.loading.edit', compact('loading', 'sales', 'produks'));
    }

    public function update(Request $request, MineralLoading $loading)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sales_id' => 'required|exists:mineral_sales,id',
            'produk_id' => 'required|exists:mineral_produk,id',
            'jumlah_loading' => 'required|numeric|min:0.01',
            'status' => 'required|in:loading,proses,selesai',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldJumlah = (float) $loading->jumlah_loading;
            $newJumlah = (float) $validated['jumlah_loading'];
            $diff = $newJumlah - $oldJumlah;

            $oldProdukId = $loading->produk_id;
            $newProdukId = $validated['produk_id'];

            if ($oldProdukId !== $newProdukId) {
                // Produk berubah: kembalikan stok lama, kurangi stok baru
                $oldProduk = MineralProduk::find($oldProdukId);
                $oldProduk->stok_gudang += $oldJumlah;
                $oldProduk->save();

                $newProduk = MineralProduk::find($newProdukId);
                if ($newProduk->stok_gudang < $newJumlah) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', 'Stok gudang tidak cukup untuk produk baru. Stok tersedia: ' . number_format($newProduk->stok_gudang, 2) . ' ' . $newProduk->satuan);
                }
                $newProduk->stok_gudang -= $newJumlah;
                $newProduk->save();

                $validated['sisa_stok'] = $newJumlah - (float) $loading->terjual;
                $validated['terjual'] = $loading->terjual;

                AuditService::logInventory('loading.update.product_change', 'MineralLoading', $loading->id, [
                    'old_produk_id' => $oldProdukId,
                    'new_produk_id' => $newProdukId,
                    'old_jumlah' => $oldJumlah,
                    'new_jumlah' => $newJumlah,
                ]);
            } elseif ($diff != 0) {
                // Produk sama, jumlah berubah: sesuaikan stok gudang
                $produk = MineralProduk::find($newProdukId);

                if ($diff > 0) {
                    if ($produk->stok_gudang < $diff) {
                        DB::rollBack();
                        return redirect()->back()->withInput()
                            ->with('error', 'Stok gudang tidak cukup untuk penambahan. Stok tersedia: ' . number_format($produk->stok_gudang, 2) . ' ' . $produk->satuan);
                    }
                }

                $stokSebelum = $produk->stok_gudang;
                $produk->stok_gudang -= $diff;
                $produk->save();

                $validated['sisa_stok'] = $newJumlah - (float) $loading->terjual;
                $validated['terjual'] = $loading->terjual;

                AuditService::logInventory('loading.update.quantity_change', 'MineralLoading', $loading->id, [
                    'produk' => $produk->nama,
                    'old_jumlah' => $oldJumlah,
                    'new_jumlah' => $newJumlah,
                    'diff' => $diff,
                    'stok_gudang_sebelum' => $stokSebelum,
                    'stok_gudang_sesudah' => $produk->stok_gudang,
                ]);
            }

            // Auto-update status
            if (isset($validated['sisa_stok']) && $validated['sisa_stok'] <= 0) {
                $validated['status'] = 'selesai';
            }

            $loading->update($validated);

            DB::commit();

            return redirect()->route('mineral.loading.index')
                ->with('success', 'Loading harian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(MineralLoading $loading)
    {
        DB::beginTransaction();
        try {
            $produk = MineralProduk::find($loading->produk_id);
            $stokSebelum = $produk->stok_gudang;
            $produk->stok_gudang += $loading->jumlah_loading;
            $produk->save();

            AuditService::logInventory('loading.delete', 'MineralLoading', $loading->id, [
                'produk' => $produk->nama,
                'jumlah_loading' => $loading->jumlah_loading,
                'stok_gudang_sebelum' => $stokSebelum,
                'stok_gudang_sesudah' => $produk->stok_gudang,
            ]);

            $loading->delete();

            DB::commit();

            return redirect()->route('mineral.loading.index')
                ->with('success', 'Loading harian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show batch distribution form (split stock to multiple vehicles).
     */
    public function distribusi(Request $request)
    {
        $sales = MineralSales::aktif()->get();
        $produks = MineralProduk::where('status', 'aktif')->orderBy('nama')->get();

        return view('mineral.loading.distribusi', compact('sales', 'produks'));
    }

    /**
     * Store batch distribution — create multiple loading records at once.
     */
    public function storeDistribusi(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'produk_id' => 'required|exists:mineral_produk,id',
            'items' => 'required|array|min:1',
            'items.*.sales_id' => 'required|exists:mineral_sales,id',
            'items.*.jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $produk = MineralProduk::findOrFail($validated['produk_id']);
            $totalDistribusi = collect($validated['items'])->sum('jumlah');

            // Validate total vs available stock
            if ($totalDistribusi > $produk->stok_gudang) {
                DB::rollBack();
                return redirect()->back()->withInput()
                    ->with('error', 'Total distribusi (' . number_format($totalDistribusi, 2, ',', '.') . ' ' . $produk->satuan . ') melebihi stok gudang (' . number_format($produk->stok_gudang, 2, ',', '.') . ' ' . $produk->satuan . ').');
            }

            // Check for duplicate sales on same date+product
            $salesIds = collect($validated['items'])->pluck('sales_id')->unique();
            $existingLoadings = MineralLoading::where('produk_id', $validated['produk_id'])
                ->whereDate('tanggal', $validated['tanggal'])
                ->whereIn('sales_id', $salesIds)
                ->pluck('sales_id')
                ->toArray();

            if (!empty($existingLoadings)) {
                $dupNames = MineralSales::whereIn('id', $existingLoadings)->pluck('nama')->implode(', ');
                DB::rollBack();
                return redirect()->back()->withInput()
                    ->with('error', 'Sales berikut sudah memiliki loading untuk produk & tanggal ini: ' . $dupNames);
            }

            $created = [];
            $stokSebelum = (float) $produk->stok_gudang;

            foreach ($validated['items'] as $item) {
                $loading = MineralLoading::create([
                    'tanggal' => $validated['tanggal'],
                    'sales_id' => $item['sales_id'],
                    'produk_id' => $validated['produk_id'],
                    'jumlah_loading' => (float) $item['jumlah'],
                    'sisa_stok' => (float) $item['jumlah'],
                    'terjual' => 0,
                    'status' => 'loading',
                    'keterangan' => $validated['keterangan'] ?? null,
                    'created_by' => Auth::id(),
                ]);
                $created[] = $loading;
            }

            // Reduce stok_gudang by total
            $produk->stok_gudang = $stokSebelum - $totalDistribusi;
            $produk->save();

            AuditService::logInventory('loading.distribusi', 'MineralProduk', $produk->id, [
                'produk' => $produk->nama,
                'total_distribusi' => $totalDistribusi,
                'jumlah_sales' => count($validated['items']),
                'stok_gudang_sebelum' => $stokSebelum,
                'stok_gudang_sesudah' => $produk->stok_gudang,
            ]);

            DB::commit();

            return redirect()->route('mineral.loading.index')
                ->with('success', 'Distribusi stok berhasil! ' . count($created) . ' sales dimuat, total ' . number_format($totalDistribusi, 2, ',', '.') . ' ' . $produk->satuan . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakProduk;
use App\Models\MinyakLoading;
use App\Models\MinyakSales;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekonsiliasiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $salesId = $request->input('sales_id');

        // Get all active products
        $produks = MinyakProduk::where('status', 'aktif')->get();

        // Get loadings for date
        $loadingsQuery = MinyakLoading::whereDate('tanggal', $tanggal);
        if ($salesId) {
            $loadingsQuery->where('sales_id', $salesId);
        }
        $loadings = $loadingsQuery->with(['produk', 'sales'])->get();

        // Get sales for filter
        $salesList = MinyakSales::where('status', 'aktif')->orderBy('nama')->get();

        // Build reconciliation data
        $rekonsiliasi = [];
        $totalSelisih = 0;
        foreach ($produks as $produk) {
            $loadingData = $loadings->where('produk_id', $produk->id);
            
            $jumlahLoading = $loadingData->sum('jumlah_loading');
            $terjual = $loadingData->sum('terjual');
            $sisaSistem = $loadingData->sum('sisa_stok');

            $rekonsiliasi[] = [
                'produk' => $produk,
                'jumlah_loading' => $jumlahLoading,
                'terjual' => $terjual,
                'sisa_sistem' => $sisaSistem,
                'sisa_fisik' => $sisaSistem, // Default = sistem (user will input actual)
                'selisih' => 0,
                'status' => 'sesuai',
            ];
        }

        // Stats
        $stats = [
            'total_loading' => $loadings->count(),
            'total_sales' => $loadings->unique('sales_id')->count(),
            'total_produk' => count($rekonsiliasi),
        ];

        return view('minyak.rekonsiliasi.index', compact(
            'rekonsiliasi',
            'loadings',
            'salesList',
            'tanggal',
            'salesId',
            'stats'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'sales_id' => 'nullable|exists:minyak_sales,id',
            'rekonsiliasi' => 'required|array',
            'rekonsiliasi.*.produk_id' => 'required|exists:minyak_produk,id',
            'rekonsiliasi.*.sisa_fisik' => 'required|numeric|min:0',
            'rekonsiliasi.*.keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $adjustments = [];

            foreach ($validated['rekonsiliasi'] as $item) {
                $produkId = $item['produk_id'];
                $sisaFisik = (float) $item['sisa_fisik'];
                $keterangan = $item['keterangan'] ?? null;

                // Find loading records for this product on this date
                $loadingsQuery = MinyakLoading::whereDate('tanggal', $validated['tanggal'])
                    ->where('produk_id', $produkId);

                if ($validated['sales_id']) {
                    $loadingsQuery->where('sales_id', $validated['sales_id']);
                }

                $loadings = $loadingsQuery->get();

                if ($loadings->isEmpty()) continue;

                $sisaSistem = $loadings->sum('sisa_stok');
                $selisih = $sisaFisik - $sisaSistem;

                if (abs($selisih) > 0) {
                    // Distribute adjustment proportionally across loading records
                    $totalSisaSistem = max(1, $sisaSistem);
                    foreach ($loadings as $loading) {
                        $ratio = $totalSisaSistem > 0
                            ? $loading->sisa_stok / $totalSisaSistem
                            : 1 / $loadings->count();
                        $adjustment = round($selisih * $ratio);

                        $oldSisa = $loading->sisa_stok;
                        $loading->sisa_stok = max(0, $loading->sisa_stok + $adjustment);
                        $loading->save();

                        $adjustments[] = [
                            'loading_id' => $loading->id,
                            'produk' => $loading->produk->nama ?? $produkId,
                            'sales' => $loading->sales->nama ?? $validated['sales_id'],
                            'sisa_sistem' => $oldSisa,
                            'sisa_fisik' => $loading->sisa_stok,
                            'adjustment' => $adjustment,
                        ];
                    }
                }
            }

            AuditService::logInventory('rekonsiliasi.save', 'MinyakRekonsiliasi', null, [
                'tanggal' => $validated['tanggal'],
                'sales_id' => $validated['sales_id'],
                'adjustments' => $adjustments,
                'total_adjustments' => count($adjustments),
            ]);

            DB::commit();

            return redirect()->route('minyak.rekonsiliasi.index', [
                'tanggal' => $validated['tanggal'],
                'sales_id' => $validated['sales_id'] ?? null,
            ])->with('success', 'Rekonsiliasi berhasil disimpan. ' . count($adjustments) . ' penyesuaian stok dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

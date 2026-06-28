<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralLoading;
use App\Models\MineralSales;
use App\Models\MineralProduk;
use App\Models\VehicleStock;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadingController extends Controller
{
    private function userCanApprove(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return in_array($role, ['supervisor', 'admin1', 'admin2']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $tanggal = $request->input('tanggal');

        $loadings = MineralLoading::with(['sales', 'produk', 'mobilInti'])
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
            ->orderByRaw("FIELD(status_approval, 'pending', 'approved', 'rejected')")
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        $sales = MineralSales::aktif()->get();

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

        $canApprove = $this->userCanApprove();
        $pendingCount = MineralLoading::where('status_approval', 'pending')->count();

        return view('mineral.loading.index', compact('loadings', 'sales', 'stats', 'canApprove', 'pendingCount'));
    }

    public function create()
    {
        $salesSub = MineralSales::with('vehicle')->aktif()->where('is_inti', false)->get();
        $produks = MineralProduk::where('status', 'aktif')->get();
        $mobilInti = MineralSales::with('vehicle')->aktif()->where('is_inti', true)->get();
        
        return view('mineral.loading.create', compact('salesSub', 'produks', 'mobilInti'));
    }

    public function vehicleStock(Request $request, $vehicleId, $produkId)
    {
        $stock = VehicleStock::where('vehicle_id', $vehicleId)
            ->where('produk_id', $produkId)
            ->first();

        return response()->json([
            'vehicle_id' => $vehicleId,
            'produk_id' => $produkId,
            'jumlah' => $stock ? (float) $stock->jumlah : 0,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'produk_id' => 'required|exists:mineral_produk,id',
            'mobil_inti_id' => 'required|exists:mineral_sales,id',
            'items' => 'required|array|min:1',
            'items.*.sales_id' => 'required|exists:mineral_sales,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'keterangan' => 'nullable|string',
        ]);

        $mobilInti = MineralSales::find($validated['mobil_inti_id']);
        if (!$mobilInti || !$mobilInti->is_inti) {
            return redirect()->back()->withInput()
                ->with('error', 'Sales yang dipilih sebagai Mobil Inti tidak valid.');
        }

        if (!$mobilInti->vehicle_id) {
            return redirect()->back()->withInput()
                ->with('error', 'Mobil Inti belum memiliki kendaraan yang terhubung.');
        }

        DB::beginTransaction();
        try {
            $salesIds = collect($validated['items'])->pluck('sales_id')->unique();

            foreach ($salesIds as $sid) {
                if ((int) $sid === (int) $validated['mobil_inti_id']) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', 'Sales pemohon tidak boleh sama dengan Mobil Inti.');
                }
                $salesCheck = MineralSales::find($sid);
                if ($salesCheck && $salesCheck->is_inti) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', "Sales {$salesCheck->nama} adalah Mobil Inti dan tidak bisa meminta stok ke dirinya sendiri.");
                }

                if (!$salesCheck->vehicle_id) {
                    DB::rollBack();
                    return redirect()->back()->withInput()
                        ->with('error', "Sales {$salesCheck->nama} belum dihubungkan dengan kendaraan.");
                }
            }

            $existingLoadings = MineralLoading::where('produk_id', $validated['produk_id'])
                ->whereDate('tanggal', $validated['tanggal'])
                ->where('mobil_inti_id', $validated['mobil_inti_id'])
                ->where('status_approval', '!=', 'rejected')
                ->whereIn('sales_id', $salesIds)
                ->pluck('sales_id')
                ->toArray();

            if (!empty($existingLoadings)) {
                $dupNames = MineralSales::whereIn('id', $existingLoadings)->pluck('nama')->implode(', ');
                DB::rollBack();
                return redirect()->back()->withInput()
                    ->with('error', 'Sales berikut sudah memiliki permintaan aktif untuk produk, tanggal & mobil inti ini: ' . $dupNames);
            }

            foreach ($validated['items'] as $item) {
                MineralLoading::create([
                    'tanggal' => $validated['tanggal'],
                    'sales_id' => $item['sales_id'],
                    'mobil_inti_id' => $validated['mobil_inti_id'],
                    'produk_id' => $validated['produk_id'],
                    'jumlah_loading' => (float) $item['jumlah'],
                    'sisa_stok' => 0,
                    'terjual' => 0,
                    'status' => 'loading',
                    'status_approval' => 'pending',
                    'keterangan' => $validated['keterangan'] ?? null,
                    'created_by' => Auth::id(),
                ]);
            }

            AuditService::logInventory('loading.create', 'MineralLoading', 0, [
                'tanggal' => $validated['tanggal'],
                'produk_id' => $validated['produk_id'],
                'mobil_inti_id' => $validated['mobil_inti_id'],
                'jumlah_sales' => count($validated['items']),
                'total_loading' => collect($validated['items'])->sum('jumlah'),
            ]);

            DB::commit();

            $count = count($validated['items']);
            $msg = $count > 1
                ? "Permintaan penugasan berhasil! {$count} sales menunggu persetujuan."
                : 'Permintaan penugasan berhasil, menunggu persetujuan.';

            return redirect()->route('mineral.loading.index')
                ->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(MineralLoading $loading)
    {
        $loading->load(['sales', 'produk', 'creator', 'mobilInti', 'approver']);
        
        return view('mineral.loading.show', compact('loading'));
    }

    public function edit(MineralLoading $loading)
    {
        if ($loading->status_approval !== 'approved') {
            return redirect()->route('mineral.loading.index')
                ->with('error', 'Hanya penugasan yang sudah disetujui yang dapat diedit.');
        }

        $sales = MineralSales::aktif()->get();
        $produks = MineralProduk::where('status', 'aktif')->get();
        
        return view('mineral.loading.edit', compact('loading', 'sales', 'produks'));
    }

    public function update(Request $request, MineralLoading $loading)
    {
        if ($loading->status_approval !== 'approved') {
            return redirect()->route('mineral.loading.index')
                ->with('error', 'Hanya penugasan yang sudah disetujui yang dapat diedit.');
        }

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
            $validated['sisa_stok'] = (float) $validated['jumlah_loading'] - (float) $loading->terjual;

            if ($validated['sisa_stok'] <= 0) {
                $validated['status'] = 'selesai';
            }

            $loading->update($validated);

            DB::commit();

            return redirect()->route('mineral.loading.index')
                ->with('success', 'Penugasan kendaraan berhasil diperbarui.');
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
            if ($loading->status_approval === 'approved') {
                $mobilInti = MineralSales::find($loading->mobil_inti_id);
                $subSales = MineralSales::find($loading->sales_id);

                if ($mobilInti && $subSales && $mobilInti->vehicle_id && $subSales->vehicle_id) {
                    VehicleStock::where('vehicle_id', $subSales->vehicle_id)
                        ->where('produk_id', $loading->produk_id)
                        ->decrement('jumlah', (float) $loading->jumlah_loading);

                    $returnStock = VehicleStock::firstOrNew([
                        'vehicle_id' => $mobilInti->vehicle_id,
                        'produk_id' => $loading->produk_id,
                    ]);
                    $returnStock->jumlah = ((float) $returnStock->jumlah) + (float) $loading->jumlah_loading;
                    $returnStock->save();

                    MineralProduk::find($loading->produk_id)?->recalculateStokGudang();
                }
            }

            $loading->delete();

            DB::commit();

            return redirect()->route('mineral.loading.index')
                ->with('success', 'Penugasan kendaraan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approve(MineralLoading $loading)
    {
        if (!$this->userCanApprove()) {
            return redirect()->route('mineral.loading.index')
                ->with('error', 'Anda tidak memiliki izin untuk menyetujui permintaan.');
        }

        if ($loading->status_approval !== 'pending') {
            return redirect()->route('mineral.loading.index')
                ->with('error', 'Permintaan ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            $mobilInti = MineralSales::findOrFail($loading->mobil_inti_id);
            $subSales = MineralSales::findOrFail($loading->sales_id);

            $mainVehicleId = $mobilInti->vehicle_id;
            $subVehicleId = $subSales->vehicle_id;

            if (!$mainVehicleId || !$subVehicleId) {
                DB::rollBack();
                return redirect()->route('mineral.loading.index')
                    ->with('error', 'Mobil inti atau sales sub belum memiliki data kendaraan.');
            }

            $mainStock = VehicleStock::firstOrNew([
                'vehicle_id' => $mainVehicleId,
                'produk_id' => $loading->produk_id,
            ]);

            $jumlah = (float) $loading->jumlah_loading;

            if ((float) $mainStock->jumlah < $jumlah) {
                DB::rollBack();
                return redirect()->route('mineral.loading.index')
                    ->with('error', 'Stok di mobil inti tidak mencukupi. Tersedia: ' . ((float) $mainStock->jumlah) . ', diminta: ' . $jumlah);
            }

            VehicleStock::where('vehicle_id', $mainVehicleId)
                ->where('produk_id', $loading->produk_id)
                ->decrement('jumlah', $jumlah);

            $subStock = VehicleStock::firstOrNew([
                'vehicle_id' => $subVehicleId,
                'produk_id' => $loading->produk_id,
            ]);
            $subStock->jumlah = ((float) $subStock->jumlah) + $jumlah;
            $subStock->save();

            $loading->update([
                'sisa_stok' => $jumlah,
                'status_approval' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            MineralProduk::find($loading->produk_id)?->recalculateStokGudang();

            AuditService::logInventory('loading.approve', 'MineralLoading', $loading->id, [
                'sales_id' => $loading->sales_id,
                'mobil_inti_id' => $loading->mobil_inti_id,
                'produk_id' => $loading->produk_id,
                'jumlah' => $jumlah,
            ]);

            DB::commit();

            return redirect()->route('mineral.loading.index')
                ->with('success', 'Permintaan penugasan disetujui. Stok telah dipindahkan ke mobil sub.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mineral.loading.index')
                ->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, MineralLoading $loading)
    {
        if (!$this->userCanApprove()) {
            return redirect()->route('mineral.loading.index')
                ->with('error', 'Anda tidak memiliki izin untuk menolak permintaan.');
        }

        if ($loading->status_approval !== 'pending') {
            return redirect()->route('mineral.loading.index')
                ->with('error', 'Permintaan ini sudah diproses.');
        }

        $validated = $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $loading->update([
            'status_approval' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'alasan' => $validated['alasan'],
        ]);

        return redirect()->route('mineral.loading.index')
            ->with('success', 'Permintaan penugasan ditolak.');
    }
}

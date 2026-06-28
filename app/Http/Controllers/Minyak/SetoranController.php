<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakSetoran;
use App\Models\MinyakSales;
use App\Models\MinyakPenjualan;
use App\Models\MinyakHutangBayar;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    private function getSalesProfile()
    {
        return MinyakSales::where('user_id', Auth::id())->first();
    }

    /**
     * Calculate sales summary for a given sales+date (exclude batal).
     * Only count verified/confirmed penjualan for accurate reconciliation.
     */
    private function calculateSalesSummary(int $salesId, string $tanggal): array
    {
        $penjualan = MinyakPenjualan::where('sales_id', $salesId)
            ->whereDate('tanggal_jual', $tanggal)
            ->where('status', '!=', 'batal');

        return [
            'total_penjualan' => (clone $penjualan)->sum('total'),
            'jumlah_transaksi' => (clone $penjualan)->count(),
            'total_hutang_baru' => (clone $penjualan)->where('tipe_bayar', 'hutang')->sum('hutang'),
            'jumlah_hutang_baru' => (clone $penjualan)->where('tipe_bayar', 'hutang')->count(),
            'total_tunai' => (clone $penjualan)->where('tipe_bayar', 'tunai')->sum('total'),
            'total_transfer' => (clone $penjualan)->where('tipe_bayar', 'transfer')->sum('total'),
        ];
    }

    /**
     * Calculate debt payments received by sales on a given date (confirmed only).
     */
    private function calculateDebtPayments(int $salesId, string $tanggal): float
    {
        return MinyakHutangBayar::whereHas('hutang', function ($q) use ($salesId) {
                $q->whereHas('penjualan', function ($q2) use ($salesId) {
                    $q2->where('sales_id', $salesId);
                });
            })
            ->whereDate('tanggal_bayar', $tanggal)
            ->where('status', 'confirmed')
            ->where('cara_bayar', 'tunai')
            ->sum('jumlah');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $status = $request->input('status');
        $tanggal = $request->input('tanggal');

        $query = MinyakSetoran::with(['sales', 'verifier']);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) $query->where('sales_id', $profile->id);
            $sales = collect([$profile]);
        } else {
            $query->when($sales_id, function ($q) use ($sales_id) {
                $q->where('sales_id', $sales_id);
            });
            $sales = MinyakSales::aktif()->get();
        }

        $setorans = $query
            ->when($search, function ($q) use ($search) {
                $q->whereHas('sales', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($tanggal, function ($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal);
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Stats scoped
        $baseQuery = MinyakSetoran::query();
        if ($this->isSales() && $profile) $baseQuery->where('sales_id', $profile->id);

        $stats = [
            'total_pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'total_terverifikasi' => (clone $baseQuery)->where('status', 'terverifikasi')->count(),
            'total_setoran_hari_ini' => (clone $baseQuery)->whereDate('tanggal', today())->where('status', 'terverifikasi')->sum('total_setor'),
        ];

        $isSalesRole = $this->isSales();

        return view('minyak.setoran.index', compact('setorans', 'sales', 'stats', 'isSalesRole'));
    }

    public function create()
    {
        $isSalesRole = $this->isSales();

        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            $sales = collect([$profile]);
            $salesId = $profile->id;
        } else {
            $sales = MinyakSales::aktif()->get();
            $salesId = null;
        }

        // Cari summary untuk tanggal default (hari ini)
        $defaultDate = old('tanggal', today()->toDateString());
        $summary = $salesId ? $this->calculateSalesSummary($salesId, $defaultDate) : null;
        $debtPayment = $salesId ? $this->calculateDebtPayments($salesId, $defaultDate) : 0;

        return view('minyak.setoran.create', compact(
            'sales', 'isSalesRole', 'summary', 'debtPayment', 'salesId'
        ));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal' => 'required|date',
            'sales_id' => $this->isSales() ? 'nullable' : 'required|exists:minyak_sales,id',
            'total_setor' => 'required|numeric|min:0',
            'catatan_sales' => 'nullable|string',
            'bukti_setor' => 'required|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];

        $validated = $request->validate($rules);

        // Force own sales_id for sales role
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile) abort(403, 'Profil sales tidak ditemukan.');
            $validated['sales_id'] = $profile->id;
        }

        // Cek duplikasi setoran untuk tanggal yang sama
        $existing = MinyakSetoran::where('sales_id', $validated['sales_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()
                ->with('error', 'Setoran untuk tanggal ini sudah ada. Silakan edit setoran yang sudah ada.');
        }

        // Upload bukti setor
        $buktiPath = null;
        if ($request->hasFile('bukti_setor')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('bukti_setor'),
                    'setoran/minyak',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $buktiPath = $upload['path'];
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal upload bukti setor: ' . $e->getMessage());
            }
        }

        // Hitung data penjualan (semua kecuali batal)
        $summary = $this->calculateSalesSummary($validated['sales_id'], $validated['tanggal']);

        // Hitung pembayaran hutang tunai hari ini (uang cash yang juga harus disetor)
        $hutangDibayarTunai = $this->calculateDebtPayments($validated['sales_id'], $validated['tanggal']);

        // Yang seharusnya disetor = tunai dari penjualan + cicilan hutang tunai
        // Transfer tidak dihitung karena langsung ke rekening perusahaan
        $seharusnyaSetor = $summary['total_tunai'] + $hutangDibayarTunai;

        // Selisih = yang disetor - yang seharusnya
        // Positif = kelebihan, Nol = pas, Negatif = kekurangan
        $selisih = $validated['total_setor'] - $seharusnyaSetor;

        MinyakSetoran::create([
            'tanggal' => $validated['tanggal'],
            'sales_id' => $validated['sales_id'],
            'total_penjualan' => $summary['total_penjualan'],
            'total_tunai' => $summary['total_tunai'],
            'total_transfer' => $summary['total_transfer'],
            'total_setor' => $validated['total_setor'],
            'selisih' => $selisih,
            'jumlah_transaksi' => $summary['jumlah_transaksi'],
            'jumlah_hutang_baru' => $summary['jumlah_hutang_baru'],
            'total_hutang_baru' => $summary['total_hutang_baru'],
            'bukti_setor' => $buktiPath,
            'status' => 'pending',
            'catatan_sales' => $validated['catatan_sales'] ?? null,
        ]);

        return redirect()->route('minyak.setoran.index')
            ->with('success', 'Setoran berhasil ditambahkan.');
    }

    public function show(MinyakSetoran $setoran)
    {
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $setoran->load(['sales', 'verifier']);
        
        return view('minyak.setoran.show', compact('setoran'));
    }

    public function edit(MinyakSetoran $setoran)
    {
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        // Only pending setoran can be edited
        if ($setoran->status !== 'pending') {
            return redirect()->route('minyak.setoran.show', $setoran)
                ->with('error', 'Setoran yang sudah diverifikasi/ditolak tidak dapat diedit.');
        }

        $isSalesRole = $this->isSales();

        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            $sales = collect([$profile]);
        } else {
            $sales = MinyakSales::aktif()->get();
        }

        return view('minyak.setoran.edit', compact('setoran', 'sales', 'isSalesRole'));
    }

    public function update(Request $request, MinyakSetoran $setoran)
    {
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        if ($setoran->status !== 'pending') {
            return redirect()->back()->with('error', 'Setoran yang sudah diverifikasi/ditolak tidak dapat diedit.');
        }

        $rules = [
            'tanggal' => 'required|date',
            'sales_id' => $this->isSales() ? 'nullable' : 'required|exists:minyak_sales,id',
            'total_setor' => 'required|numeric|min:0',
            'catatan_sales' => 'nullable|string',
            'bukti_setor' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ];

        $validated = $request->validate($rules);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            $validated['sales_id'] = $profile->id;
        }

        // Upload new bukti if provided
        if ($request->hasFile('bukti_setor')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('bukti_setor'),
                    'setoran/minyak',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $validated['bukti_setor'] = $upload['path'];
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal upload bukti setor: ' . $e->getMessage());
            }
        }

        // Recalculate summary
        $summary = $this->calculateSalesSummary($validated['sales_id'], $validated['tanggal']);
        $hutangDibayarTunai = $this->calculateDebtPayments($validated['sales_id'], $validated['tanggal']);
        $seharusnyaSetor = $summary['total_tunai'] + $hutangDibayarTunai;

        $updateData = [
            'tanggal' => $validated['tanggal'],
            'sales_id' => $validated['sales_id'],
            'total_penjualan' => $summary['total_penjualan'],
            'total_tunai' => $summary['total_tunai'],
            'total_transfer' => $summary['total_transfer'],
            'total_setor' => $validated['total_setor'],
            'selisih' => $validated['total_setor'] - $seharusnyaSetor,
            'jumlah_transaksi' => $summary['jumlah_transaksi'],
            'jumlah_hutang_baru' => $summary['jumlah_hutang_baru'],
            'total_hutang_baru' => $summary['total_hutang_baru'],
            'catatan_sales' => $validated['catatan_sales'] ?? null,
        ];

        if (isset($validated['bukti_setor'])) {
            $updateData['bukti_setor'] = $validated['bukti_setor'];
        }

        $setoran->update($updateData);

        return redirect()->route('minyak.setoran.index')
            ->with('success', 'Setoran berhasil diperbarui.');
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

        AuditService::log('minyak_setoran.verify', 'MinyakSetoran', $setoran->id, [
            'status' => $validated['status'],
            'total_setor' => $setoran->total_setor,
            'selisih' => $setoran->selisih,
            'catatan' => $validated['catatan_verifikasi'] ?? null,
        ], $validated['status'] === 'ditolak' ? 'warning' : 'info');

        $message = $validated['status'] === 'terverifikasi' 
            ? 'Setoran berhasil diverifikasi.' 
            : 'Setoran ditolak.';

        return redirect()->back()->with('success', $message);
    }
}

<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralSetoran;
use App\Models\MineralSales;
use App\Models\MineralPenjualan;
use App\Models\MineralHutang;
use App\Models\MineralHutangBayar;
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
        return MineralSales::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sales_id = $request->input('sales_id');
        $status = $request->input('status');
        $tanggal = $request->input('tanggal');

        $query = MineralSetoran::with(['sales', 'verifier']);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) $query->where('sales_id', $profile->id);
            $sales = collect([$profile]);
        } else {
            $query->when($sales_id, function ($q) use ($sales_id) {
                $q->where('sales_id', $sales_id);
            });
            $sales = MineralSales::aktif()->get();
        }

        $setorans = $query
            ->when($search, function ($q) use ($search) {
                $q->whereHas('sales', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                });
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

        $baseQuery = MineralSetoran::query();
        if ($this->isSales() && isset($profile) && $profile) $baseQuery->where('sales_id', $profile->id);

        $stats = [
            'total_pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'total_terverifikasi' => (clone $baseQuery)->where('status', 'terverifikasi')->count(),
            'total_setoran_hari_ini' => (clone $baseQuery)->whereDate('tanggal', today())->where('status', 'terverifikasi')->sum('total_setor'),
        ];

        $isSalesRole = $this->isSales();

        return view('mineral.setoran.index', compact('setorans', 'sales', 'stats', 'isSalesRole'));
    }

    public function create()
    {
        $isSalesRole = $this->isSales();
        $todaySummary = null;
        $todayDebtPayment = 0;

        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            $sales = collect([$profile]);

            // Get today's sales summary for the form
            $todaySummary = $this->calculateSalesSummary($profile->id, today()->toDateString());
            $todayDebtPayment = $this->calculateDebtPayments($profile->id, today()->toDateString());
        } else {
            $sales = MineralSales::aktif()->get();
        }

        return view('mineral.setoran.create', compact('sales', 'isSalesRole', 'todaySummary', 'todayDebtPayment'));
    }

    /**
     * Get sales summary for a given date.
     */
    private function calculateSalesSummary(int $salesId, string $tanggal): array
    {
        $query = MineralPenjualan::where('sales_id', $salesId)
            ->whereDate('tanggal_jual', $tanggal)
            ->where('status', '!=', 'batal');

        return [
            'total_penjualan'   => (clone $query)->sum('total'),
            'jumlah_transaksi'  => (clone $query)->count(),
            'total_tunai'       => (clone $query)->where('tipe_bayar', 'tunai')->sum('total'),
            'total_transfer'    => (clone $query)->where('tipe_bayar', 'transfer')->sum('total'),
            'total_hutang_baru' => (clone $query)->where('tipe_bayar', 'hutang')->sum('hutang'),
            'jumlah_hutang_baru' => (clone $query)->where('tipe_bayar', 'hutang')->count(),
        ];
    }

    /**
     * Calculate hutang paid tunai by sales on a given date.
     */
    private function calculateDebtPayments(int $salesId, string $tanggal): array
    {
        $hutangIds = MineralHutang::whereHas('penjualan', function ($q) use ($salesId) {
            $q->where('sales_id', $salesId);
        })->pluck('id');

        $query = MineralHutangBayar::whereIn('hutang_id', $hutangIds)
            ->whereDate('tanggal_bayar', $tanggal)
            ->where('status', 'confirmed');

        $totalTunai    = (clone $query)->where('cara_bayar', 'tunai')->sum('jumlah');
        $totalTransfer = (clone $query)->where('cara_bayar', 'transfer')->sum('jumlah');
        $totalHutangDibayar = (clone $query)->sum('jumlah');

        return compact('totalTunai', 'totalTransfer', 'totalHutangDibayar');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'          => 'required|date',
            'sales_id'         => $this->isSales() ? 'nullable' : 'required|exists:mineral_sales,id',
            'total_setor'      => 'required|numeric|min:0',
            'catatan_sales'    => 'nullable|string',
            'foto_bukti_setor' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        // Force own sales_id for sales role
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile) abort(403, 'Profil sales tidak ditemukan.');
            $validated['sales_id'] = $profile->id;
        }

        // Calculate summary
        $summary     = $this->calculateSalesSummary($validated['sales_id'], $validated['tanggal']);
        $debtPayment = $this->calculateDebtPayments($validated['sales_id'], $validated['tanggal']);

        // Correct selisih: total_setor - (total_tunai + hutang_dibayar_tunai)
        $selisih = (float) $validated['total_setor'] - ((float) $summary['total_tunai'] + (float) $debtPayment['totalTunai']);

        // Upload bukti setoran (image)
        $buktiSetorPath = null;
        if ($request->hasFile('foto_bukti_setor')) {
            try {
                $buktiSetorPath = FileUploadService::upload($request->file('foto_bukti_setor'), 'bukti-setor');
            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Gagal upload bukti setoran: ' . $e->getMessage());
            }
        }

        MineralSetoran::create([
            'tanggal'          => $validated['tanggal'],
            'sales_id'         => $validated['sales_id'],
            'total_penjualan'  => $summary['total_penjualan'],
            'total_tunai'      => $summary['total_tunai'],
            'total_transfer'   => $summary['total_transfer'],
            'total_setor'      => $validated['total_setor'],
            'selisih'          => $selisih,
            'jumlah_transaksi' => $summary['jumlah_transaksi'],
            'jumlah_hutang_baru' => $summary['jumlah_hutang_baru'],
            'total_hutang_baru'  => $summary['total_hutang_baru'],
            'bukti_setor'      => $buktiSetorPath,
            'status'           => 'pending',
            'catatan_sales'    => $validated['catatan_sales'] ?? null,
        ]);

        return redirect()->route('mineral.setoran.index')
            ->with('success', 'Setoran berhasil ditambahkan.');
    }

    public function show(MineralSetoran $setoran)
    {
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $setoran->load(['sales', 'verifier']);

        // Recalculate for display
        $summary     = $this->calculateSalesSummary($setoran->sales_id, $setoran->tanggal->format('Y-m-d'));
        $debtPayment = $this->calculateDebtPayments($setoran->sales_id, $setoran->tanggal->format('Y-m-d'));

        return view('mineral.setoran.show', compact('setoran', 'summary', 'debtPayment'));
    }

    public function edit(MineralSetoran $setoran)
    {
        if ($setoran->status !== 'pending') {
            return redirect()->route('mineral.setoran.show', $setoran)
                ->with('error', 'Setoran yang sudah diverifikasi tidak dapat diedit.');
        }

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $sales = $this->isSales() ? collect([$this->getSalesProfile()]) : MineralSales::aktif()->get();
        $isSalesRole = $this->isSales();

        $summary     = $this->calculateSalesSummary($setoran->sales_id, $setoran->tanggal->format('Y-m-d'));
        $debtPayment = $this->calculateDebtPayments($setoran->sales_id, $setoran->tanggal->format('Y-m-d'));

        return view('mineral.setoran.edit', compact('setoran', 'sales', 'isSalesRole', 'summary', 'debtPayment'));
    }

    public function update(Request $request, MineralSetoran $setoran)
    {
        if ($setoran->status !== 'pending') {
            return back()->with('error', 'Setoran yang sudah diverifikasi tidak dapat diedit.');
        }

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $validated = $request->validate([
            'tanggal'          => 'required|date',
            'total_setor'      => 'required|numeric|min:0',
            'catatan_sales'    => 'nullable|string',
            'foto_bukti_setor' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $summary     = $this->calculateSalesSummary($setoran->sales_id, $validated['tanggal']);
        $debtPayment = $this->calculateDebtPayments($setoran->sales_id, $validated['tanggal']);

        $selisih = (float) $validated['total_setor'] - ((float) $summary['total_tunai'] + (float) $debtPayment['totalTunai']);

        // Upload new bukti (replaces old)
        $buktiSetorPath = $setoran->bukti_setor;
        if ($request->hasFile('foto_bukti_setor')) {
            try {
                if ($setoran->bukti_setor) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($setoran->bukti_setor);
                }
                $result = FileUploadService::uploadImage($request->file('foto_bukti_setor'), 'bukti-setor');
                $buktiSetorPath = $result['path'];
            } catch (\Exception $e) {
                return back()->withInput()
                    ->with('error', 'Gagal upload bukti setoran: ' . $e->getMessage());
            }
        }

        $setoran->update([
            'tanggal'         => $validated['tanggal'],
            'total_penjualan' => $summary['total_penjualan'],
            'total_tunai'     => $summary['total_tunai'],
            'total_transfer'  => $summary['total_transfer'],
            'total_setor'     => $validated['total_setor'],
            'selisih'         => $selisih,
            'jumlah_transaksi' => $summary['jumlah_transaksi'],
            'jumlah_hutang_baru' => $summary['jumlah_hutang_baru'],
            'total_hutang_baru'  => $summary['total_hutang_baru'],
            'bukti_setor'     => $buktiSetorPath,
            'catatan_sales'   => $validated['catatan_sales'] ?? null,
        ]);

        return redirect()->route('mineral.setoran.show', $setoran)
            ->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroy(MineralSetoran $setoran)
    {
        if ($setoran->status !== 'pending') {
            return back()->with('error', 'Setoran yang sudah diverifikasi tidak dapat dihapus.');
        }

        if ($setoran->bukti_setor) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($setoran->bukti_setor);
        }

        $setoran->delete();

        return redirect()->route('mineral.setoran.index')
            ->with('success', 'Setoran berhasil dihapus.');
    }

    public function verify(Request $request, MineralSetoran $setoran)
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

        AuditService::log('mineral_setoran.verify', 'MineralSetoran', $setoran->id, [
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

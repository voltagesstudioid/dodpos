<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarSetoran;
use App\Models\PasgarSales;
use App\Models\PasgarLoading;
use App\Models\PasgarPenjualan;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasgarSetoranController extends Controller
{
    private function isSales(): bool
    {
        $role = strtolower(Auth::user()->role ?? '');
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    private function getSalesProfile(): ?PasgarSales
    {
        return PasgarSales::where('user_id', Auth::id())->first();
    }

    /**
     * List setoran with KPI stats and filters.
     */
    public function index(Request $request)
    {
        $status = $request->input('status');
        $sales_id = $request->input('sales_id');
        $tanggal = $request->input('tanggal');
        $search = $request->input('search');

        $query = PasgarSetoran::with(['sales', 'loading', 'verifier']);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->where('sales_id', $profile->id);
            }
            $sales = collect([$profile]);
        } else {
            $query->when($sales_id, fn($q) => $q->where('sales_id', $sales_id));
            $sales = PasgarSales::orderBy('nama')->get();
        }

        // Search by sales name
        $query->when($search, function ($q) use ($search) {
            $q->whereHas('sales', fn($sq) => $sq->where('nama', 'like', '%' . $search . '%'));
        });

        $setorans = $query
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($tanggal, fn($q) => $q->whereDate('tanggal', $tanggal))
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Stats — scoped to role/sales but independent of date/status filters
        $statsQuery = PasgarSetoran::query();
        if ($this->isSales() && isset($profile)) {
            $statsQuery->where('sales_id', $profile->id);
        }

        $stats = [
            'total_pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'total_terverifikasi' => (clone $statsQuery)->where('status', 'terverifikasi')->count(),
            'total_ditolak' => (clone $statsQuery)->where('status', 'ditolak')->count(),
            'setoran_hari_ini' => (clone $statsQuery)->whereDate('tanggal', today())->where('status', 'terverifikasi')->sum('total_setor'),
        ];

        $isSalesRole = $this->isSales();

        return view('pasgar.setoran.index', compact('setorans', 'sales', 'stats', 'isSalesRole'));
    }

    /**
     * Show form to create setoran for a loading.
     */
    public function create()
    {
        $isSalesRole = $this->isSales();
        $selectedLoading = null;
        $summary = null;

        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            if (!$profile) {
                return redirect()->route('pasgar.setoran.index')->with('error', 'Profil sales tidak ditemukan.');
            }

            // Find completed/loaded loadings without setoran for this sales
            $loadings = PasgarLoading::where('sales_id', $profile->id)
                ->whereIn('status', ['completed', 'loaded'])
                ->whereDoesntHave('setoran')
                ->orderBy('loaded_at', 'desc')
                ->get();
        } else {
            $loadings = PasgarLoading::whereIn('status', ['completed', 'loaded'])
                ->whereDoesntHave('setoran')
                ->with('sales')
                ->orderBy('loaded_at', 'desc')
                ->get();
        }

        if ($loadings->isEmpty()) {
            return redirect()->route('pasgar.setoran.index')
                ->with('error', 'Tidak ada loading yang tersedia untuk dibuatkan setoran.');
        }

        // Pre-select first loading and calculate summary
        $selectedLoading = $loadings->first();
        $summary = PasgarSetoran::calculateSalesSummary($selectedLoading->id);
        $seharusnyaSetor = (float) $summary['total_tunai'];

        return view('pasgar.setoran.create', compact(
            'loadings', 'selectedLoading', 'summary', 'seharusnyaSetor', 'isSalesRole'
        ));
    }

    /**
     * Store a new setoran record.
     */
    public function store(Request $request)
    {
        $isSalesRole = $this->isSales();

        $validated = $request->validate([
            'loading_id' => 'required|exists:pasgar_loadings,id',
            'tanggal' => 'required|date',
            'total_setor' => 'required|numeric|min:0',
            'catatan_sales' => 'nullable|string|max:500',
            'bukti_setor' => 'required|image|mimes:jpeg,jpg,png,webp|max:4096',
        ]);

        $loading = PasgarLoading::findOrFail($validated['loading_id']);

        // Verify ownership for sales
        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            if (!$profile || $loading->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke loading ini.');
            }
            $salesId = $profile->id;
        } else {
            $salesId = $loading->sales_id;
        }

        // Prevent duplicate setoran per loading
        $existing = PasgarSetoran::where('loading_id', $loading->id)->first();
        if ($existing) {
            return back()->withInput()
                ->with('error', 'Setoran untuk loading ini sudah ada.');
        }

        // Upload bukti
        $buktiPath = null;
        if ($request->hasFile('bukti_setor')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('bukti_setor'),
                    'setoran/pasgar',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $buktiPath = $upload['path'];
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal upload bukti: ' . $e->getMessage());
            }
        }

        // Calculate summary from penjualan
        $summary = PasgarSetoran::calculateSalesSummary($loading->id);

        // Expected deposit = cash from sales (tunai only)
        $seharusnyaSetor = (float) $summary['total_tunai'];
        $totalSetor = (float) ($validated['total_setor'] ?: $seharusnyaSetor);
        $selisih = $totalSetor - $seharusnyaSetor;

        PasgarSetoran::create([
            'nomor_setoran' => PasgarSetoran::generateNomor(),
            'loading_id' => $loading->id,
            'sales_id' => $salesId,
            'tanggal' => $validated['tanggal'],
            'total_penjualan' => $summary['total_penjualan'],
            'total_tunai' => $summary['total_tunai'],
            'total_transfer' => $summary['total_transfer'],
            'total_setor' => $totalSetor,
            'selisih' => $selisih,
            'jumlah_transaksi' => $summary['jumlah_transaksi'],
            'bukti_setor' => $buktiPath,
            'catatan_sales' => $validated['catatan_sales'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('pasgar.setoran.index')
            ->with('success', 'Setoran berhasil dibuat.');
    }

    /**
     * Show setoran detail with full financial breakdown.
     */
    public function show($id)
    {
        $setoran = PasgarSetoran::with(['sales', 'loading', 'verifier'])->findOrFail($id);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        // Live recalculate summary from actual penjualan data
        $summary = PasgarSetoran::calculateSalesSummary($setoran->loading_id);
        $seharusnyaSetor = (float) $summary['total_tunai'];

        // Fetch individual penjualan (sales transactions) for this loading
        $penjualans = PasgarPenjualan::where('loading_id', $setoran->loading_id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Breakdown: per-payment-method cash collected from individual penjualan
        $tunaiFromCash = $penjualans->where('metode_bayar', 'tunai')->sum('total');

        // Detect if live data differs from stored (drift detection)
        $driftDetected = (
            abs((float) $setoran->total_penjualan - (float) $summary['total_penjualan']) > 0.01 ||
            abs((float) $setoran->total_tunai - (float) $summary['total_tunai']) > 0.01 ||
            abs((float) $setoran->total_transfer - (float) $summary['total_transfer']) > 0.01
        );

        $isSalesRole = $this->isSales();

        return view('pasgar.setoran.show', compact(
            'setoran', 'summary', 'seharusnyaSetor',
            'penjualans', 'tunaiFromCash', 'driftDetected',
            'isSalesRole'
        ));
    }

    /**
     * Show edit form for pending setoran.
     */
    public function edit($id)
    {
        $setoran = PasgarSetoran::with(['sales', 'loading'])->findOrFail($id);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        if ($setoran->status !== 'pending') {
            return redirect()->route('pasgar.setoran.show', $id)
                ->with('error', 'Setoran yang sudah diverifikasi/ditolak tidak dapat diedit.');
        }

        $summary = PasgarSetoran::calculateSalesSummary($setoran->loading_id);
        $isSalesRole = $this->isSales();

        return view('pasgar.setoran.edit', compact('setoran', 'summary', 'isSalesRole'));
    }

    /**
     * Update a pending setoran.
     */
    public function update(Request $request, $id)
    {
        $setoran = PasgarSetoran::findOrFail($id);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        if ($setoran->status !== 'pending') {
            return back()->with('error', 'Setoran yang sudah diverifikasi/ditolak tidak dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'total_setor' => 'required|numeric|min:0',
            'catatan_sales' => 'nullable|string|max:500',
            'bukti_setor' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
        ]);

        // Upload new bukti if provided
        if ($request->hasFile('bukti_setor')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('bukti_setor'),
                    'setoran/pasgar',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $validated['bukti_setor'] = $upload['path'];
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal upload bukti: ' . $e->getMessage());
            }
        }

        // Recalculate
        $summary = PasgarSetoran::calculateSalesSummary($setoran->loading_id);
        $seharusnyaSetor = (float) $summary['total_tunai'];
        $totalSetor = (float) ($validated['total_setor'] ?: $seharusnyaSetor);

        $updateData = [
            'tanggal' => $validated['tanggal'],
            'total_penjualan' => $summary['total_penjualan'],
            'total_tunai' => $summary['total_tunai'],
            'total_transfer' => $summary['total_transfer'],
            'total_setor' => $totalSetor,
            'selisih' => $totalSetor - $seharusnyaSetor,
            'jumlah_transaksi' => $summary['jumlah_transaksi'],
            'catatan_sales' => $validated['catatan_sales'] ?? null,
        ];

        if (isset($validated['bukti_setor'])) {
            $updateData['bukti_setor'] = $validated['bukti_setor'];
        }

        $setoran->update($updateData);

        return redirect()->route('pasgar.setoran.index')
            ->with('success', 'Setoran berhasil diperbarui.');
    }

    /**
     * Delete a pending setoran.
     */
    public function destroy($id)
    {
        $setoran = PasgarSetoran::findOrFail($id);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $setoran->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        if ($setoran->status !== 'pending') {
            return redirect()->route('pasgar.setoran.index')
                ->with('error', 'Setoran yang sudah diverifikasi/ditolak tidak dapat dihapus.');
        }

        $setoran->delete();

        return redirect()->route('pasgar.setoran.index')
            ->with('success', 'Setoran berhasil dihapus.');
    }

    /**
     * Supervisor verifies or rejects a setoran.
     */
    public function verify(Request $request, $id)
    {
        $setoran = PasgarSetoran::findOrFail($id);

        if ($setoran->status !== 'pending') {
            return back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        $setoran->update([
            'status' => $validated['status'],
            'catatan_verifikasi' => $validated['catatan_verifikasi'] ?? null,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        // Mark loading as completed if verified
        if ($validated['status'] === 'terverifikasi' && $setoran->loading) {
            $setoran->loading->update(['status' => 'completed']);
        }

        $message = $validated['status'] === 'terverifikasi'
            ? 'Setoran berhasil diverifikasi.'
            : 'Setoran ditolak.';

        return redirect()->route('pasgar.setoran.show', $id)->with('success', $message);
    }
}

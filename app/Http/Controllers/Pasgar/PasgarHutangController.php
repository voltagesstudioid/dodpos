<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarHutang;
use App\Models\PasgarHutangBayar;
use App\Models\PasgarPelanggan;
use App\Models\PasgarSales;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PasgarHutangController extends Controller
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
     * List all hutang, grouped by pelanggan. Sales filtered by their regional.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $isSalesRole = $this->isSales();
        $salesProfile = $isSalesRole ? $this->getSalesProfile() : null;

        // Auto-mark overdue records (only updates those still marked 'belum_lunas')
        PasgarHutang::markAllOverdue();

        $query = PasgarHutang::with(['pelanggan.regional', 'penjualan'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($sub) use ($search) {
                    $sub->where('nama_toko', 'like', "%{$search}%")
                        ->orWhere('kode_pelanggan', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByRaw("CASE WHEN status = 'overdue' THEN 0 WHEN status = 'belum_lunas' THEN 1 ELSE 2 END")
            ->orderBy('jatuh_tempo', 'asc');

        // Sales only see hutang from their regional's pelanggan
        $regionalId = null;
        if ($salesProfile && $salesProfile->regional_id) {
            $regionalId = $salesProfile->regional_id;
            $query->whereHas('pelanggan', fn ($q) => $q->where('regional_id', $regionalId));
        }

        $allHutangs = $query->get();

        // Group by pelanggan
        $grouped = $allHutangs->groupBy(function ($h) {
            return $h->pelanggan ? $h->pelanggan->nama_toko : 'Tanpa Pelanggan';
        });

        // Stats (use DB aggregates for efficiency)
        $statsQuery = PasgarHutang::query();
        if ($regionalId) {
            $statsQuery->whereHas('pelanggan', fn ($q) => $q->where('regional_id', $regionalId));
        }

        $stats = [
            'total_outstanding' => (float) (clone $statsQuery)->whereNotIn('status', ['lunas'])->sum('sisa'),
            'belum_lunas' => (clone $statsQuery)->where('status', 'belum_lunas')->count(),
            'overdue' => (clone $statsQuery)->where('status', 'overdue')->count(),
            'lunas' => (clone $statsQuery)->where('status', 'lunas')->count(),
        ];

        return view('pasgar.hutang.index', compact('grouped', 'stats', 'isSalesRole', 'search', 'status'));
    }

    /**
     * Show hutang detail with payment history.
     */
    public function show(PasgarHutang $hutang)
    {
        $hutang->load([
            'pelanggan.regional',
            'penjualan.items.product',
            'pembayarans' => fn ($q) => $q->with(['creator', 'confirmedBy'])->latest('tanggal_bayar'),
        ]);

        // Verify sales access
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id) {
                $pelangganRegional = $hutang->pelanggan->regional_id ?? null;
                if ((int) $pelangganRegional !== (int) $salesProfile->regional_id) {
                    return redirect()->route('pasgar.hutang.index')->with('error', 'Anda tidak berhak melihat hutang ini.');
                }
            }
        }

        $isSalesRole = $this->isSales();

        return view('pasgar.hutang.show', compact('hutang', 'isSalesRole'));
    }

    /**
     * Show payment form.
     */
    public function bayar(PasgarHutang $hutang)
    {
        $hutang->load(['pelanggan', 'penjualan']);

        // Verify sales access
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id) {
                $pelangganRegional = $hutang->pelanggan->regional_id ?? null;
                if ((int) $pelangganRegional !== (int) $salesProfile->regional_id) {
                    return redirect()->route('pasgar.hutang.index')->with('error', 'Anda tidak berhak membayar hutang ini.');
                }
            }
        }

        if ($hutang->sisa <= 0) {
            return redirect()->route('pasgar.hutang.show', $id)->with('error', 'Hutang ini sudah lunas.');
        }

        $isSalesRole = $this->isSales();

        return view('pasgar.hutang.bayar', compact('hutang', 'isSalesRole'));
    }

    /**
     * Store a payment record.
     */
    public function storeBayar(Request $request, PasgarHutang $hutang)
    {
        $hutang->load(['pelanggan']);

        // Verify sales access
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile && $salesProfile->regional_id) {
                $pelangganRegional = $hutang->pelanggan->regional_id ?? null;
                if ((int) $pelangganRegional !== (int) $salesProfile->regional_id) {
                    return redirect()->route('pasgar.hutang.index')->with('error', 'Anda tidak berhak membayar hutang ini.');
                }
            }
        }

        if ($hutang->sisa <= 0) {
            return redirect()->route('pasgar.hutang.show', $id)->with('error', 'Hutang ini sudah lunas.');
        }

        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:1|max:' . (float) $hutang->sisa,
            'cara_bayar' => 'required|in:tunai,transfer,qris',
            'id_transaksi' => 'required_if:cara_bayar,transfer,qris|nullable|string|max:100',
            'bukti_transfer' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            'keterangan' => 'nullable|string|max:500',
        ], [
            'id_transaksi.required_if' => 'ID transaksi transfer wajib diisi saat menggunakan transfer atau QRIS.',
        ]);

        // Upload bukti transfer
        if ($request->hasFile('bukti_transfer')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('bukti_transfer'),
                    'hutang/pasgar',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $validated['bukti_transfer'] = $upload['path'];
            } catch (\Throwable $e) {
                return back()->withInput()->with('error', 'Gagal upload bukti: ' . $e->getMessage());
            }
        }

        // Sales payments are pending, supervisor payments are auto-confirmed
        $isSupervisor = !$this->isSales();

        DB::transaction(function () use ($hutang, $validated, $isSupervisor) {
            $bayar = PasgarHutangBayar::create([
                'hutang_id' => $hutang->id,
                'tanggal_bayar' => now(),
                'jumlah' => $validated['jumlah'],
                'cara_bayar' => $validated['cara_bayar'],
                'id_transaksi' => $validated['id_transaksi'] ?? null,
                'bukti_transfer' => $validated['bukti_transfer'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
                'created_by' => Auth::id(),
                'status' => $isSupervisor ? 'confirmed' : 'pending',
                'confirmed_by' => $isSupervisor ? Auth::id() : null,
                'confirmed_at' => $isSupervisor ? now() : null,
            ]);

            // Recalculate hutang
            $hutang->recalculate();
        });

        $message = $isSupervisor
            ? 'Pembayaran berhasil dicatat.'
            : 'Pembayaran berhasil dikirim, menunggu verifikasi supervisor.';

        return redirect()->route('pasgar.hutang.show', $id)->with('success', $message);
    }

    /**
     * Supervisor confirms or rejects a payment.
     */
    public function confirm(Request $request, PasgarHutangBayar $bayar)
    {
        $bayar->load('hutang');

        if ($bayar->status !== 'pending') {
            return redirect()->route('pasgar.hutang.show', $bayar->hutang_id)
                ->with('error', 'Pembayaran sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'action' => 'required|in:confirm,reject',
            'reject_reason' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($bayar, $validated) {
            if ($validated['action'] === 'confirm') {
                $bayar->update([
                    'status' => 'confirmed',
                    'confirmed_by' => Auth::id(),
                    'confirmed_at' => now(),
                ]);
            } else {
                $bayar->update([
                    'status' => 'rejected',
                    'confirmed_by' => Auth::id(),
                    'confirmed_at' => now(),
                    'reject_reason' => $validated['reject_reason'] ?? null,
                ]);
            }

            // Recalculate hutang
            $bayar->hutang->recalculate();
        });

        $message = $validated['action'] === 'confirm'
            ? 'Pembayaran berhasil diverifikasi.'
            : 'Pembayaran ditolak.';

        return redirect()->route('pasgar.hutang.show', $bayar->hutang_id)->with('success', $message);
    }
}

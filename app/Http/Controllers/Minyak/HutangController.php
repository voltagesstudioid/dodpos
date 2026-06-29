<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakHutang;
use App\Models\MinyakHutangBayar;
use App\Models\MinyakPelanggan;
use App\Models\MinyakSales;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HutangController extends Controller
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

    private function getPelanggansList()
    {
        $query = MinyakPelanggan::where('status', 'aktif');
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $profile->regional_id) {
                $query->where('regional_id', $profile->regional_id);
            } else {
                $query->whereNull('regional_id');
            }
        }
        return $query->orderBy('nama_toko')->get();
    }

    public function index(Request $request)
    {
        // Auto-mark overdue hutang sebelum query
        MinyakHutang::markAllOverdue();

        $search = $request->input('search');
        $pelanggan_id = $request->input('pelanggan_id');
        $status = $request->input('status');

        $query = MinyakHutang::with(['pelanggan', 'penjualan']);

        // Sales: only see debts from their own sales
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->whereHas('penjualan', function ($q) use ($profile) {
                    $q->where('sales_id', $profile->id);
                });
            }
        }

        $hutangs = $query
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($q2) use ($search) {
                    $q2->where('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%");
                });
            })
            ->when($pelanggan_id, function ($q) use ($pelanggan_id) {
                $q->where('pelanggan_id', $pelanggan_id);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderBy('jatuh_tempo', 'asc')
            ->paginate(15)
            ->withQueryString();

        $pelanggans = $this->getPelanggansList();

        // Stats scoped
        $baseQuery = MinyakHutang::query();
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile) {
                $baseQuery->whereHas('penjualan', function ($q) use ($salesProfile) {
                    $q->where('sales_id', $salesProfile->id);
                });
            }
        }

        $stats = [
            'total_hutang' => (clone $baseQuery)->sum('sisa'),
            'belum_lunas' => (clone $baseQuery)->where('status', 'belum_lunas')->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
            'lunas' => (clone $baseQuery)->where('status', 'lunas')->count(),
        ];

        $isSalesRole = $this->isSales();

        return view('minyak.hutang.index', compact('hutangs', 'pelanggans', 'stats', 'isSalesRole'));
    }

    public function piutang(Request $request)
    {
        MinyakHutang::markAllOverdue();
        $search = $request->input('search');
        $pelanggan_id = $request->input('pelanggan_id');

        $query = MinyakHutang::with(['pelanggan', 'penjualan'])->where('status', '!=', 'lunas');

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->whereHas('penjualan', function ($q) use ($profile) {
                    $q->where('sales_id', $profile->id);
                });
            }
        }

        $hutangs = $query
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($q2) use ($search) {
                    $q2->where('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%");
                });
            })
            ->when($pelanggan_id, function ($q) use ($pelanggan_id) {
                $q->where('pelanggan_id', $pelanggan_id);
            })
            ->orderBy('jatuh_tempo', 'asc')
            ->paginate(15)
            ->withQueryString();

        $pelanggans = $this->getPelanggansList();

        $baseQuery = MinyakHutang::where('status', '!=', 'lunas');
        if ($this->isSales()) {
            $salesProfile = $this->getSalesProfile();
            if ($salesProfile) {
                $baseQuery->whereHas('penjualan', function ($q) use ($salesProfile) {
                    $q->where('sales_id', $salesProfile->id);
                });
            }
        }

        $stats = [
            'total_hutang' => (clone $baseQuery)->sum('sisa'),
            'belum_lunas' => (clone $baseQuery)->where('status', 'belum_lunas')->count(),
            'overdue' => (clone $baseQuery)->overdue()->count(),
        ];

        $isSalesRole = $this->isSales();

        return view('minyak.hutang.piutang', compact('hutangs', 'pelanggans', 'stats', 'isSalesRole'));
    }

    public function totalPiutang(Request $request)
    {
        $salesProfile = $this->isSales() ? $this->getSalesProfile() : null;

        $pelanggansQuery = MinyakPelanggan::with(['hutangs' => function($q) use ($salesProfile) {
            $q->where('status', '!=', 'lunas');
            if ($salesProfile) {
                $q->whereHas('penjualan', function ($q2) use ($salesProfile) {
                    $q2->where('sales_id', $salesProfile->id);
                });
            }
        }]);
        if ($this->isSales()) {
            if ($salesProfile && $salesProfile->regional_id) {
                $pelanggansQuery->where('regional_id', $salesProfile->regional_id);
            } else {
                $pelanggansQuery->whereNull('regional_id');
            }
        }
        $pelanggans = $pelanggansQuery->get();

        foreach ($pelanggans as $p) {
            $p->calculated_debt = $p->hutangs->sum('sisa');
        }

        $pelanggans = $pelanggans->filter(fn($p) => $p->calculated_debt > 0)->sortByDesc('calculated_debt');
        $totalDebt = $pelanggans->sum('calculated_debt');
        $isSalesRole = $this->isSales();

        return view('minyak.hutang.total-piutang', compact('pelanggans', 'totalDebt', 'isSalesRole'));
    }

    public function lunas(Request $request)
    {
        $search = $request->input('search');
        $pelanggan_id = $request->input('pelanggan_id');

        $query = MinyakHutang::with(['pelanggan', 'penjualan'])->where('status', 'lunas');

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->whereHas('penjualan', function ($q) use ($profile) {
                    $q->where('sales_id', $profile->id);
                });
            }
        }

        $hutangs = $query
            ->when($search, function ($q) use ($search) {
                $q->whereHas('pelanggan', function ($q2) use ($search) {
                    $q2->where('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%");
                });
            })
            ->when($pelanggan_id, function ($q) use ($pelanggan_id) {
                $q->where('pelanggan_id', $pelanggan_id);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $pelanggans = $this->getPelanggansList();
        $isSalesRole = $this->isSales();

        return view('minyak.hutang.lunas', compact('hutangs', 'pelanggans', 'isSalesRole'));
    }

    public function show(MinyakHutang $hutang)
    {
        $hutang->load(['pelanggan', 'penjualan.sales', 'pembayarans.creator', 'pembayarans.confirmer']);
        $isSalesRole = $this->isSales();

        return view('minyak.hutang.show', compact('hutang', 'isSalesRole'));
    }

    public function bayar(Request $request, MinyakHutang $hutang)
    {
        if ($hutang->status === 'lunas' || $hutang->sisa <= 0) {
            return back()->with('error', 'Hutang ini sudah lunas.');
        }

        // Sales can only record payment for their own sales
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $hutang->penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda hanya bisa mencatat pembayaran dari penjualan Anda sendiri.');
            }
        }

        // Calculate effective remaining (sisa minus pending payments)
        $pendingTotal = $hutang->pembayarans()->where('status', 'pending')->sum('jumlah');
        $effectiveSisa = max(0, (float) $hutang->sisa - (float) $pendingTotal);

        if ($effectiveSisa <= 0) {
            return back()->with('error', 'Sisa hutang sudah tercover oleh pembayaran pending.');
        }

        $rules = [
            'jumlah'       => 'required|numeric|min:1|max:' . $effectiveSisa,
            'cara_bayar'   => 'required|in:tunai,transfer',
            'keterangan'   => 'nullable|string|max:500',
        ];

        // Jika transfer: ID transaksi dan bukti foto wajib diisi
        if ($request->input('cara_bayar') === 'transfer') {
            $rules['id_transaksi']   = 'required|string|min:3|max:100';
            $rules['bukti_transfer'] = 'required|image|mimes:jpeg,jpg,png,webp|max:4096';
        }

        $validated = $request->validate($rules);

        // Upload bukti transfer jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('bukti_transfer'),
                    'hutang/minyak',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $buktiPath = $upload['path'];
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal upload bukti transfer: ' . $e->getMessage());
            }
        }

        DB::beginTransaction();
        try {
            $hutang = MinyakHutang::where('id', $hutang->id)->lockForUpdate()->first();

            // Re-check with pending
            $pendingTotal = $hutang->pembayarans()->where('status', 'pending')->sum('jumlah');
            $effectiveSisa = max(0, (float) $hutang->sisa - (float) $pendingTotal);

            if ($validated['jumlah'] > $effectiveSisa) {
                DB::rollBack();
                return back()->with('error', 'Jumlah pembayaran melebihi sisa hutang (sisa efektif: Rp ' . number_format($effectiveSisa, 0, ',', '.') . ').');
            }

            // Determine if payment needs supervisor confirmation
            $isSales = $this->isSales();
            $needsConfirmation = $isSales && (
                $validated['cara_bayar'] === 'transfer' ||
                $validated['jumlah'] >= 500000
            );

            $paymentStatus = $needsConfirmation ? 'pending' : 'confirmed';

            $payment = MinyakHutangBayar::create([
                'hutang_id'      => $hutang->id,
                'tanggal_bayar'  => now(),
                'jumlah'         => $validated['jumlah'],
                'cara_bayar'     => $validated['cara_bayar'],
                'id_transaksi'   => $validated['id_transaksi'] ?? null,
                'bukti_transfer' => $buktiPath,
                'keterangan'     => $validated['keterangan'] ?? null,
                'created_by'     => Auth::id(),
                'status'         => $paymentStatus,
                'confirmed_by'   => $needsConfirmation ? null : Auth::id(),
                'confirmed_at'   => $needsConfirmation ? null : now(),
            ]);

            // Only update hutang balance if payment is confirmed immediately
            if ($paymentStatus === 'confirmed') {
                $totalDibayar = $hutang->pembayarans()->where('status', 'confirmed')->sum('jumlah');
                $hutang->dibayar = $totalDibayar;
                $hutang->sisa    = max(0, (float) $hutang->total_hutang - (float) $totalDibayar);
                $hutang->status  = $hutang->sisa <= 0 ? 'lunas' : 'belum_lunas';
                $hutang->save();

                $pelanggan = MinyakPelanggan::find($hutang->pelanggan_id);
                if ($pelanggan) {
                    $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $validated['jumlah']);
                    $pelanggan->save();
                }
            }

            AuditService::log(
                'minyak_hutang.pay',
                'MinyakHutang',
                $hutang->id,
                [
                    'jumlah' => $validated['jumlah'],
                    'cara_bayar' => $validated['cara_bayar'],
                    'status' => $paymentStatus,
                    'sales_id' => $isSales ? ($this->getSalesProfile()->id ?? null) : null,
                ],
                'info'
            );

            DB::commit();

            if ($paymentStatus === 'pending') {
                $msg = 'Pembayaran Rp ' . number_format($validated['jumlah'], 0, ',', '.') . ' berhasil dicatat. Menunggu konfirmasi supervisor.';
                return redirect()->back()->with('success', $msg);
            } else {
                $msg = $hutang->status === 'lunas'
                    ? 'Hutang berhasil dilunasi!'
                    : 'Pembayaran Rp ' . number_format($validated['jumlah'], 0, ',', '.') . ' berhasil dicatat. Sisa: Rp ' . number_format($hutang->sisa, 0, ',', '.');
                return redirect()->back()->with('success', $msg);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function confirmPayment(MinyakHutang $hutang, MinyakHutangBayar $payment)
    {
        // Only supervisor can confirm
        if ($this->isSales()) {
            abort(403, 'Hanya supervisor yang bisa konfirmasi pembayaran.');
        }

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        if ($payment->hutang_id !== $hutang->id) {
            abort(403, 'Data pembayaran tidak valid.');
        }

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => 'confirmed',
                'confirmed_by' => Auth::id(),
                'confirmed_at' => now(),
                'reject_reason' => null,
            ]);

            // Recalculate hutang
            $hutang = MinyakHutang::where('id', $hutang->id)->lockForUpdate()->first();
            $totalDibayar = $hutang->pembayarans()->where('status', 'confirmed')->sum('jumlah');
            $hutang->dibayar = $totalDibayar;
            $hutang->sisa    = max(0, (float) $hutang->total_hutang - (float) $totalDibayar);
            $hutang->status  = $hutang->sisa <= 0 ? 'lunas' : 'belum_lunas';
            $hutang->save();

            // Update pelanggan total_hutang
            $pelanggan = MinyakPelanggan::find($hutang->pelanggan_id);
            if ($pelanggan) {
                $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $payment->jumlah);
                $pelanggan->save();
            }

            AuditService::log(
                'minyak_hutang.confirm',
                'MinyakHutangBayar',
                $payment->id,
                ['hutang_id' => $hutang->id, 'jumlah' => $payment->jumlah, 'confirmed_by' => Auth::id()],
                'info'
            );

            DB::commit();

            return back()->with('success', 'Pembayaran Rp ' . number_format($payment->jumlah, 0, ',', '.') . ' berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectPayment(MinyakHutang $hutang, MinyakHutangBayar $payment, Request $request)
    {
        // Only supervisor can reject
        if ($this->isSales()) {
            abort(403, 'Hanya supervisor yang bisa tolak pembayaran.');
        }

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        if ($payment->hutang_id !== $hutang->id) {
            abort(403, 'Data pembayaran tidak valid.');
        }

        $validated = $request->validate([
            'reject_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => 'rejected',
                'confirmed_by' => Auth::id(),
                'confirmed_at' => now(),
                'reject_reason' => $validated['reject_reason'],
            ]);

            AuditService::log(
                'minyak_hutang.reject',
                'MinyakHutangBayar',
                $payment->id,
                ['hutang_id' => $hutang->id, 'jumlah' => $payment->jumlah, 'reason' => $validated['reject_reason']],
                'warning'
            );

            DB::commit();

            return back()->with('success', 'Pembayaran Rp ' . number_format($payment->jumlah, 0, ',', '.') . ' ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function byPelanggan(Request $request, MinyakPelanggan $pelanggan)
    {
        MinyakHutang::markAllOverdue();

        // Authorize: sales only see their own
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $profile->regional_id && $pelanggan->regional_id !== $profile->regional_id) {
                abort(403, 'Anda hanya bisa melihat pelanggan di regional Anda.');
            }
        }

        $hutangs = MinyakHutang::with(['penjualan.sales', 'pembayarans'])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('status', '!=', 'lunas')
            ->where('sisa', '>', 0)
            ->orderBy('jatuh_tempo', 'asc')
            ->orderBy('created_at', 'asc');

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $hutangs->whereHas('penjualan', function ($q) use ($profile) {
                    $q->where('sales_id', $profile->id);
                });
            }
        }

        $hutangs = $hutangs->get();

        $totalRaw = 0;
        $totalSisa = 0;
        $totalPending = 0;
        $totalEffectiveSisa = 0;

        foreach ($hutangs as $h) {
            $h->effective_sisa = (float) $h->sisa;
            $pendingSum = $h->pembayarans->where('status', 'pending')->sum('jumlah');
            if ($pendingSum > 0) {
                $h->effective_sisa = max(0, $h->effective_sisa - (float) $pendingSum);
                $totalPending += $pendingSum;
            }
            $totalRaw += (float) $h->total_hutang;
            $totalSisa += (float) $h->sisa;
            $totalEffectiveSisa += $h->effective_sisa;
        }

        $isSalesRole = $this->isSales();

        return view('minyak.hutang.bypelanggan', compact(
            'pelanggan', 'hutangs', 'totalRaw', 'totalSisa',
            'totalPending', 'totalEffectiveSisa', 'isSalesRole'
        ));
    }

    public function bayarSemua(Request $request, MinyakPelanggan $pelanggan)
    {
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (!$profile) {
                abort(403, 'Profil sales tidak ditemukan.');
            }
            if ($profile->regional_id && $pelanggan->regional_id !== $profile->regional_id) {
                abort(403, 'Anda hanya bisa mencatat pembayaran pelanggan di regional Anda.');
            }
        }

        $rules = [
            'jumlah'       => 'required|numeric|min:1',
            'cara_bayar'   => 'required|in:tunai,transfer',
            'keterangan'   => 'nullable|string|max:500',
        ];

        if ($request->input('cara_bayar') === 'transfer') {
            $rules['id_transaksi']   = 'required|string|min:3|max:100';
            $rules['bukti_transfer'] = 'required|image|mimes:jpeg,jpg,png,webp|max:4096';
        }

        $validated = $request->validate($rules);

        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            try {
                $upload = FileUploadService::uploadImage(
                    $request->file('bukti_transfer'),
                    'hutang/minyak',
                    'public',
                    ['max_width' => 1200, 'max_height' => 1200]
                );
                $buktiPath = $upload['path'];
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal upload bukti transfer: ' . $e->getMessage());
            }
        }

        DB::beginTransaction();
        try {
            // Lock all related hutang rows
            $hutangs = MinyakHutang::where('pelanggan_id', $pelanggan->id)
                ->where('status', '!=', 'lunas')
                ->where('sisa', '>', 0)
                ->orderBy('jatuh_tempo', 'asc')
                ->orderBy('created_at', 'asc')
                ->lockForUpdate()
                ->get();

            if ($hutangs->isEmpty()) {
                DB::rollBack();
                return back()->with('error', 'Tidak ada hutang yang perlu dibayar.');
            }

            // Calculate total effective sisa across all
            $totalEffective = 0;
            $hutangData = [];
            foreach ($hutangs as $h) {
                $pendingSum = $h->pembayarans()->where('status', 'pending')->sum('jumlah');
                $effective = max(0, (float) $h->sisa - (float) $pendingSum);
                $totalEffective += $effective;
                $hutangData[] = [
                    'model' => $h,
                    'effective_sisa' => $effective,
                ];
            }

            if ($totalEffective <= 0) {
                DB::rollBack();
                return back()->with('error', 'Semua hutang sudah tercover oleh pembayaran pending.');
            }

            if ($validated['jumlah'] > $totalEffective) {
                DB::rollBack();
                return back()->with('error', 'Jumlah pembayaran melebihi total sisa hutang (sisa: Rp ' . number_format($totalEffective, 0, ',', '.') . ').');
            }

            $isSales = $this->isSales();
            $sisaJumlah = (float) $validated['jumlah'];
            $paymentIds = [];
            $newConfirmedTotal = 0;

            foreach ($hutangData as $hd) {
                if ($sisaJumlah <= 0) break;

                $h = $hd['model'];
                $effective = $hd['effective_sisa'];

                if ($effective <= 0) continue;

                $payAmount = min($sisaJumlah, $effective);

                // Determine confirmation needs per payment
                $needsConfirmation = $isSales && (
                    $validated['cara_bayar'] === 'transfer' ||
                    $payAmount >= 500000
                );
                $paymentStatus = $needsConfirmation ? 'pending' : 'confirmed';

                $payment = MinyakHutangBayar::create([
                    'hutang_id'      => $h->id,
                    'tanggal_bayar'  => now(),
                    'jumlah'         => $payAmount,
                    'cara_bayar'     => $validated['cara_bayar'],
                    'id_transaksi'   => $validated['id_transaksi'] ?? null,
                    'bukti_transfer' => $buktiPath,
                    'keterangan'     => $validated['keterangan'] ?? null,
                    'created_by'     => Auth::id(),
                    'status'         => $paymentStatus,
                    'confirmed_by'   => $needsConfirmation ? null : Auth::id(),
                    'confirmed_at'   => $needsConfirmation ? null : now(),
                ]);

                $paymentIds[] = $payment->id;

                if ($paymentStatus === 'confirmed') {
                    $totalDibayar = $h->pembayarans()->where('status', 'confirmed')->sum('jumlah');
                    $h->dibayar = $totalDibayar;
                    $h->sisa    = max(0, (float) $h->total_hutang - (float) $totalDibayar);
                    $h->status  = $h->sisa <= 0 ? 'lunas' : 'belum_lunas';
                    $h->save();
                    $newConfirmedTotal += $payAmount;
                }

                $sisaJumlah -= $payAmount;
            }

            // Update pelanggan total_hutang only for confirmed payments
            if ($newConfirmedTotal > 0) {
                $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - $newConfirmedTotal);
                $pelanggan->save();
            }

            AuditService::log(
                'minyak_hutang.bayar_semua',
                'MinyakPelanggan',
                $pelanggan->id,
                [
                    'jumlah' => (float) $validated['jumlah'],
                    'payment_ids' => $paymentIds,
                    'confirmed_total' => $newConfirmedTotal,
                    'pending_total' => (float) $validated['jumlah'] - $newConfirmedTotal,
                ],
                'info'
            );

            DB::commit();

            $hasPending = count($paymentIds) > 0 && $newConfirmedTotal < (float) $validated['jumlah'];
            $msg = 'Pembayaran Rp ' . number_format((float) $validated['jumlah'], 0, ',', '.') . ' berhasil dicatat.';
            if ($hasPending) {
                $msg .= ' Sebagian menunggu konfirmasi supervisor.';
            } else {
                $msg .= ' Semua hutang berhasil dibayar!';
            }

            return redirect()->route('minyak.hutang.pelanggan', $pelanggan->id)->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('minyak.hutang.pelanggan', $pelanggan->id)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

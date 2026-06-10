<?php

namespace App\Http\Controllers\Minyak;

use App\Http\Controllers\Controller;
use App\Models\MinyakHutang;
use App\Models\MinyakHutangBayar;
use App\Models\MinyakPelanggan;
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
        return \App\Models\MinyakSales::where('user_id', Auth::id())->first();
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

        $pelanggans = MinyakPelanggan::where('status', 'aktif')->get();

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
}

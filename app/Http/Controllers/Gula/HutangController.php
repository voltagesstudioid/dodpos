<?php

namespace App\Http\Controllers\Gula;

use App\Http\Controllers\Controller;
use App\Models\GulaHutang;
use App\Models\GulaHutangBayar;
use App\Models\GulaPelanggan;
use App\Models\GulaSales;
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
        return GulaSales::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        // Auto-mark overdue hutang sebelum query
        GulaHutang::markAllOverdue();

        $search      = $request->input('search');
        $pelanggan_id = $request->input('pelanggan_id');
        $status      = $request->input('status');

        $query = GulaHutang::with(['pelanggan', 'penjualan.sales']);

        // Sales can only see hutang from their own penjualan
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->whereHas('penjualan', function ($q) use ($profile) {
                    $q->where('sales_id', $profile->id);
                });
            }
        }

        $hutangs = $query
            ->when($search, function ($query) use ($search) {
                $query->whereHas('pelanggan', function ($q) use ($search) {
                    $q->where('nama_toko', 'like', "%{$search}%")
                        ->orWhere('nama_pemilik', 'like', "%{$search}%");
                });
            })
            ->when($pelanggan_id, function ($query) use ($pelanggan_id) {
                $query->where('pelanggan_id', $pelanggan_id);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('jatuh_tempo', 'asc')
            ->paginate(15)
            ->withQueryString();

        $pelanggans = GulaPelanggan::where('status', 'aktif')->get();

        $baseQuery = GulaHutang::query();
        if ($this->isSales() && isset($profile) && $profile) {
            $baseQuery->whereHas('penjualan', function ($q) use ($profile) {
                $q->where('sales_id', $profile->id);
            });
        }

        $stats = [
            'total_hutang' => (clone $baseQuery)->sum('sisa'),
            'belum_lunas'  => (clone $baseQuery)->where('status', 'belum_lunas')->count(),
            'overdue'      => (clone $baseQuery)->overdue()->count(),
            'lunas'        => (clone $baseQuery)->where('status', 'lunas')->count(),
        ];

        $isSalesRole = $this->isSales();

        return view('gula.hutang.index', compact('hutangs', 'pelanggans', 'stats', 'isSalesRole'));
    }

    public function show(GulaHutang $hutang)
    {
        // Sales can only see their own hutang
        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if ($profile && $hutang->penjualan && $hutang->penjualan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }

        $hutang->load(['pelanggan', 'penjualan.sales', 'pembayarans.creator', 'pembayarans.confirmedBy']);
        $isSalesRole = $this->isSales();

        return view('gula.hutang.show', compact('hutang', 'isSalesRole'));
    }

    public function bayar(Request $request, GulaHutang $hutang)
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
            'jumlah'     => 'required|numeric|min:1|max:' . $effectiveSisa,
            'cara_bayar' => 'required|in:tunai,transfer',
            'keterangan' => 'nullable|string|max:500',
        ];

        // Jika transfer: ID transaksi dan bukti foto wajib diisi
        if ($request->input('cara_bayar') === 'transfer') {
            $rules['id_transaksi']   = 'required|string|min:3|max:100';
            $rules['bukti_transfer'] = 'required|image|mimes:jpeg,jpg,png,webp|max:5120';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $hutang = GulaHutang::where('id', $hutang->id)->lockForUpdate()->first();

            // Re-check with pending
            $pendingTotal = $hutang->pembayarans()->where('status', 'pending')->sum('jumlah');
            $effectiveSisa = max(0, (float) $hutang->sisa - (float) $pendingTotal);

            if ($validated['jumlah'] > $effectiveSisa) {
                DB::rollBack();
                return back()->with('error', 'Jumlah pembayaran melebihi sisa hutang (sisa efektif: Rp ' . number_format($effectiveSisa, 0, ',', '.') . ').');
            }

            // Upload bukti if transfer
            $buktiPath = null;
            if ($validated['cara_bayar'] === 'transfer' && $request->hasFile('bukti_transfer')) {
                try {
                    $result = FileUploadService::uploadImage($request->file('bukti_transfer'), 'bukti-hutang');
                    $buktiPath = $result['path'];
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Gagal upload bukti transfer: ' . $e->getMessage());
                }
            }

            // Determine payment status: sales users always need confirmation for transfer or >= 500k
            $isSales = $this->isSales();
            $needsConfirmation = $isSales && (
                $validated['cara_bayar'] === 'transfer' ||
                $validated['jumlah'] >= 500000
            );

            $paymentStatus = $needsConfirmation ? 'pending' : 'confirmed';

            $payment = GulaHutangBayar::create([
                'hutang_id'      => $hutang->id,
                'tanggal_bayar'  => now(),
                'jumlah'         => $validated['jumlah'],
                'cara_bayar'     => $validated['cara_bayar'],
                'id_transaksi'   => $validated['id_transaksi'] ?? null,
                'bukti_transfer' => $buktiPath,
                'keterangan'     => $validated['keterangan'] ?? null,
                'created_by'     => Auth::id(),
                'status'         => $paymentStatus,
            ]);

            // If auto-confirmed, update hutang balance
            if ($paymentStatus === 'confirmed') {
                $payment->confirmed_by = Auth::id();
                $payment->confirmed_at = now();
                $payment->save();

                $totalDibayar = $hutang->pembayarans()->confirmed()->sum('jumlah');
                $hutang->dibayar = $totalDibayar;
                $hutang->sisa    = max(0, (float) $hutang->total_hutang - (float) $totalDibayar);
                $hutang->status  = $hutang->sisa <= 0 ? 'lunas' : 'belum_lunas';
                $hutang->save();

                // Update pelanggan total_hutang
                $pelanggan = GulaPelanggan::find($hutang->pelanggan_id);
                if ($pelanggan) {
                    $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $validated['jumlah']);
                    $pelanggan->save();
                }
            }

            AuditService::log(
                'gula_hutang.pay',
                'GulaHutang',
                $hutang->id,
                ['jumlah' => $validated['jumlah'], 'cara_bayar' => $validated['cara_bayar'], 'status' => $paymentStatus, 'sisa' => $hutang->sisa],
                'info'
            );

            DB::commit();

            if ($paymentStatus === 'pending') {
                $msg = 'Pembayaran Rp ' . number_format($validated['jumlah'], 0, ',', '.') . ' berhasil diajukan. Menunggu konfirmasi supervisor.';
            } elseif ($hutang->status === 'lunas') {
                $msg = 'Hutang berhasil dilunasi!';
            } else {
                $msg = 'Pembayaran Rp ' . number_format($validated['jumlah'], 0, ',', '.') . ' berhasil dicatat. Sisa: Rp ' . number_format($hutang->sisa, 0, ',', '.');
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Supervisor: confirm a pending payment.
     */
    public function confirmPayment(GulaHutang $hutang, GulaHutangBayar $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        DB::beginTransaction();
        try {
            $payment->status = 'confirmed';
            $payment->confirmed_by = Auth::id();
            $payment->confirmed_at = now();
            $payment->save();

            // Recalculate hutang from all confirmed payments
            $hutang = GulaHutang::where('id', $hutang->id)->lockForUpdate()->first();
            $totalDibayar = $hutang->pembayarans()->confirmed()->sum('jumlah');
            $hutang->dibayar = $totalDibayar;
            $hutang->sisa    = max(0, (float) $hutang->total_hutang - (float) $totalDibayar);
            $hutang->status  = $hutang->sisa <= 0 ? 'lunas' : 'belum_lunas';
            $hutang->save();

            // Update pelanggan total_hutang
            $pelanggan = GulaPelanggan::find($hutang->pelanggan_id);
            if ($pelanggan) {
                $pelanggan->total_hutang = max(0, (float) $pelanggan->total_hutang - (float) $payment->jumlah);
                $pelanggan->save();
            }

            AuditService::log(
                'gula_hutang.confirm',
                'GulaHutangBayar',
                $payment->id,
                ['hutang_id' => $hutang->id, 'jumlah' => $payment->jumlah, 'confirmed_by' => Auth::id()],
                'info'
            );

            DB::commit();

            return back()->with('success', 'Pembayaran Rp ' . number_format($payment->jumlah, 0, ',', '.') . ' berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal konfirmasi: ' . $e->getMessage());
        }
    }

    /**
     * Supervisor: reject a pending payment.
     */
    public function rejectPayment(Request $request, GulaHutang $hutang, GulaHutangBayar $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        $request->validate(['reject_reason' => 'required|string|max:500']);

        DB::beginTransaction();
        try {
            $payment->status = 'rejected';
            $payment->reject_reason = $request->input('reject_reason');
            $payment->confirmed_by = Auth::id();
            $payment->confirmed_at = now();
            $payment->save();

            // Delete bukti file if exists
            if ($payment->bukti_transfer) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($payment->bukti_transfer);
            }

            AuditService::log(
                'gula_hutang.reject',
                'GulaHutangBayar',
                $payment->id,
                ['hutang_id' => $hutang->id, 'reason' => $request->input('reject_reason')],
                'warning'
            );

            DB::commit();

            return back()->with('success', 'Pembayaran ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak: ' . $e->getMessage());
        }
    }
}

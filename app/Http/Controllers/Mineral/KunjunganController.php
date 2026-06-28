<?php

namespace App\Http\Controllers\Mineral;

use App\Http\Controllers\Controller;
use App\Models\MineralKunjungan;
use App\Models\MineralSales;
use App\Models\MineralPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KunjunganController extends Controller
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
        $isSalesRole = $this->isSales();
        $tanggalMulai = $request->input('tanggal_mulai', now()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', now()->toDateString());
        $salesId = $request->input('sales_id');
        $status = $request->input('status');

        $query = MineralKunjungan::query()
            ->with(['sales', 'pelanggan'])
            ->whereDate('waktu_checkin', '>=', $tanggalMulai)
            ->whereDate('waktu_checkin', '<=', $tanggalSelesai);

        if ($isSalesRole) {
            $profile = $this->getSalesProfile();
            if ($profile) {
                $query->where('sales_id', $profile->id);
            }
            $salesList = collect([$profile]);
        } else {
            $salesList = MineralSales::where('status', 'aktif')->orderBy('nama')->get();
            if ($salesId) {
                $query->where('sales_id', $salesId);
            }
        }

        if ($status) {
            $query->where('status', $status);
        }

        $kunjungans = $query->orderBy('waktu_checkin', 'desc')->paginate(20)->withQueryString();

        return view('mineral.kunjungan.index', compact(
            'kunjungans',
            'salesList',
            'isSalesRole',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    public function show(MineralKunjungan $kunjungan)
    {
        $kunjungan->load(['sales', 'pelanggan', 'penjualans']);

        if ($this->isSales()) {
            $profile = $this->getSalesProfile();
            if (! $profile || $kunjungan->sales_id !== $profile->id) {
                abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
            }
        }

        $penjualan = $kunjungan->penjualans->first();
        $kunjungan->penjualan = $penjualan;

        $isSalesRole = $this->isSales();

        return view('mineral.kunjungan.show', compact('kunjungan', 'isSalesRole'));
    }
}

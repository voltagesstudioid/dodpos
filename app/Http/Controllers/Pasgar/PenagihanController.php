<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\CustomerCredit;
use App\Models\PasgarMember;
use App\Models\User;
use Illuminate\Http\Request;

class PenagihanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua user dengan role pasgar
        $pasgarUserIds = User::where('role', 'pasgar')->pluck('id');

        // Piutang yang dibuat oleh pasgar (dari transaksi kanvas kredit)
        // atau piutang pelanggan yang ditugaskan ke pasgar
        $query = CustomerCredit::with(['customer', 'transaction'])
            ->whereHas('transaction', fn($q) => $q->whereIn('user_id', $pasgarUserIds))
            ->latest('transaction_date');

        // Filter tanggal
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');
        $query->whereDate('transaction_date', '>=', $dateFrom)
              ->whereDate('transaction_date', '<=', $dateTo);

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter anggota
        if ($request->user_id) {
            $query->whereHas('transaction', fn($q) => $q->where('user_id', $request->user_id));
        }

        $credits = $query->paginate(20)->withQueryString();

        // Summary
        $allCredits = CustomerCredit::whereHas('transaction', fn($q) => $q->whereIn('user_id', $pasgarUserIds));

        $totalPiutang   = (clone $allCredits)->whereIn('status', ['unpaid', 'partial'])->sum(\DB::raw('amount - paid_amount'));
        $totalLunas     = (clone $allCredits)->where('status', 'paid')->count();
        $totalBelumLunas = (clone $allCredits)->whereIn('status', ['unpaid', 'partial'])->count();

        // Daftar anggota untuk filter
        $pasgarUsers = User::where('role', 'pasgar')->orderBy('name')->get();

        return view('pasgar.penagihan.index', compact(
            'credits', 'pasgarUsers', 'dateFrom', 'dateTo',
            'totalPiutang', 'totalLunas', 'totalBelumLunas'
        ));
    }
}

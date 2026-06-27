<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\SdmCashAdvance;
use App\Models\SdmDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasbonController extends Controller
{
    public function index()
    {
        $kasbons = SdmCashAdvance::with(['user.employee', 'approver'])
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $totalPending = SdmCashAdvance::where('status', 'pending')->count();
        $totalApproved = SdmCashAdvance::where('status', 'approved')->count();
        $totalRejected = SdmCashAdvance::where('status', 'rejected')->count();
        $totalAmountApproved = SdmCashAdvance::where('status', 'approved')->sum('amount');

        return view('sdm.kasbon.index', compact(
            'kasbons', 'totalPending', 'totalApproved',
            'totalRejected', 'totalAmountApproved'
        ));
    }

    public function selfIndex()
    {
        $userId = Auth::id();

        $kasbons = SdmCashAdvance::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        $totalAmount = SdmCashAdvance::where('user_id', $userId)->sum('amount');
        $totalApproved = SdmCashAdvance::where('user_id', $userId)->where('status', 'approved')->sum('amount');
        $totalPending = SdmCashAdvance::where('user_id', $userId)->where('status', 'pending')->count();

        return view('sdm.kasbon.self', compact(
            'kasbons', 'totalAmount', 'totalApproved', 'totalPending'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:999999999',
            'purpose' => 'required|string|max:500',
        ]);

        SdmCashAdvance::create([
            'user_id' => Auth::id(),
            'date' => now(),
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan kasbon berhasil dikirim, menunggu persetujuan Supervisor.');
    }

    public function approve(Request $request, SdmCashAdvance $kasbon)
    {
        $request->validate([
            'deduction_month' => 'required|date_format:Y-m',
        ]);

        if ($kasbon->status !== 'pending') {
            return redirect()->back()->with('error', 'Status kasbon sudah tidak pending.');
        }

        DB::beginTransaction();
        try {
            $deductionDate = $request->deduction_month . '-01';

            $deduction = SdmDeduction::create([
                'user_id' => $kasbon->user_id,
                'date' => $deductionDate,
                'amount' => $kasbon->amount,
                'description' => 'Potongan Kasbon: ' . ($kasbon->purpose ?? 'Kasbon Karyawan'),
            ]);

            $kasbon->update([
                'status' => 'approved',
                'deduction_month' => $request->deduction_month,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'deduction_id' => $deduction->id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Kasbon berhasil disetujui dan dijadwalkan pemotongannya.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyetujui kasbon: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, SdmCashAdvance $kasbon)
    {
        if ($kasbon->status !== 'pending') {
            return redirect()->back()->with('error', 'Status kasbon sudah tidak pending.');
        }

        $kasbon->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kasbon berhasil ditolak.');
    }
}

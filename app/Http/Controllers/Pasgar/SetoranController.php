<?php

namespace App\Http\Controllers\Pasgar;

use App\Http\Controllers\Controller;
use App\Models\PasgarDeposit;
use App\Models\PasgarMember;
use App\Models\SalesOrder;
use App\Models\CustomerCredit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetoranController extends Controller
{
    public function index(Request $request)
    {
        $query = PasgarDeposit::with(['member.user', 'member.vehicle', 'verifier'])
            ->latest('deposit_date');

        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo   = $request->date_to   ?? now()->format('Y-m-d');
        $query->whereDate('deposit_date', '>=', $dateFrom)
              ->whereDate('deposit_date', '<=', $dateTo);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->member_id) {
            $query->where('pasgar_member_id', $request->member_id);
        }

        $deposits = $query->paginate(20)->withQueryString();

        // Summary
        $summaryBase = PasgarDeposit::whereDate('deposit_date', '>=', $dateFrom)
            ->whereDate('deposit_date', '<=', $dateTo);

        $totalDeposits  = (clone $summaryBase)->count();
        $totalSetoran   = (clone $summaryBase)->sum('total_amount');
        $pendingCount   = (clone $summaryBase)->where('status', 'pending')->count();
        $totalVerified  = (clone $summaryBase)->where('status', 'verified')->sum('total_amount');
        $todaySetoran   = PasgarDeposit::whereDate('deposit_date', today())->sum('total_amount');

        $members = PasgarMember::with('user')->where('active', true)->get();

        return view('pasgar.setoran.index', compact(
            'deposits', 'members', 'dateFrom', 'dateTo',
            'totalDeposits', 'totalSetoran', 'pendingCount', 'totalVerified', 'todaySetoran'
        ));
    }

    public function create()
    {
        $members = PasgarMember::with(['user', 'vehicle'])->where('active', true)->get();

        return view('pasgar.setoran.create', compact('members'));
    }

    /**
     * AJAX: hitung otomatis penjualan & penagihan hari ini untuk member tertentu
     */
    public function getSummary(Request $request)
    {
        $member = PasgarMember::with('user')->findOrFail($request->member_id);
        $date   = $request->date ?? today()->format('Y-m-d');

        // Total penjualan kanvas hari ini
        $salesAmount = SalesOrder::where('user_id', $member->user_id)
            ->whereDate('order_date', $date)
            ->sum('total_amount');

        // Total penagihan piutang hari ini (pembayaran kredit)
        $collectionAmount = \App\Models\CustomerCreditPayment::whereHas('credit', function ($q) use ($member) {
                $q->whereHas('transaction', fn($t) => $t->where('user_id', $member->user_id));
            })
            ->whereDate('payment_date', $date)
            ->sum('amount');

        return response()->json([
            'sales_amount'      => $salesAmount,
            'collection_amount' => $collectionAmount,
            'total'             => $salesAmount + $collectionAmount,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasgar_member_id'  => 'required|exists:pasgar_members,id',
            'deposit_date'      => 'required|date',
            'sales_amount'      => 'required|numeric|min:0',
            'collection_amount' => 'required|numeric|min:0',
            'expense_amount'    => 'required|numeric|min:0',
            'notes'             => 'nullable|string',
        ]);

        $total = $request->sales_amount + $request->collection_amount - $request->expense_amount;

        DB::beginTransaction();
        try {
            $deposit = PasgarDeposit::create([
                'deposit_number'    => PasgarDeposit::generateNumber(),
                'pasgar_member_id'  => $request->pasgar_member_id,
                'deposit_date'      => $request->deposit_date,
                'sales_amount'      => $request->sales_amount,
                'collection_amount' => $request->collection_amount,
                'expense_amount'    => $request->expense_amount,
                'total_amount'      => max(0, $total),
                'status'            => 'pending',
                'notes'             => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('pasgar.setoran.show', $deposit)
                ->with('success', 'Setoran harian berhasil dicatat. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan setoran: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PasgarDeposit $setoran)
    {
        $setoran->load(['member.user', 'member.vehicle', 'verifier']);
        return view('pasgar.setoran.show', compact('setoran'));
    }

    public function verify(Request $request, PasgarDeposit $setoran)
    {
        if ($setoran->status !== 'pending') {
            return back()->with('error', 'Setoran ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'action' => 'required|in:verified,rejected',
            'notes'  => 'nullable|string',
        ]);

        $setoran->update([
            'status'      => $request->action,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes'       => $request->notes ?? $setoran->notes,
        ]);

        $label = $request->action === 'verified' ? 'diverifikasi' : 'ditolak';
        return redirect()->route('pasgar.setoran.index')
            ->with('success', "Setoran {$setoran->deposit_number} berhasil {$label}.");
    }

    public function destroy(PasgarDeposit $setoran)
    {
        if ($setoran->status !== 'pending') {
            return back()->with('error', 'Hanya setoran berstatus pending yang dapat dihapus.');
        }
        $setoran->delete();
        return redirect()->route('pasgar.setoran.index')
            ->with('success', 'Setoran berhasil dihapus.');
    }
}

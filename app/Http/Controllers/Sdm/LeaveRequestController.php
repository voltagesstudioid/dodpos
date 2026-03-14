<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\SdmLeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }
        [$year, $m] = explode('-', $month);

        $status = $request->input('status');
        if (! in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $status = 'pending';
        }

        $query = SdmLeaveRequest::query()
            ->with(['user', 'approver'])
            ->where(function ($q) use ($year, $m) {
                $start = Carbon::createFromDate((int) $year, (int) $m, 1)->toDateString();
                $end = Carbon::createFromDate((int) $year, (int) $m, 1)->endOfMonth()->toDateString();
                $q->whereDate('start_date', '<=', $end)
                    ->whereDate('end_date', '>=', $start);
            })
            ->orderBy('status')
            ->orderBy('start_date');

        if ($status) {
            $query->where('status', $status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $requests = $query->get();

        $users = User::whereHas('employee', fn ($q) => $q->where('active', true))
            ->orderBy('name')
            ->get();

        return view('sdm.cuti.index', compact('requests', 'users', 'month', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:cuti,izin,sakit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'paid' => 'nullable|in:1',
            'notes' => 'nullable|string|max:500',
        ]);

        SdmLeaveRequest::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'start_date' => Carbon::parse($validated['start_date'])->toDateString(),
            'end_date' => Carbon::parse($validated['end_date'])->toDateString(),
            'paid' => ($validated['paid'] ?? null) === '1',
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil dibuat dan menunggu persetujuan.');
    }

    public function selfIndex(Request $request)
    {
        $user = $request->user();
        if (! $user?->employee || ! $user->employee->active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terdaftar sebagai karyawan aktif.');
        }

        $month = $request->input('month');
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }
        [$year, $m] = explode('-', $month);

        $requests = SdmLeaveRequest::query()
            ->with(['approver'])
            ->where('user_id', $user->id)
            ->where(function ($q) use ($year, $m) {
                $start = Carbon::createFromDate((int) $year, (int) $m, 1)->toDateString();
                $end = Carbon::createFromDate((int) $year, (int) $m, 1)->endOfMonth()->toDateString();
                $q->whereDate('start_date', '<=', $end)
                    ->whereDate('end_date', '>=', $start);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('sdm.cuti.self', compact('requests', 'month'));
    }

    public function selfStore(Request $request)
    {
        $user = $request->user();
        if (! $user?->employee || ! $user->employee->active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terdaftar sebagai karyawan aktif.');
        }

        $validated = $request->validate([
            'type' => 'required|in:cuti,izin,sakit',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        SdmLeaveRequest::create([
            'user_id' => $user->id,
            'type' => $validated['type'],
            'start_date' => Carbon::parse($validated['start_date'])->toDateString(),
            'end_date' => Carbon::parse($validated['end_date'])->toDateString(),
            'paid' => false,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan supervisor.');
    }

    public function selfDestroy(SdmLeaveRequest $cuti)
    {
        $userId = Auth::id();
        if (! $userId || (int) $cuti->user_id !== (int) $userId) {
            abort(403);
        }
        if ($cuti->status === 'approved') {
            return redirect()->back()->with('error', 'Pengajuan cuti yang sudah disetujui tidak dapat dibatalkan.');
        }

        $cuti->delete();

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    public function approve(Request $request, SdmLeaveRequest $cuti)
    {
        if ($cuti->status !== 'approved') {
            $cuti->update([
                'status' => 'approved',
                'approved_by' => $request->user()?->id,
            ]);
        }

        return redirect()->back()->with('success', 'Pengajuan cuti disetujui.');
    }

    public function reject(Request $request, SdmLeaveRequest $cuti)
    {
        if ($cuti->status !== 'rejected') {
            $cuti->update([
                'status' => 'rejected',
                'approved_by' => $request->user()?->id,
            ]);
        }

        return redirect()->back()->with('success', 'Pengajuan cuti ditolak.');
    }

    public function destroy(SdmLeaveRequest $cuti)
    {
        if ($cuti->status === 'approved') {
            return redirect()->back()->with('error', 'Pengajuan cuti yang sudah disetujui tidak dapat dihapus.');
        }

        $cuti->delete();

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil dihapus.');
    }
}

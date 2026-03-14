<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\SdmPayroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformaController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $m] = explode('-', $month);

        $currentUserId = Auth::id();
        $isSupervisor = strtolower((string) (Auth::user()?->role ?? '')) === 'supervisor';

        $payrolls = SdmPayroll::query()
            ->with(['user.employee'])
            ->where('period_year', $year)
            ->where('period_month', $m)
            ->when(! $isSupervisor && $currentUserId, fn ($q) => $q->where('user_id', $currentUserId))
            ->orderBy('net_salary', 'desc')
            ->get();

        $monthLabel = Carbon::parse($month.'-01')->translatedFormat('F Y');

        return view('sdm.performa.index', compact('payrolls', 'month', 'monthLabel'));
    }
}

<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SdmDeduction;
use App\Models\SdmBonus;
use App\Models\SdmEmployeeAllowance;
use App\Models\SdmHoliday;
use App\Models\SdmLeaveRequest;
use App\Models\SdmPayroll;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenggajianController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $m] = explode('-', $month);

        $currentUserId = Auth::id();
        $isSupervisor = strtolower((string) (Auth::user()?->role ?? '')) === 'supervisor';

        $payrolls = SdmPayroll::with('user')
            ->where('period_year', $year)
            ->where('period_month', $m)
            ->when(! $isSupervisor && $currentUserId, fn ($q) => $q->where('user_id', $currentUserId))
            ->get();

        return view('sdm.penggajian.index', compact('payrolls', 'month'));
    }

    public function selfIndex(Request $request)
    {
        $user = $request->user();
        if (! $user?->employee || ! $user->employee->active) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terdaftar sebagai karyawan aktif.');
        }

        $month = $request->input('month', now()->format('Y-m'));
        [$year, $m] = explode('-', $month);

        $payrolls = SdmPayroll::with('user')
            ->where('user_id', $user->id)
            ->where('period_year', $year)
            ->where('period_month', $m)
            ->get();

        $history = SdmPayroll::query()
            ->where('user_id', $user->id)
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->limit(12)
            ->get();

        return view('sdm.penggajian.self', compact('payrolls', 'history', 'month'));
    }

    public function selfPrint(SdmPayroll $penggajian)
    {
        $userId = Auth::id();
        if (! $userId || (int) $penggajian->user_id !== (int) $userId) {
            abort(403);
        }

        return $this->print($penggajian);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = $request->input('month');
        [$year, $m] = explode('-', $month);

        $setting = StoreSetting::current();

        $users = User::whereHas('employee', function ($q) {
            $q->where('active', true);
        })->with('employee')->get();

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada karyawan aktif untuk digaji.');
        }

        DB::beginTransaction();
        try {
            $generatedCount = 0;

            $userIds = $users->pluck('id')->all();

            $workingDates = $this->workingDates(
                (int) $year,
                (int) $m,
                (string) ($setting->sdm_working_days_mode ?? 'mon_sat'),
                (string) ($setting->sdm_calendar_mode ?? 'auto')
            );
            $workingDatesSet = array_fill_keys($workingDates, true);
            $workingDaysCount = count($workingDates);

            $monthStart = Carbon::createFromDate((int) $year, (int) $m, 1)->startOfDay();
            $monthEnd = Carbon::createFromDate((int) $year, (int) $m, 1)->endOfMonth()->endOfDay();

            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attRows */
            $attRows = Attendance::query()
                ->whereIn('user_id', $userIds)
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->get(['user_id', 'date', 'status', 'overtime_minutes']);

            $attByUserDate = [];
            $countsByUser = [];
            $overtimeByUser = [];

            foreach ($attRows as $row) {
                $dateStr = Carbon::parse($row->date)->toDateString();
                if (! isset($workingDatesSet[$dateStr])) {
                    continue;
                }

                $uid = (int) $row->user_id;
                $attByUserDate[$uid][$dateStr] = (string) $row->status;
                $countsByUser[$uid][(string) $row->status] = (int) (($countsByUser[$uid][(string) $row->status] ?? 0) + 1);
                $overtimeByUser[$uid] = (int) (($overtimeByUser[$uid] ?? 0) + (int) ($row->overtime_minutes ?? 0));
            }

            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\SdmLeaveRequest> $leaveRows */
            $leaveRows = SdmLeaveRequest::query()
                ->whereIn('user_id', $userIds)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $monthEnd->toDateString())
                ->whereDate('end_date', '>=', $monthStart->toDateString())
                ->get(['user_id', 'start_date', 'end_date', 'type', 'paid']);

            $leaveByUserDate = [];
            foreach ($leaveRows as $leave) {
                $uid = (int) $leave->user_id;
                $start = Carbon::parse($leave->start_date)->startOfDay();
                $end = Carbon::parse($leave->end_date)->startOfDay();

                $cursor = $start->copy();
                while ($cursor->lte($end)) {
                    $dateStr = $cursor->toDateString();
                    if (isset($workingDatesSet[$dateStr])) {
                        $leaveByUserDate[$uid][$dateStr] = [
                            'type' => (string) $leave->type,
                            'paid' => (bool) $leave->paid,
                        ];
                    }
                    $cursor->addDay();
                }
            }

            /** @var \Illuminate\Support\Collection<int, float> $deductionsByUser */
            $deductionsByUser = SdmDeduction::query()
                ->select(['user_id', DB::raw('SUM(amount) as total_deductions')])
                ->whereNotNull('user_id')
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->groupBy('user_id')
                ->pluck('total_deductions', 'user_id');

            /** @var \Illuminate\Support\Collection<int, float> $bonusesByUser */
            $bonusesByUser = SdmBonus::query()
                ->select(['user_id', DB::raw('SUM(amount) as total_bonuses')])
                ->whereNotNull('user_id')
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->groupBy('user_id')
                ->pluck('total_bonuses', 'user_id');

            // Pre-load fixed allowances per employee_id → sum active amounts
            $employeeIds = $users->pluck('employee.id')->filter()->all();
            $fixedAllowanceByEmployeeId = SdmEmployeeAllowance::query()
                ->whereIn('employee_id', $employeeIds)
                ->where('active', true)
                ->get(['employee_id', 'amount'])
                ->groupBy('employee_id')
                ->map(fn ($rows) => $rows->sum('amount'))
                ->all();

            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\SdmPayroll> $payrollsByUser */
            $payrollsByUser = SdmPayroll::query()
                ->where('period_year', $year)
                ->where('period_month', $m)
                ->get()
                ->keyBy('user_id');

            /** @var \App\Models\User $user */
            foreach ($users as $user) {
                if (! $user->employee) {
                    continue;
                }

                /** @var \App\Models\SdmPayroll|null $existing */
                $existing = $payrollsByUser->get($user->id);
                if ($existing && $existing->locked_at) {
                    continue;
                }

                $basicSalary = $user->employee->basic_salary ?? 0;
                $mealAllowancePerDay = $user->employee->daily_allowance ?? 0;
                $fixedAllowanceTotal = (float) ($fixedAllowanceByEmployeeId[$user->employee->id] ?? 0);

                $presentDays = (int) (($countsByUser[$user->id]['present'] ?? 0));
                $lateDays = (int) (($countsByUser[$user->id]['late'] ?? 0));
                $izinDays = (int) (($countsByUser[$user->id]['izin'] ?? 0));
                $sakitDays = (int) (($countsByUser[$user->id]['sakit'] ?? 0));
                $absentDays = (int) (($countsByUser[$user->id]['absent'] ?? 0));
                $overtimeMinutes = (int) ($overtimeByUser[$user->id] ?? 0);

                $unpaidLeaveDays = 0;
                $missingDays = 0;
                foreach ($workingDates as $dateStr) {
                    if (isset($attByUserDate[$user->id][$dateStr])) {
                        continue;
                    }
                    if (isset($leaveByUserDate[$user->id][$dateStr])) {
                        if (! $leaveByUserDate[$user->id][$dateStr]['paid']) {
                            $unpaidLeaveDays++;
                        }

                        continue;
                    }
                    $missingDays++;
                }

                $totalAttendance = $presentDays + $lateDays;

                $mealAllowanceGross = $totalAttendance * $mealAllowancePerDay;
                $lateMealPenalty = $this->lateMealPenalty($lateDays, $mealAllowancePerDay, (string) ($setting->sdm_late_meal_cut_mode ?? 'full'), (float) ($setting->sdm_late_meal_cut_value ?? 0));

                $effectiveBasicSalary = (float) ($existing?->override_total_basic_salary ?? $basicSalary);
                $effectiveLateMealPenalty = (float) ($existing?->override_late_meal_penalty ?? $lateMealPenalty);

                $totalAllowance = $mealAllowanceGross - $effectiveLateMealPenalty;
                if ($totalAllowance < 0) {
                    $totalAllowance = 0;
                }

                $dailyBasic = 0;
                if ($workingDaysCount > 0) {
                    $dailyBasic = $effectiveBasicSalary / $workingDaysCount;
                }

                $absenceDeductionDays = $absentDays + $missingDays + $unpaidLeaveDays;
                $absenceDeduction = $absenceDeductionDays * $dailyBasic;

                $totalDeductions = (float) ($deductionsByUser[$user->id] ?? 0);
                $totalBonuses = (float) ($bonusesByUser[$user->id] ?? 0);

                $overtimeRatePerHour = (float) ($setting->sdm_overtime_rate_per_hour ?? 0);
                $overtimePay = ($overtimeMinutes / 60) * $overtimeRatePerHour;

                $oldIncentiveAmount = (float) ($existing?->incentive_amount ?? 0);
                // We add the fetched bonus to any manual previously saved incentive, or just overwrite it:
                $incentiveAmount = $oldIncentiveAmount > 0 ? $oldIncentiveAmount : $totalBonuses;

                $performanceBonus = (float) ($existing?->performance_bonus ?? 0);

                $effectiveAbsenceDeduction = (float) ($existing?->override_absence_deduction ?? $absenceDeduction);

                $netSalary = ($effectiveBasicSalary + $totalAllowance + $fixedAllowanceTotal + $overtimePay + $incentiveAmount + $performanceBonus) - ($totalDeductions + $effectiveAbsenceDeduction);
                if ($netSalary < 0) {
                    $netSalary = 0;
                }

                if ($existing) {
                    $existing->update([
                        'working_days' => $workingDaysCount,
                        'present_days' => $presentDays,
                        'late_days' => $lateDays,
                        'izin_days' => $izinDays,
                        'sakit_days' => $sakitDays,
                        'absent_days' => $absentDays,
                        'missing_days' => $missingDays,
                        'unpaid_leave_days' => $unpaidLeaveDays,
                        'total_attendance' => $totalAttendance,
                        'total_basic_salary' => $effectiveBasicSalary,
                        'total_allowance' => $totalAllowance,
                        'fixed_allowance_total' => $fixedAllowanceTotal,
                        'meal_allowance_per_day' => $mealAllowancePerDay,
                        'meal_allowance_gross' => $mealAllowanceGross,
                        'late_meal_penalty' => $effectiveLateMealPenalty,
                        'overtime_minutes' => $overtimeMinutes,
                        'overtime_pay' => $overtimePay,
                        'incentive_amount' => $incentiveAmount,
                        'performance_bonus' => $performanceBonus,
                        'total_deductions' => $totalDeductions,
                        'absence_deduction' => $effectiveAbsenceDeduction,
                        'net_salary' => $netSalary,
                    ]);
                } else {
                    SdmPayroll::create([
                        'user_id' => $user->id,
                        'period_year' => $year,
                        'period_month' => $m,
                        'working_days' => $workingDaysCount,
                        'present_days' => $presentDays,
                        'late_days' => $lateDays,
                        'izin_days' => $izinDays,
                        'sakit_days' => $sakitDays,
                        'absent_days' => $absentDays,
                        'missing_days' => $missingDays,
                        'unpaid_leave_days' => $unpaidLeaveDays,
                        'total_attendance' => $totalAttendance,
                        'total_basic_salary' => $effectiveBasicSalary,
                        'total_allowance' => $totalAllowance,
                        'fixed_allowance_total' => $fixedAllowanceTotal,
                        'meal_allowance_per_day' => $mealAllowancePerDay,
                        'meal_allowance_gross' => $mealAllowanceGross,
                        'late_meal_penalty' => $effectiveLateMealPenalty,
                        'overtime_minutes' => $overtimeMinutes,
                        'overtime_pay' => $overtimePay,
                        'incentive_amount' => $incentiveAmount,
                        'performance_bonus' => 0,
                        'total_deductions' => $totalDeductions,
                        'absence_deduction' => $effectiveAbsenceDeduction,
                        'net_salary' => $netSalary,
                    ]);
                }

                $generatedCount++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Berhasil menghitung dan membuat $generatedCount slip gaji untuk periode ".Carbon::parse($month)->translatedFormat('F Y').'.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal menghitung gaji: '.$e->getMessage());
        }
    }

    public function destroy(SdmPayroll $penggajian)
    {
        if ($penggajian->locked_at) {
            return redirect()->back()->with('error', 'Slip gaji terkunci dan tidak dapat dihapus.');
        }

        $penggajian->delete();

        return redirect()->back()->with('success', 'Slip gaji berhasil dihapus.');
    }

    public function lock(Request $request, SdmPayroll $penggajian)
    {
        if (! $penggajian->locked_at) {
            $penggajian->update([
                'locked_at' => now(),
                'locked_by' => $request->user()?->id,
            ]);
        }

        return redirect()->back()->with('success', 'Slip gaji berhasil dikunci.');
    }

    public function unlock(SdmPayroll $penggajian)
    {
        if ($penggajian->locked_at) {
            $penggajian->update([
                'locked_at' => null,
                'locked_by' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Slip gaji berhasil dibuka.');
    }

    public function adjust(Request $request, SdmPayroll $penggajian)
    {
        if ($penggajian->locked_at) {
            return redirect()->back()->with('error', 'Slip gaji terkunci dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'incentive_amount' => 'nullable|numeric|min:0',
            'performance_bonus' => 'nullable|numeric|min:0',
            'override_total_basic_salary' => 'nullable|numeric|min:0',
            'override_late_meal_penalty' => 'nullable|numeric|min:0',
            'override_absence_deduction' => 'nullable|numeric|min:0',
        ]);

        $updates = [];
        $updates['incentive_amount'] = (float) ($validated['incentive_amount'] ?? 0);
        $updates['performance_bonus'] = (float) ($validated['performance_bonus'] ?? 0);
        $updates['override_total_basic_salary'] = array_key_exists('override_total_basic_salary', $validated) ? $validated['override_total_basic_salary'] : $penggajian->override_total_basic_salary;
        $updates['override_late_meal_penalty'] = array_key_exists('override_late_meal_penalty', $validated) ? $validated['override_late_meal_penalty'] : $penggajian->override_late_meal_penalty;
        $updates['override_absence_deduction'] = array_key_exists('override_absence_deduction', $validated) ? $validated['override_absence_deduction'] : $penggajian->override_absence_deduction;

        $effectiveBasic = $updates['override_total_basic_salary'] !== null ? (float) $updates['override_total_basic_salary'] : (float) ($penggajian->total_basic_salary ?? 0);
        $effectiveLateMealPenalty = $updates['override_late_meal_penalty'] !== null ? (float) $updates['override_late_meal_penalty'] : (float) ($penggajian->late_meal_penalty ?? 0);
        $effectiveAbsenceDeduction = $updates['override_absence_deduction'] !== null ? (float) $updates['override_absence_deduction'] : (float) ($penggajian->absence_deduction ?? 0);

        $mealGross = (float) ($penggajian->meal_allowance_gross ?? 0);
        $totalAllowance = $mealGross - $effectiveLateMealPenalty;
        if ($totalAllowance < 0) {
            $totalAllowance = 0;
        }

        if ($updates['override_total_basic_salary'] !== null && (float) $penggajian->total_basic_salary !== $effectiveBasic) {
            $updates['total_basic_salary'] = $effectiveBasic;
        }
        if ($updates['override_late_meal_penalty'] !== null && (float) $penggajian->late_meal_penalty !== $effectiveLateMealPenalty) {
            $updates['late_meal_penalty'] = $effectiveLateMealPenalty;
        }
        if ($updates['override_absence_deduction'] !== null && (float) $penggajian->absence_deduction !== $effectiveAbsenceDeduction) {
            $updates['absence_deduction'] = $effectiveAbsenceDeduction;
        }
        $updates['total_allowance'] = $totalAllowance;

        $overtimePay = (float) ($penggajian->overtime_pay ?? 0);
        $totalDeductions = (float) ($penggajian->total_deductions ?? 0);
        $fixedAllowanceTotal = (float) ($penggajian->fixed_allowance_total ?? 0);

        $netSalary = ($effectiveBasic + $totalAllowance + $fixedAllowanceTotal + $overtimePay + (float) $updates['incentive_amount'] + (float) $updates['performance_bonus']) - ($totalDeductions + $effectiveAbsenceDeduction);
        if ($netSalary < 0) {
            $netSalary = 0;
        }
        $updates['net_salary'] = $netSalary;
        
        $penggajian->update($updates);

        return redirect()->back()->with('success', 'Komponen slip gaji berhasil diperbarui.');
    }

    public function print(SdmPayroll $penggajian)
    {
        $currentUserId = Auth::id();
        $isSupervisor = strtolower((string) (Auth::user()?->role ?? '')) === 'supervisor';
        if (! $isSupervisor && $currentUserId && (int) $penggajian->user_id !== (int) $currentUserId) {
            abort(403);
        }

        $penggajian->load('user.employee');

        $monthName = Carbon::createFromDate($penggajian->period_year, $penggajian->period_month, 1)->translatedFormat('F Y');

        $deductions = SdmDeduction::where('user_id', $penggajian->user_id)
            ->whereYear('date', $penggajian->period_year)
            ->whereMonth('date', $penggajian->period_month)
            ->get();

        $bonuses = SdmBonus::where('user_id', $penggajian->user_id)
            ->whereYear('date', $penggajian->period_year)
            ->whereMonth('date', $penggajian->period_month)
            ->get();

        return view('sdm.penggajian.print', compact('penggajian', 'monthName', 'deductions', 'bonuses'));
    }

    private function workingDates(int $year, int $month, string $mode, string $calendarMode): array
    {
        $start = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $end = $start->copy()->endOfMonth()->startOfDay();

        $holidayRows = SdmHoliday::query()
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get(['date', 'is_working_day']);

        if ($calendarMode === 'manual') {
            $dates = [];
            foreach ($holidayRows as $row) {
                if ((bool) $row->is_working_day) {
                    $dates[] = Carbon::parse($row->date)->toDateString();
                }
            }

            sort($dates);

            return array_values(array_unique($dates));
        }

        $holidaySet = [];
        $workingOverrideSet = [];
        foreach ($holidayRows as $row) {
            $d = Carbon::parse($row->date)->toDateString();
            if ((bool) $row->is_working_day) {
                $workingOverrideSet[$d] = true;
            } else {
                $holidaySet[$d] = true;
            }
        }

        $dates = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $dateStr = $cursor->toDateString();
            if (isset($workingOverrideSet[$dateStr])) {
                $dates[] = $dateStr;
                $cursor->addDay();

                continue;
            }

            $dow = (int) $cursor->dayOfWeek;
            $isWorkingDay = $mode === 'mon_fri'
                ? ($dow >= 1 && $dow <= 5)
                : ($dow >= 1 && $dow <= 6);

            if ($isWorkingDay && ! isset($holidaySet[$dateStr])) {
                $dates[] = $dateStr;
            }
            $cursor->addDay();
        }

        return $dates;
    }

    private function lateMealPenalty(int $lateDays, float $mealPerDay, string $mode, float $value): float
    {
        if ($lateDays <= 0) {
            return 0;
        }

        if ($mode === 'none') {
            return 0;
        }

        if ($mode === 'fixed') {
            return $lateDays * $value;
        }

        if ($mode === 'percent') {
            return $lateDays * ($mealPerDay * ($value / 100));
        }

        return $lateDays * $mealPerDay;
    }
}

<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SdmDeduction;
use App\Models\SdmEmployee;
use App\Models\SdmEmployeeAllowance;
use App\Models\SdmHoliday;
use App\Models\SdmLeaveRequest;
use App\Models\SdmPayroll;
use App\Models\StoreSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = SdmEmployee::query()->with('user')->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('phone', 'like', '%'.$q.'%')
                    ->orWhere('position', 'like', '%'.$q.'%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('email', 'like', '%'.$q.'%')
                            ->orWhere('name', 'like', '%'.$q.'%')
                            ->orWhere('role', 'like', '%'.$q.'%');
                    });
            });
        }

        if ($request->filled('has_account')) {
            if ($request->has_account === 'yes') {
                $query->whereNotNull('user_id');
            } elseif ($request->has_account === 'no') {
                $query->whereNull('user_id');
            }
        }

        if ($request->filled('role')) {
            $query->whereHas('user', fn ($u) => $u->where('role', $request->role));
        }

        $karyawan = $query->paginate(15)->withQueryString();
        $roles = User::query()
            ->select('role')
            ->whereNotNull('role')
            ->distinct()
            ->orderBy('role')
            ->pluck('role')
            ->values();

        return view('sdm.karyawan.index', compact('karyawan', 'roles'));
    }

    public function export(Request $request)
    {
        $query = SdmEmployee::query()->with('user')->latest();

        if ($request->filled('q')) {
            $q = trim((string) $request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', '%'.$q.'%')
                    ->orWhere('phone', 'like', '%'.$q.'%')
                    ->orWhere('position', 'like', '%'.$q.'%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('email', 'like', '%'.$q.'%')
                            ->orWhere('name', 'like', '%'.$q.'%')
                            ->orWhere('role', 'like', '%'.$q.'%');
                    });
            });
        }

        if ($request->filled('has_account')) {
            if ($request->has_account === 'yes') {
                $query->whereNotNull('user_id');
            } elseif ($request->has_account === 'no') {
                $query->whereNull('user_id');
            }
        }

        if ($request->filled('role')) {
            $query->whereHas('user', fn ($u) => $u->where('role', $request->role));
        }

        $rows = $query->get();

        $filename = 'karyawan-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'id',
                'name',
                'phone',
                'position',
                'join_date',
                'active',
                'basic_salary',
                'daily_allowance',
                'user_id',
                'user_name',
                'user_email',
                'user_role',
            ]);

            foreach ($rows as $e) {
                fputcsv($out, [
                    $e->id,
                    $e->name,
                    $e->phone,
                    $e->position,
                    optional($e->join_date)->format('Y-m-d'),
                    $e->active ? 1 : 0,
                    $e->basic_salary,
                    $e->daily_allowance,
                    $e->user_id,
                    $e->user?->name,
                    $e->user?->email,
                    $e->user?->role,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create()
    {
        return view('sdm.karyawan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:120',
            'join_date' => 'nullable|date',
            'active' => 'nullable|in:1',
            'notes' => 'nullable|string',
            'basic_salary' => 'nullable|numeric|min:0',
            'daily_allowance' => 'nullable|numeric|min:0',
        ]);

        SdmEmployee::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'] ?? null,
            'join_date' => $validated['join_date'] ?? null,
            'active' => ($validated['active'] ?? null) === '1',
            'notes' => $validated['notes'] ?? null,
            'basic_salary' => $validated['basic_salary'] ?? 0,
            'daily_allowance' => $validated['daily_allowance'] ?? 0,
        ]);

        return redirect()->route('sdm.karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(SdmEmployee $karyawan)
    {
        $karyawan->load(['user', 'allowances']);

        return view('sdm.karyawan.edit', compact('karyawan'));
    }

    public function show(Request $request, SdmEmployee $karyawan)
    {
        $karyawan->load('user');

        $linkableUsers = User::query()
            ->where(function ($q) use ($karyawan) {
                $q->whereDoesntHave('employee')
                    ->orWhereHas('employee', fn ($e) => $e->where('id', $karyawan->id));
            })
            ->orderBy('name')
            ->get();

        $month = $request->input('month', now()->format('Y-m'));
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }
        [$year, $m] = explode('-', $month);

        $monthStart = Carbon::createFromDate((int) $year, (int) $m, 1)->startOfDay();
        $monthEnd = Carbon::createFromDate((int) $year, (int) $m, 1)->endOfMonth()->endOfDay();

        $stats = [
            'counts' => [],
            'work_hours' => 0,
            'late_minutes' => 0,
            'overtime_minutes' => 0,
            'unpaid_leave_days' => 0,
        ];
        $attendances = collect();
        $leaveRequests = collect();
        $deductions = collect();
        $payroll = null;
        $payrollHistory = collect();
        $calendarDays = [];

        if ($karyawan->user_id) {
            $attendances = Attendance::query()
                ->where('user_id', $karyawan->user_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->orderBy('date')
                ->get();

            $attByDate = $attendances->keyBy(fn ($a) => Carbon::parse($a->date)->toDateString());

            foreach ($attendances as $a) {
                $st = (string) ($a->status ?? '');
                if ($st !== '') {
                    $stats['counts'][$st] = (int) (($stats['counts'][$st] ?? 0) + 1);
                }
                $stats['work_hours'] += (float) ($a->work_hours ?? 0);
                $stats['late_minutes'] += (int) ($a->late_minutes ?? 0);
                $stats['overtime_minutes'] += (int) ($a->overtime_minutes ?? 0);
            }

            $leaveRequests = SdmLeaveRequest::query()
                ->where('user_id', $karyawan->user_id)
                ->whereDate('start_date', '<=', $monthEnd->toDateString())
                ->whereDate('end_date', '>=', $monthStart->toDateString())
                ->orderBy('start_date')
                ->get();

            $approvedLeaves = $leaveRequests->where('status', 'approved')->values();
            $leaveByDate = [];
            foreach ($approvedLeaves as $leave) {
                if ((bool) ($leave->paid ?? false)) {
                    $start = Carbon::parse($leave->start_date)->startOfDay();
                    $end = Carbon::parse($leave->end_date)->startOfDay();
                    $cursor = $start->copy();
                    while ($cursor->lte($end)) {
                        if ($cursor->betweenIncluded($monthStart, $monthEnd)) {
                            $leaveByDate[$cursor->toDateString()] = [
                                'type' => (string) $leave->type,
                                'paid' => true,
                            ];
                        }
                        $cursor->addDay();
                    }

                    continue;
                }
                $start = Carbon::parse($leave->start_date)->startOfDay();
                $end = Carbon::parse($leave->end_date)->startOfDay();
                $cursor = $start->copy();
                while ($cursor->lte($end)) {
                    if ($cursor->betweenIncluded($monthStart, $monthEnd)) {
                        $stats['unpaid_leave_days']++;
                        $leaveByDate[$cursor->toDateString()] = [
                            'type' => (string) $leave->type,
                            'paid' => false,
                        ];
                    }
                    $cursor->addDay();
                }
            }

            $deductions = SdmDeduction::query()
                ->where('user_id', $karyawan->user_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $m)
                ->orderBy('date')
                ->get();

            $payroll = SdmPayroll::query()
                ->where('user_id', $karyawan->user_id)
                ->where('period_year', $year)
                ->where('period_month', $m)
                ->first();

            $payrollHistory = SdmPayroll::query()
                ->where('user_id', $karyawan->user_id)
                ->orderByDesc('period_year')
                ->orderByDesc('period_month')
                ->limit(6)
                ->get();

            $setting = StoreSetting::current();
            $calendarMode = (string) ($setting->sdm_calendar_mode ?? 'auto');
            $workingMode = (string) ($setting->sdm_working_days_mode ?? 'mon_sat');

            $holidayRows = SdmHoliday::query()
                ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->get(['date', 'is_working_day', 'name']);
            $holidayByDate = $holidayRows->keyBy(fn ($h) => Carbon::parse($h->date)->toDateString());

            $cursor = $monthStart->copy();
            while ($cursor->lte($monthEnd)) {
                $dateStr = $cursor->toDateString();
                $h = $holidayByDate->get($dateStr);

                $isWorkingDay = false;
                if ($h) {
                    $isWorkingDay = (bool) $h->is_working_day;
                } elseif ($calendarMode !== 'manual') {
                    $dow = (int) $cursor->dayOfWeek;
                    $isWorkingDay = $workingMode === 'mon_fri'
                        ? ($dow >= Carbon::MONDAY && $dow <= Carbon::FRIDAY)
                        : ($dow >= Carbon::MONDAY && $dow <= Carbon::SATURDAY);
                }

                $badgeClass = 'badge-gray';
                $statusText = $isWorkingDay ? 'Hari Kerja' : 'Libur';
                $metaText = $h?->name ?: null;

                $att = $attByDate->get($dateStr);
                if ($isWorkingDay && $att) {
                    $st = (string) ($att->status ?? '');
                    $statusText = strtoupper($st);
                    $badgeClass = match ($st) {
                        'present' => 'badge-success',
                        'late' => 'badge-warning',
                        'izin', 'sakit' => 'badge-blue',
                        'absent' => 'badge-danger',
                        default => 'badge-gray',
                    };
                    $metaText = trim((string) (($att->check_in_time ?? '').' - '.($att->check_out_time ?? ''))) ?: $metaText;
                } elseif ($isWorkingDay && isset($leaveByDate[$dateStr])) {
                    $lt = $leaveByDate[$dateStr];
                    $statusText = strtoupper((string) $lt['type']).(($lt['paid'] ?? false) ? '' : ' (UNPAID)');
                    $badgeClass = ($lt['paid'] ?? false) ? 'badge-blue' : 'badge-danger';
                } elseif ($isWorkingDay) {
                    $statusText = 'MISSING';
                    $badgeClass = 'badge-danger';
                }

                $calendarDays[] = [
                    'date' => $dateStr,
                    'day' => $cursor->day,
                    'dow' => $cursor->translatedFormat('D'),
                    'is_working_day' => $isWorkingDay,
                    'status_text' => $statusText,
                    'badge_class' => $badgeClass,
                    'meta' => $metaText,
                ];

                $cursor->addDay();
            }
        }

        $monthLabel = Carbon::parse($month.'-01')->translatedFormat('F Y');

        return view('sdm.karyawan.show', compact(
            'karyawan',
            'month',
            'monthLabel',
            'stats',
            'linkableUsers',
            'calendarDays',
            'attendances',
            'leaveRequests',
            'deductions',
            'payroll',
            'payrollHistory'
        ));
    }

    public function linkUser(Request $request, SdmEmployee $karyawan)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = (int) $validated['user_id'];
        $usedByOther = SdmEmployee::query()
            ->where('user_id', $userId)
            ->where('id', '!=', $karyawan->id)
            ->exists();

        if ($usedByOther) {
            return redirect()->back()->with('error', 'Akun ini sudah tertaut ke karyawan lain.');
        }

        $karyawan->update(['user_id' => $userId]);

        return redirect()->route('sdm.karyawan.show', $karyawan)->with('success', 'Akun berhasil ditautkan ke karyawan.');
    }

    public function unlinkUser(SdmEmployee $karyawan)
    {
        $karyawan->update(['user_id' => null]);

        return redirect()->route('sdm.karyawan.show', $karyawan)->with('success', 'Tautan akun karyawan berhasil dilepas.');
    }

    public function update(Request $request, SdmEmployee $karyawan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:120',
            'join_date' => 'nullable|date',
            'active' => 'nullable|in:1',
            'notes' => 'nullable|string',
            'basic_salary' => 'nullable|numeric|min:0',
            'daily_allowance' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|array',
            'allowances.*.id' => 'nullable|integer|exists:sdm_employee_allowances,id',
            'allowances.*.label' => 'required_with:allowances|string|max:100',
            'allowances.*.amount' => 'required_with:allowances|numeric|min:0',
            'allowances.*.active' => 'nullable|in:1',
        ]);

        $karyawan->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'] ?? null,
            'join_date' => $validated['join_date'] ?? null,
            'active' => ($validated['active'] ?? null) === '1',
            'notes' => $validated['notes'] ?? null,
            'basic_salary' => $validated['basic_salary'] ?? 0,
            'daily_allowance' => $validated['daily_allowance'] ?? 0,
        ]);

        // Sync allowance rows
        $submittedAllowances = $validated['allowances'] ?? [];
        $submittedIds = array_filter(array_column($submittedAllowances, 'id'));

        // Delete rows not in submitted list
        SdmEmployeeAllowance::where('employee_id', $karyawan->id)
            ->when(! empty($submittedIds), fn ($q) => $q->whereNotIn('id', $submittedIds))
            ->when(empty($submittedIds), fn ($q) => $q)
            ->delete();

        foreach ($submittedAllowances as $row) {
            $data = [
                'employee_id' => $karyawan->id,
                'label' => $row['label'],
                'amount' => (float) $row['amount'],
                'active' => ($row['active'] ?? null) === '1',
            ];

            if (! empty($row['id'])) {
                SdmEmployeeAllowance::where('id', $row['id'])
                    ->where('employee_id', $karyawan->id)
                    ->update($data);
            } else {
                SdmEmployeeAllowance::create($data);
            }
        }

        return redirect()->route('sdm.karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
    }

    public function importFromAccounts()
    {
        $users = User::query()->select(['id', 'name', 'active'])->get();
        $existingUserIds = SdmEmployee::query()->whereNotNull('user_id')->pluck('user_id')->all();

        $created = 0;
        foreach ($users as $u) {
            if (in_array($u->id, $existingUserIds, true)) {
                continue;
            }

            SdmEmployee::create([
                'name' => $u->name,
                'active' => (bool) ($u->active ?? true),
                'user_id' => $u->id,
            ]);
            $created++;
        }

        return redirect()->route('sdm.karyawan.index')->with('success', 'Import selesai. Ditambahkan: '.$created.' karyawan dari akun.');
    }
}

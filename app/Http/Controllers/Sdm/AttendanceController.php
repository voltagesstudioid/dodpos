<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SdmHoliday;
use App\Models\SdmLeaveRequest;
use App\Models\StockOpnameSession;
use App\Models\StoreSetting;
use App\Models\SdmEmployee;
use App\Models\User;
use App\Services\FileUploadService;
use App\Support\WarehouseConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jmrashed\Zkteco\Lib\ZKTeco;

class AttendanceController extends Controller
{
    /**
     * Securely store uploaded selfie using FileUploadService.
     * Replaces the old storeSelfieSecurely method.
     */
    private function storeSelfieSecurely($file, string $date, string $tag, array $context): ?string
    {
        try {
            $upload = FileUploadService::uploadImage(
                $file,
                "attendance-selfies/{$date}",
                'public',
                ['max_width' => 1200, 'max_height' => 1200, 'strip_exif' => true]
            );

            Log::info('attendance_selfie_saved', $context + [
                'path' => $upload['path'],
                'tag' => $tag,
                'size' => $upload['size'],
            ]);

            return $upload['path'];
        } catch (\Throwable $e) {
            Log::error('attendance_selfie_save_failed', $context + [
                'tag' => $tag,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function tryPutSelfieBytes(string $path, string $bytes, array $context): bool
    {
        try {
            Storage::disk('public')->put($path, $bytes);
            Log::info('attendance_selfie_saved', $context + ['path' => $path]);

            return true;
        } catch (\Throwable $e) {
            Log::error('attendance_selfie_save_failed', $context + ['path' => $path, 'error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Tampilkan data absensi hari ini atau tanggal tertentu
     */
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $month = $request->input('month');
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = Carbon::parse($date)->format('Y-m');
        }
        [$year, $m] = explode('-', $month);

        $currentUserId = Auth::id();
        $isSupervisor = strtolower((string) (Auth::user()?->role ?? '')) === 'supervisor';

        // Get attendances with their related user (use whereDate for SQLite compatibility)
        $attendances = Attendance::with('user')
            ->whereDate('date', $date)
            ->when(! $isSupervisor && $currentUserId, fn ($q) => $q->where('user_id', $currentUserId))
            ->orderBy('check_in_time', 'asc')
            ->get();

        $usersQuery = User::whereHas('employee', fn ($q) => $q->where('active', true));
        if (! $isSupervisor && $currentUserId) {
            $usersQuery->where('id', $currentUserId);
        }
        $users = $usersQuery
            ->orderBy('name')
            ->get();

        $monthRows = Attendance::query()
            ->select(['user_id', 'status', DB::raw('COUNT(*) as total')])
            ->whereNotNull('user_id')
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->when(! $isSupervisor && $currentUserId, fn ($q) => $q->where('user_id', $currentUserId))
            ->groupBy('user_id', 'status')
            ->get();

        $monthlyCounts = [];
        foreach ($monthRows as $row) {
            $monthlyCounts[$row->user_id][$row->status] = (int) $row->total;
        }

        $monthlyHours = Attendance::query()
            ->select(['user_id', DB::raw('SUM(work_hours) as total_hours')])
            ->whereNotNull('user_id')
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->when(! $isSupervisor && $currentUserId, fn ($q) => $q->where('user_id', $currentUserId))
            ->groupBy('user_id')
            ->pluck('total_hours', 'user_id')
            ->toArray();

        $monthLabel = Carbon::createFromDate((int) $year, (int) $m, 1)->translatedFormat('F Y');

        // Build attendance map: user_id => attendance for this date
        $attMap = $attendances->keyBy('user_id');

        // Compute daily stats
        $stats = [
            'total_employees' => $users->count(),
            'present'         => $attendances->whereIn('status', ['present', 'late'])->count(),
            'late'            => $attendances->where('status', 'late')->count(),
            'absent'          => $attendances->where('status', 'absent')->count(),
            'izin_sakit'      => $attendances->whereIn('status', ['izin', 'sakit'])->count(),
            'belum'           => max(0, $users->count() - $attendances->whereNotNull('user_id')->count()),
        ];

        // Load store setting for display & editing by supervisor
        $setting = StoreSetting::current();

        return view('sdm.absensi.index', compact(
            'date', 'month', 'monthLabel', 'attendances', 'users',
            'monthlyCounts', 'monthlyHours', 'attMap', 'stats',
            'setting', 'isSupervisor'
        ));
    }

    /**
     * Supervisor menyimpan pengaturan jam kerja langsung dari halaman absensi.
     */
    public function updateSchedule(Request $request)
    {
        // Hanya supervisor yang boleh mengubah
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if ($role !== 'supervisor') {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'sdm_work_start_time'      => 'required|date_format:H:i',
            'sdm_work_end_time'        => 'required|date_format:H:i',
            'sdm_late_grace_minutes'   => 'required|integer|min:0|max:120',
            'sdm_overtime_rate_per_hour' => 'nullable|numeric|min:0',
        ]);

        $setting = StoreSetting::current();
        $setting->update([
            'sdm_work_start_time'        => $validated['sdm_work_start_time'],
            'sdm_work_end_time'          => $validated['sdm_work_end_time'],
            'sdm_late_grace_minutes'     => (int) $validated['sdm_late_grace_minutes'],
            'sdm_overtime_rate_per_hour' => $validated['sdm_overtime_rate_per_hour'] ?? $setting->sdm_overtime_rate_per_hour,
        ]);

        return redirect()->back()->with('success', 'Pengaturan jam kerja berhasil disimpan.');
    }

    public function monthly(Request $request)
    {
        $month = $request->input('month');
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }
        [$year, $m] = explode('-', $month);

        $setting = StoreSetting::current();
        $workingDates = $this->workingDates((int) $year, (int) $m, (string) ($setting->sdm_working_days_mode ?? 'mon_sat'), (string) ($setting->sdm_calendar_mode ?? 'auto'));
        $workingDatesSet = array_fill_keys($workingDates, true);
        $workingDaysCount = count($workingDates);

        $users = User::whereHas('employee', fn ($q) => $q->where('active', true))
            ->with('employee')
            ->orderBy('name')
            ->get();

        if ($request->filled('user_id')) {
            $users = $users->where('id', (int) $request->user_id)->values();
        }

        $userIds = $users->pluck('id')->all();

        $attRows = Attendance::query()
            ->whereIn('user_id', $userIds)
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->get(['user_id', 'date', 'status', 'work_hours', 'late_minutes', 'overtime_minutes']);

        $attByUserDate = [];
        $recap = [];
        foreach ($attRows as $row) {
            $dateStr = Carbon::parse($row->date)->toDateString();
            if (! isset($workingDatesSet[$dateStr])) {
                continue;
            }

            $uid = (int) $row->user_id;
            $attByUserDate[$uid][$dateStr] = (string) $row->status;

            $recap[$uid]['work_hours'] = (float) (($recap[$uid]['work_hours'] ?? 0) + (float) ($row->work_hours ?? 0));
            $recap[$uid]['late_minutes'] = (int) (($recap[$uid]['late_minutes'] ?? 0) + (int) ($row->late_minutes ?? 0));
            $recap[$uid]['overtime_minutes'] = (int) (($recap[$uid]['overtime_minutes'] ?? 0) + (int) ($row->overtime_minutes ?? 0));

            $st = (string) ($row->status ?? '');
            if ($st !== '') {
                $recap[$uid]['counts'][$st] = (int) (($recap[$uid]['counts'][$st] ?? 0) + 1);
            }
        }

        $monthStart = Carbon::createFromDate((int) $year, (int) $m, 1)->startOfDay();
        $monthEnd = Carbon::createFromDate((int) $year, (int) $m, 1)->endOfMonth()->endOfDay();

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

        foreach ($users as $u) {
            $uid = (int) $u->id;
            $missing = 0;
            $unpaidLeaveDays = 0;
            $paidLeaveDays = 0;
            $leaveTypeCounts = ['cuti' => 0, 'izin' => 0, 'sakit' => 0];

            foreach ($workingDates as $dateStr) {
                if (isset($attByUserDate[$uid][$dateStr])) {
                    continue;
                }
                if (isset($leaveByUserDate[$uid][$dateStr])) {
                    $t = (string) $leaveByUserDate[$uid][$dateStr]['type'];
                    if (isset($leaveTypeCounts[$t])) {
                        $leaveTypeCounts[$t]++;
                    }
                    if (! $leaveByUserDate[$uid][$dateStr]['paid']) {
                        $unpaidLeaveDays++;
                    } else {
                        $paidLeaveDays++;
                    }

                    continue;
                }
                $missing++;
            }

            $counts = $recap[$uid]['counts'] ?? [];
            $present = (int) ($counts['present'] ?? 0);
            $late = (int) ($counts['late'] ?? 0);
            $izin = (int) ($counts['izin'] ?? 0);
            $sakit = (int) ($counts['sakit'] ?? 0);
            $absent = (int) ($counts['absent'] ?? 0);

            $recap[$uid]['working_days'] = $workingDaysCount;
            $recap[$uid]['missing_days'] = $missing;
            $recap[$uid]['paid_leave_days'] = $paidLeaveDays;
            $recap[$uid]['unpaid_leave_days'] = $unpaidLeaveDays;
            $recap[$uid]['leave_type_counts'] = $leaveTypeCounts;
            $recap[$uid]['total_attendance'] = $present + $late;
            $recap[$uid]['alpha_total'] = $absent + $missing;
        }

        $monthLabel = Carbon::createFromDate((int) $year, (int) $m, 1)->translatedFormat('F Y');

        return view('sdm.absensi.monthly', compact('month', 'monthLabel', 'users', 'recap', 'workingDaysCount'));
    }

    public function monthlyExport(Request $request)
    {
        $request->merge(['month' => $request->input('month', now()->format('Y-m'))]);
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = (string) $request->month;
        [$year, $m] = explode('-', $month);

        $setting = StoreSetting::current();
        $workingDates = $this->workingDates((int) $year, (int) $m, (string) ($setting->sdm_working_days_mode ?? 'mon_sat'), (string) ($setting->sdm_calendar_mode ?? 'auto'));
        $workingDatesSet = array_fill_keys($workingDates, true);
        $workingDaysCount = count($workingDates);

        $users = User::whereHas('employee', fn ($q) => $q->where('active', true))
            ->with('employee')
            ->orderBy('name')
            ->get();

        if ($request->filled('user_id')) {
            $users = $users->where('id', (int) $request->user_id)->values();
        }

        $userIds = $users->pluck('id')->all();

        $attRows = Attendance::query()
            ->whereIn('user_id', $userIds)
            ->whereYear('date', $year)
            ->whereMonth('date', $m)
            ->get(['user_id', 'date', 'status', 'work_hours', 'late_minutes', 'overtime_minutes']);

        $attByUserDate = [];
        $recap = [];
        foreach ($attRows as $row) {
            $dateStr = Carbon::parse($row->date)->toDateString();
            if (! isset($workingDatesSet[$dateStr])) {
                continue;
            }

            $uid = (int) $row->user_id;
            $attByUserDate[$uid][$dateStr] = (string) $row->status;
            $recap[$uid]['work_hours'] = (float) (($recap[$uid]['work_hours'] ?? 0) + (float) ($row->work_hours ?? 0));
            $recap[$uid]['late_minutes'] = (int) (($recap[$uid]['late_minutes'] ?? 0) + (int) ($row->late_minutes ?? 0));
            $recap[$uid]['overtime_minutes'] = (int) (($recap[$uid]['overtime_minutes'] ?? 0) + (int) ($row->overtime_minutes ?? 0));

            $st = (string) ($row->status ?? '');
            if ($st !== '') {
                $recap[$uid]['counts'][$st] = (int) (($recap[$uid]['counts'][$st] ?? 0) + 1);
            }
        }

        $monthStart = Carbon::createFromDate((int) $year, (int) $m, 1)->startOfDay();
        $monthEnd = Carbon::createFromDate((int) $year, (int) $m, 1)->endOfMonth()->endOfDay();

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

        foreach ($users as $u) {
            $uid = (int) $u->id;
            $missing = 0;
            $unpaidLeaveDays = 0;
            $paidLeaveDays = 0;
            $leaveTypeCounts = ['cuti' => 0, 'izin' => 0, 'sakit' => 0];

            foreach ($workingDates as $dateStr) {
                if (isset($attByUserDate[$uid][$dateStr])) {
                    continue;
                }
                if (isset($leaveByUserDate[$uid][$dateStr])) {
                    $t = (string) $leaveByUserDate[$uid][$dateStr]['type'];
                    if (isset($leaveTypeCounts[$t])) {
                        $leaveTypeCounts[$t]++;
                    }
                    if (! $leaveByUserDate[$uid][$dateStr]['paid']) {
                        $unpaidLeaveDays++;
                    } else {
                        $paidLeaveDays++;
                    }

                    continue;
                }
                $missing++;
            }

            $counts = $recap[$uid]['counts'] ?? [];
            $present = (int) ($counts['present'] ?? 0);
            $late = (int) ($counts['late'] ?? 0);
            $absent = (int) ($counts['absent'] ?? 0);

            $recap[$uid]['working_days'] = $workingDaysCount;
            $recap[$uid]['missing_days'] = $missing;
            $recap[$uid]['paid_leave_days'] = $paidLeaveDays;
            $recap[$uid]['unpaid_leave_days'] = $unpaidLeaveDays;
            $recap[$uid]['leave_type_counts'] = $leaveTypeCounts;
            $recap[$uid]['total_attendance'] = $present + $late;
            $recap[$uid]['alpha_total'] = $absent + $missing;
        }

        $filename = 'rekap-absensi-'.$month.'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($users, $recap) {
            $out = fopen('php://output', 'w');

            fputcsv($out, [
                'user_id',
                'nama',
                'role',
                'working_days',
                'present',
                'late',
                'izin',
                'sakit',
                'absent',
                'cuti_days',
                'paid_leave_days',
                'unpaid_leave_days',
                'missing_days',
                'alpha_total',
                'work_hours',
                'late_minutes',
                'overtime_minutes',
            ]);

            foreach ($users as $u) {
                $uid = (int) $u->id;
                $r = $recap[$uid] ?? [];
                $counts = $r['counts'] ?? [];
                $lt = $r['leave_type_counts'] ?? ['cuti' => 0, 'izin' => 0, 'sakit' => 0];

                fputcsv($out, [
                    $uid,
                    $u->name,
                    $u->role,
                    (int) ($r['working_days'] ?? 0),
                    (int) ($counts['present'] ?? 0),
                    (int) ($counts['late'] ?? 0),
                    (int) ($counts['izin'] ?? 0),
                    (int) ($counts['sakit'] ?? 0),
                    (int) ($counts['absent'] ?? 0),
                    (int) ($lt['cuti'] ?? 0),
                    (int) ($r['paid_leave_days'] ?? 0),
                    (int) ($r['unpaid_leave_days'] ?? 0),
                    (int) ($r['missing_days'] ?? 0),
                    (int) ($r['alpha_total'] ?? 0),
                    number_format((float) ($r['work_hours'] ?? 0), 2, '.', ''),
                    (int) ($r['late_minutes'] ?? 0),
                    (int) ($r['overtime_minutes'] ?? 0),
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Kaitkan UID Fingerprint yang Tidak Diketahui ke User tertentu
     */
    public function linkUser(Request $request)
    {
        $request->validate([
            'fingerprint_id' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $fingerprintId = $request->input('fingerprint_id');
        $userId = $request->input('user_id');

        $user = User::findOrFail($userId);

        // Cek apakah UID ini kebetulan sudah dipakai user lain
        $existingUser = User::where('fingerprint_id', $fingerprintId)
            ->where('id', '!=', $userId)
            ->first();

        if ($existingUser) {
            return redirect()->back()->with('error', "Gagal: Nomor Finger (UID) $fingerprintId sudah digunakan oleh {$existingUser->name}.");
        }

        // Simpan / update UID di profil user tujuan
        $user->fingerprint_id = $fingerprintId;
        $user->save();

        // Update SEMUA history absen "Tidak Diketahui" yang UID nya ini, agar sekarang menjadi milik user tersebut
        Attendance::where('fingerprint_id', $fingerprintId)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        return redirect()->back()->with('success', "Sukses! Nomor Finger (UID) $fingerprintId telah berhasil dikaitkan ke {$user->name}. Semua histori absen sebelumnya dengan UID ini otomatis terhubung.");
    }

    public function storeManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,late,absent,izin,sakit',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'overtime_minutes' => 'nullable|integer|min:0|max:1440',
            'selfie_in' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'selfie_out' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $validator->after(function ($v) use ($request) {
            $status = (string) $request->input('status');
            $checkIn = $request->input('check_in_time');
            $checkOut = $request->input('check_out_time');

            if (in_array($status, ['present', 'late'], true) && $checkIn && ! $request->hasFile('selfie_in')) {
                $v->errors()->add('selfie_in', 'Selfie masuk wajib diisi.');
            }
            if (in_array($status, ['present', 'late'], true) && $checkOut && ! $request->hasFile('selfie_out')) {
                $v->errors()->add('selfie_out', 'Selfie pulang wajib diisi.');
            }
        });

        $validated = $validator->validate();

        $user = User::with('employee')->findOrFail($validated['user_id']);
        $setting = StoreSetting::current();
        $workStartTime = $setting->sdm_work_start_time ?? '08:00';
        $lateGraceMinutes = (int) ($setting->sdm_late_grace_minutes ?? 10);

        $date = Carbon::parse($validated['date'])->toDateString();
        $status = (string) $validated['status'];
        $checkIn = $validated['check_in_time'] ?? null;
        $checkOut = $validated['check_out_time'] ?? null;

        $fingerprintId = (string) ($user->fingerprint_id ?: ('manual_'.$user->id));

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $date)
            ->first();

        if (! $attendance) {
            $attendance = Attendance::where('fingerprint_id', $fingerprintId)
                ->where('date', $date)
                ->first();
        }

        $attributes = [
            'user_id' => $user->id,
            'fingerprint_id' => $fingerprintId,
            'date' => $date,
            'status' => $status,
        ];

        if (in_array($status, ['present', 'late'], true)) {
            $attributes['check_in_time'] = $checkIn;
            $attributes['check_out_time'] = $checkOut;
            $attributes['overtime_minutes'] = (int) ($validated['overtime_minutes'] ?? 0);
            $lateMinutes = 0;
            if ($attributes['check_in_time']) {
                $lateMinutes = $this->lateMinutes($date, $attributes['check_in_time'], $workStartTime, $lateGraceMinutes);
                $attributes['status'] = $lateMinutes > 0 ? 'late' : 'present';
            }
            $attributes['late_minutes'] = $lateMinutes > 0 ? $lateMinutes : 0;
        } else {
            $attributes['check_in_time'] = null;
            $attributes['check_out_time'] = null;
            $attributes['late_minutes'] = null;
            $attributes['overtime_minutes'] = null;
        }

        $attributes['work_hours'] = null;
        if ($attributes['check_in_time'] && $attributes['check_out_time']) {
            $in = Carbon::parse($date.' '.$attributes['check_in_time']);
            $out = Carbon::parse($date.' '.$attributes['check_out_time']);
            $attributes['work_hours'] = $in->diffInMinutes($out) / 60;
        }

        if (! in_array($status, ['present', 'late'], true)) {
            if ($attendance) {
                if ($attendance->check_in_selfie_path) {
                    Storage::disk('public')->delete($attendance->check_in_selfie_path);
                }
                if ($attendance->check_out_selfie_path) {
                    Storage::disk('public')->delete($attendance->check_out_selfie_path);
                }
            }
            $attributes['check_in_selfie_path'] = null;
            $attributes['check_out_selfie_path'] = null;
        }

        $context = [
            'actor_user_id' => Auth::id(),
            'target_user_id' => $user->id,
            'attendance_id' => $attendance?->id,
            'date' => $date,
        ];

        try {
            DB::beginTransaction();

            if ($attendance) {
                if ($request->hasFile('selfie_in')) {
                    if ($attendance->check_in_selfie_path) {
                        Storage::disk('public')->delete($attendance->check_in_selfie_path);
                    }
                    $path = $this->storeSelfieSecurely($request->file('selfie_in'), $date, 'manual_in', $context);
                    if (! $path) {
                        DB::rollBack();
                        return back()->with('error', 'Gagal menyimpan foto masuk. Coba lagi.');
                    }
                    $attributes['check_in_selfie_path'] = $path;
                }
                if ($request->hasFile('selfie_out')) {
                    if ($attendance->check_out_selfie_path) {
                        Storage::disk('public')->delete($attendance->check_out_selfie_path);
                    }
                    $path = $this->storeSelfieSecurely($request->file('selfie_out'), $date, 'manual_out', $context);
                    if (! $path) {
                        DB::rollBack();
                        return back()->with('error', 'Gagal menyimpan foto pulang. Coba lagi.');
                    }
                    $attributes['check_out_selfie_path'] = $path;
                }
                if (! $attributes['check_out_time']) {
                    if ($attendance->check_out_selfie_path) {
                        Storage::disk('public')->delete($attendance->check_out_selfie_path);
                    }
                    $attributes['check_out_selfie_path'] = null;
                }

                $attendance->update($attributes);
            } else {
                if ($request->hasFile('selfie_in')) {
                    $path = $this->storeSelfieSecurely($request->file('selfie_in'), $date, 'manual_in', $context);
                    if (! $path) {
                        DB::rollBack();
                        return back()->with('error', 'Gagal menyimpan foto masuk. Coba lagi.');
                    }
                    $attributes['check_in_selfie_path'] = $path;
                }
                if ($request->hasFile('selfie_out')) {
                    $path = $this->storeSelfieSecurely($request->file('selfie_out'), $date, 'manual_out', $context);
                    if (! $path) {
                        DB::rollBack();
                        return back()->with('error', 'Gagal menyimpan foto pulang. Coba lagi.');
                    }
                    $attributes['check_out_selfie_path'] = $path;
                }
                if (! $attributes['check_out_time']) {
                    $attributes['check_out_selfie_path'] = null;
                }

                Attendance::create($attributes);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Attendance storeManual failed', ['error' => $e->getMessage(), 'context' => $context]);
            return back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }

        return redirect()->route('sdm.absensi.index', ['date' => $date])->with('success', 'Absensi manual berhasil disimpan.');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:present,late,absent,izin,sakit',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'overtime_minutes' => 'nullable|integer|min:0|max:1440',
            'selfie_in' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'selfie_out' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $validator->after(function ($v) use ($request, $attendance) {
            $status = (string) $request->input('status');
            $checkIn = $request->input('check_in_time');
            $checkOut = $request->input('check_out_time');

            if (! in_array($status, ['present', 'late'], true)) {
                return;
            }
            if ($checkIn && ! $request->hasFile('selfie_in') && ! $attendance->check_in_selfie_path) {
                $v->errors()->add('selfie_in', 'Selfie masuk wajib diisi.');
            }
            if ($checkOut && ! $request->hasFile('selfie_out') && ! $attendance->check_out_selfie_path) {
                $v->errors()->add('selfie_out', 'Selfie pulang wajib diisi.');
            }
        });

        $validated = $validator->validate();

        $date = Carbon::parse($attendance->date)->toDateString();
        $status = (string) $validated['status'];
        $checkIn = $validated['check_in_time'] ?? null;
        $checkOut = $validated['check_out_time'] ?? null;
        $setting = StoreSetting::current();
        $workStartTime = $setting->sdm_work_start_time ?? '08:00';
        $lateGraceMinutes = (int) ($setting->sdm_late_grace_minutes ?? 10);

        $updates = [
            'status' => $status,
        ];

        if (in_array($status, ['present', 'late'], true)) {
            $updates['check_in_time'] = $checkIn;
            $updates['check_out_time'] = $checkOut;
            $updates['overtime_minutes'] = (int) ($validated['overtime_minutes'] ?? 0);
            $lateMinutes = 0;
            if ($updates['check_in_time']) {
                $lateMinutes = $this->lateMinutes($date, $updates['check_in_time'], $workStartTime, $lateGraceMinutes);
            }
            $updates['late_minutes'] = $lateMinutes > 0 ? $lateMinutes : 0;
            $updates['status'] = $lateMinutes > 0 ? 'late' : 'present';
        } else {
            $updates['check_in_time'] = null;
            $updates['check_out_time'] = null;
            $updates['late_minutes'] = null;
            $updates['overtime_minutes'] = null;
        }

        $updates['work_hours'] = null;
        if ($updates['check_in_time'] && $updates['check_out_time']) {
            $in = Carbon::parse($date.' '.$updates['check_in_time']);
            $out = Carbon::parse($date.' '.$updates['check_out_time']);
            $updates['work_hours'] = $in->diffInMinutes($out) / 60;
        }

        if (! in_array($status, ['present', 'late'], true)) {
            if ($attendance->check_in_selfie_path) {
                Storage::disk('public')->delete($attendance->check_in_selfie_path);
            }
            if ($attendance->check_out_selfie_path) {
                Storage::disk('public')->delete($attendance->check_out_selfie_path);
            }
            $updates['check_in_selfie_path'] = null;
            $updates['check_out_selfie_path'] = null;
        } else {
            $context = [
                'actor_user_id' => Auth::id(),
                'target_user_id' => $attendance->user_id,
                'attendance_id' => $attendance->id,
                'date' => $date,
            ];

            try {
                DB::beginTransaction();

                if ($request->hasFile('selfie_in')) {
                    if ($attendance->check_in_selfie_path) {
                        Storage::disk('public')->delete($attendance->check_in_selfie_path);
                    }
                    $path = $this->storeSelfieSecurely($request->file('selfie_in'), $date, 'update_in', $context);
                    if (! $path) {
                        DB::rollBack();
                        return back()->with('error', 'Gagal menyimpan foto masuk. Coba lagi.');
                    }
                    $updates['check_in_selfie_path'] = $path;
                }
                if ($request->hasFile('selfie_out')) {
                    if ($attendance->check_out_selfie_path) {
                        Storage::disk('public')->delete($attendance->check_out_selfie_path);
                    }
                    $path = $this->storeSelfieSecurely($request->file('selfie_out'), $date, 'update_out', $context);
                    if (! $path) {
                        DB::rollBack();
                        return back()->with('error', 'Gagal menyimpan foto pulang. Coba lagi.');
                    }
                    $updates['check_out_selfie_path'] = $path;
                }
                if (! $updates['check_out_time']) {
                    if ($attendance->check_out_selfie_path) {
                        Storage::disk('public')->delete($attendance->check_out_selfie_path);
                    }
                    $updates['check_out_selfie_path'] = null;
                }

                $attendance->update($updates);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Attendance update failed', ['error' => $e->getMessage(), 'context' => $context]);
                return back()->with('error', 'Gagal memperbarui absensi: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Absensi berhasil diperbarui.');
        }

        $attendance->update($updates);

        return redirect()->back()->with('success', 'Absensi berhasil diperbarui.');
    }

    public function selfie(Attendance $attendance, string $type)
    {
        $type = strtolower(trim($type));
        if (! in_array($type, ['in', 'out'], true)) {
            abort(404);
        }

        $currentUser = Auth::user();
        if (! $currentUser) {
            abort(401, 'Authentication required.');
        }

        $role = strtolower((string) $currentUser->role);

        // Check if user can view this selfie
        // 1. Supervisor can view all selfies
        // 2. Admin3/Admin4 can view selfies of users in their warehouse
        // 3. Users can only view their own selfies
        $canView = false;

        if ($role === 'supervisor' || $role === 'admin' || $role === 'owner') {
            $canView = true;
        } elseif ($attendance->user_id === $currentUser->id) {
            $canView = true;
        } elseif (in_array($role, ['admin3', 'admin4'], true) && $attendance->user_id !== null) {
            // Check if the attendance user belongs to same warehouse
            $attendanceUser = User::find($attendance->user_id);
            if ($attendanceUser?->employee?->warehouse_id === $currentUser->employee?->warehouse_id) {
                $canView = true;
            }
        }

        if (! $canView) {
            abort(403, 'Anda tidak memiliki akses untuk melihat foto ini.');
        }

        $path = $type === 'in' ? $attendance->check_in_selfie_path : $attendance->check_out_selfie_path;
        if (! $path) {
            abort(404);
        }
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($path), [
            'Cache-Control' => 'private, max-age=604800',
        ]);
    }

    /**
     * Check if user is a sales role (sales_minyak, sales_mineral, etc.)
     */
    private function isSalesRole(?User $user): bool
    {
        $role = strtolower((string) ($user?->role ?? ''));
        return str_starts_with($role, 'sales_') || $role === 'sales';
    }

    /**
     * Halaman absen mandiri untuk karyawan (check-in / check-out dengan selfie)
     */
    public function selfPanel(Request $request)
    {
        $user = Auth::user();
        $isSales = $this->isSalesRole($user);
        if (! $isSales && (! $user?->employee || ! $user->employee->active)) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terdaftar sebagai karyawan aktif.');
        }
        $date = Carbon::parse($request->input('date', now()->toDateString()))->toDateString();
        $attendance = Attendance::where('user_id', $user->id)->where('date', $date)->first();

        $setting = StoreSetting::current();
        $workStartTime = $setting->sdm_work_start_time ?? '08:00';
        $workEndTime = $setting->sdm_work_end_time ?? '17:00';
        $lateGraceMinutes = (int) ($setting->sdm_late_grace_minutes ?? 10);
        $calendarMode = (string) ($setting->sdm_calendar_mode ?? 'auto');
        $mode = (string) ($setting->sdm_working_days_mode ?? 'mon_sat');

        $isWorking = $this->isWorkingDate($date, $calendarMode, $mode);
        $canCheckIn = $isWorking && (! $attendance || ! $attendance->check_in_time);
        $canCheckOut = $isWorking && $attendance && $attendance->check_in_time && ! $attendance->check_out_time;

        $opnameInfo = null;
        $opnameRequiredForCheckout = false;
        $role = strtolower((string) ($user->role ?? ''));
        if (in_array($role, ['admin3', 'admin4'], true) && Schema::hasTable('stock_opname_sessions')) {
            $warehouseId = $role === 'admin4' ? 2 : 1;
            $status = 'missing';

            $approved = StockOpnameSession::query()
                ->where('warehouse_id', $warehouseId)
                ->where('status', 'approved')
                ->whereDate('approved_at', $date)
                ->exists();
            if ($approved) {
                $status = 'approved';
            } else {
                $submitted = StockOpnameSession::query()
                    ->where('warehouse_id', $warehouseId)
                    ->whereIn('status', ['submitted', 'approved'])
                    ->whereDate('submitted_at', $date)
                    ->exists();
                if ($submitted) {
                    $status = 'submitted';
                }
            }

            $opnameInfo = [
                'warehouse_id' => $warehouseId,
                'status' => $status,
            ];

            if ($user->can('create_opname_stok') && $status === 'missing') {
                $opnameRequiredForCheckout = true;
            }
        }

        return view('sdm.absensi.self', compact('user', 'date', 'attendance', 'canCheckIn', 'canCheckOut', 'workStartTime', 'workEndTime', 'lateGraceMinutes', 'opnameInfo', 'opnameRequiredForCheckout', 'isWorking', 'calendarMode', 'mode'));
    }

    /**
     * Simpan absen mandiri (selfie wajib)
     */
    public function selfStore(Request $request)
    {
        $user = Auth::user();
        $isSales = $this->isSalesRole($user);
        if (! $isSales && (! $user?->employee || ! $user->employee->active)) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terdaftar sebagai karyawan aktif.');
        }
        $action = strtolower((string) $request->input('action'));
        if (! in_array($action, ['in', 'out'], true)) {
            return back()->with('error', 'Aksi absen tidak dikenal.');
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'selfie' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'selfie_data' => 'nullable|string',
        ]);
        $validator->after(function ($v) use ($request) {
            if (! $request->hasFile('selfie') && ! $request->filled('selfie_data')) {
                $v->errors()->add('selfie', 'Selfie wajib diunggah atau diambil dari kamera.');

                return;
            }
            if ($request->filled('selfie_data')) {
                $data = (string) $request->input('selfie_data');
                if (! preg_match('/^data:image\/(png|jpeg);base64,/', $data)) {
                    $v->errors()->add('selfie_data', 'Format selfie tidak valid.');

                    return;
                }
                $raw = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $data);
                if (! is_string($raw) || $raw === '') {
                    $v->errors()->add('selfie_data', 'Format selfie tidak valid.');

                    return;
                }
                $bytes = base64_decode($raw, true);
                if ($bytes === false) {
                    $v->errors()->add('selfie_data', 'Format selfie tidak valid.');

                    return;
                }
                if (strlen($bytes) > 4 * 1024 * 1024) {
                    $v->errors()->add('selfie_data', 'Ukuran selfie terlalu besar (maks 4MB).');
                }
            }
        });
        $validated = $validator->validate();

        $date = Carbon::parse($validated['date'])->toDateString();
        $setting = StoreSetting::current();
        $workStartTime = $setting->sdm_work_start_time ?? '08:00';
        $lateGraceMinutes = (int) ($setting->sdm_late_grace_minutes ?? 10);

        $attendance = Attendance::where('user_id', $user->id)->where('date', $date)->first();
        $fingerprintId = (string) ($user->fingerprint_id ?: ('self_'.$user->id));

        if (! $attendance) {
            if ($action === 'out') {
                return back()->with('error', 'Anda belum absen masuk.');
            }
            $attendance = Attendance::create([
                'user_id' => $user->id,
                'fingerprint_id' => $fingerprintId,
                'date' => $date,
                'status' => 'present',
                'check_in_time' => null,
                'check_out_time' => null,
                'late_minutes' => null,
                'overtime_minutes' => null,
                'work_hours' => null,
            ]);
        }

        $selfiePath = null;
        $context = [
            'actor_user_id' => $user->id,
            'target_user_id' => $user->id,
            'attendance_id' => $attendance?->id,
            'date' => $date,
            'action' => $action,
        ];
        if ($request->hasFile('selfie')) {
            $path = $this->storeSelfieSecurely($request->file('selfie'), $date, 'self_'.$action, $context);
            if (! $path) {
                return back()->with('error', 'Gagal menyimpan foto. Coba lagi.');
            }
            $selfiePath = $path;
        } else {
            $data = (string) $request->input('selfie_data', '');
            preg_match('/^data:image\/(png|jpeg);base64,/', $data, $m);
            $ext = ($m[1] ?? 'jpeg') === 'png' ? 'png' : 'jpg';
            $raw = preg_replace('/^data:image\/(png|jpeg);base64,/', '', $data);
            $bytes = is_string($raw) ? base64_decode($raw, true) : false;
            if ($bytes === false) {
                return back()->with('error', 'Selfie tidak valid.');
            }
            $filename = $action.'_'.$user->id.'_'.now()->format('His').'_'.bin2hex(random_bytes(4)).'.'.$ext;
            $selfiePath = "attendance-selfies/{$date}/{$filename}";
            if (! $this->tryPutSelfieBytes($selfiePath, $bytes, $context)) {
                return back()->with('error', 'Gagal menyimpan foto. Coba lagi.');
            }
        }

        if ($action === 'in') {
            if ($attendance->check_in_time) {
                return back()->with('error', 'Anda sudah absen masuk.');
            }
            $nowTime = now()->format('H:i');
            $attendance->check_in_time = $nowTime;
            $attendance->check_in_selfie_path = $selfiePath;
            $lateMinutes = $this->lateMinutes($date, $nowTime, $workStartTime, $lateGraceMinutes);
            $attendance->late_minutes = $lateMinutes > 0 ? $lateMinutes : 0;
            $attendance->status = $lateMinutes > 0 ? 'late' : 'present';
        } else {
            if (! $attendance->check_in_time) {
                return back()->with('error', 'Anda belum absen masuk.');
            }
            if ($attendance->check_out_time) {
                return back()->with('error', 'Anda sudah absen pulang.');
            }

            if (! $this->hasSubmittedOpnameForDate($user, $date)) {
                Log::warning('opname_required_for_checkout', [
                    'user_id' => $user->id,
                    'role' => strtolower((string) ($user->role ?? '')),
                    'date' => $date,
                ]);

                return back()->with('error', 'Anda wajib menyelesaikan Opname Stok sebelum absen pulang.');
            }

            $nowTime = now()->format('H:i');
            $attendance->check_out_time = $nowTime;
            $attendance->check_out_selfie_path = $selfiePath;

            $in = Carbon::parse($date.' '.$attendance->check_in_time);
            $out = Carbon::parse($date.' '.$attendance->check_out_time);
            $attendance->work_hours = $in->diffInMinutes($out) / 60;
        }

        $attendance->save();

        return redirect()->route('sdm.absensi.self_panel', ['date' => $date])->with('success', 'Absen berhasil disimpan.');
    }

    private function hasSubmittedOpnameForDate(?User $user, string $date): bool
    {
        if (! $user) {
            return true;
        }
        $role = strtolower((string) ($user->role ?? ''));
        if (! in_array($role, ['admin3', 'admin4'], true)) {
            return true;
        }
        if (! $user->can('create_opname_stok')) {
            return true;
        }
        if (! Schema::hasTable('stock_opname_sessions')) {
            return true;
        }

        $warehouseId = WarehouseConfig::getIdByRole($role) ?? ($role === 'admin4' ? WarehouseConfig::getBranchId() : WarehouseConfig::getMainId());

        return StockOpnameSession::query()
            ->where('warehouse_id', $warehouseId)
            ->whereIn('status', ['submitted', 'approved'])
            ->where(function ($q) use ($date) {
                $q->whereDate('submitted_at', $date)
                    ->orWhereDate('approved_at', $date)
                    ->orWhereDate('created_at', $date);
            })
            ->exists();
    }

    private function lateMinutes(string $date, string $checkInTime, string $workStartTime, int $graceMinutes): int
    {
        $checkIn = Carbon::parse($date.' '.$checkInTime);
        $threshold = Carbon::parse($date.' '.$workStartTime)->addMinutes($graceMinutes);
        if ($checkIn->lte($threshold)) {
            return 0;
        }

        return $threshold->diffInMinutes($checkIn);
    }

    public function generateAbsent(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($validated['date'])->toDateString();
        $setting = StoreSetting::current();
        $mode = (string) ($setting->sdm_working_days_mode ?? 'mon_sat');
        $calendarMode = (string) ($setting->sdm_calendar_mode ?? 'auto');

        if (! $this->isWorkingDate($date, $calendarMode, $mode)) {
            return redirect()->back()->with('error', 'Tanggal ini bukan hari kerja (sesuai aturan dan kalender libur).');
        }

        $employees = User::query()
            ->whereHas('employee', fn ($q) => $q->where('active', true))
            ->select(['id', 'fingerprint_id'])
            ->get();

        $existingByUser = Attendance::query()
            ->where('date', $date)
            ->whereNotNull('user_id')
            ->pluck('id', 'user_id')
            ->toArray();

        $existingByFingerprint = Attendance::query()
            ->where('date', $date)
            ->pluck('id', 'fingerprint_id')
            ->toArray();

        $created = 0;

        foreach ($employees as $user) {
            if (isset($existingByUser[$user->id])) {
                continue;
            }

            $userFingerprintId = $user->fingerprint_id ? (string) $user->fingerprint_id : null;
            if ($userFingerprintId && isset($existingByFingerprint[$userFingerprintId])) {
                continue;
            }

            $fingerprintId = $userFingerprintId ?: ('manual_'.$user->id);
            if (isset($existingByFingerprint[$fingerprintId])) {
                $fingerprintId = 'absent_'.$user->id;
            }

            Attendance::create([
                'user_id' => $user->id,
                'fingerprint_id' => $fingerprintId,
                'date' => $date,
                'check_in_time' => null,
                'check_out_time' => null,
                'status' => 'absent',
                'late_minutes' => null,
                'overtime_minutes' => null,
                'work_hours' => null,
            ]);

            $created++;
        }

        return redirect()->route('sdm.absensi.index', ['date' => $date])->with('success', 'Generate alpha selesai. Ditambahkan: '.$created.' record.');
    }

    private function isWorkingDate(string $date, string $calendarMode, string $mode): bool
    {
        $holiday = SdmHoliday::query()->whereDate('date', $date)->first();
        if ($holiday) {
            return (bool) $holiday->is_working_day;
        }

        if ($calendarMode === 'manual') {
            return false;
        }

        $c = Carbon::parse($date);
        $dow = (int) $c->dayOfWeek;

        return $mode === 'mon_fri'
            ? ($dow >= \Carbon\CarbonInterface::MONDAY && $dow <= \Carbon\CarbonInterface::FRIDAY)
            : ($dow >= \Carbon\CarbonInterface::MONDAY && $dow <= \Carbon\CarbonInterface::SATURDAY);
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
                ? ($dow >= \Carbon\CarbonInterface::MONDAY && $dow <= \Carbon\CarbonInterface::FRIDAY)
                : ($dow >= \Carbon\CarbonInterface::MONDAY && $dow <= \Carbon\CarbonInterface::SATURDAY);

            if ($isWorkingDay && ! isset($holidaySet[$dateStr])) {
                $dates[] = $dateStr;
            }
            $cursor->addDay();
        }

        return $dates;
    }

    /**
     * Tarik data absensi dari mesin ZKTeco secara manual (SPV only).
     */
    public function sync()
    {
        $role = strtolower((string) (Auth::user()?->role ?? ''));
        if ($role !== 'supervisor') {
            return back()->with('error', 'Hanya Supervisor yang bisa sinkronisasi mesin fingerprint.');
        }

        $setting = StoreSetting::current();
        $ip = $setting->fingerprint_ip;
        $port = (int) ($setting->fingerprint_port ?: 4370);

        if (!$ip) {
            return back()->with('error', 'Alamat IP mesin fingerprint belum diatur. Isi di Pengaturan SDM.');
        }

        try {
            $zk = new ZKTeco($ip, $port);
            $connected = $zk->connect();

            if (!$connected) {
                return back()->with('error', "Gagal terhubung ke mesin fingerprint di {$ip}:{$port}. Pastikan mesin menyala dan terhubung jaringan.");
            }

            $attendanceLogs = $zk->getAttendance();
            $zk->disconnect();

            if (empty($attendanceLogs)) {
                return back()->with('success', "Terhubung ke mesin {$ip}:{$port} — tidak ada data absensi baru.");
            }

            $workStartTime = $setting->sdm_work_start_time ?? '08:00';
            $lateGraceMinutes = (int) ($setting->sdm_late_grace_minutes ?? 10);
            $created = 0;
            $updated = 0;

            foreach ($attendanceLogs as $log) {
                $uid = (string) ($log['uid'] ?? $log['id'] ?? '0');
                if ($uid === '0') continue;

                // Format timestamp dari mesin: "2025-01-15 08:30:00"
                $timestamp = $log['timestamp'] ?? $log['time'] ?? null;
                if (!$timestamp) continue;

                try {
                    $scanTime = Carbon::parse($timestamp);
                } catch (\Exception $e) {
                    continue;
                }

                $date = $scanTime->toDateString();
                $time = $scanTime->toTimeString();

                $user = User::where('fingerprint_id', $uid)->first();
                $userId = $user ? $user->id : null;

                $attendance = Attendance::where('fingerprint_id', $uid)
                    ->where('date', $date)
                    ->first();

                if (!$attendance) {
                    $lateMinutes = $this->lateMinutes($date, $time, $workStartTime, $lateGraceMinutes);
                    Attendance::create([
                        'user_id' => $userId,
                        'fingerprint_id' => $uid,
                        'date' => $date,
                        'check_in_time' => $time,
                        'check_out_time' => null,
                        'status' => $lateMinutes > 0 ? 'late' : 'present',
                        'late_minutes' => $lateMinutes > 0 ? $lateMinutes : 0,
                    ]);
                    $created++;
                } else {
                    if ($attendance->user_id === null && $userId !== null) {
                        $attendance->update(['user_id' => $userId]);
                    }
                    if (!$attendance->check_out_time || $time > $attendance->check_out_time) {
                        if ($time > ($attendance->check_in_time ?? '00:00')) {
                            $attendance->update(['check_out_time' => $time]);
                            $updated++;
                        }
                    }
                }
            }

            $msg = "Sync selesai: {$created} data baru, {$updated} check-out diperbarui dari mesin {$ip}:{$port}.";
            return back()->with('success', $msg);
        } catch (\Throwable $e) {
            Log::error('ZKTeco sync failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal sinkronisasi: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\Controller;
use App\Models\SdmHoliday;
use App\Models\StoreSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = now()->format('Y-m');
        }
        [$year, $m] = explode('-', $month);

        $start = Carbon::createFromDate((int) $year, (int) $m, 1)->toDateString();
        $end = Carbon::createFromDate((int) $year, (int) $m, 1)->endOfMonth()->toDateString();

        $holidays = SdmHoliday::query()
            ->whereBetween('date', [$start, $end])
            ->orderBy('date')
            ->get();

        $setting = StoreSetting::current();
        $calendarMode = (string) ($setting->sdm_calendar_mode ?? 'auto');

        $holidayByDate = $holidays->keyBy(fn ($h) => $h->date->format('Y-m-d'));

        $calendar = [];
        $cursor = Carbon::parse($start);
        $endC = Carbon::parse($end);
        while ($cursor->lte($endC)) {
            $dateStr = $cursor->toDateString();
            $row = $holidayByDate->get($dateStr);
            $calendar[] = [
                'date' => $dateStr,
                'row' => $row,
                'dow' => $cursor->translatedFormat('D'),
            ];
            $cursor->addDay();
        }

        return view('sdm.libur.index', compact('holidays', 'calendar', 'month', 'calendarMode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:sdm_holidays,date',
            'name' => 'nullable|string|max:120',
            'is_working_day' => 'nullable|in:1',
            'notes' => 'nullable|string|max:500',
        ]);

        SdmHoliday::create([
            'date' => Carbon::parse($validated['date'])->toDateString(),
            'name' => $validated['name'] ?? null,
            'is_working_day' => ($validated['is_working_day'] ?? null) === '1',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Tanggal libur berhasil disimpan.');
    }

    public function update(Request $request, SdmHoliday $libur)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:120',
            'is_working_day' => 'nullable|in:0,1',
            'notes' => 'nullable|string|max:500',
        ]);

        $libur->update([
            'name' => $validated['name'] ?? $libur->name,
            'is_working_day' => ($validated['is_working_day'] ?? null) === '1',
            'notes' => $validated['notes'] ?? $libur->notes,
        ]);

        return redirect()->back()->with('success', 'Kalender kerja berhasil diperbarui.');
    }

    public function generateMonth(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|date_format:Y-m',
            'overwrite' => 'nullable|in:1',
        ]);

        $setting = StoreSetting::current();
        $mode = (string) ($setting->sdm_working_days_mode ?? 'mon_sat');
        [$year, $m] = explode('-', $validated['month']);
        $start = Carbon::createFromDate((int) $year, (int) $m, 1)->startOfDay();
        $end = $start->copy()->endOfMonth()->startOfDay();

        $overwrite = ($validated['overwrite'] ?? null) === '1';

        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $dow = (int) $cursor->dayOfWeek;
            $isWorkingDay = $mode === 'mon_fri'
                ? ($dow >= Carbon::MONDAY && $dow <= Carbon::FRIDAY)
                : ($dow >= Carbon::MONDAY && $dow <= Carbon::SATURDAY);

            $dateStr = $cursor->toDateString();
            $existing = SdmHoliday::query()->whereDate('date', $dateStr)->first();
            if ($existing && ! $overwrite) {
                $cursor->addDay();

                continue;
            }

            SdmHoliday::updateOrCreate(
                ['date' => $dateStr],
                [
                    'name' => $existing?->name,
                    'is_working_day' => $isWorkingDay,
                    'notes' => $existing?->notes,
                ]
            );

            $cursor->addDay();
        }

        return redirect()->back()->with('success', 'Kalender bulan ini berhasil di-generate.');
    }

    public function destroy(SdmHoliday $libur)
    {
        $libur->delete();

        return redirect()->back()->with('success', 'Tanggal libur berhasil dihapus.');
    }
}

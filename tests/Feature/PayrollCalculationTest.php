<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\SdmBonus;
use App\Models\SdmDeduction;
use App\Models\SdmEmployee;
use App\Models\SdmHoliday;
use App\Models\SdmLeaveRequest;
use App\Models\SdmPayroll;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayrollCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_payroll_calculates_components_from_attendance_deductions_and_bonuses(): void
    {
        StoreSetting::query()->create([
            'store_name' => 'Test Store',
            'timezone' => 'Asia/Jakarta',
            'sdm_calendar_mode' => 'manual',
            'sdm_working_days_mode' => 'mon_fri',
            'sdm_overtime_rate_per_hour' => 10000,
            'sdm_late_meal_cut_mode' => 'fixed',
            'sdm_late_meal_cut_value' => 5000,
        ]);

        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
            'fingerprint_id' => 'fp-super',
        ]);

        $employeeUser = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
            'fingerprint_id' => 'fp-emp-1',
        ]);

        SdmEmployee::query()->create([
            'name' => $employeeUser->name,
            'user_id' => $employeeUser->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 20000,
        ]);

        $month = '2026-03';
        $dates = [
            '2026-03-03',
            '2026-03-04',
            '2026-03-05',
            '2026-03-06',
            '2026-03-07',
        ];

        foreach ($dates as $d) {
            SdmHoliday::query()->create([
                'date' => $d,
                'name' => 'WD',
                'is_working_day' => true,
            ]);
        }

        Attendance::query()->create([
            'user_id' => $employeeUser->id,
            'fingerprint_id' => $employeeUser->fingerprint_id,
            'date' => '2026-03-03',
            'status' => 'present',
            'late_minutes' => 0,
            'overtime_minutes' => 0,
        ]);
        Attendance::query()->create([
            'user_id' => $employeeUser->id,
            'fingerprint_id' => $employeeUser->fingerprint_id,
            'date' => '2026-03-04',
            'status' => 'late',
            'late_minutes' => 15,
            'overtime_minutes' => 120,
        ]);
        Attendance::query()->create([
            'user_id' => $employeeUser->id,
            'fingerprint_id' => $employeeUser->fingerprint_id,
            'date' => '2026-03-05',
            'status' => 'absent',
        ]);

        SdmLeaveRequest::query()->create([
            'user_id' => $employeeUser->id,
            'start_date' => '2026-03-06',
            'end_date' => '2026-03-06',
            'type' => 'izin',
            'paid' => false,
            'status' => 'approved',
            'approved_by' => $supervisor->id,
        ]);

        SdmDeduction::query()->create([
            'user_id' => $employeeUser->id,
            'date' => '2026-03-10',
            'description' => 'Denda',
            'amount' => 10000,
        ]);
        SdmBonus::query()->create([
            'user_id' => $employeeUser->id,
            'date' => '2026-03-11',
            'description' => 'Bonus Target',
            'amount' => 20000,
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.generate'), ['month' => $month])
            ->assertRedirect();

        $payroll = SdmPayroll::query()
            ->where('user_id', $employeeUser->id)
            ->where('period_year', '2026')
            ->where('period_month', '03')
            ->first();

        $this->assertNotNull($payroll);

        $workingDays = 5;
        $presentDays = 1;
        $lateDays = 1;
        $totalAttendance = 2;
        $mealGross = $totalAttendance * 20000;
        $latePenalty = $lateDays * 5000;
        $totalAllowance = max($mealGross - $latePenalty, 0);
        $dailyBasic = 1000000 / $workingDays;
        $absentDays = 1;
        $missingDays = 1;
        $unpaidLeaveDays = 1;
        $absenceDeduction = ($absentDays + $missingDays + $unpaidLeaveDays) * $dailyBasic;
        $overtimePay = (120 / 60) * 10000;
        $incentiveAmount = 20000;
        $totalDeductions = 10000;
        $expectedNet = (1000000 + $totalAllowance + $overtimePay + $incentiveAmount) - ($totalDeductions + $absenceDeduction);
        if ($expectedNet < 0) {
            $expectedNet = 0;
        }

        $this->assertSame($workingDays, (int) $payroll->working_days);
        $this->assertSame($presentDays, (int) $payroll->present_days);
        $this->assertSame($lateDays, (int) $payroll->late_days);
        $this->assertSame($totalAttendance, (int) $payroll->total_attendance);
        $this->assertEqualsWithDelta($latePenalty, (float) $payroll->late_meal_penalty, 0.01);
        $this->assertEqualsWithDelta($totalAllowance, (float) $payroll->total_allowance, 0.01);
        $this->assertEqualsWithDelta($absenceDeduction, (float) $payroll->absence_deduction, 0.01);
        $this->assertEqualsWithDelta($overtimePay, (float) $payroll->overtime_pay, 0.01);
        $this->assertEqualsWithDelta($incentiveAmount, (float) $payroll->incentive_amount, 0.01);
        $this->assertEqualsWithDelta($totalDeductions, (float) $payroll->total_deductions, 0.01);
        $this->assertEqualsWithDelta($expectedNet, (float) $payroll->net_salary, 0.01);
    }

    public function test_print_slip_hides_insentif_when_matches_bonus_breakdown(): void
    {
        StoreSetting::current();

        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        $employeeUser = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        SdmEmployee::query()->create([
            'name' => $employeeUser->name,
            'user_id' => $employeeUser->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 20000,
        ]);

        $payroll = SdmPayroll::query()->create([
            'user_id' => $employeeUser->id,
            'period_year' => '2026',
            'period_month' => '03',
            'working_days' => 0,
            'present_days' => 0,
            'late_days' => 0,
            'izin_days' => 0,
            'sakit_days' => 0,
            'absent_days' => 0,
            'missing_days' => 0,
            'unpaid_leave_days' => 0,
            'total_attendance' => 0,
            'total_basic_salary' => 0,
            'total_allowance' => 0,
            'meal_allowance_per_day' => 0,
            'meal_allowance_gross' => 0,
            'late_meal_penalty' => 0,
            'overtime_minutes' => 0,
            'overtime_pay' => 0,
            'incentive_amount' => 30000,
            'performance_bonus' => 0,
            'total_deductions' => 0,
            'absence_deduction' => 0,
            'net_salary' => 30000,
        ]);

        SdmBonus::query()->create([
            'user_id' => $employeeUser->id,
            'date' => '2026-03-11',
            'description' => 'Bonus A',
            'amount' => 10000,
        ]);
        SdmBonus::query()->create([
            'user_id' => $employeeUser->id,
            'date' => '2026-03-12',
            'description' => 'Bonus B',
            'amount' => 20000,
        ]);

        $this->actingAs($supervisor)
            ->get(route('sdm.penggajian.print', $payroll))
            ->assertOk()
            ->assertSee('Bonus A (Bonus)')
            ->assertSee('Bonus B (Bonus)')
            ->assertDontSee('Insentif</td>');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\SdmEmployee;
use App\Models\SdmHoliday;
use App\Models\SdmPayroll;
use App\Models\StoreSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealAllowanceAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_meal_allowance_is_paid_only_on_active_working_days_manual_calendar(): void
    {
        StoreSetting::query()->create([
            'store_name' => 'Test Store',
            'timezone' => 'Asia/Jakarta',
            'sdm_calendar_mode' => 'manual',
            'sdm_working_days_mode' => 'mon_fri',
            'sdm_late_meal_cut_mode' => 'none',
            'sdm_late_meal_cut_value' => 0,
            'sdm_overtime_rate_per_hour' => 0,
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
            'fingerprint_id' => 'fp-emp',
        ]);

        SdmEmployee::query()->create([
            'name' => $employeeUser->name,
            'user_id' => $employeeUser->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 10000,
        ]);

        SdmHoliday::query()->create([
            'date' => '2026-03-03',
            'name' => 'WD',
            'is_working_day' => true,
        ]);

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
            'status' => 'present',
            'late_minutes' => 0,
            'overtime_minutes' => 0,
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.generate'), ['month' => '2026-03'])
            ->assertRedirect();

        $payroll = SdmPayroll::query()
            ->where('user_id', $employeeUser->id)
            ->where('period_year', '2026')
            ->where('period_month', '03')
            ->first();

        $this->assertNotNull($payroll);
        $this->assertSame(1, (int) $payroll->working_days);
        $this->assertSame(1, (int) $payroll->total_attendance);
        $this->assertEqualsWithDelta(10000, (float) $payroll->meal_allowance_gross, 0.01);
        $this->assertEqualsWithDelta(0, (float) $payroll->late_meal_penalty, 0.01);
        $this->assertEqualsWithDelta(10000, (float) $payroll->total_allowance, 0.01);
    }

    public function test_meal_allowance_is_not_paid_on_holiday_in_auto_calendar_mode(): void
    {
        StoreSetting::query()->create([
            'store_name' => 'Test Store',
            'timezone' => 'Asia/Jakarta',
            'sdm_calendar_mode' => 'auto',
            'sdm_working_days_mode' => 'mon_fri',
            'sdm_late_meal_cut_mode' => 'none',
            'sdm_late_meal_cut_value' => 0,
            'sdm_overtime_rate_per_hour' => 0,
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
            'fingerprint_id' => 'fp-emp',
        ]);

        SdmEmployee::query()->create([
            'name' => $employeeUser->name,
            'user_id' => $employeeUser->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 10000,
        ]);

        SdmHoliday::query()->create([
            'date' => '2026-03-04',
            'name' => 'Libur',
            'is_working_day' => false,
        ]);

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
            'status' => 'present',
            'late_minutes' => 0,
            'overtime_minutes' => 0,
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.generate'), ['month' => '2026-03'])
            ->assertRedirect();

        $payroll = SdmPayroll::query()
            ->where('user_id', $employeeUser->id)
            ->where('period_year', '2026')
            ->where('period_month', '03')
            ->first();

        $this->assertNotNull($payroll);
        $this->assertSame(1, (int) $payroll->total_attendance);
        $this->assertEqualsWithDelta(10000, (float) $payroll->meal_allowance_gross, 0.01);
        $this->assertEqualsWithDelta(10000, (float) $payroll->total_allowance, 0.01);
    }

    public function test_meal_allowance_is_not_paid_when_employee_does_not_come(): void
    {
        StoreSetting::query()->create([
            'store_name' => 'Test Store',
            'timezone' => 'Asia/Jakarta',
            'sdm_calendar_mode' => 'manual',
            'sdm_working_days_mode' => 'mon_fri',
            'sdm_late_meal_cut_mode' => 'none',
            'sdm_late_meal_cut_value' => 0,
            'sdm_overtime_rate_per_hour' => 0,
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
            'fingerprint_id' => 'fp-emp',
        ]);

        SdmEmployee::query()->create([
            'name' => $employeeUser->name,
            'user_id' => $employeeUser->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 10000,
        ]);

        SdmHoliday::query()->create([
            'date' => '2026-03-03',
            'name' => 'WD',
            'is_working_day' => true,
        ]);

        Attendance::query()->create([
            'user_id' => $employeeUser->id,
            'fingerprint_id' => $employeeUser->fingerprint_id,
            'date' => '2026-03-03',
            'status' => 'absent',
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.generate'), ['month' => '2026-03'])
            ->assertRedirect();

        $payroll = SdmPayroll::query()
            ->where('user_id', $employeeUser->id)
            ->where('period_year', '2026')
            ->where('period_month', '03')
            ->first();

        $this->assertNotNull($payroll);
        $this->assertSame(0, (int) $payroll->total_attendance);
        $this->assertEqualsWithDelta(0, (float) $payroll->meal_allowance_gross, 0.01);
        $this->assertEqualsWithDelta(0, (float) $payroll->late_meal_penalty, 0.01);
        $this->assertEqualsWithDelta(0, (float) $payroll->total_allowance, 0.01);
    }

    public function test_meal_allowance_is_cut_when_employee_is_late_percent_mode(): void
    {
        StoreSetting::query()->create([
            'store_name' => 'Test Store',
            'timezone' => 'Asia/Jakarta',
            'sdm_calendar_mode' => 'manual',
            'sdm_working_days_mode' => 'mon_fri',
            'sdm_late_meal_cut_mode' => 'percent',
            'sdm_late_meal_cut_value' => 50,
            'sdm_overtime_rate_per_hour' => 0,
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
            'fingerprint_id' => 'fp-emp',
        ]);

        SdmEmployee::query()->create([
            'name' => $employeeUser->name,
            'user_id' => $employeeUser->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 20000,
        ]);

        SdmHoliday::query()->create([
            'date' => '2026-03-03',
            'name' => 'WD',
            'is_working_day' => true,
        ]);
        SdmHoliday::query()->create([
            'date' => '2026-03-04',
            'name' => 'WD',
            'is_working_day' => true,
        ]);

        Attendance::query()->create([
            'user_id' => $employeeUser->id,
            'fingerprint_id' => $employeeUser->fingerprint_id,
            'date' => '2026-03-03',
            'status' => 'late',
            'late_minutes' => 20,
            'overtime_minutes' => 0,
        ]);
        Attendance::query()->create([
            'user_id' => $employeeUser->id,
            'fingerprint_id' => $employeeUser->fingerprint_id,
            'date' => '2026-03-04',
            'status' => 'late',
            'late_minutes' => 5,
            'overtime_minutes' => 0,
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.generate'), ['month' => '2026-03'])
            ->assertRedirect();

        $payroll = SdmPayroll::query()
            ->where('user_id', $employeeUser->id)
            ->where('period_year', '2026')
            ->where('period_month', '03')
            ->first();

        $this->assertNotNull($payroll);
        $this->assertSame(2, (int) $payroll->total_attendance);

        $mealGross = 2 * 20000;
        $expectedPenalty = 2 * (20000 * 0.5);
        $expectedNetAllowance = $mealGross - $expectedPenalty;

        $this->assertEqualsWithDelta($mealGross, (float) $payroll->meal_allowance_gross, 0.01);
        $this->assertEqualsWithDelta($expectedPenalty, (float) $payroll->late_meal_penalty, 0.01);
        $this->assertEqualsWithDelta($expectedNetAllowance, (float) $payroll->total_allowance, 0.01);
    }
}

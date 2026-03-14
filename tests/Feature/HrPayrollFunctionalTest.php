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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HrPayrollFunctionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_karyawan_create_update_and_export_csv(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.karyawan.store', absolute: false), [
                'name' => 'Budi',
                'phone' => '081234',
                'position' => 'Staff',
                'join_date' => '2026-03-01',
                'active' => '1',
                'basic_salary' => 1500000,
                'daily_allowance' => 20000,
            ])
            ->assertRedirect(route('sdm.karyawan.index', absolute: false));

        $karyawan = SdmEmployee::query()->first();
        $this->assertNotNull($karyawan);
        $this->assertSame('Budi', $karyawan->name);
        $this->assertEqualsWithDelta(20000, (float) $karyawan->daily_allowance, 0.01);

        $this->actingAs($supervisor)
            ->put(route('sdm.karyawan.update', $karyawan, absolute: false), [
                'name' => 'Budi Update',
                'phone' => '0812345',
                'position' => 'Supervisor',
                'join_date' => '2026-03-02',
                'active' => '1',
                'basic_salary' => 2000000,
                'daily_allowance' => 25000,
            ])
            ->assertRedirect(route('sdm.karyawan.index', absolute: false));

        $karyawan->refresh();
        $this->assertSame('Budi Update', $karyawan->name);
        $this->assertEqualsWithDelta(25000, (float) $karyawan->daily_allowance, 0.01);

        $this->actingAs($supervisor)
            ->get(route('sdm.karyawan.export', absolute: false))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_cuti_crud_and_approval_rules(): void
    {
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
            'daily_allowance' => 10000,
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.cuti.store', absolute: false), [
                'user_id' => $employeeUser->id,
                'type' => 'izin',
                'start_date' => '2026-03-10',
                'end_date' => '2026-03-10',
                'paid' => '1',
                'notes' => 'Test',
            ])
            ->assertRedirect();

        $cuti = SdmLeaveRequest::query()->first();
        $this->assertNotNull($cuti);
        $this->assertSame('pending', $cuti->status);

        $this->actingAs($supervisor)
            ->post(route('sdm.cuti.approve', $cuti, absolute: false))
            ->assertRedirect();
        $cuti->refresh();
        $this->assertSame('approved', $cuti->status);

        $this->actingAs($supervisor)
            ->delete(route('sdm.cuti.destroy', $cuti, absolute: false))
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertDatabaseHas('sdm_leave_requests', ['id' => $cuti->id]);

        $user = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        SdmEmployee::query()->create([
            'name' => $user->name,
            'user_id' => $user->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 10000,
        ]);
        $selfCuti = SdmLeaveRequest::query()->create([
            'user_id' => $user->id,
            'type' => 'cuti',
            'start_date' => '2026-03-11',
            'end_date' => '2026-03-11',
            'paid' => false,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->delete(route('sdm.cuti.self_destroy', $selfCuti, absolute: false))
            ->assertRedirect();
        $this->assertDatabaseMissing('sdm_leave_requests', ['id' => $selfCuti->id]);
    }

    public function test_libur_store_update_generate_and_delete(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        StoreSetting::current();

        $this->actingAs($supervisor)
            ->post(route('sdm.libur.store', absolute: false), [
                'date' => '2026-03-12',
                'name' => 'Libur nasional',
                'notes' => 'Test',
            ])
            ->assertRedirect();

        $libur = SdmHoliday::query()->first();
        $this->assertNotNull($libur);

        $this->actingAs($supervisor)
            ->patch(route('sdm.libur.update', $libur, absolute: false), [
                'name' => 'Update',
                'is_working_day' => '1',
                'notes' => 'Update',
            ])
            ->assertRedirect();
        $libur->refresh();
        $this->assertTrue((bool) $libur->is_working_day);

        $this->actingAs($supervisor)
            ->post(route('sdm.libur.generate', absolute: false), [
                'month' => '2026-03',
                'overwrite' => '1',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->actingAs($supervisor)
            ->delete(route('sdm.libur.destroy', $libur, absolute: false))
            ->assertRedirect();
        $this->assertDatabaseMissing('sdm_holidays', ['id' => $libur->id]);
    }

    public function test_potongan_bonus_crud(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        $user = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        SdmEmployee::query()->create([
            'name' => $user->name,
            'user_id' => $user->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 10000,
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.potongan.store', absolute: false), [
                'user_id' => $user->id,
                'date' => '2026-03-05',
                'type' => 'bonus',
                'description' => 'Bonus',
                'amount' => 10000,
            ])
            ->assertRedirect(route('sdm.potongan.index', absolute: false));

        $this->actingAs($supervisor)
            ->post(route('sdm.potongan.store', absolute: false), [
                'user_id' => $user->id,
                'date' => '2026-03-06',
                'type' => 'potongan',
                'description' => 'Potongan',
                'amount' => 5000,
            ])
            ->assertRedirect(route('sdm.potongan.index', absolute: false));

        $bonus = SdmBonus::query()->first();
        $deduction = SdmDeduction::query()->first();
        $this->assertNotNull($bonus);
        $this->assertNotNull($deduction);

        $this->actingAs($supervisor)
            ->delete(route('sdm.bonus.destroy', $bonus, absolute: false))
            ->assertRedirect(route('sdm.potongan.index', absolute: false));
        $this->assertDatabaseMissing('sdm_bonuses', ['id' => $bonus->id]);

        $this->actingAs($supervisor)
            ->delete(route('sdm.potongan.destroy', $deduction, absolute: false))
            ->assertRedirect(route('sdm.potongan.index', absolute: false));
        $this->assertDatabaseMissing('sdm_deductions', ['id' => $deduction->id]);
    }

    public function test_absensi_manual_validation_and_exports(): void
    {
        Storage::fake('public');
        $pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==', true);
        $this->assertNotFalse($pngBytes);

        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        $user = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
            'fingerprint_id' => 'fp-emp',
        ]);
        SdmEmployee::query()->create([
            'name' => $user->name,
            'user_id' => $user->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 10000,
        ]);

        StoreSetting::query()->create([
            'store_name' => 'Test Store',
            'timezone' => 'Asia/Jakarta',
            'sdm_work_start_time' => '08:00',
            'sdm_late_grace_minutes' => 10,
            'sdm_working_days_mode' => 'mon_fri',
            'sdm_calendar_mode' => 'auto',
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.absensi.manual.store', absolute: false), [
                'user_id' => $user->id,
                'date' => '2026-03-03',
                'status' => 'present',
                'check_in_time' => '08:00',
            ])
            ->assertRedirect()
            ->assertSessionHasErrors('selfie_in');

        $this->actingAs($supervisor)
            ->post(route('sdm.absensi.manual.store', absolute: false), [
                'user_id' => $user->id,
                'date' => '2026-03-03',
                'status' => 'present',
                'check_in_time' => '08:00',
                'selfie_in' => UploadedFile::fake()->createWithContent('in.png', $pngBytes),
            ])
            ->assertRedirect(route('sdm.absensi.index', ['date' => '2026-03-03'], absolute: false));

        $attendance = Attendance::query()->where('user_id', $user->id)->where('date', '2026-03-03')->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->check_in_selfie_path);
        Storage::disk('public')->assertExists($attendance->check_in_selfie_path);

        $this->actingAs($supervisor)
            ->patch(route('sdm.absensi.update', $attendance, absolute: false), [
                'status' => 'absent',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $attendance->refresh();
        $this->assertNull($attendance->check_in_selfie_path);

        $this->actingAs($supervisor)
            ->get(route('sdm.absensi.monthly.export', ['month' => '2026-03'], absolute: false))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_penggajian_generate_print_lock_adjust_and_destroy(): void
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
        ]);
        $user = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
            'fingerprint_id' => 'fp-emp',
        ]);
        SdmEmployee::query()->create([
            'name' => $user->name,
            'user_id' => $user->id,
            'active' => true,
            'basic_salary' => 1000000,
            'daily_allowance' => 20000,
        ]);

        SdmHoliday::query()->create([
            'date' => '2026-03-03',
            'name' => 'WD',
            'is_working_day' => true,
        ]);
        Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => $user->fingerprint_id,
            'date' => '2026-03-03',
            'status' => 'late',
            'late_minutes' => 15,
            'overtime_minutes' => 60,
        ]);
        SdmDeduction::query()->create([
            'user_id' => $user->id,
            'date' => '2026-03-03',
            'description' => 'Potongan',
            'amount' => 10000,
        ]);
        SdmBonus::query()->create([
            'user_id' => $user->id,
            'date' => '2026-03-03',
            'description' => 'Bonus',
            'amount' => 20000,
        ]);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.generate', absolute: false), ['month' => '2026-03'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $payroll = SdmPayroll::query()->where('user_id', $user->id)->first();
        $this->assertNotNull($payroll);

        $this->actingAs($supervisor)
            ->get(route('sdm.penggajian.print', $payroll, absolute: false))
            ->assertOk()
            ->assertSee('SLIP GAJI', false);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.lock', $payroll, absolute: false))
            ->assertRedirect();
        $payroll->refresh();
        $this->assertNotNull($payroll->locked_at);

        $this->actingAs($supervisor)
            ->patch(route('sdm.penggajian.adjust', $payroll, absolute: false), [
                'incentive_amount' => 1,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->actingAs($supervisor)
            ->delete(route('sdm.penggajian.destroy', $payroll, absolute: false))
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertDatabaseHas('sdm_payrolls', ['id' => $payroll->id]);

        $this->actingAs($supervisor)
            ->post(route('sdm.penggajian.unlock', $payroll, absolute: false))
            ->assertRedirect();

        $this->actingAs($supervisor)
            ->patch(route('sdm.penggajian.adjust', $payroll, absolute: false), [
                'incentive_amount' => 50000,
                'performance_bonus' => 25000,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->actingAs($supervisor)
            ->delete(route('sdm.penggajian.destroy', $payroll, absolute: false))
            ->assertRedirect()
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('sdm_payrolls', ['id' => $payroll->id]);
    }

    public function test_performa_index_loads(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($supervisor)
            ->get(route('sdm.performa.index', ['month' => now()->format('Y-m')], absolute: false))
            ->assertOk();
    }
}

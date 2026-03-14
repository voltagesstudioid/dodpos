<?php

namespace Tests\Feature;

use App\Models\SdmBonus;
use App\Models\SdmDeduction;
use App\Models\SdmEmployee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HrPayrollMenuAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_sidebar_contains_hr_payroll_links(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $resp = $this->actingAs($supervisor)->get(route('dashboard', absolute: false));
        $resp->assertOk();

        $resp->assertSee('🧑‍🤝‍🧑 HR &amp; Payroll', false);
        $resp->assertSee(route('sdm.karyawan.index', absolute: false), false);
        $resp->assertSee(route('sdm.absensi.index', absolute: false), false);
        $resp->assertSee(route('sdm.cuti.index', absolute: false), false);
        $resp->assertSee(route('sdm.potongan.index', absolute: false), false);
        $resp->assertSee(route('sdm.penggajian.index', absolute: false), false);
        $resp->assertSee(route('sdm.performa.index', absolute: false), false);
    }

    public function test_supervisor_can_open_all_hr_payroll_pages(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($supervisor)->get(route('sdm.karyawan.index', absolute: false))->assertOk();
        $this->actingAs($supervisor)->get(route('sdm.absensi.index', absolute: false))->assertOk();
        $this->actingAs($supervisor)->get(route('sdm.absensi.monthly', absolute: false))->assertOk();
        $this->actingAs($supervisor)->get(route('sdm.cuti.index', absolute: false))->assertOk();
        $this->actingAs($supervisor)->get(route('sdm.libur.index', absolute: false))->assertOk();
        $this->actingAs($supervisor)->get(route('sdm.potongan.index', absolute: false))->assertOk();
        $this->actingAs($supervisor)->get(route('sdm.penggajian.index', absolute: false))->assertOk();
        $this->actingAs($supervisor)->get(route('sdm.performa.index', absolute: false))->assertOk();
    }

    public function test_non_supervisor_cannot_open_hr_payroll_pages_but_can_open_self_service_pages(): void
    {
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

        $this->actingAs($user)->get(route('sdm.karyawan.index', absolute: false))->assertForbidden();
        $this->actingAs($user)->get(route('sdm.absensi.index', absolute: false))->assertForbidden();
        $this->actingAs($user)->get(route('sdm.absensi.monthly', absolute: false))->assertForbidden();
        $this->actingAs($user)->get(route('sdm.cuti.index', absolute: false))->assertForbidden();
        $this->actingAs($user)->get(route('sdm.libur.index', absolute: false))->assertForbidden();
        $this->actingAs($user)->get(route('sdm.potongan.index', absolute: false))->assertForbidden();
        $this->actingAs($user)->get(route('sdm.penggajian.index', absolute: false))->assertForbidden();
        $this->actingAs($user)->get(route('sdm.performa.index', absolute: false))->assertForbidden();

        $this->actingAs($user)->get(route('sdm.absensi.self_panel', absolute: false))->assertOk();
        $this->actingAs($user)->get(route('sdm.cuti.self_index', absolute: false))->assertOk();
        $this->actingAs($user)->get(route('sdm.penggajian.self_index', absolute: false))->assertOk();
        $this->actingAs($user)->get(route('sdm.potongan.self_index', absolute: false))->assertOk();
    }

    public function test_supervisor_can_delete_bonus_and_deduction_from_potongan_module(): void
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

        $bonus = SdmBonus::query()->create([
            'user_id' => $user->id,
            'date' => '2026-03-01',
            'description' => 'Bonus Test',
            'amount' => 10000,
        ]);
        $deduction = SdmDeduction::query()->create([
            'user_id' => $user->id,
            'date' => '2026-03-01',
            'description' => 'Potongan Test',
            'amount' => 5000,
        ]);

        $this->actingAs($supervisor)
            ->delete(route('sdm.bonus.destroy', $bonus, absolute: false))
            ->assertRedirect(route('sdm.potongan.index', absolute: false));
        $this->assertDatabaseMissing('sdm_bonuses', ['id' => $bonus->id]);

        $this->actingAs($supervisor)
            ->delete(route('sdm.potongan.destroy', $deduction, absolute: false))
            ->assertRedirect(route('sdm.potongan.index', absolute: false));
        $this->assertDatabaseMissing('sdm_deductions', ['id' => $deduction->id]);
    }
}

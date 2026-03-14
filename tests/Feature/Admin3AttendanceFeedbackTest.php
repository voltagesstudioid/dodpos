<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\SdmEmployee;
use App\Models\StockOpnameSession;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Admin3AttendanceFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin3_checkout_requires_opname_and_returns_error_feedback(): void
    {
        $user = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        SdmEmployee::query()->create([
            'name' => $user->name,
            'user_id' => $user->id,
            'active' => true,
            'basic_salary' => 0,
            'daily_allowance' => 0,
        ]);

        $date = now()->toDateString();

        $attendance = Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => 'self_'.$user->id,
            'date' => $date,
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => null,
        ]);

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store', absolute: false), [
                'action' => 'out',
                'date' => $date,
                'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Anda wajib menyelesaikan Opname Stok sebelum absen pulang.');

        $attendance->refresh();
        $this->assertNull($attendance->check_out_time);
    }

    public function test_admin3_checkout_succeeds_after_opname_submitted(): void
    {
        $user = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        SdmEmployee::query()->create([
            'name' => $user->name,
            'user_id' => $user->id,
            'active' => true,
            'basic_salary' => 0,
            'daily_allowance' => 0,
        ]);

        $date = now()->toDateString();

        Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => 'self_'.$user->id,
            'date' => $date,
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => null,
        ]);

        Warehouse::query()->create([
            'id' => 1,
            'name' => 'Gudang Utama',
            'active' => true,
        ]);

        StockOpnameSession::query()->create([
            'warehouse_id' => 1,
            'created_by' => $user->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store', absolute: false), [
                'action' => 'out',
                'date' => $date,
                'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
            ])
            ->assertRedirect(route('sdm.absensi.self_panel', ['date' => $date], absolute: false))
            ->assertSessionHas('success', 'Absen berhasil disimpan.');

        $attendance = Attendance::query()->where('user_id', $user->id)->where('date', $date)->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->check_out_time);
    }

    public function test_admin3_out_without_checkin_returns_checkin_error(): void
    {
        $user = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);
        SdmEmployee::query()->create([
            'name' => $user->name,
            'user_id' => $user->id,
            'active' => true,
            'basic_salary' => 0,
            'daily_allowance' => 0,
        ]);

        $date = now()->toDateString();

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store', absolute: false), [
                'action' => 'out',
                'date' => $date,
                'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Anda belum absen masuk.');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\SdmEmployee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Admin12AttendanceSelfStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin1_can_checkin_with_selfie_data_and_gets_success_feedback(): void
    {
        $user = User::factory()->create([
            'role' => 'admin1',
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
                'action' => 'in',
                'date' => $date,
                'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
            ])
            ->assertRedirect(route('sdm.absensi.self_panel', ['date' => $date], absolute: false))
            ->assertSessionHas('success', 'Absen berhasil disimpan.');

        $attendance = Attendance::query()->where('user_id', $user->id)->where('date', $date)->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->check_in_time);
        $this->assertNotNull($attendance->check_in_selfie_path);
    }

    public function test_admin2_can_checkin_with_selfie_data_and_gets_success_feedback(): void
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
            'basic_salary' => 0,
            'daily_allowance' => 0,
        ]);

        $date = now()->toDateString();

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store', absolute: false), [
                'action' => 'in',
                'date' => $date,
                'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
            ])
            ->assertRedirect(route('sdm.absensi.self_panel', ['date' => $date], absolute: false))
            ->assertSessionHas('success', 'Absen berhasil disimpan.');

        $attendance = Attendance::query()->where('user_id', $user->id)->where('date', $date)->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->check_in_time);
        $this->assertNotNull($attendance->check_in_selfie_path);
    }
}

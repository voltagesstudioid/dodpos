<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\SdmEmployee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceSelfieRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_upload_selfie_in_manual_attendance_and_render_it(): void
    {
        Storage::fake('public');

        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);
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

        $date = '2026-03-14';
        $pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==', true);
        $this->assertNotFalse($pngBytes);

        $this->actingAs($supervisor)
            ->post(route('sdm.absensi.manual.store', absolute: false), [
                'user_id' => $user->id,
                'date' => $date,
                'status' => 'present',
                'check_in_time' => '08:00',
                'selfie_in' => UploadedFile::fake()->createWithContent('in.png', $pngBytes),
            ])
            ->assertRedirect(route('sdm.absensi.index', ['date' => $date], absolute: false))
            ->assertSessionHas('success');

        $attendance = Attendance::query()->where('user_id', $user->id)->where('date', $date)->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->check_in_selfie_path);
        Storage::disk('public')->assertExists($attendance->check_in_selfie_path);

        $this->actingAs($supervisor)
            ->get(route('sdm.absensi.selfie', ['attendance' => $attendance->id, 'type' => 'in'], absolute: false))
            ->assertOk();
    }
}

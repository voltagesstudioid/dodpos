<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AttendanceSelfieTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_attendance_requires_selfie_in_for_present_with_check_in_time(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        $employee = User::factory()->create([
            'role' => 'kasir',
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        $resp = $this->actingAs($supervisor)->post(route('sdm.absensi.manual.store', absolute: false), [
            'user_id' => $employee->id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => '',
            'overtime_minutes' => 0,
        ]);

        $resp->assertSessionHasErrors(['selfie_in']);
    }

    public function test_manual_attendance_stores_selfie_in(): void
    {
        Storage::fake('public');

        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        $employee = User::factory()->create([
            'role' => 'kasir',
            'active' => true,
            'password' => Hash::make('password'),
        ]);

        $date = now()->toDateString();

        $resp = $this->actingAs($supervisor)->post(route('sdm.absensi.manual.store', absolute: false), [
            'user_id' => $employee->id,
            'date' => $date,
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => '',
            'overtime_minutes' => 0,
            'selfie_in' => UploadedFile::fake()->create('selfie.jpg', 120, 'image/jpeg'),
        ]);

        $resp->assertRedirect();

        $att = \App\Models\Attendance::query()->where('user_id', $employee->id)->where('date', $date)->firstOrFail();
        $this->assertNotNull($att->check_in_selfie_path);
        $this->assertTrue(Storage::disk('public')->exists($att->check_in_selfie_path));
    }
}

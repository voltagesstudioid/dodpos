<?php

namespace Tests\Feature;

use App\Models\SdmEmployee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SelfAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_check_in_with_selfie(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'kasir',
            'active' => true,
            'password' => Hash::make('password'),
        ]);
        SdmEmployee::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'position' => 'Kasir',
            'join_date' => now()->subMonth()->toDateString(),
            'active' => true,
            'basic_salary' => 0,
            'daily_allowance' => 0,
        ]);

        $date = now()->toDateString();

        $resp = $this->actingAs($user)->post(route('sdm.absensi.self_store', absolute: false), [
            'date' => $date,
            'action' => 'in',
            'selfie' => UploadedFile::fake()->create('self.jpg', 120, 'image/jpeg'),
        ]);

        $resp->assertRedirect();

        $att = \App\Models\Attendance::query()
            ->where('user_id', $user->id)
            ->where('date', $date)
            ->firstOrFail();

        $this->assertNotNull($att->check_in_time);
        $this->assertNotNull($att->check_in_selfie_path);
        $this->assertTrue(Storage::disk('public')->exists($att->check_in_selfie_path));
    }
}

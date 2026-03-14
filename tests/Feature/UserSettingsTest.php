<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_updating_user_without_email_change_keeps_email_verified_at(): void
    {
        $this->withoutMiddleware();

        $verifiedAt = now()->subDays(7);
        $user = User::factory()->create([
            'role' => 'admin1',
            'email_verified_at' => $verifiedAt,
            'active' => true,
        ]);

        $actor = User::factory()->create(['role' => 'admin2', 'active' => true]);

        $resp = $this->actingAs($actor)->put(route('pengguna.update', $user, absolute: false), [
            'name' => 'Nama Baru',
            'nik' => '3210123456789012',
            'email' => $user->email,
            'role' => 'admin2',
            'fingerprint_id' => null,
            'active' => '1',
        ]);
        $resp->assertRedirect();

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertSame($verifiedAt->timestamp, $user->email_verified_at->timestamp);
    }

    public function test_updating_user_with_email_change_nulls_email_verified_at(): void
    {
        $this->withoutMiddleware();

        $verifiedAt = now()->subDays(7);
        $user = User::factory()->create([
            'role' => 'admin1',
            'email_verified_at' => $verifiedAt,
            'active' => true,
        ]);

        $actor = User::factory()->create(['role' => 'admin1', 'active' => true]);

        $resp = $this->actingAs($actor)->put(route('pengguna.update', $user, absolute: false), [
            'name' => 'Nama Baru',
            'nik' => '3210123456789013',
            'email' => 'changed@example.com',
            'role' => 'admin1',
            'fingerprint_id' => null,
            'active' => '1',
        ]);
        $resp->assertRedirect();

        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileRequirementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_update_requires_nik_and_password(): void
    {
        $user = User::factory()->create([
            'role' => 'admin1',
            'active' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $resp = $this->actingAs($user)->patch(route('profile.update', absolute: false), [
            'name' => 'Nama Baru',
            'email' => 'new@example.com',
            'nik' => '',
            'password' => '',
        ]);

        $resp->assertSessionHasErrors(['nik', 'password']);
    }

    public function test_profile_update_succeeds_with_required_fields(): void
    {
        $user = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $resp = $this->actingAs($user)->patch(route('profile.update', absolute: false), [
            'name' => 'Nama Baru',
            'email' => 'new2@example.com',
            'nik' => '3210123456789012',
            'password' => 'passwordBaru123',
        ]);

        $resp->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('new2@example.com', $user->email);
        $this->assertSame('3210123456789012', $user->nik);
    }
}

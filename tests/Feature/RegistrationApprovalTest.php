<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_creates_inactive_pending_user(): void
    {
        Mail::fake();

        $resp = $this->post(route('register', absolute: false), [
            'name' => 'User Baru',
            'email' => 'baru@example.com',
            'password' => 'password-12345',
            'password_confirmation' => 'password-12345',
            'requested_role' => 'admin1',
        ]);

        $resp->assertRedirect(route('login', absolute: false));

        $u = User::query()->where('email', 'baru@example.com')->firstOrFail();
        $this->assertFalse((bool) $u->active);
        $this->assertSame('pending', $u->role);
        $this->assertSame('admin1', $u->requested_role);

        Mail::assertNothingSent();
    }

    public function test_supervisor_can_approve_pending_user(): void
    {
        $this->withoutMiddleware();
        Mail::fake();

        $supervisor = User::factory()->create(['role' => 'supervisor', 'active' => true]);

        $pending = User::factory()->create([
            'role' => 'pending',
            'requested_role' => 'admin1',
            'active' => false,
        ]);

        $resp = $this->actingAs($supervisor)->post(route('pengguna.approve', $pending, absolute: false));
        $resp->assertRedirect();

        $pending->refresh();
        $this->assertTrue((bool) $pending->active);
        $this->assertSame('admin1', $pending->role);
    }

    public function test_supervisor_can_reject_pending_user(): void
    {
        $this->withoutMiddleware();
        Mail::fake();

        $supervisor = User::factory()->create(['role' => 'supervisor', 'active' => true]);

        $pending = User::factory()->create([
            'role' => 'pending',
            'requested_role' => 'admin1',
            'active' => false,
        ]);

        $resp = $this->actingAs($supervisor)->post(route('pengguna.reject', $pending, absolute: false));
        $resp->assertRedirect();

        $pending->refresh();
        $this->assertFalse((bool) $pending->active);
        $this->assertNotNull($pending->rejected_at);
    }

    public function test_rejected_user_can_re_register_with_same_email(): void
    {
        Mail::fake();

        $rejected = User::factory()->create([
            'name' => 'User Lama',
            'email' => 'rejected@example.com',
            'role' => 'pending',
            'requested_role' => 'admin1',
            'active' => false,
            'approved_at' => null,
            'rejected_at' => now(),
        ]);

        $resp = $this->post(route('register', absolute: false), [
            'name' => 'User Baru',
            'email' => 'rejected@example.com',
            'password' => 'password-12345',
            'password_confirmation' => 'password-12345',
            'requested_role' => 'admin1',
        ]);

        $resp->assertRedirect(route('login', absolute: false));

        $newUser = User::query()->where('email', 'rejected@example.com')->firstOrFail();
        $this->assertSame('User Baru', $newUser->name);
        $this->assertFalse((bool) $newUser->active);
        $this->assertSame('pending', $newUser->role);
        $this->assertNull($newUser->rejected_at);
    }
}

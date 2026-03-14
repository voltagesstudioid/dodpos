<?php

namespace Tests\Feature;

use App\Models\AppRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppRoleMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_supervisor_can_migrate_role_for_users(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor', 'active' => true]);

        AppRole::create(['key' => 'old_role', 'label' => 'Old Role', 'active' => true]);
        AppRole::create(['key' => 'new_role', 'label' => 'New Role', 'active' => true]);

        $u1 = User::factory()->create(['role' => 'old_role', 'active' => true]);
        $u2 = User::factory()->create(['role' => 'old_role', 'active' => true, 'requested_role' => 'old_role']);

        $resp = $this->actingAs($supervisor)->post(route('pengaturan.roles.migrate.store', absolute: false), [
            'from' => 'old_role',
            'to' => 'new_role',
            'include_requested_role' => '1',
        ]);
        $resp->assertRedirect(route('pengaturan.roles.migrate', absolute: false));

        $u1->refresh();
        $u2->refresh();
        $this->assertSame('new_role', $u1->role);
        $this->assertSame('new_role', $u2->role);
        $this->assertSame('new_role', $u2->requested_role);
    }

    public function test_migrate_rejects_supervisor_source_role(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor', 'active' => true]);

        $resp = $this->actingAs($supervisor)->post(route('pengaturan.roles.migrate.store', absolute: false), [
            'from' => 'supervisor',
            'to' => 'kasir',
        ]);

        $resp->assertRedirect();
        $resp->assertSessionHas('error');
    }
}

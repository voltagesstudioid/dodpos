<?php

namespace Tests\Feature;

use App\Models\AppRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppRoleCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_supervisor_cannot_access_roles_master(): void
    {
        $user = User::factory()->create(['role' => 'kasir', 'active' => true]);

        $resp = $this->actingAs($user)->get(route('pengaturan.roles.index', absolute: false));
        $resp->assertStatus(403);
    }

    public function test_supervisor_can_crud_roles(): void
    {
        $supervisor = User::factory()->create(['role' => 'supervisor', 'active' => true]);

        $resp = $this->actingAs($supervisor)->get(route('pengaturan.roles.index', absolute: false));
        $resp->assertOk();

        $resp = $this->actingAs($supervisor)->post(route('pengaturan.roles.store', absolute: false), [
            'key' => 'custom_role',
            'label' => 'Custom Role',
            'description' => 'Role tambahan',
            'active' => '1',
        ]);
        $resp->assertRedirect(route('pengaturan.roles.index', absolute: false));

        $role = AppRole::where('key', 'custom_role')->firstOrFail();
        $this->assertSame('Custom Role', $role->label);

        $resp = $this->actingAs($supervisor)->put(route('pengaturan.roles.update', $role, absolute: false), [
            'key' => 'custom_role',
            'label' => 'Custom Role Updated',
            'description' => null,
            'active' => '0',
        ]);
        $resp->assertRedirect(route('pengaturan.roles.index', absolute: false));

        $role->refresh();
        $this->assertSame('Custom Role Updated', $role->label);
        $this->assertFalse((bool) $role->active);

        $resp = $this->actingAs($supervisor)->delete(route('pengaturan.roles.destroy', $role, absolute: false));
        $resp->assertRedirect(route('pengaturan.roles.index', absolute: false));

        $this->assertDatabaseMissing('app_roles', ['key' => 'custom_role']);
    }
}

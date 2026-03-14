<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\SdmEmployee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_state_changing_requests_create_activity_log_entries_with_expected_fields(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $this->assertSame(0, Activity::query()->count());

        $customer = Customer::query()->create([
            'name' => 'PT Test',
            'phone' => '08123',
            'address' => 'Alamat',
            'category' => 'general',
        ]);

        $this->actingAs($supervisor)
            ->put(route('pelanggan.update', $customer, absolute: false), [
                'name' => 'PT Test Update',
                'phone' => '08123',
                'address' => 'Alamat 2',
            ])
            ->assertRedirect();

        $log = Activity::query()->latest()->first();
        $this->assertNotNull($log);
        $this->assertSame($supervisor->id, $log->causer_id);
        $this->assertNotNull($log->created_at);
        $this->assertNotNull($log->event);

        $this->assertNotEmpty($log->description);

        $props = $log->properties?->toArray() ?? [];
        $this->assertArrayHasKey('request', $props);
        $this->assertArrayHasKey('response', $props);
        $this->assertSame('PUT', $props['request']['method'] ?? null);
        $this->assertIsInt($props['response']['status_code'] ?? null);
        $this->assertIsInt($props['response']['duration_ms'] ?? null);

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

        $this->actingAs($supervisor)
            ->post(route('sdm.potongan.store', absolute: false), [
                'user_id' => $user->id,
                'date' => '2026-03-14',
                'type' => 'potongan',
                'description' => 'Test',
                'amount' => 1000,
            ])
            ->assertRedirect();

        $this->assertGreaterThanOrEqual(2, Activity::query()->count());
    }

    public function test_activity_log_page_renders_performed_event_filter(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        activity('web')
            ->causedBy($supervisor)
            ->event('performed')
            ->withProperties(['request' => ['route' => 'test.route'], 'response' => ['status_code' => 200]])
            ->log('test.route');

        $this->actingAs($supervisor)
            ->get(route('activity-log.index', ['event' => 'performed'], absolute: false))
            ->assertOk()
            ->assertSee('Performed (Aksi)');
    }
}

<?php

namespace Tests\Feature;

use App\Models\PosCashMovement;
use App\Models\PosSession;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosSessionCashTest extends TestCase
{
    use RefreshDatabase;

    public function test_close_session_stores_expected_actual_and_variance(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create(['role' => 'supervisor']);
        $customer = \App\Models\Customer::create(['name' => 'Pelanggan A']);

        $session = PosSession::create([
            'user_id' => $user->id,
            'opening_amount' => 100000,
            'payment_method' => 'cash',
            'status' => 'open',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'customer_id' => null,
            'total_amount' => 50000,
            'paid_amount' => 50000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
            'created_at' => now()->addMinute(),
            'updated_at' => now()->addMinute(),
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'total_amount' => 100000,
            'paid_amount' => 20000,
            'change_amount' => 0,
            'payment_method' => 'kredit',
            'status' => 'completed',
            'created_at' => now()->addMinutes(2),
            'updated_at' => now()->addMinutes(2),
        ]);

        PosCashMovement::create([
            'pos_session_id' => $session->id,
            'type' => 'out',
            'amount' => 10000,
            'notes' => 'belanja',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(route('kasir.close_session', absolute: false), [
            'actual_cash' => 155000,
        ]);

        $response->assertRedirect();

        $session->refresh();
        $this->assertSame('closed', $session->status);
        $this->assertSame(160000.0, (float) $session->expected_cash);
        $this->assertSame(155000.0, (float) $session->actual_cash);
        $this->assertSame(-5000.0, (float) $session->cash_variance);
        $this->assertSame(155000.0, (float) $session->closing_amount);
    }
}

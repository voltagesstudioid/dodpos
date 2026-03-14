<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Admin1MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin1_sees_expected_sidebar_menus_on_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'admin1',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $resp = $this->actingAs($user)->get(route('dashboard', absolute: false));
        $resp->assertOk();

        $resp->assertSee('Dashboard Kasir Utama');

        $resp->assertSee('Sales Order');

        $resp->assertDontSee('🏪 POINT OF SALE');
        $resp->assertSee('Transaksi');
        $resp->assertSee('Pelanggan');
        $resp->assertSee('Daftar Harga');

        $resp->assertDontSee('📈 LAPORAN');
        $resp->assertSee('Lap. Penjualan');
        $resp->assertSee('Lap. Pelanggan');

        $resp->assertDontSee('⚙️ OPERASIONAL');
        $resp->assertSee('Absen Saya');
        $resp->assertSee('Cuti Saya');
        $resp->assertDontSee('PENGATURAN');
        $resp->assertDontSee('Master Roles');
    }
}

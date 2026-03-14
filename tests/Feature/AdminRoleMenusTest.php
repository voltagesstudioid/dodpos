<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleMenusTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin2_menu(): void
    {
        $user = User::factory()->create([
            'role' => 'admin2',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $resp = $this->actingAs($user)->get(route('dashboard', absolute: false));
        $resp->assertOk();
        $resp->assertSee('Dashboard Kasir & Operasional', false);

        $resp->assertSee('Absen Saya');
        $resp->assertSee('Cuti Saya');
        $resp->assertDontSee('🧑‍🤝‍🧑 SDM / HR');

        $resp->assertSee('Sales Order');

        $resp->assertDontSee('🏢 MANAJEMEN GUDANG');
        $resp->assertDontSee('📦 MASTER DATA');
        $resp->assertDontSee('🦅 PASUKAN GARUDA');
        $resp->assertDontSee('🛠️ PENGATURAN');
        $resp->assertDontSee('Master Roles');
    }

    public function test_admin3_menu(): void
    {
        $user = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $resp = $this->actingAs($user)->get(route('dashboard', absolute: false));
        $resp->assertOk();
        $resp->assertSee('Dashboard Gudang Masuk');

        $resp->assertDontSee('🏢 MANAJEMEN GUDANG');
        $resp->assertSee('Data Stok Gudang');
        $resp->assertSee('Terima dari PO');
        $resp->assertSee('Masuk & Keluar');
        $resp->assertDontSee('Pengeluaran Barang');
        $resp->assertDontSee('Transfer Gudang');

        $resp->assertSee('Absen Saya');
        $resp->assertSee('Cuti Saya');
        $resp->assertDontSee('🧑‍🤝‍🧑 SDM / HR');
        $resp->assertDontSee('🏪 POINT OF SALE');
        $resp->assertDontSee('Sales Order');
        $resp->assertDontSee('🛒 PEMBELIAN');
        $resp->assertDontSee('📦 MASTER DATA');
        $resp->assertDontSee('📈 LAPORAN');
        $resp->assertDontSee('🦅 PASUKAN GARUDA');
        $resp->assertDontSee('🚐 MODUL KANVAS');
        $resp->assertDontSee('🍬 MODUL GULA');
        $resp->assertDontSee('💧 MODUL MINERAL');
        $resp->assertDontSee('🛢️ MINYAK');
        $resp->assertDontSee('🛠️ PENGATURAN');
        $resp->assertDontSee('Master Roles');
    }

    public function test_admin4_menu(): void
    {
        $user = User::factory()->create([
            'role' => 'admin4',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $resp = $this->actingAs($user)->get(route('dashboard', absolute: false));
        $resp->assertOk();
        $resp->assertSee('Dashboard Gudang Keluar & Distribusi', false);

        $resp->assertDontSee('Sales Order');
        $resp->assertDontSee('🏢 MANAJEMEN GUDANG');
        $resp->assertDontSee('🛒 PEMBELIAN');
        $resp->assertDontSee('📦 MASTER DATA');
        $resp->assertSee('Terima Transfer Cabang');
        $resp->assertSee('Pengeluaran Penjualan');
        $resp->assertSee('Opname Stok');
        $resp->assertSee('Permintaan Barang');
        $resp->assertSee('Retur Pembelian');
        $resp->assertDontSee('🦅 PASUKAN GARUDA');
        $resp->assertDontSee('🚐 MODUL KANVAS');
        $resp->assertDontSee('🍬 MODUL GULA');
        $resp->assertDontSee('💧 MODUL MINERAL');
        $resp->assertDontSee('🛢️ MINYAK');

        $resp->assertDontSee('⚙️ OPERASIONAL');
        $resp->assertSee('Absen Saya');
        $resp->assertSee('Cuti Saya');
        $resp->assertDontSee('🧑‍🤝‍🧑 SDM / HR');
        $resp->assertDontSee('🏪 POINT OF SALE');
        $resp->assertSee('Lap. Stok');
        $resp->assertDontSee('🛠️ PENGATURAN');
        $resp->assertDontSee('Master Roles');
    }
}

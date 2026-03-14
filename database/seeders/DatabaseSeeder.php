<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@dodpos.com'],
            [
                'name' => 'Supervisor DODPOS',
                'password' => bcrypt('D0dP0s@Admin2024!'), // Password lebih kuat
                'role' => 'supervisor',
                'active' => true,
            ]
        );

        $salesMineral = User::firstOrCreate(
            ['email' => 'sales_mineral@dodpos.com'],
            [
                'name' => 'Prajurit Mineral 1',
                'password' => bcrypt('S@lesMineral2026!'),
                'role' => 'sales_mineral',
                'active' => true,
            ]
        );

        $salesKanvas = User::firstOrCreate(
            ['email' => 'sales_kanvas@dodpos.com'],
            [
                'name' => 'Kapten Kanvas 1',
                'password' => bcrypt('S@lesKanvas2026!'),
                'role' => 'sales_kanvas',
                'active' => true,
            ]
        );

        foreach ([$admin, $salesMineral, $salesKanvas] as $u) {
            if ($u && ! $u->email_verified_at) {
                $u->forceFill(['email_verified_at' => now()])->save();
            }
        }

        $this->call([
            MineralProductSeeder::class,
            KanvasProductSeeder::class,
        ]);
    }
}

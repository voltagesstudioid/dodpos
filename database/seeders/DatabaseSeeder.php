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
                'password' => bcrypt('D0dP0s@Admin2024!'),
                'role' => 'supervisor',
                'active' => true,
            ]
        );

        if ($admin && ! $admin->email_verified_at) {
            $admin->forceFill(['email_verified_at' => now()])->save();
        }
    }
}

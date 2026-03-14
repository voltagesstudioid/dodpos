<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MineralProduct;

class MineralProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Air Mineral Gelas 220ml (Dus)',
                'price_cash' => 25000,
                'price_tempo' => 26000,
                'is_active' => true,
            ],
            [
                'name' => 'Air Mineral Botol 600ml (Dus)',
                'price_cash' => 45000,
                'price_tempo' => 47000,
                'is_active' => true,
            ],
            [
                'name' => 'Air Mineral Botol 1500ml (Dus)',
                'price_cash' => 50000,
                'price_tempo' => 52000,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            MineralProduct::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}

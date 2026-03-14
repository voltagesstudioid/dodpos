<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KanvasProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [];
        $categories = ['Rokok', 'Sembako', 'Snack', 'Minuman', 'Kopi', 'Sabun'];
        $units = ['Dus', 'Bal', 'Renceng', 'Pack'];

        // Generate 50 produk dummy untuk testing performance
        for ($i = 1; $i <= 50; $i++) {
            $cat = $categories[array_rand($categories)];
            $unit = $units[array_rand($units)];
            $basePrice = rand(10, 150) * 1000;

            $products[] = [
                'name' => "Item Kanvas $cat " . str_pad($i, 3, '0', STR_PAD_LEFT),
                'barcode' => '899' . rand(100000000, 999999999),
                'unit' => $unit,
                'price_cash' => $basePrice,
                'price_tempo' => $basePrice + ($basePrice * 0.05), // Tempo lebih mahal 5%
                'qty_per_carton' => rand(10, 50),
                'status' => 'active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('kanvas_products')->insert($products);

        // Setelah produk ada, kita berikan stok awal ke gudang utama kanvas
        $productIds = DB::table('kanvas_products')->pluck('id');
        $stocks = [];
        foreach ($productIds as $pid) {
            $stocks[] = [
                'product_id' => $pid,
                'qty_tersedia' => rand(500, 2000),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        DB::table('kanvas_warehouse_stocks')->insert($stocks);
    }
}

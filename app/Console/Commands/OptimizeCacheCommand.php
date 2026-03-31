<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OptimizeCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize cache by warming up frequently accessed data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cache optimization...');

        // Warm up product cache
        $this->warmUpProductCache();

        // Warm up warehouse cache
        $this->warmUpWarehouseCache();

        // Warm up category cache
        $this->warmUpCategoryCache();

        // Warm up settings cache
        $this->warmUpSettingsCache();

        $this->info('Cache optimization completed!');

        return Command::SUCCESS;
    }

    /**
     * Warm up product cache
     */
    private function warmUpProductCache(): void
    {
        $this->info('Warming up product cache...');

        $products = \App\Models\Product::select('id', 'name', 'sku', 'stock', 'min_stock')
            ->get();

        foreach ($products as $product) {
            Cache::remember("product:{$product->id}", 3600, function () use ($product) {
                return $product;
            });
        }

        $this->info("Cached {$products->count()} products");
    }

    /**
     * Warm up warehouse cache
     */
    private function warmUpWarehouseCache(): void
    {
        $this->info('Warming up warehouse cache...');

        $warehouses = \App\Models\Warehouse::select('id', 'name', 'code')
            ->get();

        Cache::remember('warehouses:all', 3600, function () use ($warehouses) {
            return $warehouses;
        });

        foreach ($warehouses as $warehouse) {
            Cache::remember("warehouse:{$warehouse->id}", 3600, function () use ($warehouse) {
                return $warehouse;
            });
        }

        $this->info("Cached {$warehouses->count()} warehouses");
    }

    /**
     * Warm up category cache
     */
    private function warmUpCategoryCache(): void
    {
        $this->info('Warming up category cache...');

        $categories = \App\Models\Category::select('id', 'name')
            ->get();

        Cache::remember('categories:all', 3600, function () use ($categories) {
            return $categories;
        });

        $this->info("Cached {$categories->count()} categories");
    }

    /**
     * Warm up settings cache
     */
    private function warmUpSettingsCache(): void
    {
        $this->info('Warming up settings cache...');

        $settings = \App\Models\StoreSetting::first();

        if ($settings) {
            Cache::remember('store:settings', 3600, function () use ($settings) {
                return $settings;
            });
        }

        $this->info('Cached store settings');
    }
}

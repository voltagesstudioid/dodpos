<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderProductSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_requires_minimum_two_characters(): void
    {
        $this->withoutMiddleware();

        $resp = $this->get(route('pembelian.order.products.search', ['q' => 'a'], absolute: false));
        $resp->assertOk();
        $this->assertSame([], $resp->json());
    }

    public function test_search_can_find_product_by_sku_and_returns_conversions(): void
    {
        $this->withoutMiddleware();

        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);

        $product = Product::create([
            'name' => 'Produk Test',
            'sku' => 'SKU-TEST-PO',
            'barcode' => '1234567890',
            'price' => 10000,
            'purchase_price' => 8000,
            'stock' => 10,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'active' => true,
        ]);

        $resp = $this->get(route('pembelian.order.products.search', ['q' => 'SKU-TEST'], absolute: false));
        $resp->assertOk();
        $resp->assertJsonFragment(['id' => $product->id]);
        $resp->assertJsonStructure([
            ['id', 'name', 'sku', 'purchase_price', 'unit_id', 'unit_name', 'conversions'],
        ]);
    }

    public function test_search_can_fetch_product_by_id(): void
    {
        $this->withoutMiddleware();

        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);

        $product = Product::create([
            'name' => 'Produk A',
            'sku' => 'SKU-A',
            'barcode' => '999',
            'price' => 10000,
            'purchase_price' => 8000,
            'stock' => 10,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'active' => true,
        ]);

        $resp = $this->get(route('pembelian.order.products.search', ['id' => $product->id], absolute: false));
        $resp->assertOk();
        $resp->assertJsonFragment(['id' => $product->id]);
    }
}

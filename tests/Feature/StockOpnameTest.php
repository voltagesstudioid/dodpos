<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockOpnameTest extends TestCase
{
    use RefreshDatabase;

    public function test_opname_adds_stock_when_actual_is_greater_than_system(): void
    {
        $admin3 = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $category = Category::create(['name' => 'Test']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);
        $warehouse = Warehouse::create(['name' => 'Gudang A', 'code' => 'WHA', 'active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'name' => 'Produk A',
            'description' => null,
            'sku' => 'SKU-OP-1',
            'barcode' => null,
            'price' => 0,
            'purchase_price' => 0,
            'stock' => 5,
            'min_stock' => 0,
            'image' => null,
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 5,
        ]);

        $resp = $this->actingAs($admin3)->post(route('gudang.opname.store', absolute: false), [
            'reference_number' => 'OP-TEST-001',
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'actual_qty' => 8,
            'notes' => 'Hitung ulang',
        ]);

        $resp->assertRedirect(route('gudang.opname', absolute: false));

        $product->refresh();
        $this->assertSame(8, (int) $product->stock);

        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'stock' => 8,
        ]);

        $movement = StockMovement::query()
            ->where('type', 'adjustment')
            ->where('reference_number', 'OP-TEST-001')
            ->firstOrFail();
        $this->assertSame(3, (int) $movement->quantity);
        $this->assertSame($admin3->id, (int) $movement->user_id);
    }

    public function test_opname_deducts_stock_when_actual_is_less_than_system(): void
    {
        $admin3 = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $category = Category::create(['name' => 'Test']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);
        $warehouse = Warehouse::create(['name' => 'Gudang A', 'code' => 'WHA', 'active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'name' => 'Produk B',
            'description' => null,
            'sku' => 'SKU-OP-2',
            'barcode' => null,
            'price' => 0,
            'purchase_price' => 0,
            'stock' => 10,
            'min_stock' => 0,
            'image' => null,
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 10,
        ]);

        $resp = $this->actingAs($admin3)->post(route('gudang.opname.store', absolute: false), [
            'reference_number' => 'OP-TEST-002',
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'actual_qty' => 7,
            'notes' => 'Hitung ulang',
        ]);

        $resp->assertRedirect(route('gudang.opname', absolute: false));

        $product->refresh();
        $this->assertSame(7, (int) $product->stock);

        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'stock' => 7,
        ]);

        $movement = StockMovement::query()
            ->where('type', 'adjustment')
            ->where('reference_number', 'OP-TEST-002')
            ->firstOrFail();
        $this->assertSame(-3, (int) $movement->quantity);
        $this->assertSame($admin3->id, (int) $movement->user_id);
    }

    public function test_opname_can_use_difference_input_without_actual_qty(): void
    {
        $admin3 = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $category = Category::create(['name' => 'Test']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);
        $warehouse = Warehouse::create(['name' => 'Gudang A', 'code' => 'WHA', 'active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'name' => 'Produk C',
            'description' => null,
            'sku' => 'SKU-OP-3',
            'barcode' => null,
            'price' => 0,
            'purchase_price' => 0,
            'stock' => 5,
            'min_stock' => 0,
            'image' => null,
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 5,
        ]);

        $resp = $this->actingAs($admin3)->post(route('gudang.opname.store', absolute: false), [
            'reference_number' => 'OP-TEST-003',
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'difference' => 3,
            'notes' => 'Selisih',
        ]);

        $resp->assertRedirect(route('gudang.opname', absolute: false));

        $product->refresh();
        $this->assertSame(8, (int) $product->stock);

        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'stock' => 8,
        ]);

        $movement = StockMovement::query()
            ->where('type', 'adjustment')
            ->where('reference_number', 'OP-TEST-003')
            ->firstOrFail();
        $this->assertSame(3, (int) $movement->quantity);
        $this->assertSame($admin3->id, (int) $movement->user_id);
    }
}

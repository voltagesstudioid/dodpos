<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderUnitConversionReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_receiving_po_uses_unit_conversion_factor_into_base_stock(): void
    {
        $supervisor = User::factory()->create([
            'role' => 'supervisor',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $admin3 = User::factory()->create([
            'role' => 'admin3',
            'active' => true,
            'email_verified_at' => now(),
        ]);

        $category = Category::create(['name' => 'Test']);
        $unitBungkus = Unit::create(['name' => 'Bungkus', 'abbreviation' => 'bks']);
        $unitBale = Unit::create(['name' => 'Bale', 'abbreviation' => 'bal']);
        $supplier = Supplier::create(['name' => 'Supplier Test', 'active' => true]);
        $warehouse = Warehouse::create(['name' => 'Gudang Test', 'code' => 'WH-TEST', 'active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'unit_id' => $unitBale->id,
            'name' => 'Produk Bale',
            'description' => null,
            'sku' => 'SKU-BALE-1',
            'barcode' => null,
            'price' => 0,
            'purchase_price' => 0,
            'stock' => 0,
            'min_stock' => 0,
            'image' => null,
        ]);

        ProductUnitConversion::create([
            'product_id' => $product->id,
            'unit_id' => $unitBungkus->id,
            'conversion_factor' => 1,
            'purchase_price' => 1,
            'sell_price_ecer' => 1,
            'sell_price_grosir' => 1,
            'is_base_unit' => true,
        ]);

        ProductUnitConversion::create([
            'product_id' => $product->id,
            'unit_id' => $unitBale->id,
            'conversion_factor' => 800,
            'purchase_price' => 800,
            'sell_price_ecer' => 800,
            'sell_price_grosir' => 800,
            'is_base_unit' => false,
        ]);

        $poResp = $this->actingAs($supervisor)->post(route('pembelian.order.store', absolute: false), [
            'po_number' => PurchaseOrder::generatePoNumber(),
            'supplier_id' => $supplier->id,
            'order_date' => now()->toDateString(),
            'expected_date' => now()->addDays(2)->toDateString(),
            'payment_term' => 'credit',
            'due_date' => now()->addDays(30)->toDateString(),
            'notes' => null,
            'items' => [
                [
                    'product_id' => $product->id,
                    'unit_id' => $unitBale->id,
                    'qty_ordered' => 10,
                    'unit_price' => 0,
                    'notes' => null,
                ],
            ],
        ]);

        $poResp->assertRedirect();

        $po = PurchaseOrder::query()->latest('id')->firstOrFail();
        $item = PurchaseOrderItem::query()->where('purchase_order_id', $po->id)->firstOrFail();

        $this->assertSame($unitBale->id, $item->unit_id);
        $this->assertSame(800, (int) $item->conversion_factor);

        $receiveResp = $this->actingAs($admin3)->post(route('gudang.terimapo.store', $po, absolute: false), [
            'warehouse_id' => $warehouse->id,
            'items' => [
                [
                    'item_id' => $item->id,
                    'qty' => 10,
                    'batch_number' => null,
                    'expired_date' => null,
                ],
            ],
        ]);

        $receiveResp->assertRedirect(route('gudang.terimapo.index', absolute: false));

        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'stock' => 8000,
        ]);
    }
}

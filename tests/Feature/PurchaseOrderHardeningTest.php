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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PurchaseOrderHardeningTest extends TestCase
{
    use RefreshDatabase;

    private function basePayload(array $overrides = []): array
    {
        $payload = array_merge([
            'po_number' => 'PO-TEST-0001',
            'supplier_id' => $overrides['supplier_id'] ?? null,
            'order_date' => now()->toDateString(),
            'expected_date' => null,
            'notes' => null,
        ], $overrides);

        if (Schema::hasColumn('purchase_orders', 'payment_term')) {
            $payload['payment_term'] = $payload['payment_term'] ?? 'credit';
        }
        if (Schema::hasColumn('purchase_orders', 'due_date')) {
            $payload['due_date'] = $payload['due_date'] ?? now()->addDays(30)->toDateString();
        }

        return $payload;
    }

    public function test_store_rejects_duplicate_products_in_items(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $supplier = Supplier::create(['name' => 'Supplier A', 'active' => true]);
        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);

        $product = Product::create([
            'name' => 'Produk Test',
            'sku' => 'SKU-PO-001',
            'barcode' => '111',
            'price' => 10000,
            'purchase_price' => 8000,
            'stock' => 10,
            'min_stock' => 0,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'active' => true,
        ]);

        $payload = $this->basePayload([
            'po_number' => 'PO-TEST-0002',
            'supplier_id' => $supplier->id,
            'items' => [
                ['product_id' => $product->id, 'unit_id' => $unit->id, 'qty_ordered' => 1, 'unit_price' => 8000],
                ['product_id' => $product->id, 'unit_id' => $unit->id, 'qty_ordered' => 2, 'unit_price' => 8000],
            ],
        ]);

        $resp = $this->actingAs($user)->post(route('pembelian.order.store', absolute: false), $payload);
        $resp->assertRedirect();
        $this->assertDatabaseMissing('purchase_orders', ['po_number' => 'PO-TEST-0002']);
    }

    public function test_store_computes_conversion_factor_from_unit_conversion(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $supplier = Supplier::create(['name' => 'Supplier A', 'active' => true]);
        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $pcs = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);
        $dus = Unit::create(['name' => 'Dus', 'abbreviation' => 'dus']);

        $product = Product::create([
            'name' => 'Produk Test',
            'sku' => 'SKU-PO-002',
            'barcode' => '222',
            'price' => 10000,
            'purchase_price' => 8000,
            'stock' => 10,
            'min_stock' => 0,
            'category_id' => $category->id,
            'unit_id' => $pcs->id,
            'active' => true,
        ]);
        ProductUnitConversion::create([
            'product_id' => $product->id,
            'unit_id' => $dus->id,
            'conversion_factor' => 12,
            'purchase_price' => 90000,
            'sell_price_ecer' => 0,
            'sell_price_grosir' => 0,
            'sell_price_jual1' => 0,
            'sell_price_jual2' => 0,
            'sell_price_jual3' => 0,
            'sell_price_minimal' => 0,
            'is_base_unit' => false,
        ]);

        $payload = $this->basePayload([
            'po_number' => 'PO-TEST-0003',
            'supplier_id' => $supplier->id,
            'items' => [
                [
                    'product_id' => $product->id,
                    'unit_id' => $dus->id,
                    'conversion_factor' => 999,
                    'qty_ordered' => 2,
                    'unit_price' => 90000,
                ],
            ],
        ]);

        $resp = $this->actingAs($user)->post(route('pembelian.order.store', absolute: false), $payload);
        $resp->assertRedirect();

        $po = PurchaseOrder::where('po_number', 'PO-TEST-0003')->firstOrFail();
        $item = PurchaseOrderItem::where('purchase_order_id', $po->id)->firstOrFail();
        $this->assertSame($dus->id, (int) $item->unit_id);
        $this->assertSame(12, (int) $item->conversion_factor);
    }

    public function test_update_recomputes_conversion_factor(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $supplier = Supplier::create(['name' => 'Supplier A', 'active' => true]);
        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $pcs = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);
        $dus = Unit::create(['name' => 'Dus', 'abbreviation' => 'dus']);

        $product = Product::create([
            'name' => 'Produk Test',
            'sku' => 'SKU-PO-003',
            'barcode' => '333',
            'price' => 10000,
            'purchase_price' => 8000,
            'stock' => 10,
            'min_stock' => 0,
            'category_id' => $category->id,
            'unit_id' => $pcs->id,
            'active' => true,
        ]);
        ProductUnitConversion::create([
            'product_id' => $product->id,
            'unit_id' => $dus->id,
            'conversion_factor' => 6,
            'purchase_price' => 45000,
            'sell_price_ecer' => 0,
            'sell_price_grosir' => 0,
            'sell_price_jual1' => 0,
            'sell_price_jual2' => 0,
            'sell_price_jual3' => 0,
            'sell_price_minimal' => 0,
            'is_base_unit' => false,
        ]);

        $poPayload = $this->basePayload([
            'po_number' => 'PO-TEST-0004',
            'supplier_id' => $supplier->id,
        ]);
        $po = PurchaseOrder::create(array_merge($poPayload, [
            'status' => 'draft',
            'total_amount' => 0,
            'user_id' => $user->id,
        ]));
        PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'product_id' => $product->id,
            'unit_id' => $pcs->id,
            'conversion_factor' => 1,
            'qty_ordered' => 1,
            'qty_received' => 0,
            'unit_price' => 8000,
            'subtotal' => 8000,
        ]);

        $updatePayload = $this->basePayload([
            'supplier_id' => $supplier->id,
            'order_date' => now()->toDateString(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'unit_id' => $dus->id,
                    'conversion_factor' => 999,
                    'qty_ordered' => 3,
                    'unit_price' => 45000,
                ],
            ],
        ]);

        $resp = $this->actingAs($user)->put(route('pembelian.order.update', $po, absolute: false), $updatePayload);
        $resp->assertRedirect();

        $item = $po->fresh()->items()->firstOrFail();
        $this->assertSame($dus->id, (int) $item->unit_id);
        $this->assertSame(6, (int) $item->conversion_factor);
    }
}

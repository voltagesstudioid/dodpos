<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\SupplierDebt;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PurchaseOrderReceiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_receive_updates_stock_movements_status_and_supplier_debt(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $supplier = Supplier::create(['name' => 'Supplier A', 'active' => true]);
        $warehouse = Warehouse::create(['name' => 'WH A', 'active' => true]);
        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);

        $product = Product::create([
            'name' => 'Produk Test',
            'sku' => 'SKU-PO-RCV-001',
            'barcode' => 'RCV-001',
            'price' => 10000,
            'purchase_price' => 8000,
            'stock' => 0,
            'min_stock' => 0,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'active' => true,
        ]);

        $po = PurchaseOrder::create(array_filter([
            'po_number' => 'PO-RCV-0001',
            'supplier_id' => $supplier->id,
            'status' => 'ordered',
            'order_date' => now()->toDateString(),
            'expected_date' => null,
            'due_date' => Schema::hasColumn('purchase_orders', 'due_date') ? now()->addDays(10)->toDateString() : null,
            'total_amount' => 16000,
            'notes' => null,
            'payment_term' => Schema::hasColumn('purchase_orders', 'payment_term') ? 'credit' : null,
            'user_id' => $user->id,
        ], fn ($v) => $v !== null));

        $item = PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'product_id' => $product->id,
            'unit_id' => $unit->id,
            'conversion_factor' => 2,
            'qty_ordered' => 5,
            'qty_received' => 0,
            'unit_price' => 8000,
            'subtotal' => 40000,
        ]);

        $resp = $this->actingAs($user)->post(route('pembelian.order.process_receive', $po, absolute: false), [
            'warehouse_id' => $warehouse->id,
            'receive_date' => now()->toDateString(),
            'items' => [
                ['item_id' => $item->id, 'qty' => 3, 'expired_date' => null, 'batch_number' => null],
            ],
        ]);
        $resp->assertRedirect();

        $this->assertDatabaseHas('purchase_order_items', [
            'id' => $item->id,
            'qty_received' => 3,
        ]);

        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 6,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 6,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'type' => 'in',
            'source_type' => 'purchase_order',
            'purchase_order_id' => $po->id,
            'reference_number' => $po->po_number,
            'quantity' => 6,
        ]);

        $this->assertSame('partial', $po->fresh()->status);

        $this->assertDatabaseHas('supplier_debts', [
            'supplier_id' => $supplier->id,
            'purchase_order_id' => $po->id,
            'total_amount' => 16000,
            'status' => 'unpaid',
        ]);

        $resp2 = $this->actingAs($user)->post(route('pembelian.order.process_receive', $po, absolute: false), [
            'warehouse_id' => $warehouse->id,
            'receive_date' => now()->toDateString(),
            'items' => [
                ['item_id' => $item->id, 'qty' => 2, 'expired_date' => null, 'batch_number' => null],
            ],
        ]);
        $resp2->assertRedirect();

        $this->assertSame('received', $po->fresh()->status);
        $this->assertSame(10, (int) $product->fresh()->stock);
        $this->assertSame(10, (int) ProductStock::where('product_id', $product->id)->where('warehouse_id', $warehouse->id)->value('stock'));

        $this->assertSame(1, SupplierDebt::where('purchase_order_id', $po->id)->count());
    }

    public function test_receive_rejects_item_not_belonging_to_po(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $supplier = Supplier::create(['name' => 'Supplier A', 'active' => true]);
        $warehouse = Warehouse::create(['name' => 'WH A', 'active' => true]);
        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $unit = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);

        $product = Product::create([
            'name' => 'Produk Test',
            'sku' => 'SKU-PO-RCV-002',
            'barcode' => 'RCV-002',
            'price' => 10000,
            'purchase_price' => 8000,
            'stock' => 0,
            'min_stock' => 0,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'active' => true,
        ]);

        $po1 = PurchaseOrder::create(array_filter([
            'po_number' => 'PO-RCV-0002',
            'supplier_id' => $supplier->id,
            'status' => 'ordered',
            'order_date' => now()->toDateString(),
            'expected_date' => null,
            'due_date' => Schema::hasColumn('purchase_orders', 'due_date') ? now()->addDays(10)->toDateString() : null,
            'total_amount' => 1000,
            'notes' => null,
            'payment_term' => Schema::hasColumn('purchase_orders', 'payment_term') ? 'credit' : null,
            'user_id' => $user->id,
        ], fn ($v) => $v !== null));

        $po2 = PurchaseOrder::create(array_filter([
            'po_number' => 'PO-RCV-0003',
            'supplier_id' => $supplier->id,
            'status' => 'ordered',
            'order_date' => now()->toDateString(),
            'expected_date' => null,
            'due_date' => Schema::hasColumn('purchase_orders', 'due_date') ? now()->addDays(10)->toDateString() : null,
            'total_amount' => 1000,
            'notes' => null,
            'payment_term' => Schema::hasColumn('purchase_orders', 'payment_term') ? 'credit' : null,
            'user_id' => $user->id,
        ], fn ($v) => $v !== null));

        $itemPo2 = PurchaseOrderItem::create([
            'purchase_order_id' => $po2->id,
            'product_id' => $product->id,
            'unit_id' => $unit->id,
            'conversion_factor' => 1,
            'qty_ordered' => 5,
            'qty_received' => 0,
            'unit_price' => 8000,
            'subtotal' => 40000,
        ]);

        $resp = $this->actingAs($user)->post(route('pembelian.order.process_receive', $po1, absolute: false), [
            'warehouse_id' => $warehouse->id,
            'receive_date' => now()->toDateString(),
            'items' => [
                ['item_id' => $itemPo2->id, 'qty' => 1, 'expired_date' => null, 'batch_number' => null],
            ],
        ]);
        $resp->assertRedirect();

        $this->assertSame(0, (int) $product->fresh()->stock);
        $this->assertSame('ordered', $po1->fresh()->status);
        $this->assertDatabaseMissing('stock_movements', [
            'purchase_order_id' => $po1->id,
        ]);
    }
}

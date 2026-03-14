<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderShortageReport;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseOrderShortageReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin3_partial_receipt_creates_shortage_report_visible_to_supervisor(): void
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
        $unit = Unit::create(['name' => 'Pieces', 'abbreviation' => 'pcs']);
        $supplier = Supplier::create(['name' => 'Supplier Test', 'active' => true]);
        $warehouse = Warehouse::create(['name' => 'Gudang Test', 'code' => 'WH-TEST', 'active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'name' => 'Produk A',
            'price' => 1000,
            'purchase_price' => 700,
            'stock' => 0,
            'min_stock' => 0,
            'sku' => 'SKU-TEST-1',
        ]);

        $po = PurchaseOrder::create([
            'po_number' => PurchaseOrder::generatePoNumber(),
            'supplier_id' => $supplier->id,
            'status' => 'ordered',
            'order_date' => now()->toDateString(),
            'expected_date' => now()->addDays(2)->toDateString(),
            'total_amount' => 7000,
            'notes' => null,
            'user_id' => $supervisor->id,
            'payment_term' => 'credit',
        ]);

        $item = PurchaseOrderItem::create([
            'purchase_order_id' => $po->id,
            'product_id' => $product->id,
            'unit_id' => $unit->id,
            'conversion_factor' => 1,
            'qty_ordered' => 10,
            'qty_received' => 0,
            'unit_price' => 700,
            'subtotal' => 7000,
        ]);

        $resp = $this->actingAs($admin3)->post(route('gudang.terimapo.store', $po, absolute: false), [
            'warehouse_id' => $warehouse->id,
            'shortage_notes' => 'Barang kurang dari surat jalan',
            'items' => [
                [
                    'item_id' => $item->id,
                    'qty' => 6,
                    'batch_number' => null,
                    'expired_date' => null,
                ],
            ],
        ]);

        $resp->assertRedirect(route('gudang.terimapo.index', absolute: false));

        $po->refresh();
        $this->assertSame('partial', $po->status);

        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'stock' => 6,
        ]);

        $this->assertDatabaseCount('purchase_order_shortage_reports', 1);
        $report = PurchaseOrderShortageReport::firstOrFail();
        $this->assertSame($po->id, $report->purchase_order_id);
        $this->assertSame($admin3->id, $report->reported_by);
        $this->assertSame('Barang kurang dari surat jalan', $report->notes);

        $page = $this->actingAs($supervisor)->get(route('pembelian.order.show', $po, absolute: false));
        $page->assertOk();
        $page->assertSee('LAPORAN KEKURANGAN');
        $page->assertSee('Produk A');
        $page->assertSee('4');
    }
}

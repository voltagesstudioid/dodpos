<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosRoutesHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaksi_create_is_404(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create();
        $this->actingAs($user);

        $resp = $this->get('/transaksi/create');
        $resp->assertStatus(404);
    }

    public function test_transaksi_non_numeric_is_404(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create();
        $this->actingAs($user);

        $resp = $this->get('/transaksi/abc');
        $resp->assertStatus(404);
    }

    public function test_legacy_kasir_transaksi_route_removed(): void
    {
        $this->withoutMiddleware();
        $user = User::factory()->create();
        $this->actingAs($user);

        $resp = $this->post('/kasir/transaksi', [
            'items' => [],
            'total_amount' => 0,
            'paid_amount' => 0,
            'payment_method' => 'cash',
        ]);
        $resp->assertStatus(404);
    }

    public function test_void_restock_uses_stock_movements_when_detail_missing_warehouse(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $category = Category::create(['name' => 'POS']);
        $w1 = Warehouse::create(['name' => 'WH-1', 'active' => true]);
        $w2 = Warehouse::create(['name' => 'WH-2', 'active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk Test',
            'price' => 1000,
            'stock' => 0,
            'sku' => 'SKU-TST',
        ]);
        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $w1->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 0,
        ]);
        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $w2->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 0,
        ]);

        $trx = Transaction::create([
            'user_id' => $user->id,
            'customer_id' => null,
            'total_amount' => 3000,
            'paid_amount' => 3000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
        TransactionDetail::create([
            'transaction_id' => $trx->id,
            'product_id' => $product->id,
            'warehouse_id' => null, // legacy path tanpa warehouse_id
            'quantity' => 3,
            'price' => 1000,
            'subtotal' => 3000,
        ]);

        // Jejak pengeluaran stok (POS lama): 2 dari WH-1, 1 dari WH-2
        StockMovement::create([
            'product_id' => $product->id,
            'warehouse_id' => $w1->id,
            'location_id' => null,
            'type' => 'out',
            'status' => 'completed',
            'source_type' => 'pos_transaction',
            'reference_number' => 'TRX-'.$trx->id,
            'batch_number' => null,
            'expired_date' => null,
            'quantity' => 2,
            'balance' => 0,
            'notes' => 'Test out WH-1',
            'user_id' => $user->id,
        ]);
        StockMovement::create([
            'product_id' => $product->id,
            'warehouse_id' => $w2->id,
            'location_id' => null,
            'type' => 'out',
            'status' => 'completed',
            'source_type' => 'pos_transaction',
            'reference_number' => 'TRX-'.$trx->id,
            'batch_number' => null,
            'expired_date' => null,
            'quantity' => 1,
            'balance' => 0,
            'notes' => 'Test out WH-2',
            'user_id' => $user->id,
        ]);

        $resp = $this->actingAs($user)->patch(route('transaksi.void', $trx, absolute: false));
        $resp->assertStatus(302);

        $product->refresh();
        $this->assertSame(3.0, (float) $product->stock, 'Global stock should be restored by 3');
        $this->assertSame(2.0, (float) ProductStock::where('product_id', $product->id)->where('warehouse_id', $w1->id)->value('stock'));
        $this->assertSame(1.0, (float) ProductStock::where('product_id', $product->id)->where('warehouse_id', $w2->id)->value('stock'));
    }
}


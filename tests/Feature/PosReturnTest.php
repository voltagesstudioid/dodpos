<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\PosReturn;
use App\Models\PosReturnItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosReturnTest extends TestCase
{
    use RefreshDatabase;

    public function test_void_is_blocked_when_transaction_has_return(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $category = Category::create(['name' => 'Test']);
        $warehouse = Warehouse::create(['name' => 'WH A', 'active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk A',
            'price' => 10000,
            'stock' => 10,
            'sku' => 'SKU-A',
        ]);
        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 10,
        ]);

        $trx = Transaction::create([
            'user_id' => $user->id,
            'customer_id' => null,
            'total_amount' => 10000,
            'paid_amount' => 10000,
            'change_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);
        $detail = TransactionDetail::create([
            'transaction_id' => $trx->id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 1,
            'price' => 10000,
            'subtotal' => 10000,
        ]);

        $retur = PosReturn::create([
            'return_number' => PosReturn::generateNumber(),
            'transaction_id' => $trx->id,
            'customer_id' => null,
            'user_id' => $user->id,
            'return_date' => today(),
            'refund_method' => 'tunai',
            'refund_amount' => 10000,
            'status' => 'completed',
        ]);
        PosReturnItem::create([
            'pos_return_id' => $retur->id,
            'transaction_detail_id' => $detail->id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 1,
            'price' => 10000,
            'subtotal' => 10000,
        ]);

        $resp = $this->actingAs($user)->patch(route('transaksi.void', $trx, absolute: false));
        $resp->assertStatus(302);
        $this->assertSame('completed', $trx->fresh()->status);
    }

    public function test_return_reduces_customer_debt_for_credit_transaction(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $customer = Customer::create(['name' => 'Pelanggan A']);
        $category = Category::create(['name' => 'Test']);
        $warehouse = Warehouse::create(['name' => 'WH A', 'active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk A',
            'price' => 10000,
            'stock' => 0,
            'sku' => 'SKU-A',
        ]);
        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 0,
        ]);

        $trx = Transaction::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'total_amount' => 50000,
            'paid_amount' => 0,
            'change_amount' => 0,
            'payment_method' => 'kredit',
            'status' => 'completed',
        ]);
        $detail = TransactionDetail::create([
            'transaction_id' => $trx->id,
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'quantity' => 5,
            'price' => 10000,
            'subtotal' => 50000,
        ]);

        $credit = CustomerCredit::create([
            'credit_number' => CustomerCredit::generateNumber('debt'),
            'customer_id' => $customer->id,
            'transaction_id' => $trx->id,
            'type' => 'debt',
            'transaction_date' => today(),
            'due_date' => today()->addDays(30),
            'amount' => 50000,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'description' => 'Pembelian POS - #'.$trx->id,
            'created_by' => $user->id,
        ]);
        $customer->refreshDebt();

        $resp = $this->actingAs($user)->post(route('transaksi.retur.store', $trx, absolute: false), [
            'refund_method' => 'tanpa_refund',
            'items' => [
                ['detail_id' => $detail->id, 'quantity' => 2, 'warehouse_id' => $warehouse->id],
            ],
        ]);
        $resp->assertRedirect();

        $credit->refresh();
        $this->assertSame(30000.0, (float) $credit->amount);
        $this->assertSame('unpaid', $credit->status);
        $this->assertSame(30000.0, (float) $customer->fresh()->current_debt);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\PosSession;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosEceranMovementTest extends TestCase
{
    use RefreshDatabase;

    public function test_eceran_transaction_creates_stock_movement_with_source_type(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $this->actingAs($user);

        PosSession::create([
            'user_id' => $user->id,
            'opening_amount' => 0,
            'payment_method' => 'cash',
            'status' => 'open',
            'notes' => null,
        ]);

        $category = Category::create(['name' => 'Test']);
        $warehouse = Warehouse::create(['name' => 'WH-1', 'active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produk A',
            'price' => 10000,
            'stock' => 5,
            'sku' => 'SKU-A',
        ]);
        ProductStock::create([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => 5,
        ]);

        $payload = [
            'items' => [
                [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'quantity' => 2,
                ],
            ],
            'paid_amount' => 20000,
            'payment_method' => 'cash',
            'customer_id' => null,
        ];

        $resp = $this->postJson(route('kasir.eceran.store', absolute: false), $payload);
        $resp->assertStatus(200)->assertJson(['success' => true]);

        $mov = StockMovement::where('product_id', $product->id)->latest()->first();
        $this->assertNotNull($mov);
        $this->assertSame('out', $mov->type);
        $this->assertSame('pos_transaction', (string) $mov->source_type);
        $this->assertNotEmpty($mov->reference_number);
    }
}


<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalesOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $pasgarUser;
    private Customer $customer;
    private Product $product;
    private Warehouse $warehouse;
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user dengan role pasgar
        $this->pasgarUser = User::factory()->create([
            'role'   => 'pasgar',
            'active' => true,
        ]);

        // Buat customer
        $this->customer = Customer::create([
            'name'         => 'Toko Test',
            'phone'        => '08123456789',
            'credit_limit' => 1000000,
            'current_debt' => 0,
            'active'       => true,
        ]);

        // Buat warehouse & vehicle
        $this->warehouse = Warehouse::create([
            'name'   => 'Gudang Kendaraan Test',
            'code'   => 'GKT-001',
            'active' => true,
        ]);

        $this->vehicle = Vehicle::create([
            'name'          => 'Mobil Test',
            'license_plate' => 'B 1234 TEST',
            'type'          => 'mobil',
            'warehouse_id'  => $this->warehouse->id,
            'active'        => true,
        ]);

        // Buat category & unit (required FK)
        $category = Category::create(['name' => 'Kategori Test', 'description' => '']);
        $unit      = Unit::create(['name' => 'Pcs', 'abbreviation' => 'pcs']);

        // Buat produk dengan stok
        $this->product = Product::create([
            'name'           => 'Produk Test',
            'sku'            => 'SKU-TEST-001',
            'price'          => 10000,
            'purchase_price' => 8000,
            'stock'          => 50,
            'min_stock'      => 5,
            'category_id'    => $category->id,
            'unit_id'        => $unit->id,
            'active'         => true,
        ]);

        // Buat stok di gudang kendaraan
        ProductStock::create([
            'product_id'   => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'stock'        => 50,
        ]);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_orders(): void
    {
        $response = $this->getJson('/api/sales/orders');
        $response->assertStatus(401);
    }

    /** @test */
    public function pasgar_user_can_list_their_orders(): void
    {
        $response = $this->actingAs($this->pasgarUser, 'sanctum')
            ->getJson('/api/sales/orders');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data',
                 ])
                 ->assertJson(['status' => 'success']);
    }

    /** @test */
    public function pasgar_user_can_create_preorder(): void
    {
        $response = $this->actingAs($this->pasgarUser, 'sanctum')
            ->postJson('/api/sales/orders', [
                'customer_id'    => $this->customer->id,
                'order_type'     => 'preorder',
                'payment_method' => 'tunai',
                'items'          => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 5,
                        'price'      => 10000,
                    ],
                ],
            ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'status'  => 'success',
                     'message' => 'Pesanan berhasil dibuat.',
                 ])
                 ->assertJsonStructure([
                     'data' => ['id', 'so_number', 'status', 'total_amount', 'customer'],
                 ]);

        $this->assertDatabaseHas('sales_orders', [
            'customer_id' => $this->customer->id,
            'user_id'     => $this->pasgarUser->id,
            'status'      => 'draft',
            'total_amount'=> 50000,
        ]);
    }

    /** @test */
    public function canvas_order_requires_vehicle_id(): void
    {
        $response = $this->actingAs($this->pasgarUser, 'sanctum')
            ->postJson('/api/sales/orders', [
                'customer_id'    => $this->customer->id,
                'order_type'     => 'canvas',
                'payment_method' => 'tunai',
                // vehicle_id sengaja tidak dikirim
                'items'          => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 5,
                        'price'      => 10000,
                    ],
                ],
            ]);

        $response->assertStatus(422)
                 ->assertJsonPath('status', 'error');
    }

    /** @test */
    public function canvas_order_deducts_stock_from_vehicle_warehouse(): void
    {
        $initialStock = $this->product->stock;

        $response = $this->actingAs($this->pasgarUser, 'sanctum')
            ->postJson('/api/sales/orders', [
                'customer_id'    => $this->customer->id,
                'order_type'     => 'canvas',
                'payment_method' => 'tunai',
                'vehicle_id'     => $this->vehicle->id,
                'items'          => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 10,
                        'price'      => 10000,
                    ],
                ],
            ]);

        $response->assertStatus(201);

        // Stok global harus berkurang
        $this->assertDatabaseHas('products', [
            'id'    => $this->product->id,
            'stock' => $initialStock - 10,
        ]);

        // Stok di gudang kendaraan harus berkurang
        $this->assertDatabaseHas('product_stocks', [
            'product_id'   => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'stock'        => 40,
        ]);

        // Stock movement harus tercatat
        $this->assertDatabaseHas('stock_movements', [
            'product_id'   => $this->product->id,
            'warehouse_id' => $this->warehouse->id,
            'type'         => 'out',
            'source_type'  => 'sales_order',
            'quantity'     => 10,
        ]);
    }

    /** @test */
    public function canvas_order_fails_when_warehouse_stock_insufficient(): void
    {
        // Set stok gudang kendaraan ke 0
        ProductStock::where('product_id', $this->product->id)
            ->where('warehouse_id', $this->warehouse->id)
            ->update(['stock' => 0]);

        $response = $this->actingAs($this->pasgarUser, 'sanctum')
            ->postJson('/api/sales/orders', [
                'customer_id'    => $this->customer->id,
                'order_type'     => 'canvas',
                'payment_method' => 'tunai',
                'vehicle_id'     => $this->vehicle->id,
                'items'          => [
                    [
                        'product_id' => $this->product->id,
                        'quantity'   => 5,
                        'price'      => 10000,
                    ],
                ],
            ]);

        $response->assertStatus(422)
                 ->assertJsonPath('status', 'error');
    }

    /** @test */
    public function order_validation_rejects_invalid_product(): void
    {
        $response = $this->actingAs($this->pasgarUser, 'sanctum')
            ->postJson('/api/sales/orders', [
                'customer_id'    => $this->customer->id,
                'order_type'     => 'preorder',
                'payment_method' => 'tunai',
                'items'          => [
                    [
                        'product_id' => 99999, // ID tidak ada
                        'quantity'   => 1,
                        'price'      => 10000,
                    ],
                ],
            ]);

        $response->assertStatus(422)
                 ->assertJsonPath('status', 'error')
                 ->assertJsonStructure(['errors']);
    }

    /** @test */
    public function pasgar_user_can_view_their_own_order(): void
    {
        // Buat order dulu
        $createResponse = $this->actingAs($this->pasgarUser, 'sanctum')
            ->postJson('/api/sales/orders', [
                'customer_id'    => $this->customer->id,
                'order_type'     => 'preorder',
                'payment_method' => 'tunai',
                'items'          => [
                    ['product_id' => $this->product->id, 'quantity' => 2, 'price' => 10000],
                ],
            ]);

        $orderId = $createResponse->json('data.id');

        $response = $this->actingAs($this->pasgarUser, 'sanctum')
            ->getJson("/api/sales/orders/{$orderId}");

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success')
                 ->assertJsonPath('data.id', $orderId);
    }
}

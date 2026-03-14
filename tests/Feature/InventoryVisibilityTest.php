<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\SdmEmployee;
use App\Models\StockOpnameSession;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function seedWarehouses(): void
    {
        Warehouse::query()->insert([
            ['id' => 1, 'name' => 'Gudang Utama', 'code' => 'GUD-UT', 'active' => 1],
            ['id' => 2, 'name' => 'Gudang Cabang', 'code' => 'GUD-CB', 'active' => 1],
        ]);
    }

    private function seedProductStock(int $warehouseId, int $qty): void
    {
        $categoryId = Category::query()->insertGetId([
            'name' => 'Rokok',
            'description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $productId = Product::query()->insertGetId([
            'category_id' => $categoryId,
            'name' => 'Gudang Garam Surya 12',
            'description' => null,
            'price' => 1000,
            'stock' => $qty,
            'min_stock' => 5,
            'sku' => 'PRD-0001',
            'image' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ProductStock::query()->create([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'location_id' => null,
            'batch_number' => null,
            'expired_date' => null,
            'stock' => $qty,
        ]);
    }

    private function makeActiveEmployeeUser(string $role): User
    {
        $user = User::factory()->create([
            'role' => $role,
            'active' => true,
        ]);

        SdmEmployee::query()->create([
            'user_id' => $user->id,
            'name' => $user->name,
            'position' => $role,
            'join_date' => now()->toDateString(),
            'active' => true,
        ]);

        return $user;
    }

    public function test_admin3_can_view_stock_without_opname(): void
    {
        $this->seedWarehouses();
        $user = $this->makeActiveEmployeeUser('admin3');

        $this->actingAs($user)
            ->get(route('gudang.stok'))
            ->assertOk();
    }

    public function test_admin4_can_view_stock_without_opname(): void
    {
        $this->seedWarehouses();
        $user = $this->makeActiveEmployeeUser('admin4');

        $this->actingAs($user)
            ->get(route('gudang.stok'))
            ->assertOk();
    }

    public function test_admin3_checkout_is_blocked_until_opname_submitted(): void
    {
        $this->seedWarehouses();
        $user = $this->makeActiveEmployeeUser('admin3');

        Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => 'self_'.$user->id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => null,
        ]);

        $payload = [
            'action' => 'out',
            'date' => now()->toDateString(),
            'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        ];

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store'), $payload)
            ->assertSessionHas('error');

        $this->assertNull(Attendance::query()->where('user_id', $user->id)->where('date', now()->toDateString())->value('check_out_time'));
    }

    public function test_admin3_checkout_is_allowed_after_opname_submitted(): void
    {
        $this->seedWarehouses();
        $user = $this->makeActiveEmployeeUser('admin3');

        Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => 'self_'.$user->id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => null,
        ]);

        StockOpnameSession::query()->create([
            'warehouse_id' => 1,
            'created_by' => $user->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        $this->assertDatabaseHas('stock_opname_sessions', [
            'warehouse_id' => 1,
            'status' => 'submitted',
        ]);

        $payload = [
            'action' => 'out',
            'date' => now()->toDateString(),
            'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        ];

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store'), $payload)
            ->assertSessionHas('success');

        $this->assertNotNull(Attendance::query()->where('user_id', $user->id)->where('date', now()->toDateString())->value('check_out_time'));
    }

    public function test_admin4_checkout_is_allowed_after_opname_submitted(): void
    {
        $this->seedWarehouses();
        $user = $this->makeActiveEmployeeUser('admin4');

        Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => 'self_'.$user->id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => null,
        ]);

        StockOpnameSession::query()->create([
            'warehouse_id' => 2,
            'created_by' => $user->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        $this->assertDatabaseHas('stock_opname_sessions', [
            'warehouse_id' => 2,
            'status' => 'submitted',
        ]);

        $payload = [
            'action' => 'out',
            'date' => now()->toDateString(),
            'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        ];

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store'), $payload)
            ->assertSessionHas('success');

        $this->assertNotNull(Attendance::query()->where('user_id', $user->id)->where('date', now()->toDateString())->value('check_out_time'));
    }

    public function test_admin3_checkout_is_allowed_when_opname_submitted_by_other_user_same_warehouse(): void
    {
        $this->seedWarehouses();
        $user = $this->makeActiveEmployeeUser('admin3');
        $other = $this->makeActiveEmployeeUser('admin3');

        Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => 'self_'.$user->id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => null,
        ]);

        StockOpnameSession::query()->create([
            'warehouse_id' => 1,
            'created_by' => $other->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $payload = [
            'action' => 'out',
            'date' => now()->toDateString(),
            'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        ];

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store'), $payload)
            ->assertSessionHas('success');
    }

    public function test_non_warehouse_role_can_checkout_without_opname(): void
    {
        $this->seedWarehouses();
        $user = $this->makeActiveEmployeeUser('kasir');

        Attendance::query()->create([
            'user_id' => $user->id,
            'fingerprint_id' => 'self_'.$user->id,
            'date' => now()->toDateString(),
            'status' => 'present',
            'check_in_time' => '08:00',
            'check_out_time' => null,
        ]);

        $payload = [
            'action' => 'out',
            'date' => now()->toDateString(),
            'selfie_data' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        ];

        $this->actingAs($user)
            ->post(route('sdm.absensi.self_store'), $payload)
            ->assertSessionHas('success');
    }

    public function test_admin3_stock_is_masked_until_opname_submitted(): void
    {
        $this->seedWarehouses();
        $this->seedProductStock(1, 7210);
        $user = $this->makeActiveEmployeeUser('admin3');

        $this->actingAs($user)
            ->get(route('gudang.stok'))
            ->assertOk()
            ->assertSee('Terkunci')
            ->assertDontSee('7210');

        StockOpnameSession::query()->create([
            'warehouse_id' => 1,
            'created_by' => $user->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('gudang.stok'))
            ->assertOk()
            ->assertSee('7210');
    }

    public function test_admin4_stock_is_masked_until_opname_submitted(): void
    {
        $this->seedWarehouses();
        $this->seedProductStock(2, 81234);
        $user = $this->makeActiveEmployeeUser('admin4');

        $this->actingAs($user)
            ->get(route('gudang.stok'))
            ->assertOk()
            ->assertSee('Terkunci')
            ->assertDontSee('81234');

        StockOpnameSession::query()->create([
            'warehouse_id' => 2,
            'created_by' => $user->id,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        $this->assertDatabaseHas('stock_opname_sessions', [
            'warehouse_id' => 2,
            'status' => 'submitted',
        ]);

        $this->actingAs($user)
            ->get(route('gudang.stok'))
            ->assertOk()
            ->assertSee('81234');
    }
}

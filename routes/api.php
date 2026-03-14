<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// =========================================================
// GENERAL API — Rate limited
// =========================================================
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    // Register endpoint diamankan — hanya admin yang bisa buat user via web panel
    // Route::post('/register', [AuthController::class, 'register']); // DIHAPUS: security risk

    Route::middleware(['auth:sanctum', 'active'])->post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware(['auth:sanctum', 'active']);

    // Products API — hanya admin & gudang
    Route::middleware(['auth:sanctum', 'active', 'role:supervisor|gudang'])->group(function () {
        Route::apiResource('products', ProductController::class)->names('api.products');
    });
});

// =========================================================
// SISTEM PASGAR API (MOBILE APP) — Rate limited
// =========================================================
Route::prefix('sales')->name('api.sales.')->middleware('throttle:60,1')->group(function () {
    // Auth Routes — login dibatasi lebih ketat (10 percobaan per menit)
    Route::post('/login', [\App\Http\Controllers\Api\Sales\AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login');

    // Protected Routes — harus login + role pasgar, admin_sales, atau admin
    Route::middleware(['auth:sanctum', 'active', 'role:pasgar|supervisor'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Sales\AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [\App\Http\Controllers\Api\Sales\AuthController::class, 'me'])->name('me');

        // Products & Master Data
        Route::get('/products', [\App\Http\Controllers\Api\Sales\ProductController::class, 'index'])->name('products.index');
        Route::get('/customers', [\App\Http\Controllers\Api\Sales\CustomerController::class, 'index'])->name('customers.index');

        // Orders — hanya pasgar & admin_sales yang bisa buat order lapangan
        Route::post('/orders', [\App\Http\Controllers\Api\Sales\OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders', [\App\Http\Controllers\Api\Sales\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\Api\Sales\OrderController::class, 'show'])->name('orders.show');

        // Vehicles — untuk pilih kendaraan saat canvas order
        Route::get('/vehicles', [\App\Http\Controllers\Api\Sales\VehicleController::class, 'index'])->name('vehicles.index');

        // Customer Credits (Piutang)
        Route::get('/credits/unpaid', [\App\Http\Controllers\Api\Sales\CustomerCreditController::class, 'unpaid'])->name('credits.unpaid');
        Route::post('/credits/pay', [\App\Http\Controllers\Api\Sales\CustomerCreditController::class, 'pay'])->name('credits.pay');

        // Loading / Order Barang
        Route::get('/loadings', [\App\Http\Controllers\Api\Sales\LoadingController::class, 'index'])->name('loadings.index');
        Route::post('/loadings', [\App\Http\Controllers\Api\Sales\LoadingController::class, 'store'])->name('loadings.store');
        Route::get('/loadings/{id}', [\App\Http\Controllers\Api\Sales\LoadingController::class, 'show'])->name('loadings.show');
        Route::post('/loadings/{id}/crosscheck', [\App\Http\Controllers\Api\Sales\LoadingController::class, 'crosscheck'])->name('loadings.crosscheck');

        // Stok Kendaraan & Gudang Sumber
        Route::get('/vehicle-stock', [\App\Http\Controllers\Api\Sales\LoadingController::class, 'vehicleStock'])->name('vehicle-stock.index');
        Route::get('/warehouses', [\App\Http\Controllers\Api\Sales\LoadingController::class, 'warehouses'])->name('warehouses.index');
    });
});

// =========================================================
// MINYAK SALES API — /api/minyak/...
// =========================================================
Route::prefix('minyak')->name('api.minyak.')->group(function () {

    // Login (shared dengan pasgar — same endpoint, beda role check)
    Route::post('/login', [\App\Http\Controllers\Api\Sales\AuthController::class, 'login'])
        ->name('login');

    // Protected — hanya sales_minyak, admin_sales, atau admin
    Route::middleware(['auth:sanctum', 'active', 'role:sales_minyak|supervisor'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Sales\AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [\App\Http\Controllers\Api\Sales\AuthController::class, 'me'])->name('me');

        // Transaksi Minyak
        Route::get('/transaksi', [\App\Http\Controllers\Api\Minyak\TransaksiController::class, 'index'])->name('transaksi.index');
        Route::post('/transaksi', [\App\Http\Controllers\Api\Minyak\TransaksiController::class, 'store'])->name('transaksi.store');
        Route::delete('/transaksi/{id}', [\App\Http\Controllers\Api\Minyak\TransaksiController::class, 'destroy'])->name('transaksi.destroy');
        Route::get('/rekap', [\App\Http\Controllers\Api\Minyak\TransaksiController::class, 'rekap'])->name('rekap');

        // Pelanggan & Rute Kunjungan
        Route::get('/customers', [\App\Http\Controllers\Api\Sales\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/rute', [\App\Http\Controllers\Api\Minyak\RuteController::class, 'index'])->name('rute.index');
    });
});

// =========================================================
// MODUL GULA SALES API — /api/gula/...
// =========================================================
Route::prefix('gula')->name('api.gula.')->group(function () {
    // Login menggunakan auth controller Sales yang sudah ada
    Route::post('/login', [\App\Http\Controllers\Api\Sales\AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login');

    // Protected — hanya admin_sales atau admin (atau role khusus gula jika nanti dibuat)
    Route::middleware(['auth:sanctum', 'active', 'role:supervisor|sales'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Sales\AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [\App\Http\Controllers\Api\Sales\AuthController::class, 'me'])->name('me');

        // Master Data & Operasional Mobile Gula
        Route::get('/vehicle-stock', [\App\Http\Controllers\Api\Gula\VehicleStockController::class, 'index'])->name('stok');

        // POS Multi-satuan & Tiering Price
        Route::post('/pos/transaction', [\App\Http\Controllers\Api\Gula\PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/history', [\App\Http\Controllers\Api\Gula\PosController::class, 'history'])->name('pos.history');

        // Pelanggan & Kunjungan
        Route::get('/customers', [\App\Http\Controllers\Api\Sales\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/kunjungan', [\App\Http\Controllers\Api\Gula\KunjunganController::class, 'index'])->name('kunjungan.index');

        // Retur Barang Rusak dari Toko
        Route::post('/retur', [\App\Http\Controllers\Api\Gula\ReturController::class, 'store'])->name('retur.store');

        // Rincian Closing Shift / Rekap Setoran
        Route::get('/rekap/summary', [\App\Http\Controllers\Api\Gula\RekapController::class, 'summary'])->name('rekap.summary');
        Route::post('/rekap/submit', [\App\Http\Controllers\Api\Gula\RekapController::class, 'submit'])->name('rekap.submit');
    });
});

// =========================================================
// MODUL MINERAL SALES API — /api/mineral/...
// =========================================================
Route::prefix('mineral')->name('api.mineral.')->group(function () {
    // Shared Sales Auth login
    Route::post('/login', [\App\Http\Controllers\Api\Sales\AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login');

    // Protected (Harus Sales Mineral / Admin)
    Route::middleware(['auth:sanctum', 'active', 'role:sales_mineral|supervisor'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Sales\AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [\App\Http\Controllers\Api\Sales\AuthController::class, 'me'])->name('me');

        // Tarik Stok Murni (Sisa di Mobil)
        Route::get('/vehicle-stock', [\App\Http\Controllers\Api\Mineral\VehicleStockController::class, 'index'])->name('stok');

        // Tarik Data Toko Plgn
        Route::get('/customers', [\App\Http\Controllers\Api\Sales\CustomerController::class, 'index'])->name('customers.index');

        // Transaksi Pos (Jualan Dus-dusan)
        Route::post('/pos/transaction', [\App\Http\Controllers\Api\Mineral\PosController::class, 'store'])->name('pos.store');
        Route::get('/pos/history', [\App\Http\Controllers\Api\Mineral\PosController::class, 'history'])->name('pos.history');

        // Rekonsiliasi (Validasi Setoran Terminal)
        Route::get('/rekap/summary', [\App\Http\Controllers\Api\Mineral\SetoranController::class, 'summary'])->name('rekap.summary');
        Route::post('/rekap/submit', [\App\Http\Controllers\Api\Mineral\SetoranController::class, 'submit'])->name('rekap.submit');
    });
});

// =========================================================
// MODUL KANVAS SALES API — /api/kanvas/...
// =========================================================
Route::prefix('kanvas')->name('api.kanvas.')->group(function () {
    // Shared Sales Auth login
    Route::post('/login', [\App\Http\Controllers\Api\Sales\AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login');

    // Protected (Harus Sales Kanvas / Admin)
    Route::middleware(['auth:sanctum', 'active', 'role:sales_kanvas|supervisor'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Sales\AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [\App\Http\Controllers\Api\Sales\AuthController::class, 'me'])->name('me');

        // Van Inventory (Stok Berjalan)
        Route::get('/inventory', [\App\Http\Controllers\Api\Kanvas\InventoryController::class, 'index'])->name('inventory');

        // Data Toko / Pelanggan
        Route::get('/customers', [\App\Http\Controllers\Api\Sales\CustomerController::class, 'index'])->name('customers');

        // Product Catalog & Barcode Scanner
        Route::get('/catalog', [\App\Http\Controllers\Api\Kanvas\CatalogController::class, 'index'])->name('catalog');
        Route::get('/catalog/scan', [\App\Http\Controllers\Api\Kanvas\CatalogController::class, 'scan'])->name('catalog.scan');

        // Transaksi POS Kanvas (High Performance)
        Route::post('/pos', [\App\Http\Controllers\Api\Kanvas\PosController::class, 'store'])->name('pos');

        // Area & Journey Plan
        Route::get('/route', [\App\Http\Controllers\Api\Kanvas\RouteController::class, 'index'])->name('route');
        Route::post('/route/checkin', [\App\Http\Controllers\Api\Kanvas\RouteController::class, 'checkIn'])->name('route.checkin');

        // NOO (New Open Outlet - Toko Baru)
        Route::post('/noo', [\App\Http\Controllers\Api\Kanvas\NooController::class, 'store'])->name('noo');

        // Rekap Setoran Sore
        Route::get('/setoran/summary', [\App\Http\Controllers\Api\Kanvas\SetoranController::class, 'summary'])->name('setoran.summary');
        Route::post('/setoran/submit', [\App\Http\Controllers\Api\Kanvas\SetoranController::class, 'submit'])->name('setoran.submit');
    });
});

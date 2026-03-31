<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MinyakApiController;
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

    // =========================================================
    // MINYAK API — Mobile Sales App
    // =========================================================
    Route::middleware(['auth:sanctum', 'active'])->prefix('v1/minyak')->group(function () {

        // Dashboard
        Route::get('/dashboard', [MinyakApiController::class, 'dashboard']);

        // Pelanggan (Customers)
        Route::get('/pelanggan', [MinyakApiController::class, 'pelangganList']);
        Route::get('/pelanggan/{id}', [MinyakApiController::class, 'pelangganDetail']);

        // Produk
        Route::get('/produk', [MinyakApiController::class, 'produkList']);

        // Loading (Stok Kendaraan)
        Route::get('/loading/today', [MinyakApiController::class, 'loadingToday']);

        // Penjualan (Sales)
        Route::post('/penjualan', [MinyakApiController::class, 'storePenjualan']);
        Route::post('/penjualan/sync', [MinyakApiController::class, 'syncPenjualan']);
        Route::get('/penjualan/history', [MinyakApiController::class, 'penjualanHistory']);

        // Hutang (Debts)
        Route::get('/hutang', [MinyakApiController::class, 'hutangList']);
        Route::post('/hutang/{id}/bayar', [MinyakApiController::class, 'bayarHutang']);

        // Setoran (Daily Deposit)
        Route::get('/setoran/info', [MinyakApiController::class, 'setoranInfo']);
        Route::post('/setoran', [MinyakApiController::class, 'storeSetoran']);

        // Kunjungan (Visits)
        Route::post('/kunjungan', [MinyakApiController::class, 'storeKunjungan']);
        Route::get('/kunjungan/target', [MinyakApiController::class, 'kunjunganTarget']);
    });
});





<?php

use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sales Mobile Routes (PWA)
|--------------------------------------------------------------------------
| Routes untuk aplikasi mobile sales (PWA) yang bisa diakses dari browser
| di smartphone Android/iOS tanpa perlu install APK.
|
| Fitur: Offline mode, push notification, camera access, GPS tracking
*/

Route::middleware(['auth'])->prefix('sales')->name('sales.')->group(function () {
    
    // Main routes
    Route::get('/', [SalesController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [SalesController::class, 'dashboard'])->name('dashboard');
    Route::get('/menu', [SalesController::class, 'menu'])->name('menu');
    
    // Penjualan
    Route::get('/penjualan', [SalesController::class, 'listPenjualan'])->name('penjualan.list');
    Route::get('/penjualan/create', [SalesController::class, 'createPenjualan'])->name('penjualan.create');
    Route::get('/penjualan/produk', [SalesController::class, 'selectProduk'])->name('penjualan.produk');
    Route::post('/penjualan/store', [SalesController::class, 'storePenjualan'])->name('penjualan.store');
    
    // Loading/Stok
    Route::get('/loading', [SalesController::class, 'loading'])->name('loading');
    
    // Hutang
    Route::get('/hutang', [SalesController::class, 'hutang'])->name('hutang');
    Route::post('/hutang/{id}/bayar', [SalesController::class, 'bayarHutang'])->name('hutang.bayar');
    
    // Setoran
    Route::get('/setoran', [SalesController::class, 'setoran'])->name('setoran');
    Route::post('/setoran/store', [SalesController::class, 'storeSetoran'])->name('setoran.store');
    
    // Kunjungan
    Route::get('/kunjungan', [SalesController::class, 'listKunjungan'])->name('kunjungan.list');
    Route::get('/kunjungan/create', [SalesController::class, 'createKunjungan'])->name('kunjungan.create');
    Route::post('/kunjungan/store', [SalesController::class, 'storeKunjungan'])->name('kunjungan.store');
    
    // Pelanggan
    Route::get('/pelanggan', [SalesController::class, 'pelanggan'])->name('pelanggan');
    
    // Sync
    Route::get('/sync', [SalesController::class, 'sync'])->name('sync');
});

// API routes for PWA (these are handled by the existing API routes in api.php)
// The PWA will use: /api/v1/{division}/dashboard, etc.

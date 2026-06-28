<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mineral\DashboardController;
use App\Http\Controllers\Mineral\SalesController;
use App\Http\Controllers\Mineral\StokMasukController;
use App\Http\Controllers\Mineral\PelangganController;
use App\Http\Controllers\Mineral\ProdukController;
use App\Http\Controllers\Mineral\LoadingController;
use App\Http\Controllers\Mineral\StokController;
use App\Http\Controllers\Mineral\PenjualanController;
use App\Http\Controllers\Mineral\HutangController;
use App\Http\Controllers\Mineral\SetoranController;
use App\Http\Controllers\Mineral\KunjunganController;
use App\Http\Controllers\Mineral\LaporanController;

use App\Http\Controllers\Mineral\RegionalController;
use App\Http\Controllers\Mineral\SettingController;

// =========================================================
// MODUL MINERAL - Sales & Distribution
// =========================================================

// --- Shared routes (all mineral roles: supervisor, admin4, sales_mineral) ---
Route::prefix('mineral')->name('mineral.')->middleware('role:supervisor|admin4|sales_mineral')->group(function () {
    // Dashboard (controller handles role-based view + sales auto-redirect)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pelanggan (sales can view + create, supervisor has full CRUD)
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');

    // Produk (view only for sales, full for supervisor)
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');

    // Stok Kendaraan
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');

    // Stok Masuk (Penerimaan & Koreksi)
    Route::get('/stok-masuk', [StokMasukController::class, 'index'])->name('stok-masuk.index');
    Route::get('/stok-masuk/create', [StokMasukController::class, 'create'])->name('stok-masuk.create');
    Route::post('/stok-masuk', [StokMasukController::class, 'store'])->name('stok-masuk.store');
    Route::get('/stok-masuk/{stokMasuk}', [StokMasukController::class, 'show'])->name('stok-masuk.show');
    Route::delete('/stok-masuk/{stokMasuk}', [StokMasukController::class, 'destroy'])->name('stok-masuk.destroy');

    // Penjualan
    Route::resource('penjualan', PenjualanController::class);
    Route::get('/penjualan/{penjualan}/print', [PenjualanController::class, 'printStruk'])->name('penjualan.print');

    // Kunjungan (auto tercatat saat penjualan dibuat)
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::get('/kunjungan/{kunjungan}', [KunjunganController::class, 'show'])->name('kunjungan.show');

    // Setoran
    Route::resource('setoran', SetoranController::class);

    // Hutang (sales can view & pay, supervisor full access)
    Route::get('/hutang', [HutangController::class, 'index'])->name('hutang.index'); // Fallback
    Route::get('/hutang/piutang', [HutangController::class, 'piutang'])->name('hutang.piutang');
    Route::get('/hutang/total', [HutangController::class, 'totalPiutang'])->name('hutang.total');
    Route::get('/hutang/lunas', [HutangController::class, 'lunas'])->name('hutang.lunas');
    Route::get('/hutang/{hutang}', [HutangController::class, 'show'])->name('hutang.show');
    Route::post('/hutang/{hutang}/bayar', [HutangController::class, 'bayar'])->name('hutang.bayar');
});

// --- Supervisor & Admin only (full access features) ---
Route::prefix('mineral')->name('mineral.')->middleware('role:supervisor|admin4')->group(function () {
    // Hutang confirm/reject (supervisor only)
    Route::post('/hutang/{hutang}/payment/{payment}/confirm', [HutangController::class, 'confirmPayment'])->name('hutang.payment.confirm');
    Route::post('/hutang/{hutang}/payment/{payment}/reject', [HutangController::class, 'rejectPayment'])->name('hutang.payment.reject');

    // Master Data - Sales
    Route::resource('sales', SalesController::class)->parameters(['sales' => 'sales']);

    // Master Data - Pelanggan (edit/update/delete only)
    Route::resource('pelanggan', PelangganController::class)->except(['index', 'create', 'store', 'show']);

    // Master Data - Produk (CRUD)
    Route::resource('produk', ProdukController::class)->except(['index']);

    // Penugasan Kendaraan
    Route::resource('loading', LoadingController::class);
    Route::post('/loading/{loading}/approve', [LoadingController::class, 'approve'])->name('loading.approve');
    Route::post('/loading/{loading}/reject', [LoadingController::class, 'reject'])->name('loading.reject');

    // Penjualan verify
    Route::post('/penjualan/{penjualan}/verify', [PenjualanController::class, 'verify'])->name('penjualan.verify');

    // Setoran verify
    Route::post('/setoran/{setoran}/verify', [SetoranController::class, 'verify'])->name('setoran.verify');

    // Master Data - Regional
    Route::resource('regional', RegionalController::class);

    // Master Data - Setting (Jenis & Satuan)
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/jenis', [SettingController::class, 'storeJenis'])->name('jenis.store');
        Route::put('/jenis/{jenis}', [SettingController::class, 'updateJenis'])->name('jenis.update');
        Route::delete('/jenis/{jenis}', [SettingController::class, 'destroyJenis'])->name('jenis.destroy');
        Route::post('/satuan', [SettingController::class, 'storeSatuan'])->name('satuan.store');
        Route::put('/satuan/{satuan}', [SettingController::class, 'updateSatuan'])->name('satuan.update');
        Route::delete('/satuan/{satuan}', [SettingController::class, 'destroySatuan'])->name('satuan.destroy');
    });

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

    // API Internal for AJAX
    Route::get('/api/vehicle-stock/{vehicle}/{produk}', [LoadingController::class, 'vehicleStock'])->name('api.vehicle-stock');
});

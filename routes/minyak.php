<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Minyak\DashboardController;
use App\Http\Controllers\Minyak\SalesController;
use App\Http\Controllers\Minyak\PelangganController;
use App\Http\Controllers\Minyak\ProdukController;
use App\Http\Controllers\Minyak\LoadingController;
use App\Http\Controllers\Minyak\StokController;
use App\Http\Controllers\Minyak\PenjualanController;
use App\Http\Controllers\Minyak\HutangController;
use App\Http\Controllers\Minyak\SetoranController;

// =========================================================
// MODUL MINYAK - Sales & Distribution
// =========================================================

Route::prefix('minyak')->name('minyak.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data - Sales
    Route::resource('sales', SalesController::class);

    // Master Data - Pelanggan
    Route::resource('pelanggan', PelangganController::class);

    // Master Data - Produk
    Route::resource('produk', ProdukController::class);

    // Loading Harian
    Route::resource('loading', LoadingController::class);

    // Stok Kendaraan
    Route::get('/stok', [StokController::class, 'index'])->name('stok.index');

    // Penjualan
    Route::resource('penjualan', PenjualanController::class);
    Route::post('/penjualan/{penjualan}/verify', [PenjualanController::class, 'verify'])->name('penjualan.verify');

    // Hutang Pelanggan
    Route::get('/hutang', [HutangController::class, 'index'])->name('hutang.index');
    Route::get('/hutang/{hutang}', [HutangController::class, 'show'])->name('hutang.show');
    Route::post('/hutang/{hutang}/bayar', [HutangController::class, 'bayar'])->name('hutang.bayar');

    // Setoran
    Route::resource('setoran', SetoranController::class);
    Route::post('/setoran/{setoran}/verify', [SetoranController::class, 'verify'])->name('setoran.verify');

    // Kunjungan (placeholder)
    Route::get('/kunjungan', [DashboardController::class, 'kunjungan'])->name('kunjungan.index');

    // Laporan & Rekonsiliasi (placeholder)
    Route::get('/laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::get('/rekonsiliasi', [DashboardController::class, 'rekonsiliasi'])->name('rekonsiliasi');
});

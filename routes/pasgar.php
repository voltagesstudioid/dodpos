<?php

use App\Http\Controllers\Pasgar\PasgarDashboardController;
use App\Http\Controllers\Pasgar\PasgarHutangController;
use App\Http\Controllers\Pasgar\PasgarLoadingController;
use App\Http\Controllers\Pasgar\PasgarPelangganController;
use App\Http\Controllers\Pasgar\PasgarPenjualanController;
use App\Http\Controllers\Pasgar\PasgarSetoranController;
use Illuminate\Support\Facades\Route;

Route::prefix('pasgar')->name('pasgar.')->group(function () {
    // Dashboard Pasgar
    Route::get('/dashboard', [PasgarDashboardController::class, 'index'])->name('dashboard');

    // Sales Pasgar
    Route::get('/sales', [PasgarDashboardController::class, 'sales'])->name('sales.index');
    Route::get('/sales/create', [PasgarDashboardController::class, 'salesCreate'])->name('sales.create');
    Route::post('/sales', [PasgarDashboardController::class, 'salesStore'])->name('sales.store');

    // Pelanggan Pasgar
    Route::get('/pelanggan', [PasgarPelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PasgarPelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [PasgarPelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}', [PasgarPelangganController::class, 'show'])->name('pelanggan.show');
    Route::get('/pelanggan/{pelanggan}/edit', [PasgarPelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [PasgarPelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PasgarPelangganController::class, 'destroy'])->name('pelanggan.destroy');

    // Loading Barang
    Route::get('/loading', [PasgarLoadingController::class, 'index'])->name('loading.index');
    Route::get('/loading/create', [PasgarLoadingController::class, 'create'])->name('loading.create');
    Route::post('/loading', [PasgarLoadingController::class, 'store'])->name('loading.store');
    Route::get('/loading/{id}', [PasgarLoadingController::class, 'show'])->name('loading.show');
    Route::get('/loading/{id}/print', [PasgarLoadingController::class, 'print'])->name('loading.print');
    Route::post('/loading/{id}/approve', [PasgarLoadingController::class, 'approve'])->name('loading.approve');
    Route::post('/loading/{id}/reject', [PasgarLoadingController::class, 'reject'])->name('loading.reject');

    // Penjualan Lapangan
    Route::get('/penjualan', [PasgarPenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create/{loadingId}', [PasgarPenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PasgarPenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{id}', [PasgarPenjualanController::class, 'show'])->name('penjualan.show');

    // Setoran
    Route::get('/setoran', [PasgarSetoranController::class, 'index'])->name('setoran.index');
    Route::get('/setoran/create/{loadingId}', [PasgarSetoranController::class, 'create'])->name('setoran.create');
    Route::post('/setoran', [PasgarSetoranController::class, 'store'])->name('setoran.store');
    Route::get('/setoran/{id}', [PasgarSetoranController::class, 'show'])->name('setoran.show');
    Route::post('/setoran/{id}/verify', [PasgarSetoranController::class, 'verify'])->name('setoran.verify');

    // Hutang Piutang
    Route::get('/hutang', [PasgarHutangController::class, 'index'])->name('hutang.index');
    Route::get('/hutang/{hutang}', [PasgarHutangController::class, 'show'])->name('hutang.show');
    Route::get('/hutang/{hutang}/bayar', [PasgarHutangController::class, 'bayar'])->name('hutang.bayar');
    Route::post('/hutang/{hutang}/bayar', [PasgarHutangController::class, 'storeBayar'])->name('hutang.storeBayar');
    Route::post('/hutang/bayar/{bayar}/confirm', [PasgarHutangController::class, 'confirm'])->name('hutang.confirm');
});

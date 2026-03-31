<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

// =========================================================
// LAPORAN
// =========================================================

// Laporan Penjualan
Route::middleware(['can:view_laporan_penjualan'])->group(function () {
    Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
});

// Laporan Keuangan
Route::middleware(['can:view_laporan_keuangan'])->group(function () {
    Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
});

// Laporan Pelanggan
Route::middleware(['can:view_laporan_pelanggan'])->group(function () {
    Route::get('/laporan/pelanggan', [LaporanController::class, 'pelanggan'])->name('laporan.pelanggan');
});

// Laporan Supplier
Route::middleware(['can:view_laporan_supplier'])->group(function () {
    Route::get('/laporan/supplier', [LaporanController::class, 'supplier'])->name('laporan.supplier');
});

// Laporan Pembelian
Route::middleware(['can:view_laporan_pembelian'])->group(function () {
    Route::get('/laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
});

// Laporan Stok
Route::middleware(['can:view_laporan_stok'])->group(function () {
    Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KasirEceranController;
use App\Http\Controllers\KasirGrosirController;
use App\Http\Controllers\PosReturnController;
use App\Http\Controllers\TransactionController;

// =========================================================
// POINT OF SALE — akses kasir
// =========================================================

Route::middleware('can:view_pos_kasir')->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');

    // Eceran
    Route::get('/kasir/eceran', [KasirEceranController::class, 'index'])->name('kasir.eceran');
    Route::get('/kasir/eceran/search-products', [KasirEceranController::class, 'searchProducts'])->name('kasir.eceran.search_products');
    Route::get('/kasir/eceran/search-customers', [KasirEceranController::class, 'searchCustomers'])->name('kasir.eceran.search_customers');
    Route::post('/kasir/eceran', [KasirEceranController::class, 'store'])->name('kasir.eceran.store');

    // Grosir
    Route::get('/kasir/grosir', [KasirGrosirController::class, 'index'])->name('kasir.grosir');
    Route::get('/kasir/grosir/search-products', [KasirGrosirController::class, 'searchProducts'])->name('kasir.grosir.search_products');
    Route::get('/kasir/grosir/search-customers', [KasirGrosirController::class, 'searchCustomers'])->name('kasir.grosir.search_customers');
    Route::post('/kasir/grosir', [KasirGrosirController::class, 'store'])->name('kasir.grosir.store');
    Route::post('/kasir/transaksi', [KasirController::class, 'storeTransaksi'])->name('kasir.transaksi.store');
});

Route::middleware('can:view_sesi_kasir')->group(function () {
    Route::get('/kasir/sesi', [KasirController::class, 'session'])->name('kasir.session');
});

Route::middleware('can:delete_sesi_kasir')->group(function () {
    Route::post('/kasir/cash-movement', [KasirController::class, 'addCashMovement'])->name('kasir.cash_movement');
    Route::post('/kasir/open-session', [KasirController::class, 'openSession'])->name('kasir.open_session');
    Route::post('/kasir/close-session', [KasirController::class, 'closeSession'])->name('kasir.close_session');
});

// =========================================================
// TRANSAKSI
// =========================================================

Route::middleware('can:view_transaksi')->group(function () {
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/riwayat', fn () => redirect()->route('transaksi.index'))->name('transaksi.riwayat');
    Route::get('/transaksi/{transaksi}', [TransactionController::class, 'show'])->name('transaksi.show');
    Route::patch('/transaksi/{transaksi}/void', [TransactionController::class, 'destroy'])->name('transaksi.void');
    Route::get('/transaksi/retur/{retur}', [PosReturnController::class, 'show'])->name('transaksi.retur.show');
});

Route::middleware('can:edit_transaksi')->group(function () {
    Route::get('/transaksi/{transaksi}/retur', [PosReturnController::class, 'create'])->name('transaksi.retur.create');
    Route::post('/transaksi/{transaksi}/retur', [PosReturnController::class, 'store'])->name('transaksi.retur.store');
});

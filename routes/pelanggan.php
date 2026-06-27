<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerCreditController;

// =========================================================
// PELANGGAN (Customer)
// =========================================================

Route::middleware('can:create_pelanggan')->group(function () {
    Route::get('/pelanggan/create', [CustomerController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [CustomerController::class, 'store'])->name('pelanggan.store');
});

Route::middleware('can:view_pelanggan')->group(function () {
    Route::get('/pelanggan', [CustomerController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/{pelanggan}', [CustomerController::class, 'show'])->name('pelanggan.show');
});

Route::middleware('can:edit_pelanggan')->group(function () {
    Route::get('/pelanggan/{pelanggan}/edit', [CustomerController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [CustomerController::class, 'update'])->name('pelanggan.update');
});

Route::middleware('can:delete_pelanggan')->group(function () {
    Route::delete('/pelanggan/{pelanggan}', [CustomerController::class, 'destroy'])->name('pelanggan.destroy');
});

// =========================================================
// HUTANG PIUTANG
// =========================================================

Route::middleware('can:view_hutang_piutang')->group(function () {
    Route::get('/hutang-piutang', [CustomerCreditController::class, 'index'])->name('hutang.index'); // Fallback
    Route::get('/hutang-piutang/piutang', [CustomerCreditController::class, 'piutang'])->name('hutang.piutang');
    Route::get('/hutang-piutang/total', [CustomerCreditController::class, 'totalPiutang'])->name('hutang.total');
    Route::get('/hutang-piutang/lunas', [CustomerCreditController::class, 'lunas'])->name('hutang.lunas');
    
    Route::get('/hutang-piutang/list', [CustomerCreditController::class, 'index'])->name('pelanggan.kredit.index');
    Route::get('/hutang-piutang/konsolidasi', [CustomerCreditController::class, 'consolidated'])->name('pelanggan.kredit.consolidated');
    Route::get('/hutang-piutang/pelanggan/{customer}', [CustomerCreditController::class, 'customerDebt'])->name('pelanggan.kredit.customer');
    Route::get('/hutang-piutang/{kredit}', [CustomerCreditController::class, 'show'])->name('pelanggan.kredit.show');
});

Route::middleware('can:create_hutang_piutang')->group(function () {
    Route::get('/hutang-piutang/create', [CustomerCreditController::class, 'create'])->name('pelanggan.kredit.create');
    Route::post('/hutang-piutang', [CustomerCreditController::class, 'store'])->name('pelanggan.kredit.store');
    Route::post('/hutang-piutang/{kredit}/pay', [CustomerCreditController::class, 'pay'])->name('pelanggan.kredit.pay');
    Route::post('/hutang-piutang/pelanggan/{customer}/bayar', [CustomerCreditController::class, 'payConsolidated'])->name('pelanggan.kredit.pay_consolidated');
});

Route::middleware('can:delete_hutang_piutang')->group(function () {
    Route::delete('/hutang-piutang/{kredit}', [CustomerCreditController::class, 'destroy'])->name('pelanggan.kredit.destroy');
    Route::delete('/hutang-piutang/pembayaran/{payment}', [CustomerCreditController::class, 'deletePayment'])->name('pelanggan.kredit.delete_payment');
});

// =========================================================
// DAFTAR HARGA
// =========================================================

Route::middleware('can:view_daftar_harga')->group(function () {
    Route::get('/harga', [\App\Http\Controllers\HargaController::class, 'index'])->name('harga.index');
});

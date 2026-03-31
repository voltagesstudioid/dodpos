<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\AdminSalesController;
use Illuminate\Support\Facades\Route;

// =========================================================
// ROOT REDIRECT
// =========================================================

Route::get('/', function () {
    return redirect()->route('login');
});

// =========================================================
// DASHBOARD
// =========================================================

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth', 'active']);

Route::get('/admin-sales', [AdminSalesController::class, 'index'])
    ->middleware('can:view_dashboard')
    ->name('admin-sales.dashboard');

// =========================================================
// AUTHENTICATED ROUTES
// =========================================================

Route::middleware(['auth', 'active'])->group(function () {

    // --------------------------------------------------------
    // PROFILE
    // --------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/photo/{user}', [ProfileController::class, 'photo'])->name('profile.photo');

    // --------------------------------------------------------
    // PRINTING
    // --------------------------------------------------------
    Route::get('/print/receipt/{transaction}', [PrintController::class, 'printReceipt'])->name('print.receipt');
    Route::get('/print/faktur-grosir/{transaction}', [PrintController::class, 'printFakturGrosir'])->name('print.faktur_grosir');
    Route::get('/print/purchase/{order}', [PrintController::class, 'printPurchase'])
        ->name('print.purchase')
        ->middleware('can:view_laporan_pembelian');
    Route::get('/print/return/{retur}', [PrintController::class, 'printReturn'])->name('print.return');

    // --------------------------------------------------------
    // MODULAR ROUTES
    // --------------------------------------------------------
    require __DIR__ . '/kasir.php';
    require __DIR__ . '/pelanggan.php';
    require __DIR__ . '/master.php';
    require __DIR__ . '/gudang.php';
    require __DIR__ . '/pembelian.php';
    require __DIR__ . '/sales-order.php';
    require __DIR__ . '/laporan.php';
    require __DIR__ . '/operasional.php';
    require __DIR__ . '/sdm.php';
    require __DIR__ . '/minyak.php';
    require __DIR__ . '/sales.php';
    require __DIR__ . '/pengaturan.php';
});

// =========================================================
// AUTH ROUTES
// =========================================================

require __DIR__ . '/auth.php';

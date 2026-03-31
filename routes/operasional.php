<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperationalExpenseController;
use App\Http\Controllers\OperationalCategoryController;
use App\Http\Controllers\VehicleController;

// =========================================================
// KEUANGAN / OPERASIONAL
// =========================================================

Route::name('operasional.')->group(function () {
    // Dashboard
    Route::get('/operasional/dashboard', [OperationalExpenseController::class, 'dashboard'])
        ->name('operasional_dashboard')
        ->middleware('can:view_riwayat_operasional');

    // Export
    Route::get('/operasional/riwayat/export', [OperationalExpenseController::class, 'export'])
        ->name('riwayat.export')
        ->middleware('can:view_riwayat_operasional');

    // Kategori
    Route::resource('/operasional/kategori', OperationalCategoryController::class)
        ->only(['index'])
        ->middleware('can:view_kategori_operasional');
    Route::get('/operasional/kategori/export', [OperationalCategoryController::class, 'export'])
        ->name('kategori.export')
        ->middleware('can:view_kategori_operasional');
    Route::resource('/operasional/kategori', OperationalCategoryController::class)
        ->only(['create', 'store'])
        ->middleware('can:create_kategori_operasional');
    Route::resource('/operasional/kategori', OperationalCategoryController::class)
        ->only(['edit', 'update'])
        ->middleware('can:edit_kategori_operasional');
    Route::resource('/operasional/kategori', OperationalCategoryController::class)
        ->only(['destroy'])
        ->middleware('can:delete_kategori_operasional');

    // Kendaraan
    Route::resource('/operasional/kendaraan', VehicleController::class)
        ->only(['index'])
        ->middleware('can:view_kendaraan_operasional');
    Route::get('/operasional/kendaraan/export', [VehicleController::class, 'export'])
        ->name('kendaraan.export')
        ->middleware('can:view_kendaraan_operasional');
    Route::resource('/operasional/kendaraan', VehicleController::class)
        ->only(['create', 'store'])
        ->middleware('can:create_kendaraan_operasional');
    Route::resource('/operasional/kendaraan', VehicleController::class)
        ->only(['edit', 'update'])
        ->middleware('can:edit_kendaraan_operasional');
    Route::resource('/operasional/kendaraan', VehicleController::class)
        ->only(['destroy'])
        ->middleware('can:delete_kendaraan_operasional');

    // Session
    Route::post('/operasional/open-session', [OperationalExpenseController::class, 'openSession'])
        ->name('open_session')
        ->middleware('can:manage_sesi_operasional');
    Route::post('/operasional/close-session', [OperationalExpenseController::class, 'closeSession'])
        ->name('close_session')
        ->middleware('can:manage_sesi_operasional');
    Route::get('/operasional/sesi', [OperationalExpenseController::class, 'sessions'])
        ->name('sesi.index')
        ->middleware('can:view_sesi_operasional');

    // Riwayat & Pengeluaran
    Route::get('/operasional/riwayat', [OperationalExpenseController::class, 'index'])
        ->name('riwayat.index')
        ->middleware('can:view_riwayat_operasional');

    Route::get('/operasional/pengeluaran/create', [OperationalExpenseController::class, 'create'])
        ->name('pengeluaran.create')
        ->middleware('can:create_pengeluaran_operasional');
    Route::post('/operasional/pengeluaran', [OperationalExpenseController::class, 'store'])
        ->name('pengeluaran.store')
        ->middleware('can:create_pengeluaran_operasional');
    Route::get('/operasional/pengeluaran/{pengeluaran}/edit', [OperationalExpenseController::class, 'edit'])
        ->name('pengeluaran.edit')
        ->middleware('can:edit_pengeluaran_operasional');
    Route::put('/operasional/pengeluaran/{pengeluaran}', [OperationalExpenseController::class, 'update'])
        ->name('pengeluaran.update')
        ->middleware('can:edit_pengeluaran_operasional');
    Route::delete('/operasional/pengeluaran/{pengeluaran}', [OperationalExpenseController::class, 'destroy'])
        ->name('pengeluaran.destroy')
        ->middleware('can:delete_pengeluaran_operasional');
});

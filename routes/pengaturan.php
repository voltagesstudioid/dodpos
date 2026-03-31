<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreSettingController;
use App\Http\Controllers\SettingsBackupController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Settings\AppRoleController;

// =========================================================
// PENGATURAN — supervisor only
// =========================================================

// Roles Management
Route::prefix('/pengaturan/roles')->middleware('role:supervisor')->group(function () {
    Route::get('/', [AppRoleController::class, 'index'])->name('pengaturan.roles.index');
    Route::get('/migrate', [AppRoleController::class, 'migrate'])->name('pengaturan.roles.migrate');
    Route::post('/migrate', [AppRoleController::class, 'migrateStore'])->name('pengaturan.roles.migrate.store');
    Route::get('/create', [AppRoleController::class, 'create'])->name('pengaturan.roles.create');
    Route::post('/', [AppRoleController::class, 'store'])->name('pengaturan.roles.store');
    Route::get('/{role}/edit', [AppRoleController::class, 'edit'])->name('pengaturan.roles.edit');
    Route::put('/{role}', [AppRoleController::class, 'update'])->name('pengaturan.roles.update');
    Route::delete('/{role}', [AppRoleController::class, 'destroy'])->name('pengaturan.roles.destroy');
});

// Pengguna
Route::get('pengaturan/pengguna', [UserController::class, 'index'])
    ->name('pengguna.index')
    ->middleware('role:supervisor');
Route::get('pengaturan/pengguna/create', [UserController::class, 'create'])
    ->name('pengguna.create')
    ->middleware('role:supervisor');
Route::post('pengaturan/pengguna', [UserController::class, 'store'])
    ->name('pengguna.store')
    ->middleware('role:supervisor');
Route::get('pengaturan/pengguna/{pengguna}/edit', [UserController::class, 'edit'])
    ->name('pengguna.edit')
    ->middleware('role:supervisor');
Route::put('pengaturan/pengguna/{pengguna}', [UserController::class, 'update'])
    ->name('pengguna.update')
    ->middleware('role:supervisor');
Route::post('pengaturan/pengguna/{pengguna}/approve', [UserController::class, 'approve'])
    ->name('pengguna.approve')
    ->middleware('role:supervisor');
Route::post('pengaturan/pengguna/{pengguna}/reject', [UserController::class, 'reject'])
    ->name('pengguna.reject')
    ->middleware('role:supervisor');
Route::delete('pengaturan/pengguna/{pengguna}', [UserController::class, 'destroy'])
    ->name('pengguna.destroy')
    ->middleware('role:supervisor');

// Toko
Route::get('/pengaturan/toko', [StoreSettingController::class, 'edit'])
    ->name('pengaturan.toko')
    ->middleware('can:view_pengaturan_toko');
Route::put('/pengaturan/toko', [StoreSettingController::class, 'update'])
    ->name('pengaturan.toko.update')
    ->middleware('can:edit_pengaturan_toko');

// SDM
Route::get('/pengaturan/sdm', [StoreSettingController::class, 'sdmEdit'])
    ->name('pengaturan.sdm')
    ->middleware('can:view_pengaturan_toko');
Route::put('/pengaturan/sdm', [StoreSettingController::class, 'sdmUpdate'])
    ->name('pengaturan.sdm.update')
    ->middleware('can:edit_pengaturan_toko');

// Backup
Route::get('/pengaturan/backup', [SettingsBackupController::class, 'index'])
    ->name('pengaturan.backup')
    ->middleware('can:view_backup_restore');
Route::post('/pengaturan/backup/export', [SettingsBackupController::class, 'export'])
    ->name('pengaturan.backup.export')
    ->middleware('can:create_backup_restore');
Route::post('/pengaturan/backup/restore', [SettingsBackupController::class, 'restore'])
    ->name('pengaturan.backup.restore')
    ->middleware('can:create_backup_restore');

// Activity Log
Route::get('/pengaturan/activity-log', [ActivityLogController::class, 'index'])
    ->name('activity-log.index')
    ->middleware('can:view_log_aktivitas');
Route::post('/pengaturan/activity-log/prune', [ActivityLogController::class, 'prune'])
    ->name('activity-log.prune')
    ->middleware('can:delete_log_aktivitas');

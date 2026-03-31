<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sdm\EmployeeController;
use App\Http\Controllers\Sdm\AttendanceController;
use App\Http\Controllers\Sdm\HolidayController;
use App\Http\Controllers\Sdm\LeaveRequestController;
use App\Http\Controllers\Sdm\PenggajianController;
use App\Http\Controllers\Sdm\PerformaController;
use App\Http\Controllers\Sdm\PotonganGajiController;

// =========================================================
// SDM / HR
// =========================================================

Route::prefix('sdm')->name('sdm.')->group(function () {
    
    // Karyawan
    Route::middleware('can:view_karyawan')->group(function () {
        Route::get('/karyawan', [EmployeeController::class, 'index'])->name('karyawan.index');
        Route::get('/karyawan/export', [EmployeeController::class, 'export'])->name('karyawan.export');
        Route::get('/karyawan/{karyawan}', [EmployeeController::class, 'show'])->name('karyawan.show');
    });

    Route::middleware('can:create_karyawan')->group(function () {
        Route::get('/karyawan/create', [EmployeeController::class, 'create'])->name('karyawan.create');
        Route::post('/karyawan', [EmployeeController::class, 'store'])->name('karyawan.store');
        Route::post('/karyawan/import', [EmployeeController::class, 'importFromAccounts'])->name('karyawan.import');
    });

    Route::middleware('can:edit_karyawan')->group(function () {
        Route::get('/karyawan/{karyawan}/edit', [EmployeeController::class, 'edit'])->name('karyawan.edit');
        Route::put('/karyawan/{karyawan}', [EmployeeController::class, 'update'])->name('karyawan.update');
        Route::post('/karyawan/{karyawan}/link-user', [EmployeeController::class, 'linkUser'])->name('karyawan.link_user');
        Route::post('/karyawan/{karyawan}/unlink-user', [EmployeeController::class, 'unlinkUser'])->name('karyawan.unlink_user');
    });

    // Absensi
    Route::middleware('can:view_absensi')->group(function () {
        Route::get('/absensi', [AttendanceController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/bulanan', [AttendanceController::class, 'monthly'])->name('absensi.monthly');
        Route::get('/absensi/bulanan/export', [AttendanceController::class, 'monthlyExport'])->name('absensi.monthly.export');
        Route::get('/absensi/{attendance}/selfie/{type}', [AttendanceController::class, 'selfie'])->name('absensi.selfie');
        Route::get('/cuti', [LeaveRequestController::class, 'index'])->name('cuti.index');
        Route::get('/libur', [HolidayController::class, 'index'])->name('libur.index');
    });

    Route::middleware('can:create_absensi')->group(function () {
        Route::post('/absensi/sync', [AttendanceController::class, 'sync'])->name('absensi.sync');
        Route::post('/absensi/link-user', [AttendanceController::class, 'linkUser'])->name('absensi.link_user');
        Route::post('/absensi/manual', [AttendanceController::class, 'storeManual'])->name('absensi.manual.store');
        Route::post('/absensi/generate-absent', [AttendanceController::class, 'generateAbsent'])->name('absensi.generate_absent');
        Route::post('/cuti', [LeaveRequestController::class, 'store'])->name('cuti.store');
        Route::post('/libur', [HolidayController::class, 'store'])->name('libur.store');
        Route::post('/libur/generate', [HolidayController::class, 'generateMonth'])->name('libur.generate');
    });

    Route::middleware('can:edit_absensi')->group(function () {
        Route::patch('/absensi/{attendance}', [AttendanceController::class, 'update'])->name('absensi.update');
        Route::post('/cuti/{cuti}/approve', [LeaveRequestController::class, 'approve'])->name('cuti.approve');
        Route::post('/cuti/{cuti}/reject', [LeaveRequestController::class, 'reject'])->name('cuti.reject');
        Route::delete('/cuti/{cuti}', [LeaveRequestController::class, 'destroy'])->name('cuti.destroy');
        Route::patch('/libur/{libur}', [HolidayController::class, 'update'])->name('libur.update');
        Route::delete('/libur/{libur}', [HolidayController::class, 'destroy'])->name('libur.destroy');
    });

    // Penggajian
    Route::middleware('can:view_penggajian')->group(function () {
        Route::get('/penggajian', [PenggajianController::class, 'index'])->name('penggajian.index');
        Route::get('/penggajian/{penggajian}/print', [PenggajianController::class, 'print'])->name('penggajian.print');
    });

    Route::middleware('can:view_performa')->group(function () {
        Route::get('/performa', [PerformaController::class, 'index'])->name('performa.index');
    });

    Route::middleware('can:create_penggajian')->group(function () {
        Route::post('/penggajian/generate', [PenggajianController::class, 'generate'])->name('penggajian.generate');
    });

    Route::middleware('can:edit_penggajian')->group(function () {
        Route::post('/penggajian/{penggajian}/lock', [PenggajianController::class, 'lock'])->name('penggajian.lock');
        Route::post('/penggajian/{penggajian}/unlock', [PenggajianController::class, 'unlock'])->name('penggajian.unlock');
        Route::patch('/penggajian/{penggajian}/adjust', [PenggajianController::class, 'adjust'])->name('penggajian.adjust');
    });

    Route::middleware('can:delete_penggajian')->group(function () {
        Route::delete('/penggajian/{penggajian}', [PenggajianController::class, 'destroy'])->name('penggajian.destroy');
    });

    // Potongan Gaji
    Route::middleware('can:view_potongan_gaji')->group(function () {
        Route::get('/potongan', [PotonganGajiController::class, 'index'])->name('potongan.index');
    });

    Route::middleware('can:create_potongan_gaji')->group(function () {
        Route::post('/potongan', [PotonganGajiController::class, 'store'])->name('potongan.store');
    });

    Route::middleware('can:delete_potongan_gaji')->group(function () {
        Route::delete('/potongan/{potongan}', [PotonganGajiController::class, 'destroy'])->name('potongan.destroy');
    });

    // Absen mandiri oleh karyawan
    Route::get('/absen-saya', [AttendanceController::class, 'selfPanel'])
        ->name('absensi.self_panel')
        ->middleware('active');
    Route::post('/absen-saya', [AttendanceController::class, 'selfStore'])
        ->name('absensi.self_store')
        ->middleware('active');

    Route::get('/cuti-saya', [LeaveRequestController::class, 'selfIndex'])
        ->name('cuti.self_index')
        ->middleware('active');
    Route::post('/cuti-saya', [LeaveRequestController::class, 'selfStore'])
        ->name('cuti.self_store')
        ->middleware('active');
    Route::delete('/cuti-saya/{cuti}', [LeaveRequestController::class, 'selfDestroy'])
        ->name('cuti.self_destroy')
        ->middleware('active');

    Route::get('/gaji-saya', [PenggajianController::class, 'selfIndex'])
        ->name('penggajian.self_index')
        ->middleware('active');
    Route::get('/gaji-saya/{penggajian}/print', [PenggajianController::class, 'selfPrint'])
        ->name('penggajian.self_print')
        ->middleware('active');

    Route::get('/potongan-saya', [PotonganGajiController::class, 'selfIndex'])
        ->name('potongan.self_index')
        ->middleware('active');
});

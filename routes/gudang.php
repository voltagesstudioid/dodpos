<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\ProductRequestController;
use App\Http\Controllers\Gudang\OpnameSessionController;
use App\Http\Controllers\Gudang\OpnameApprovalController;
use App\Http\Controllers\Gudang\PenerimaanTransferController;
use App\Http\Controllers\Gudang\WarehouseReceiptController;
use App\Http\Controllers\Reports\StockInOutController;

// =========================================================
// MANAJEMEN GUDANG
// =========================================================

// Dashboard Gudang
Route::get('/gudang/dashboard', [StockReportController::class, 'dashboard'])
    ->name('gudang.dashboard')
    ->middleware('can:view_stok_gudang');

Route::middleware('can:view_stok_gudang')->group(function () {
    Route::get('/gudang/stok', [StockReportController::class, 'index'])->name('gudang.stok');
    Route::get('/gudang/stok/export', [StockReportController::class, 'export'])->name('gudang.stok.export');
    Route::get('/gudang/stok-semua', [\App\Http\Controllers\LaporanController::class, 'stok'])->name('gudang.stok-semua');

    // Permintaan Barang (PO / Transfer)
    Route::get('/gudang/request', [ProductRequestController::class, 'index'])->name('gudang.request.index');
    Route::get('/gudang/request/create', [ProductRequestController::class, 'create'])->name('gudang.request.create');
    Route::post('/gudang/request', [ProductRequestController::class, 'store'])->name('gudang.request.store');
    Route::put('/gudang/request/{productRequest}/status', [ProductRequestController::class, 'updateStatus'])->name('gudang.request.update_status');
});

Route::middleware('can:view_penerimaan_barang')->group(function () {
    Route::get('/gudang/expired', [StockReportController::class, 'expired'])->name('gudang.expired');
    Route::get('/gudang/minstok', [StockReportController::class, 'minimumStock'])->name('gudang.minstok');

    // Penerimaan Barang (Inbound)
    Route::get('/gudang/penerimaan', [InboundController::class, 'index'])->name('gudang.penerimaan');
    Route::get('/gudang/penerimaan/export', [InboundController::class, 'export'])->name('gudang.penerimaan.export');
    Route::get('/gudang/penerimaan/create', [InboundController::class, 'create'])->name('gudang.penerimaan.create');
    Route::post('/gudang/penerimaan', [InboundController::class, 'store'])->name('gudang.penerimaan.store');
    Route::get('/gudang/penerimaan/{inbound}', [InboundController::class, 'show'])->name('gudang.penerimaan.show');
    Route::delete('/gudang/penerimaan/{inbound}', [InboundController::class, 'destroy'])->name('gudang.penerimaan.destroy');

    Route::get('/gudang/masuk-keluar', [StockInOutController::class, 'index'])->name('gudang.inout');

    // Penerimaan PO dari Supplier (Blind Receipt)
    Route::get('/gudang/terima-po', [WarehouseReceiptController::class, 'index'])->name('gudang.terimapo.index');
    Route::get('/gudang/terima-po/{order}', [WarehouseReceiptController::class, 'show'])->name('gudang.terimapo.show');
    Route::post('/gudang/terima-po/{order}', [WarehouseReceiptController::class, 'store'])->name('gudang.terimapo.store');
});

// Opname Stok (Sesi + Approval Supervisor)
Route::get('/gudang/opname', [OpnameSessionController::class, 'index'])
    ->name('gudang.opname_sessions.index')
    ->middleware('can:view_opname_stok');
Route::get('/gudang/opname/create', [OpnameSessionController::class, 'create'])
    ->name('gudang.opname_sessions.create')
    ->middleware('can:create_opname_stok');
Route::post('/gudang/opname', [OpnameSessionController::class, 'store'])
    ->name('gudang.opname_sessions.store')
    ->middleware('can:create_opname_stok');
Route::get('/gudang/opname/{session}', [OpnameSessionController::class, 'edit'])
    ->name('gudang.opname_sessions.edit')
    ->middleware('can:view_opname_stok');
Route::post('/gudang/opname/{session}/items', [OpnameSessionController::class, 'addItem'])
    ->name('gudang.opname_sessions.items.add')
    ->middleware('can:create_opname_stok');
Route::delete('/gudang/opname/{session}/items/{item}', [OpnameSessionController::class, 'deleteItem'])
    ->name('gudang.opname_sessions.items.delete')
    ->middleware('can:create_opname_stok');
Route::post('/gudang/opname/{session}/submit', [OpnameSessionController::class, 'submit'])
    ->name('gudang.opname_sessions.submit')
    ->middleware('can:create_opname_stok');

Route::prefix('/gudang/opname-approval')->middleware('role:supervisor')->group(function () {
    Route::get('/', [OpnameApprovalController::class, 'index'])->name('gudang.opname_approval.index');
    Route::get('/{session}', [OpnameApprovalController::class, 'show'])->name('gudang.opname_approval.show');
    Route::post('/{session}/approve', [OpnameApprovalController::class, 'approve'])->name('gudang.opname_approval.approve');
    Route::post('/{session}/reject', [OpnameApprovalController::class, 'reject'])->name('gudang.opname_approval.reject');
});

Route::middleware('can:view_pengeluaran_barang')->group(function () {
    // Pengeluaran Barang (Outbound)
    Route::get('/gudang/pengeluaran', [OutboundController::class, 'index'])->name('gudang.pengeluaran');
    Route::get('/gudang/pengeluaran/create', [OutboundController::class, 'create'])->name('gudang.pengeluaran.create');
    Route::post('/gudang/pengeluaran', [OutboundController::class, 'store'])->name('gudang.pengeluaran.store');
    Route::get('/gudang/pengeluaran/{outbound}', [OutboundController::class, 'show'])->name('gudang.pengeluaran.show');
    Route::delete('/gudang/pengeluaran/{outbound}', [OutboundController::class, 'destroy'])->name('gudang.pengeluaran.destroy');

    // Transfer Stok
    Route::get('/gudang/transfer', [TransferController::class, 'index'])->name('gudang.transfer');
    Route::get('/gudang/transfer/requests', [TransferController::class, 'approvedRequests'])->name('gudang.transfer.requests');
    Route::post('/gudang/transfer/requests/{productRequest}/process', [TransferController::class, 'processFromRequest'])->name('gudang.transfer.process_request');
    Route::get('/gudang/transfer/create', [TransferController::class, 'create'])->name('gudang.transfer.create');
    Route::post('/gudang/transfer', [TransferController::class, 'store'])->name('gudang.transfer.store');
    Route::get('/gudang/transfer/{transfer}', [TransferController::class, 'show'])->name('gudang.transfer.show');
    Route::delete('/gudang/transfer/{transfer}', [TransferController::class, 'destroy'])->name('gudang.transfer.destroy');

    // Penerimaan Transfer (Cross-Check Admin 4)
    Route::get('/gudang/terima-transfer', [PenerimaanTransferController::class, 'index'])->name('gudang.terima_transfer.index');
    Route::get('/gudang/terima-transfer/{reference}', [PenerimaanTransferController::class, 'show'])->name('gudang.terima_transfer.show');
    Route::post('/gudang/terima-transfer/{reference}/receive', [PenerimaanTransferController::class, 'receive'])->name('gudang.terima_transfer.receive');
});

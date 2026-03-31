<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\SupplierDebtController;
use App\Http\Controllers\Pembelian\PurchaseOrderReceiptFollowupController;

// =========================================================
// PEMBELIAN / PURCHASE ORDER
// =========================================================

// Dashboard Pembelian
Route::get('/pembelian/dashboard', [PurchaseOrderController::class, 'dashboard'])
    ->name('pembelian.dashboard')
    ->middleware('can:view_purchase_order');

// Hutang Supplier
Route::get('/pembelian/hutang', [SupplierDebtController::class, 'index'])
    ->name('pembelian.hutang.index')
    ->middleware('can:view_hutang_supplier');
Route::get('/pembelian/hutang/create', [SupplierDebtController::class, 'create'])
    ->name('pembelian.hutang.create')
    ->middleware('can:create_hutang_supplier');
Route::post('/pembelian/hutang', [SupplierDebtController::class, 'store'])
    ->name('pembelian.hutang.store')
    ->middleware('can:create_hutang_supplier');
Route::get('/pembelian/hutang/{hutang}', [SupplierDebtController::class, 'show'])
    ->name('pembelian.hutang.show')
    ->middleware('can:view_hutang_supplier');
Route::post('/pembelian/hutang/{hutang}/pay', [SupplierDebtController::class, 'pay'])
    ->name('pembelian.hutang.pay')
    ->middleware('can:edit_hutang_supplier');
Route::delete('/pembelian/hutang/{hutang}', [SupplierDebtController::class, 'destroy'])
    ->name('pembelian.hutang.destroy')
    ->middleware('can:delete_hutang_supplier');

// Purchase Order
Route::get('/pembelian/order', [PurchaseOrderController::class, 'index'])
    ->name('pembelian.order')
    ->middleware('can:view_purchase_order');
Route::get('/pembelian/order/create', [PurchaseOrderController::class, 'create'])
    ->name('pembelian.order.create')
    ->middleware('can:create_purchase_order');
Route::post('/pembelian/order', [PurchaseOrderController::class, 'store'])
    ->name('pembelian.order.store')
    ->middleware('can:create_purchase_order');
Route::get('/pembelian/order/products/search', [PurchaseOrderController::class, 'searchProducts'])
    ->name('pembelian.order.products.search')
    ->middleware('can:view_purchase_order');
Route::get('/pembelian/order/{order}', [PurchaseOrderController::class, 'show'])
    ->name('pembelian.order.show')
    ->middleware('can:view_purchase_order');
Route::get('/pembelian/order/{order}/edit', [PurchaseOrderController::class, 'edit'])
    ->name('pembelian.order.edit')
    ->middleware('can:edit_purchase_order');
Route::get('/pembelian/order/{order}/append-items', [PurchaseOrderController::class, 'appendItems'])
    ->name('pembelian.order.append_items')
    ->middleware('can:edit_purchase_order');
Route::put('/pembelian/order/{order}', [PurchaseOrderController::class, 'update'])
    ->name('pembelian.order.update')
    ->middleware('can:edit_purchase_order');
Route::delete('/pembelian/order/{order}', [PurchaseOrderController::class, 'destroy'])
    ->name('pembelian.order.destroy')
    ->middleware('can:delete_purchase_order');
Route::get('/pembelian/order/{order}/receive', [PurchaseOrderController::class, 'receive'])
    ->name('pembelian.order.receive')
    ->middleware('can:edit_purchase_order');
Route::post('/pembelian/order/{order}/receive', [PurchaseOrderController::class, 'processReceive'])
    ->name('pembelian.order.process_receive')
    ->middleware('can:edit_purchase_order');
Route::post('/pembelian/order/{order}/status', [PurchaseOrderController::class, 'updateStatus'])
    ->name('pembelian.order.status')
    ->middleware('can:edit_purchase_order');

Route::get('/pembelian/receipts-followup', [PurchaseOrderReceiptFollowupController::class, 'index'])
    ->name('pembelian.receipts_followup.index')
    ->middleware(['can:view_purchase_order']);
Route::get('/pembelian/receipts-followup/{receipt}', [PurchaseOrderReceiptFollowupController::class, 'show'])
    ->name('pembelian.receipts_followup.show')
    ->middleware(['can:view_purchase_order']);
Route::post('/pembelian/receipts-followup/{receipt}/resolve', [PurchaseOrderReceiptFollowupController::class, 'resolve'])
    ->name('pembelian.receipts_followup.resolve')
    ->middleware(['can:edit_purchase_order']);
Route::post('/pembelian/receipts-followup/{receipt}/create-reorder-po', [PurchaseOrderReceiptFollowupController::class, 'createReorderPo'])
    ->name('pembelian.receipts_followup.create_reorder_po')
    ->middleware(['can:create_purchase_order']);
Route::post('/pembelian/receipts-followup/{receipt}/create-retur', [PurchaseOrderReceiptFollowupController::class, 'createReturn'])
    ->name('pembelian.receipts_followup.create_retur')
    ->middleware(['can:create_retur_pembelian']);
Route::get('/pembelian/riwayat', fn () => redirect()->route('pembelian.order'))
    ->name('pembelian.riwayat')
    ->middleware('can:view_purchase_order');

// Retur Pembelian
Route::get('/pembelian/retur', [PurchaseReturnController::class, 'index'])
    ->name('pembelian.retur.index')
    ->middleware('can:view_retur_pembelian');
Route::get('/pembelian/retur/create', [PurchaseReturnController::class, 'create'])
    ->name('pembelian.retur.create')
    ->middleware('can:create_retur_pembelian');
Route::post('/pembelian/retur', [PurchaseReturnController::class, 'store'])
    ->name('pembelian.retur.store')
    ->middleware('can:create_retur_pembelian');
Route::get('/pembelian/retur/{retur}', [PurchaseReturnController::class, 'show'])
    ->name('pembelian.retur.show')
    ->middleware('can:view_retur_pembelian');
Route::post('/pembelian/retur/{retur}/approve', [PurchaseReturnController::class, 'approve'])
    ->name('pembelian.retur.approve')
    ->middleware('can:edit_retur_pembelian');
Route::post('/pembelian/retur/{retur}/process', [PurchaseReturnController::class, 'process'])
    ->name('pembelian.retur.process')
    ->middleware('can:edit_retur_pembelian');
Route::post('/pembelian/retur/{retur}/cancel', [PurchaseReturnController::class, 'cancel'])
    ->name('pembelian.retur.cancel')
    ->middleware('can:edit_retur_pembelian');
Route::delete('/pembelian/retur/{retur}', [PurchaseReturnController::class, 'destroy'])
    ->name('pembelian.retur.destroy')
    ->middleware('can:delete_retur_pembelian');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Warehouse\WarehouseOrderController;

// =========================================================
// WAREHOUSE ORDER MANAGEMENT
// Menu untuk admin gudang melihat dan mengelola pesanan
// =========================================================

Route::middleware(['auth', 'can:view_warehouse_orders'])->group(function () {
    // Dashboard pesanan gudang
    Route::get('/warehouse/orders', [WarehouseOrderController::class, 'index'])->name('warehouse.orders.index');
    
    // Detail pesanan
    Route::get('/warehouse/orders/{order}', [WarehouseOrderController::class, 'show'])->name('warehouse.orders.show');
    
    // Proses packing
    Route::post('/warehouse/orders/{order}/start-packing', [WarehouseOrderController::class, 'startPacking'])->name('warehouse.orders.start_packing');
    Route::post('/warehouse/orders/{order}/finish-packing', [WarehouseOrderController::class, 'finishPacking'])->name('warehouse.orders.finish_packing');
    
    // Cross-check admin
    Route::post('/warehouse/orders/{order}/cross-check', [WarehouseOrderController::class, 'crossCheck'])->name('warehouse.orders.cross_check');
    
    // Konfirmasi pengiriman
    Route::post('/warehouse/orders/{order}/confirm-delivery', [WarehouseOrderController::class, 'confirmDelivery'])->name('warehouse.orders.confirm_delivery');
});

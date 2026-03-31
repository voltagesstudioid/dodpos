<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesOrderController;

// =========================================================
// SALES ORDER
// =========================================================

Route::get('/penjualan/sales-order/products/search', [SalesOrderController::class, 'searchProducts'])
    ->middleware('can:view_sales_order')
    ->name('sales-order.products.search');

Route::get('/penjualan/sales-order', [SalesOrderController::class, 'index'])
    ->middleware('can:view_sales_order')
    ->name('sales-order.index');

Route::get('/penjualan/sales-order/create', [SalesOrderController::class, 'create'])
    ->middleware('can:create_sales_order')
    ->name('sales-order.create');

Route::post('/penjualan/sales-order', [SalesOrderController::class, 'store'])
    ->middleware('can:create_sales_order')
    ->name('sales-order.store');

Route::get('/penjualan/sales-order/{sales_order}/edit', [SalesOrderController::class, 'edit'])
    ->whereNumber('sales_order')
    ->middleware('can:edit_sales_order')
    ->name('sales-order.edit');

Route::put('/penjualan/sales-order/{sales_order}', [SalesOrderController::class, 'update'])
    ->whereNumber('sales_order')
    ->middleware('can:edit_sales_order')
    ->name('sales-order.update');

Route::delete('/penjualan/sales-order/{sales_order}', [SalesOrderController::class, 'destroy'])
    ->whereNumber('sales_order')
    ->middleware('can:delete_sales_order')
    ->name('sales-order.destroy');

Route::get('/penjualan/sales-order/{sales_order}', [SalesOrderController::class, 'show'])
    ->whereNumber('sales_order')
    ->middleware('can:view_sales_order')
    ->name('sales-order.show');

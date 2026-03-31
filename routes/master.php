<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\BrandController;

// =========================================================
// MASTER DATA
// =========================================================

// Produk (perizinan spesifik)
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index')
    ->middleware('can:view_master_produk');
Route::get('/products/create', [ProductController::class, 'create'])
    ->name('products.create')
    ->middleware('can:create_master_produk');
Route::post('/products', [ProductController::class, 'store'])
    ->name('products.store')
    ->middleware('can:create_master_produk');
Route::get('/products/import', [ProductController::class, 'importForm'])
    ->name('products.import')
    ->middleware('can:create_master_produk');
Route::post('/products/import', [ProductController::class, 'importProcess'])
    ->name('products.import.process')
    ->middleware('can:create_master_produk');
Route::get('/products/template', [ProductController::class, 'downloadTemplate'])
    ->name('products.template')
    ->middleware('can:create_master_produk');
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show')
    ->middleware('can:view_master_produk');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
    ->name('products.edit')
    ->middleware('can:edit_master_produk');
Route::put('/products/{product}', [ProductController::class, 'update'])
    ->name('products.update')
    ->middleware('can:edit_master_produk');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->name('products.destroy')
    ->middleware('can:delete_master_produk');

// Kategori
Route::get('/master/kategori', [KategoriController::class, 'index'])
    ->name('master.kategori')
    ->middleware('can:view_master_kategori');
Route::get('/master/kategori/create', [KategoriController::class, 'create'])
    ->name('master.kategori.create')
    ->middleware('can:create_master_kategori');
Route::post('/master/kategori', [KategoriController::class, 'store'])
    ->name('master.kategori.store')
    ->middleware('can:create_master_kategori');
Route::get('/master/kategori/{kategori}/edit', [KategoriController::class, 'edit'])
    ->name('master.kategori.edit')
    ->middleware('can:edit_master_kategori');
Route::put('/master/kategori/{kategori}', [KategoriController::class, 'update'])
    ->name('master.kategori.update')
    ->middleware('can:edit_master_kategori');
Route::delete('/master/kategori/{kategori}', [KategoriController::class, 'destroy'])
    ->name('master.kategori.destroy')
    ->middleware('can:delete_master_kategori');

// Satuan
Route::get('/master/satuan', [UnitController::class, 'index'])
    ->name('master.satuan')
    ->middleware('can:view_master_satuan');
Route::get('/master/satuan/create', [UnitController::class, 'create'])
    ->name('master.satuan.create')
    ->middleware('can:create_master_satuan');
Route::post('/master/satuan', [UnitController::class, 'store'])
    ->name('master.satuan.store')
    ->middleware('can:create_master_satuan');
Route::get('/master/satuan/{satuan}/edit', [UnitController::class, 'edit'])
    ->name('master.satuan.edit')
    ->middleware('can:edit_master_satuan');
Route::put('/master/satuan/{satuan}', [UnitController::class, 'update'])
    ->name('master.satuan.update')
    ->middleware('can:edit_master_satuan');
Route::delete('/master/satuan/{satuan}', [UnitController::class, 'destroy'])
    ->name('master.satuan.destroy')
    ->middleware('can:delete_master_satuan');

// Supplier
Route::get('/master/supplier', [SupplierController::class, 'index'])
    ->name('master.supplier')
    ->middleware('can:view_master_supplier');
Route::get('/master/supplier/create', [SupplierController::class, 'create'])
    ->name('master.supplier.create')
    ->middleware('can:create_master_supplier');
Route::post('/master/supplier', [SupplierController::class, 'store'])
    ->name('master.supplier.store')
    ->middleware('can:create_master_supplier');
Route::get('/master/supplier/{supplier}/edit', [SupplierController::class, 'edit'])
    ->name('master.supplier.edit')
    ->middleware('can:edit_master_supplier');
Route::put('/master/supplier/{supplier}', [SupplierController::class, 'update'])
    ->name('master.supplier.update')
    ->middleware('can:edit_master_supplier');
Route::delete('/master/supplier/{supplier}', [SupplierController::class, 'destroy'])
    ->name('master.supplier.destroy')
    ->middleware('can:delete_master_supplier');

// Gudang
Route::get('/master/gudang', [WarehouseController::class, 'index'])
    ->name('master.gudang')
    ->middleware('can:view_master_gudang');
Route::get('/master/gudang/create', [WarehouseController::class, 'create'])
    ->name('master.gudang.create')
    ->middleware('can:create_master_gudang');
Route::post('/master/gudang', [WarehouseController::class, 'store'])
    ->name('master.gudang.store')
    ->middleware('can:create_master_gudang');
Route::get('/master/gudang/{gudang}/edit', [WarehouseController::class, 'edit'])
    ->name('master.gudang.edit')
    ->middleware('can:edit_master_gudang');
Route::put('/master/gudang/{gudang}', [WarehouseController::class, 'update'])
    ->name('master.gudang.update')
    ->middleware('can:edit_master_gudang');
Route::delete('/master/gudang/{gudang}', [WarehouseController::class, 'destroy'])
    ->name('master.gudang.destroy')
    ->middleware('can:delete_master_gudang');

// Brand
Route::resource('/master/brand', BrandController::class)
    ->names('master.brand')
    ->middleware('can:view_master_produk');

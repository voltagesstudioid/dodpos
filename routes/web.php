<?php

use App\Http\Controllers\Gudang\OpnameSessionController;
use App\Http\Controllers\InboundController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OutboundController;
use App\Http\Controllers\Pembelian\PurchaseOrderReceiptFollowupController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierDebtController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'active', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'active'])->group(function () {

    // =========================================================
    // PROFILE — semua user yang login
    // =========================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/photo/{user}', [ProfileController::class, 'photo'])->name('profile.photo');

    // =========================================================
    // MASTER ROLES — khusus supervisor
    // =========================================================
    Route::prefix('/pengaturan/roles')->middleware('role:supervisor')->group(function () {
        Route::get('/', [\App\Http\Controllers\Settings\AppRoleController::class, 'index'])
            ->name('pengaturan.roles.index');
        Route::get('/migrate', [\App\Http\Controllers\Settings\AppRoleController::class, 'migrate'])
            ->name('pengaturan.roles.migrate');
        Route::post('/migrate', [\App\Http\Controllers\Settings\AppRoleController::class, 'migrateStore'])
            ->name('pengaturan.roles.migrate.store');
        Route::get('/create', [\App\Http\Controllers\Settings\AppRoleController::class, 'create'])
            ->name('pengaturan.roles.create');
        Route::post('/', [\App\Http\Controllers\Settings\AppRoleController::class, 'store'])
            ->name('pengaturan.roles.store');
        Route::get('/{role}/edit', [\App\Http\Controllers\Settings\AppRoleController::class, 'edit'])
            ->name('pengaturan.roles.edit');
        Route::put('/{role}', [\App\Http\Controllers\Settings\AppRoleController::class, 'update'])
            ->name('pengaturan.roles.update');
        Route::delete('/{role}', [\App\Http\Controllers\Settings\AppRoleController::class, 'destroy'])
            ->name('pengaturan.roles.destroy');
    });

    // =========================================================
    // PRINTING ROUTES
    // =========================================================
    Route::get('/print/receipt/{transaction}', [PrintController::class, 'printReceipt'])->name('print.receipt');
    Route::get('/print/faktur-grosir/{transaction}', [PrintController::class, 'printFakturGrosir'])->name('print.faktur_grosir');
    Route::get('/print/purchase/{order}', [PrintController::class, 'printPurchase'])
        ->name('print.purchase')
        ->middleware('can:view_laporan_pembelian');
    Route::get('/print/return/{retur}', [PrintController::class, 'printReturn'])->name('print.return');

    // =========================================================
    // ADMIN SALES DASHBOARD
    // =========================================================
    Route::get('/admin-sales', [\App\Http\Controllers\AdminSalesController::class, 'index'])
        ->middleware('can:view_dashboard')
        ->name('admin-sales.dashboard');

    // =========================================================
    // POINT OF SALE — akses kasir (tanpa buka/tutup sesi)
    // =========================================================
    Route::middleware('can:view_pos_kasir')->group(function () {
        Route::get('/kasir', [\App\Http\Controllers\KasirController::class, 'index'])->name('kasir.index');

        // Eceran
        Route::get('/kasir/eceran', [\App\Http\Controllers\KasirEceranController::class, 'index'])->name('kasir.eceran');
        Route::get('/kasir/eceran/search-products', [\App\Http\Controllers\KasirEceranController::class, 'searchProducts'])->name('kasir.eceran.search_products');
        Route::get('/kasir/eceran/search-customers', [\App\Http\Controllers\KasirEceranController::class, 'searchCustomers'])->name('kasir.eceran.search_customers');
        Route::post('/kasir/eceran', [\App\Http\Controllers\KasirEceranController::class, 'store'])->name('kasir.eceran.store');

        // Grosir
        Route::get('/kasir/grosir', [\App\Http\Controllers\KasirGrosirController::class, 'index'])->name('kasir.grosir');
        Route::get('/kasir/grosir/search-products', [\App\Http\Controllers\KasirGrosirController::class, 'searchProducts'])->name('kasir.grosir.search_products');
        Route::get('/kasir/grosir/search-customers', [\App\Http\Controllers\KasirGrosirController::class, 'searchCustomers'])->name('kasir.grosir.search_customers');
        Route::post('/kasir/grosir', [\App\Http\Controllers\KasirGrosirController::class, 'store'])->name('kasir.grosir.store');
        Route::post('/kasir/transaksi', [\App\Http\Controllers\KasirController::class, 'storeTransaksi'])->name('kasir.transaksi.store');
    });

    Route::middleware('can:view_sesi_kasir')->group(function () {
        Route::get('/kasir/sesi', [\App\Http\Controllers\KasirController::class, 'session'])->name('kasir.session');
    });

    Route::middleware('can:delete_sesi_kasir')->group(function () {
        Route::post('/kasir/cash-movement', [\App\Http\Controllers\KasirController::class, 'addCashMovement'])->name('kasir.cash_movement');
        Route::post('/kasir/open-session', [\App\Http\Controllers\KasirController::class, 'openSession'])->name('kasir.open_session');
        Route::post('/kasir/close-session', [\App\Http\Controllers\KasirController::class, 'closeSession'])->name('kasir.close_session');
    });

    // Transaksi — kasir, admin, admin1, admin2
    Route::middleware('can:view_transaksi')->group(function () {
        Route::get('/transaksi', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/riwayat', fn () => redirect()->route('transaksi.index'))->name('transaksi.riwayat');
        Route::get('/transaksi/{transaksi}', [\App\Http\Controllers\TransactionController::class, 'show'])->name('transaksi.show');
        Route::patch('/transaksi/{transaksi}/void', [\App\Http\Controllers\TransactionController::class, 'destroy'])->name('transaksi.void');

        Route::get('/transaksi/retur/{retur}', [\App\Http\Controllers\PosReturnController::class, 'show'])->name('transaksi.retur.show');
    });

    Route::middleware('can:edit_transaksi')->group(function () {
        Route::get('/transaksi/{transaksi}/retur', [\App\Http\Controllers\PosReturnController::class, 'create'])->name('transaksi.retur.create');
        Route::post('/transaksi/{transaksi}/retur', [\App\Http\Controllers\PosReturnController::class, 'store'])->name('transaksi.retur.store');
    });

    // Pelanggan (Customer) — kasir, admin, admin_sales, admin1
    Route::middleware('can:view_pelanggan')->group(function () {
        Route::get('/pelanggan', [\App\Http\Controllers\CustomerController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/create', [\App\Http\Controllers\CustomerController::class, 'create'])->name('pelanggan.create');
        Route::post('/pelanggan', [\App\Http\Controllers\CustomerController::class, 'store'])->name('pelanggan.store');
        Route::get('/pelanggan/{pelanggan}', [\App\Http\Controllers\CustomerController::class, 'show'])->name('pelanggan.show');
        Route::get('/pelanggan/{pelanggan}/edit', [\App\Http\Controllers\CustomerController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/pelanggan/{pelanggan}', [\App\Http\Controllers\CustomerController::class, 'update'])->name('pelanggan.update');
        Route::delete('/pelanggan/{pelanggan}', [\App\Http\Controllers\CustomerController::class, 'destroy'])->name('pelanggan.destroy');
    });

    Route::middleware('can:view_hutang_piutang')->group(function () {
        Route::get('/hutang-piutang', [\App\Http\Controllers\CustomerCreditController::class, 'index'])->name('hutang.index');
        Route::get('/hutang-piutang/{kredit}', [\App\Http\Controllers\CustomerCreditController::class, 'show'])->name('pelanggan.kredit.show');
        Route::get('/hutang-piutang/list', [\App\Http\Controllers\CustomerCreditController::class, 'index'])->name('pelanggan.kredit.index');
    });

    Route::middleware('can:create_hutang_piutang')->group(function () {
        Route::get('/hutang-piutang/create', [\App\Http\Controllers\CustomerCreditController::class, 'create'])->name('pelanggan.kredit.create');
        Route::post('/hutang-piutang', [\App\Http\Controllers\CustomerCreditController::class, 'store'])->name('pelanggan.kredit.store');
        Route::post('/hutang-piutang/{kredit}/pay', [\App\Http\Controllers\CustomerCreditController::class, 'pay'])->name('pelanggan.kredit.pay');
    });

    Route::middleware('can:delete_hutang_piutang')->group(function () {
        Route::delete('/hutang-piutang/{kredit}', [\App\Http\Controllers\CustomerCreditController::class, 'destroy'])->name('pelanggan.kredit.destroy');
    });

    Route::middleware('can:view_daftar_harga')->group(function () {
        Route::get('/harga', [\App\Http\Controllers\HargaController::class, 'index'])->name('harga.index');
    });

    // =========================================================
    // MASTER DATA — admin, admin4
    // =========================================================
    // Produk (perizinan spesifik)
    Route::get('products', [ProductController::class, 'index'])
        ->name('products.index')
        ->middleware('can:view_master_produk');
    Route::get('products/create', [ProductController::class, 'create'])
        ->name('products.create')
        ->middleware('can:create_master_produk');
    Route::post('products', [ProductController::class, 'store'])
        ->name('products.store')
        ->middleware('can:create_master_produk');
    Route::get('products/import', [ProductController::class, 'importForm'])
        ->name('products.import')
        ->middleware('can:create_master_produk');
    Route::post('products/import', [ProductController::class, 'importProcess'])
        ->name('products.import.process')
        ->middleware('can:create_master_produk');
    Route::get('products/template', [ProductController::class, 'downloadTemplate'])
        ->name('products.template')
        ->middleware('can:create_master_produk');
    Route::get('products/{product}', [ProductController::class, 'show'])
        ->name('products.show')
        ->middleware('can:view_master_produk');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])
        ->name('products.edit')
        ->middleware('can:edit_master_produk');
    Route::put('products/{product}', [ProductController::class, 'update'])
        ->name('products.update')
        ->middleware('can:edit_master_produk');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])
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

    // =========================================================
    // MANAJEMEN GUDANG
    // =========================================================
    Route::middleware('can:view_stok_gudang')->group(function () {
        Route::get('/gudang/stok', [StockReportController::class, 'index'])->name('gudang.stok');
        Route::get('/gudang/stok-semua', [\App\Http\Controllers\LaporanController::class, 'stok'])->name('gudang.stok-semua');

        // Permintaan Barang (PO / Transfer)
        Route::get('/gudang/request', [\App\Http\Controllers\ProductRequestController::class, 'index'])->name('gudang.request.index');
        Route::get('/gudang/request/create', [\App\Http\Controllers\ProductRequestController::class, 'create'])->name('gudang.request.create');
        Route::post('/gudang/request', [\App\Http\Controllers\ProductRequestController::class, 'store'])->name('gudang.request.store');
        Route::put('/gudang/request/{productRequest}/status', [\App\Http\Controllers\ProductRequestController::class, 'updateStatus'])->name('gudang.request.update_status');
    });

    Route::middleware('can:view_penerimaan_barang')->group(function () {
        Route::get('/gudang/expired', [StockReportController::class, 'expired'])->name('gudang.expired');
        Route::get('/gudang/minstok', [StockReportController::class, 'minimumStock'])->name('gudang.minstok');

        // Penerimaan Barang (Inbound)
        Route::get('/gudang/penerimaan', [InboundController::class, 'index'])->name('gudang.penerimaan');
        Route::get('/gudang/penerimaan/create', [InboundController::class, 'create'])->name('gudang.penerimaan.create');
        Route::post('/gudang/penerimaan', [InboundController::class, 'store'])->name('gudang.penerimaan.store');
        Route::get('/gudang/penerimaan/{inbound}', [InboundController::class, 'show'])->name('gudang.penerimaan.show');
        Route::delete('/gudang/penerimaan/{inbound}', [InboundController::class, 'destroy'])->name('gudang.penerimaan.destroy');

        Route::get('/gudang/masuk-keluar', [\App\Http\Controllers\Reports\StockInOutController::class, 'index'])->name('gudang.inout');

        // Penerimaan PO dari Supplier (Blind Receipt)
        Route::get('/gudang/terima-po', [\App\Http\Controllers\Gudang\WarehouseReceiptController::class, 'index'])->name('gudang.terimapo.index');
        Route::get('/gudang/terima-po/{order}', [\App\Http\Controllers\Gudang\WarehouseReceiptController::class, 'show'])->name('gudang.terimapo.show');
        Route::post('/gudang/terima-po/{order}', [\App\Http\Controllers\Gudang\WarehouseReceiptController::class, 'store'])->name('gudang.terimapo.store');
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
        Route::get('/', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'index'])->name('gudang.opname_approval.index');
        Route::get('/{session}', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'show'])->name('gudang.opname_approval.show');
        Route::post('/{session}/approve', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'approve'])->name('gudang.opname_approval.approve');
        Route::post('/{session}/reject', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'reject'])->name('gudang.opname_approval.reject');
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
        Route::get('/gudang/terima-transfer', [\App\Http\Controllers\Gudang\PenerimaanTransferController::class, 'index'])->name('gudang.terima_transfer.index');
        Route::get('/gudang/terima-transfer/{reference}', [\App\Http\Controllers\Gudang\PenerimaanTransferController::class, 'show'])->name('gudang.terima_transfer.show');
        Route::post('/gudang/terima-transfer/{reference}/receive', [\App\Http\Controllers\Gudang\PenerimaanTransferController::class, 'receive'])->name('gudang.terima_transfer.receive');
    });

    // =========================================================
    // MODUL GULA — admin, admin_sales, admin1 (for setoran)
    // =========================================================
    Route::prefix('gula')->name('gula.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Gula\DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('can:view_gula_stok');

        Route::resource('stok', \App\Http\Controllers\Gula\StokController::class)
            ->only(['index'])
            ->middleware('can:view_gula_stok');
        Route::resource('stok', \App\Http\Controllers\Gula\StokController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_gula_stok');
        Route::resource('stok', \App\Http\Controllers\Gula\StokController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_gula_stok');
        Route::resource('stok', \App\Http\Controllers\Gula\StokController::class)
            ->only(['destroy'])
            ->middleware('can:delete_gula_stok');

        Route::resource('repacking', \App\Http\Controllers\Gula\RepackingController::class)
            ->only(['index', 'show'])
            ->middleware('can:view_gula_repacking');
        Route::resource('repacking', \App\Http\Controllers\Gula\RepackingController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_gula_repacking');
        Route::resource('repacking', \App\Http\Controllers\Gula\RepackingController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_gula_repacking');
        Route::resource('repacking', \App\Http\Controllers\Gula\RepackingController::class)
            ->only(['destroy'])
            ->middleware('can:delete_gula_repacking');

        Route::resource('loading', \App\Http\Controllers\Gula\LoadingController::class)
            ->only(['index', 'show'])
            ->middleware('can:view_gula_loading');
        Route::resource('loading', \App\Http\Controllers\Gula\LoadingController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_gula_loading');
        Route::resource('loading', \App\Http\Controllers\Gula\LoadingController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_gula_loading');
        Route::resource('loading', \App\Http\Controllers\Gula\LoadingController::class)
            ->only(['destroy'])
            ->middleware('can:delete_gula_loading');

        Route::get('/setoran', [\App\Http\Controllers\Gula\SetoranController::class, 'index'])
            ->name('setoran.index')
            ->middleware('can:view_gula_setoran');
        Route::get('/setoran/{setoran}', [\App\Http\Controllers\Gula\SetoranController::class, 'show'])
            ->name('setoran.show')
            ->middleware('can:view_gula_setoran');
        Route::post('/setoran/{setoran}/verify', [\App\Http\Controllers\Gula\SetoranController::class, 'verify'])
            ->name('setoran.verify')
            ->middleware('can:edit_gula_setoran');
    });

    // =========================================================
    // MODUL MINERAL — admin, admin_sales, admin1 (for setoran)
    // =========================================================
    Route::prefix('mineral')->name('mineral.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Mineral\DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('can:view_mineral_stok');

        Route::resource('stok', \App\Http\Controllers\Mineral\StokController::class)
            ->only(['index'])
            ->middleware('can:view_mineral_stok');
        Route::resource('stok', \App\Http\Controllers\Mineral\StokController::class)
            ->only(['store'])
            ->middleware('can:create_mineral_stok');

        Route::resource('loading', \App\Http\Controllers\Mineral\LoadingController::class)
            ->only(['index', 'show'])
            ->middleware('can:view_mineral_loading');
        Route::resource('loading', \App\Http\Controllers\Mineral\LoadingController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_mineral_loading');

        Route::get('/setoran', [\App\Http\Controllers\Mineral\SetoranController::class, 'index'])
            ->name('setoran.index')
            ->middleware('can:view_mineral_setoran');
        Route::get('/setoran/{setoran}', [\App\Http\Controllers\Mineral\SetoranController::class, 'show'])
            ->name('setoran.show')
            ->middleware('can:view_mineral_setoran');
        Route::post('/setoran/{setoran}/verify', [\App\Http\Controllers\Mineral\SetoranController::class, 'verify'])
            ->name('setoran.verify')
            ->middleware('can:edit_mineral_setoran');
    });

    // =========================================================
    // SALES ORDER — admin, admin_sales
    // =========================================================
    Route::get('penjualan/sales-order/products/search', [\App\Http\Controllers\SalesOrderController::class, 'searchProducts'])
        ->middleware('can:view_sales_order')
        ->name('sales-order.products.search');

    Route::get('penjualan/sales-order', [\App\Http\Controllers\SalesOrderController::class, 'index'])
        ->middleware('can:view_sales_order')
        ->name('sales-order.index');

    Route::get('penjualan/sales-order/create', [\App\Http\Controllers\SalesOrderController::class, 'create'])
        ->middleware('can:create_sales_order')
        ->name('sales-order.create');

    Route::post('penjualan/sales-order', [\App\Http\Controllers\SalesOrderController::class, 'store'])
        ->middleware('can:create_sales_order')
        ->name('sales-order.store');

    Route::get('penjualan/sales-order/{sales_order}/edit', [\App\Http\Controllers\SalesOrderController::class, 'edit'])
        ->whereNumber('sales_order')
        ->middleware('can:edit_sales_order')
        ->name('sales-order.edit');

    Route::put('penjualan/sales-order/{sales_order}', [\App\Http\Controllers\SalesOrderController::class, 'update'])
        ->whereNumber('sales_order')
        ->middleware('can:edit_sales_order')
        ->name('sales-order.update');

    Route::delete('penjualan/sales-order/{sales_order}', [\App\Http\Controllers\SalesOrderController::class, 'destroy'])
        ->whereNumber('sales_order')
        ->middleware('can:delete_sales_order')
        ->name('sales-order.destroy');

    Route::get('penjualan/sales-order/{sales_order}', [\App\Http\Controllers\SalesOrderController::class, 'show'])
        ->whereNumber('sales_order')
        ->middleware('can:view_sales_order')
        ->name('sales-order.show');

    // =========================================================
    // PEMBELIAN / PURCHASE ORDER — admin, gudang
    // =========================================================
    // Hutang Supplier
    Route::get('/pembelian/hutang', [SupplierDebtController::class, 'index'])
        ->name('pembelian.hutang.index')
        ->middleware('can:view_hutang_supplier');
    Route::get('/pembelian/hutang/create', [\App\Http\Controllers\SupplierDebtController::class, 'create'])
        ->name('pembelian.hutang.create')
        ->middleware('can:create_hutang_supplier');
    Route::post('/pembelian/hutang', [\App\Http\Controllers\SupplierDebtController::class, 'store'])
        ->name('pembelian.hutang.store')
        ->middleware('can:create_hutang_supplier');
    Route::get('/pembelian/hutang/{hutang}', [\App\Http\Controllers\SupplierDebtController::class, 'show'])
        ->name('pembelian.hutang.show')
        ->middleware('can:view_hutang_supplier');
    Route::post('/pembelian/hutang/{hutang}/pay', [SupplierDebtController::class, 'pay'])
        ->name('pembelian.hutang.pay')
        ->middleware('can:edit_hutang_supplier');
    Route::delete('/pembelian/hutang/{hutang}', [\App\Http\Controllers\SupplierDebtController::class, 'destroy'])
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
        ->middleware(['role:supervisor', 'can:view_purchase_order']);
    Route::get('/pembelian/receipts-followup/{receipt}', [PurchaseOrderReceiptFollowupController::class, 'show'])
        ->name('pembelian.receipts_followup.show')
        ->middleware(['role:supervisor', 'can:view_purchase_order']);
    Route::post('/pembelian/receipts-followup/{receipt}/resolve', [PurchaseOrderReceiptFollowupController::class, 'resolve'])
        ->name('pembelian.receipts_followup.resolve')
        ->middleware(['role:supervisor', 'can:view_purchase_order']);
    Route::post('/pembelian/receipts-followup/{receipt}/create-reorder-po', [PurchaseOrderReceiptFollowupController::class, 'createReorderPo'])
        ->name('pembelian.receipts_followup.create_reorder_po')
        ->middleware(['role:supervisor', 'can:create_purchase_order']);
    Route::post('/pembelian/receipts-followup/{receipt}/create-retur', [PurchaseOrderReceiptFollowupController::class, 'createReturn'])
        ->name('pembelian.receipts_followup.create_retur')
        ->middleware(['role:supervisor', 'can:create_retur_pembelian']);
    Route::get('/pembelian/riwayat', fn () => redirect()->route('pembelian.order'))
        ->name('pembelian.riwayat')
        ->middleware('can:view_purchase_order');

    // Retur Pembelian
    Route::get('/pembelian/retur', [\App\Http\Controllers\PurchaseReturnController::class, 'index'])
        ->name('pembelian.retur.index')
        ->middleware('can:view_retur_pembelian');
    Route::get('/pembelian/retur/create', [\App\Http\Controllers\PurchaseReturnController::class, 'create'])
        ->name('pembelian.retur.create')
        ->middleware('can:create_retur_pembelian');
    Route::post('/pembelian/retur', [\App\Http\Controllers\PurchaseReturnController::class, 'store'])
        ->name('pembelian.retur.store')
        ->middleware('can:create_retur_pembelian');
    Route::get('/pembelian/retur/{retur}', [\App\Http\Controllers\PurchaseReturnController::class, 'show'])
        ->name('pembelian.retur.show')
        ->middleware('can:view_retur_pembelian');
    Route::post('/pembelian/retur/{retur}/approve', [\App\Http\Controllers\PurchaseReturnController::class, 'approve'])
        ->name('pembelian.retur.approve')
        ->middleware('can:edit_retur_pembelian');
    Route::post('/pembelian/retur/{retur}/process', [\App\Http\Controllers\PurchaseReturnController::class, 'process'])
        ->name('pembelian.retur.process')
        ->middleware('can:edit_retur_pembelian');
    Route::post('/pembelian/retur/{retur}/cancel', [\App\Http\Controllers\PurchaseReturnController::class, 'cancel'])
        ->name('pembelian.retur.cancel')
        ->middleware('can:edit_retur_pembelian');
    Route::delete('/pembelian/retur/{retur}', [\App\Http\Controllers\PurchaseReturnController::class, 'destroy'])
        ->name('pembelian.retur.destroy')
        ->middleware('can:delete_retur_pembelian');

    // =========================================================
    // LAPORAN — admin, admin_sales, admin1
    // =========================================================
    // ----- LAPORAN -----
    // Laporan Penjualan (Supervisor, Admin1, Admin2)
    Route::middleware(['can:view_laporan_penjualan'])->group(function () {
        Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
    });

    // Laporan Keuangan (Supervisor, Admin2)
    Route::middleware(['can:view_laporan_keuangan'])->group(function () {
        Route::get('/laporan/keuangan', [LaporanController::class, 'keuangan'])->name('laporan.keuangan');
    });

    // Laporan Pelanggan (Supervisor, Admin1)
    Route::middleware(['can:view_laporan_pelanggan'])->group(function () {
        Route::get('/laporan/pelanggan', [LaporanController::class, 'pelanggan'])->name('laporan.pelanggan');
    });

    // Supplier juga bisa diakses admin2, admin4
    Route::middleware(['can:view_laporan_supplier'])->group(function () {
        Route::get('/laporan/supplier', [LaporanController::class, 'supplier'])->name('laporan.supplier');
    });

    Route::middleware(['can:view_laporan_pembelian'])->group(function () {
        Route::get('/laporan/pembelian', [LaporanController::class, 'pembelian'])->name('laporan.pembelian');
    });

    Route::middleware(['can:view_laporan_stok'])->group(function () {
        Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    });

    // =========================================================
    // KEUANGAN / OPERASIONAL — admin, admin2
    // =========================================================
    Route::name('operasional.')->group(function () {
        Route::resource('kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['index'])
            ->middleware('can:view_kategori_operasional');
        Route::resource('kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_kategori_operasional');
        Route::resource('kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_kategori_operasional');
        Route::resource('kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['destroy'])
            ->middleware('can:delete_kategori_operasional');

        Route::resource('kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['index'])
            ->middleware('can:view_kendaraan_operasional');
        Route::resource('kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_kendaraan_operasional');
        Route::resource('kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_kendaraan_operasional');
        Route::resource('kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['destroy'])
            ->middleware('can:delete_kendaraan_operasional');

        Route::post('open-session', [\App\Http\Controllers\OperationalExpenseController::class, 'openSession'])
            ->name('open_session')
            ->middleware('can:manage_sesi_operasional');
        Route::post('close-session', [\App\Http\Controllers\OperationalExpenseController::class, 'closeSession'])
            ->name('close_session')
            ->middleware('can:manage_sesi_operasional');
        Route::get('sesi', [\App\Http\Controllers\OperationalExpenseController::class, 'sessions'])
            ->name('sesi.index')
            ->middleware('can:view_sesi_operasional');

        Route::get('riwayat', [\App\Http\Controllers\OperationalExpenseController::class, 'index'])
            ->name('riwayat.index')
            ->middleware('can:view_riwayat_operasional');

        Route::get('pengeluaran/create', [\App\Http\Controllers\OperationalExpenseController::class, 'create'])
            ->name('pengeluaran.create')
            ->middleware('can:create_pengeluaran_operasional');
        Route::post('pengeluaran', [\App\Http\Controllers\OperationalExpenseController::class, 'store'])
            ->name('pengeluaran.store')
            ->middleware('can:create_pengeluaran_operasional');
        Route::get('pengeluaran/{pengeluaran}/edit', [\App\Http\Controllers\OperationalExpenseController::class, 'edit'])
            ->name('pengeluaran.edit')
            ->middleware('can:edit_pengeluaran_operasional');
        Route::put('pengeluaran/{pengeluaran}', [\App\Http\Controllers\OperationalExpenseController::class, 'update'])
            ->name('pengeluaran.update')
            ->middleware('can:edit_pengeluaran_operasional');
        Route::delete('pengeluaran/{pengeluaran}', [\App\Http\Controllers\OperationalExpenseController::class, 'destroy'])
            ->name('pengeluaran.destroy')
            ->middleware('can:delete_pengeluaran_operasional');
    });

    // =========================================================
    // SDM / HR — supervisor
    // =========================================================
    Route::prefix('sdm')->name('sdm.')->group(function () {
        Route::middleware('can:view_karyawan')->group(function () {
            Route::get('/karyawan', [\App\Http\Controllers\Sdm\EmployeeController::class, 'index'])->name('karyawan.index');
            Route::get('/karyawan/export', [\App\Http\Controllers\Sdm\EmployeeController::class, 'export'])->name('karyawan.export');
            Route::get('/karyawan/{karyawan}', [\App\Http\Controllers\Sdm\EmployeeController::class, 'show'])->name('karyawan.show');
        });

        Route::middleware('can:create_karyawan')->group(function () {
            Route::get('/karyawan/create', [\App\Http\Controllers\Sdm\EmployeeController::class, 'create'])->name('karyawan.create');
            Route::post('/karyawan', [\App\Http\Controllers\Sdm\EmployeeController::class, 'store'])->name('karyawan.store');
            Route::post('/karyawan/import', [\App\Http\Controllers\Sdm\EmployeeController::class, 'importFromAccounts'])->name('karyawan.import');
        });

        Route::middleware('can:edit_karyawan')->group(function () {
            Route::get('/karyawan/{karyawan}/edit', [\App\Http\Controllers\Sdm\EmployeeController::class, 'edit'])->name('karyawan.edit');
            Route::put('/karyawan/{karyawan}', [\App\Http\Controllers\Sdm\EmployeeController::class, 'update'])->name('karyawan.update');
            Route::post('/karyawan/{karyawan}/link-user', [\App\Http\Controllers\Sdm\EmployeeController::class, 'linkUser'])->name('karyawan.link_user');
            Route::post('/karyawan/{karyawan}/unlink-user', [\App\Http\Controllers\Sdm\EmployeeController::class, 'unlinkUser'])->name('karyawan.unlink_user');
        });

        Route::middleware('can:view_absensi')->group(function () {
            Route::get('/absensi', [\App\Http\Controllers\Sdm\AttendanceController::class, 'index'])->name('absensi.index');
            Route::get('/absensi/bulanan', [\App\Http\Controllers\Sdm\AttendanceController::class, 'monthly'])->name('absensi.monthly');
            Route::get('/absensi/bulanan/export', [\App\Http\Controllers\Sdm\AttendanceController::class, 'monthlyExport'])->name('absensi.monthly.export');
            Route::get('/absensi/{attendance}/selfie/{type}', [\App\Http\Controllers\Sdm\AttendanceController::class, 'selfie'])->name('absensi.selfie');
            Route::get('/cuti', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'index'])->name('cuti.index');
            Route::get('/libur', [\App\Http\Controllers\Sdm\HolidayController::class, 'index'])->name('libur.index');
        });

        Route::middleware('can:create_absensi')->group(function () {
            Route::post('/absensi/sync', [\App\Http\Controllers\Sdm\AttendanceController::class, 'sync'])->name('absensi.sync');
            Route::post('/absensi/link-user', [\App\Http\Controllers\Sdm\AttendanceController::class, 'linkUser'])->name('absensi.link_user');
            Route::post('/absensi/manual', [\App\Http\Controllers\Sdm\AttendanceController::class, 'storeManual'])->name('absensi.manual.store');
            Route::post('/absensi/generate-absent', [\App\Http\Controllers\Sdm\AttendanceController::class, 'generateAbsent'])->name('absensi.generate_absent');
            Route::post('/cuti', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'store'])->name('cuti.store');
            Route::post('/libur', [\App\Http\Controllers\Sdm\HolidayController::class, 'store'])->name('libur.store');
            Route::post('/libur/generate', [\App\Http\Controllers\Sdm\HolidayController::class, 'generateMonth'])->name('libur.generate');
        });

        Route::middleware('can:edit_absensi')->group(function () {
            Route::patch('/absensi/{attendance}', [\App\Http\Controllers\Sdm\AttendanceController::class, 'update'])->name('absensi.update');
            Route::post('/cuti/{cuti}/approve', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'approve'])->name('cuti.approve');
            Route::post('/cuti/{cuti}/reject', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'reject'])->name('cuti.reject');
            Route::delete('/cuti/{cuti}', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'destroy'])->name('cuti.destroy');
            Route::patch('/libur/{libur}', [\App\Http\Controllers\Sdm\HolidayController::class, 'update'])->name('libur.update');
            Route::delete('/libur/{libur}', [\App\Http\Controllers\Sdm\HolidayController::class, 'destroy'])->name('libur.destroy');
        });

        Route::middleware('can:view_penggajian')->group(function () {
            Route::get('/penggajian', [\App\Http\Controllers\Sdm\PenggajianController::class, 'index'])->name('penggajian.index');
            Route::get('/penggajian/{penggajian}/print', [\App\Http\Controllers\Sdm\PenggajianController::class, 'print'])->name('penggajian.print');
        });

        Route::middleware('can:view_performa')->group(function () {
            Route::get('/performa', [\App\Http\Controllers\Sdm\PerformaController::class, 'index'])->name('performa.index');
        });

        Route::middleware('can:create_penggajian')->group(function () {
            Route::post('/penggajian/generate', [\App\Http\Controllers\Sdm\PenggajianController::class, 'generate'])->name('penggajian.generate');
        });

        Route::middleware('can:edit_penggajian')->group(function () {
            Route::post('/penggajian/{penggajian}/lock', [\App\Http\Controllers\Sdm\PenggajianController::class, 'lock'])->name('penggajian.lock');
            Route::post('/penggajian/{penggajian}/unlock', [\App\Http\Controllers\Sdm\PenggajianController::class, 'unlock'])->name('penggajian.unlock');
            Route::patch('/penggajian/{penggajian}/adjust', [\App\Http\Controllers\Sdm\PenggajianController::class, 'adjust'])->name('penggajian.adjust');
        });

        Route::middleware('can:delete_penggajian')->group(function () {
            Route::delete('/penggajian/{penggajian}', [\App\Http\Controllers\Sdm\PenggajianController::class, 'destroy'])->name('penggajian.destroy');
        });

        Route::middleware('can:view_potongan_gaji')->group(function () {
            Route::get('/potongan', [\App\Http\Controllers\Sdm\PotonganGajiController::class, 'index'])->name('potongan.index');
        });

        // Absen mandiri oleh karyawan (tanpa akses daftar absensi)
        Route::get('/absen-saya', [\App\Http\Controllers\Sdm\AttendanceController::class, 'selfPanel'])
            ->name('absensi.self_panel')
            ->middleware('active');
        Route::post('/absen-saya', [\App\Http\Controllers\Sdm\AttendanceController::class, 'selfStore'])
            ->name('absensi.self_store')
            ->middleware('active');

        Route::get('/cuti-saya', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'selfIndex'])
            ->name('cuti.self_index')
            ->middleware('active');
        Route::post('/cuti-saya', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'selfStore'])
            ->name('cuti.self_store')
            ->middleware('active');
        Route::delete('/cuti-saya/{cuti}', [\App\Http\Controllers\Sdm\LeaveRequestController::class, 'selfDestroy'])
            ->name('cuti.self_destroy')
            ->middleware('active');

        Route::get('/gaji-saya', [\App\Http\Controllers\Sdm\PenggajianController::class, 'selfIndex'])
            ->name('penggajian.self_index')
            ->middleware('active');
        Route::get('/gaji-saya/{penggajian}/print', [\App\Http\Controllers\Sdm\PenggajianController::class, 'selfPrint'])
            ->name('penggajian.self_print')
            ->middleware('active');

        Route::get('/potongan-saya', [\App\Http\Controllers\Sdm\PotonganGajiController::class, 'selfIndex'])
            ->name('potongan.self_index')
            ->middleware('active');

        Route::middleware('can:create_potongan_gaji')->group(function () {
            Route::post('/potongan', [\App\Http\Controllers\Sdm\PotonganGajiController::class, 'store'])->name('potongan.store');
        });

        Route::middleware('can:delete_potongan_gaji')->group(function () {
            Route::delete('/potongan/{potongan}', [\App\Http\Controllers\Sdm\PotonganGajiController::class, 'destroy'])->name('potongan.destroy');
            Route::delete('/bonus/{potongan}', [\App\Http\Controllers\Sdm\PotonganGajiController::class, 'destroyBonus'])->name('bonus.destroy');
        });
    });

    // =========================================================
    // PENGATURAN — supervisor only
    // =========================================================
    Route::get('pengaturan/pengguna', [\App\Http\Controllers\UserController::class, 'index'])
        ->name('pengguna.index')
        ->middleware('role:supervisor');
    Route::get('pengaturan/pengguna/create', [\App\Http\Controllers\UserController::class, 'create'])
        ->name('pengguna.create')
        ->middleware('role:supervisor');
    Route::post('pengaturan/pengguna', [\App\Http\Controllers\UserController::class, 'store'])
        ->name('pengguna.store')
        ->middleware('role:supervisor');
    Route::get('pengaturan/pengguna/{pengguna}/edit', [\App\Http\Controllers\UserController::class, 'edit'])
        ->name('pengguna.edit')
        ->middleware('role:supervisor');
    Route::put('pengaturan/pengguna/{pengguna}', [\App\Http\Controllers\UserController::class, 'update'])
        ->name('pengguna.update')
        ->middleware('role:supervisor');
    Route::post('pengaturan/pengguna/{pengguna}/approve', [\App\Http\Controllers\UserController::class, 'approve'])
        ->name('pengguna.approve')
        ->middleware('role:supervisor');
    Route::post('pengaturan/pengguna/{pengguna}/reject', [\App\Http\Controllers\UserController::class, 'reject'])
        ->name('pengguna.reject')
        ->middleware('role:supervisor');
    Route::delete('pengaturan/pengguna/{pengguna}', [\App\Http\Controllers\UserController::class, 'destroy'])
        ->name('pengguna.destroy')
        ->middleware('role:supervisor');

    Route::get('/pengaturan/toko', [\App\Http\Controllers\StoreSettingController::class, 'edit'])
        ->name('pengaturan.toko')
        ->middleware('can:view_pengaturan_toko');
    Route::put('/pengaturan/toko', [\App\Http\Controllers\StoreSettingController::class, 'update'])
        ->name('pengaturan.toko.update')
        ->middleware('can:edit_pengaturan_toko');

    Route::get('/pengaturan/backup', [\App\Http\Controllers\SettingsBackupController::class, 'index'])
        ->name('pengaturan.backup')
        ->middleware('can:view_backup_restore');
    Route::post('/pengaturan/backup/export', [\App\Http\Controllers\SettingsBackupController::class, 'export'])
        ->name('pengaturan.backup.export')
        ->middleware('can:create_backup_restore');
    Route::post('/pengaturan/backup/restore', [\App\Http\Controllers\SettingsBackupController::class, 'restore'])
        ->name('pengaturan.backup.restore')
        ->middleware('can:create_backup_restore');

    Route::get('/pengaturan/activity-log', [\App\Http\Controllers\ActivityLogController::class, 'index'])
        ->name('activity-log.index')
        ->middleware('can:view_log_aktivitas');
    Route::post('/pengaturan/activity-log/prune', [\App\Http\Controllers\ActivityLogController::class, 'prune'])
        ->name('activity-log.prune')
        ->middleware('can:delete_log_aktivitas');

    // =========================================================
    // MINYAK — Dashboard Tangki & Setoran
    // =========================================================
    Route::prefix('minyak')->name('minyak.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Minyak\DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('can:view_minyak_setoran');

        Route::get('setoran', [\App\Http\Controllers\Minyak\SetoranController::class, 'index'])
            ->name('setoran.index')
            ->middleware('can:view_minyak_setoran');
        Route::get('setoran/tambah', [\App\Http\Controllers\Minyak\SetoranController::class, 'create'])
            ->name('setoran.create')
            ->middleware('can:create_minyak_setoran');
        Route::post('setoran', [\App\Http\Controllers\Minyak\SetoranController::class, 'store'])
            ->name('setoran.store')
            ->middleware('can:create_minyak_setoran');
        Route::delete('setoran/{id}', [\App\Http\Controllers\Minyak\SetoranController::class, 'destroy'])
            ->name('setoran.destroy')
            ->middleware('can:delete_minyak_setoran');

        Route::resource('pelanggan', \App\Http\Controllers\Minyak\PelangganController::class)
            ->only(['index'])
            ->middleware('can:view_minyak_pelanggan');
        Route::resource('pelanggan', \App\Http\Controllers\Minyak\PelangganController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_minyak_pelanggan');
        Route::resource('pelanggan', \App\Http\Controllers\Minyak\PelangganController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_minyak_pelanggan');
        Route::resource('pelanggan', \App\Http\Controllers\Minyak\PelangganController::class)
            ->only(['destroy'])
            ->middleware('can:delete_minyak_pelanggan');
    });

    // =========================================================
    // SISTEM PASGAR (TIM LAPANGAN / KANVAS) — admin, admin_sales, admin1
    // =========================================================
    Route::prefix('pasgar')->name('pasgar.')->group(function () {

        // ── Loading Barang (existing) ──────────────────────────
        Route::resource('loadings', \App\Http\Controllers\Pasgar\LoadingController::class)
            ->only(['index', 'show'])
            ->middleware('can:view_pasgar_pesanan');
        Route::resource('loadings', \App\Http\Controllers\Pasgar\LoadingController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_pasgar_pesanan');
        Route::resource('loadings', \App\Http\Controllers\Pasgar\LoadingController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_pasgar_pesanan');
        Route::resource('loadings', \App\Http\Controllers\Pasgar\LoadingController::class)
            ->only(['destroy'])
            ->middleware('can:delete_pasgar_pesanan');
        Route::post('loadings/{loading}/approve', [\App\Http\Controllers\Pasgar\LoadingController::class, 'approve'])
            ->name('loadings.approve')
            ->middleware('can:edit_pasgar_pesanan');
        Route::post('loadings/{loading}/disiapkan', [\App\Http\Controllers\Pasgar\LoadingController::class, 'disiapkan'])
            ->name('loadings.disiapkan')
            ->middleware('can:edit_pasgar_pesanan');

        // ── Manajemen Stok Pasgar ──────────────────────────────
        // Stok On-Hand Kendaraan
        Route::get('stok-onhand', [\App\Http\Controllers\Pasgar\StokOnHandController::class, 'index'])
            ->name('stok-onhand.index')
            ->middleware('can:view_pasgar_stok');

        // Pengembalian Sisa Barang
        Route::get('pengembalian', [\App\Http\Controllers\Pasgar\PengembalianController::class, 'index'])
            ->name('pengembalian.index')
            ->middleware('can:view_pasgar_pengembalian');
        Route::get('pengembalian/create', [\App\Http\Controllers\Pasgar\PengembalianController::class, 'create'])
            ->name('pengembalian.create')
            ->middleware('can:create_pasgar_pengembalian');
        Route::get('pengembalian/vehicle-stock', [\App\Http\Controllers\Pasgar\PengembalianController::class, 'getVehicleStock'])
            ->name('pengembalian.vehicle-stock')
            ->middleware('can:view_pasgar_pengembalian');
        Route::post('pengembalian', [\App\Http\Controllers\Pasgar\PengembalianController::class, 'store'])
            ->name('pengembalian.store')
            ->middleware('can:create_pasgar_pengembalian');
        Route::get('pengembalian/{pengembalian}', [\App\Http\Controllers\Pasgar\PengembalianController::class, 'show'])
            ->name('pengembalian.show')
            ->middleware('can:view_pasgar_pengembalian');

        // ── Transaksi & Keuangan ───────────────────────────────
        // Penjualan Kanvas
        Route::get('penjualan', [\App\Http\Controllers\Pasgar\PenjualanKanvasController::class, 'index'])
            ->name('penjualan.index')
            ->middleware('can:view_pasgar_penjualan');
        Route::get('penjualan/{order}', [\App\Http\Controllers\Pasgar\PenjualanKanvasController::class, 'show'])
            ->name('penjualan.show')
            ->middleware('can:view_pasgar_penjualan');

        // Penagihan Piutang
        Route::get('penagihan', [\App\Http\Controllers\Pasgar\PenagihanController::class, 'index'])
            ->name('penagihan.index')
            ->middleware('can:view_pasgar_penagihan');

        // Setoran Harian
        Route::get('setoran', [\App\Http\Controllers\Pasgar\SetoranController::class, 'index'])
            ->name('setoran.index')
            ->middleware('can:view_pasgar_setoran');
        Route::get('setoran/create', [\App\Http\Controllers\Pasgar\SetoranController::class, 'create'])
            ->name('setoran.create')
            ->middleware('can:create_pasgar_setoran');
        Route::get('setoran/summary', [\App\Http\Controllers\Pasgar\SetoranController::class, 'getSummary'])
            ->name('setoran.summary')
            ->middleware('can:view_pasgar_setoran');
        Route::post('setoran', [\App\Http\Controllers\Pasgar\SetoranController::class, 'store'])
            ->name('setoran.store')
            ->middleware('can:create_pasgar_setoran');
        Route::get('setoran/{deposit}', [\App\Http\Controllers\Pasgar\SetoranController::class, 'show'])
            ->name('setoran.show')
            ->middleware('can:view_pasgar_setoran');
        Route::post('setoran/{deposit}/verify', [\App\Http\Controllers\Pasgar\SetoranController::class, 'verify'])
            ->name('setoran.verify')
            ->middleware('can:edit_pasgar_setoran');
        Route::delete('setoran/{deposit}', [\App\Http\Controllers\Pasgar\SetoranController::class, 'destroy'])
            ->name('setoran.destroy')
            ->middleware('can:delete_pasgar_setoran');

        // ── Rute & Kunjungan ───────────────────────────────────
        // Jadwal Kunjungan
        Route::get('jadwal', [\App\Http\Controllers\Pasgar\JadwalKunjunganController::class, 'index'])
            ->name('jadwal.index')
            ->middleware('can:view_pasgar_jadwal');
        Route::get('jadwal/create', [\App\Http\Controllers\Pasgar\JadwalKunjunganController::class, 'create'])
            ->name('jadwal.create')
            ->middleware('can:create_pasgar_jadwal');
        Route::post('jadwal', [\App\Http\Controllers\Pasgar\JadwalKunjunganController::class, 'store'])
            ->name('jadwal.store')
            ->middleware('can:create_pasgar_jadwal');
        Route::delete('jadwal/{jadwal}', [\App\Http\Controllers\Pasgar\JadwalKunjunganController::class, 'destroy'])
            ->name('jadwal.destroy')
            ->middleware('can:delete_pasgar_jadwal');

        // Laporan Kunjungan
        Route::get('kunjungan', [\App\Http\Controllers\Pasgar\JadwalKunjunganController::class, 'laporanIndex'])
            ->name('kunjungan.index')
            ->middleware('can:view_pasgar_kunjungan');
        Route::get('kunjungan/create', [\App\Http\Controllers\Pasgar\JadwalKunjunganController::class, 'laporanCreate'])
            ->name('kunjungan.create')
            ->middleware('can:create_pasgar_kunjungan');
        Route::post('kunjungan', [\App\Http\Controllers\Pasgar\JadwalKunjunganController::class, 'laporanStore'])
            ->name('kunjungan.store')
            ->middleware('can:create_pasgar_kunjungan');

        // ── Data Pasukan & Pelanggan ───────────────────────────────────────
        // Data Pelanggan Pasgar (Kanvas)
        Route::resource('pelanggan', \App\Http\Controllers\Pasgar\PelangganController::class)
            ->only(['index'])
            ->middleware('can:view_pasgar_pelanggan');
        Route::resource('pelanggan', \App\Http\Controllers\Pasgar\PelangganController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_pasgar_pelanggan');
        Route::resource('pelanggan', \App\Http\Controllers\Pasgar\PelangganController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_pasgar_pelanggan');
        Route::resource('pelanggan', \App\Http\Controllers\Pasgar\PelangganController::class)
            ->only(['destroy'])
            ->middleware('can:delete_pasgar_pelanggan');

        // Daftar Anggota Pasgar
        Route::get('anggota', [\App\Http\Controllers\Pasgar\MemberController::class, 'index'])
            ->name('anggota.index')
            ->middleware('can:view_pasgar_anggota');
        Route::get('anggota/create', [\App\Http\Controllers\Pasgar\MemberController::class, 'create'])
            ->name('anggota.create')
            ->middleware('can:create_pasgar_anggota');
        Route::post('anggota', [\App\Http\Controllers\Pasgar\MemberController::class, 'store'])
            ->name('anggota.store')
            ->middleware('can:create_pasgar_anggota');
        Route::get('anggota/{anggota}', [\App\Http\Controllers\Pasgar\MemberController::class, 'show'])
            ->name('anggota.show')
            ->middleware('can:view_pasgar_anggota');
        Route::get('anggota/{anggota}/edit', [\App\Http\Controllers\Pasgar\MemberController::class, 'edit'])
            ->name('anggota.edit')
            ->middleware('can:edit_pasgar_anggota');
        Route::put('anggota/{anggota}', [\App\Http\Controllers\Pasgar\MemberController::class, 'update'])
            ->name('anggota.update')
            ->middleware('can:edit_pasgar_anggota');
        Route::delete('anggota/{anggota}', [\App\Http\Controllers\Pasgar\MemberController::class, 'destroy'])
            ->name('anggota.destroy')
            ->middleware('can:delete_pasgar_anggota');

        // ── Kendaraan (kept for vehicle management) ────────────
        Route::resource('vehicles', \App\Http\Controllers\Pasgar\VehicleController::class)
            ->only(['index'])
            ->middleware('can:view_pasgar_kendaraan');
        Route::resource('vehicles', \App\Http\Controllers\Pasgar\VehicleController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_pasgar_kendaraan');
        Route::resource('vehicles', \App\Http\Controllers\Pasgar\VehicleController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_pasgar_kendaraan');
        Route::resource('vehicles', \App\Http\Controllers\Pasgar\VehicleController::class)
            ->only(['destroy'])
            ->middleware('can:delete_pasgar_kendaraan');
    });

    // =========================================================
    // MODUL VAN SALES KANVAS
    // =========================================================
    Route::prefix('kanvas')->name('kanvas.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Kanvas\DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('can:view_kanvas_rute');

        Route::get('/loading', [\App\Http\Controllers\Kanvas\LoadingController::class, 'index'])
            ->name('loading.index')
            ->middleware('can:view_kanvas_loading');
        Route::get('/loading/create', [\App\Http\Controllers\Kanvas\LoadingController::class, 'create'])
            ->name('loading.create')
            ->middleware('can:create_kanvas_loading');
        Route::get('/loading/search', [\App\Http\Controllers\Kanvas\LoadingController::class, 'searchProducts'])
            ->name('loading.search')
            ->middleware('can:view_kanvas_loading');
        Route::post('/loading', [\App\Http\Controllers\Kanvas\LoadingController::class, 'store'])
            ->name('loading.store')
            ->middleware('can:create_kanvas_loading');
        Route::get('/loading/{id}', [\App\Http\Controllers\Kanvas\LoadingController::class, 'show'])
            ->name('loading.show')
            ->middleware('can:view_kanvas_loading');

        Route::get('/route', [\App\Http\Controllers\Kanvas\RouteController::class, 'index'])
            ->name('route.index')
            ->middleware('can:view_kanvas_rute');
        Route::get('/route/create', [\App\Http\Controllers\Kanvas\RouteController::class, 'create'])
            ->name('route.create')
            ->middleware('can:create_kanvas_rute');
        Route::post('/route', [\App\Http\Controllers\Kanvas\RouteController::class, 'store'])
            ->name('route.store')
            ->middleware('can:create_kanvas_rute');

        Route::get('/setoran', [\App\Http\Controllers\Kanvas\SetoranController::class, 'index'])
            ->name('setoran.index')
            ->middleware('can:view_kanvas_setoran');
        Route::get('/setoran/{id}', [\App\Http\Controllers\Kanvas\SetoranController::class, 'show'])
            ->name('setoran.show')
            ->middleware('can:view_kanvas_setoran');
        Route::post('/setoran/{id}/verify', [\App\Http\Controllers\Kanvas\SetoranController::class, 'verify'])
            ->name('setoran.verify')
            ->middleware('can:edit_kanvas_setoran');
    });

    // =========================================================
    // POINT OF SALE - MOBILE (PWA OFFLINE-FIRST)
    // =========================================================
    Route::middleware('can:view_pos_kasir')->prefix('mobile')->name('mobile.')->group(function () {
        Route::get('/pos', [\App\Http\Controllers\MobilePosController::class, 'index'])->name('pos');
        Route::get('/pos/stock', [\App\Http\Controllers\MobilePosController::class, 'getVanStock'])->name('stock');
        Route::post('/pos/sync', [\App\Http\Controllers\MobilePosController::class, 'syncOfflineOrders'])->name('sync');
    });

});

require __DIR__.'/auth.php';

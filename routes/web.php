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

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'active']);

Route::middleware(['auth', 'active'])->group(function () {

    // =========================================================
    // PROFILE — semua user yang login
    // =========================================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/photo/{user}', [ProfileController::class, 'photo'])->name('profile.photo');

    // MASTER ROLES — defined in routes/pengaturan.php

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

        // Add items to completed transaction
        Route::get('/kasir/transactions/{transaction}/add-items', [\App\Http\Controllers\KasirController::class, 'addItemsForm'])->name('kasir.transactions.add_items_form');
    });

    Route::middleware('can:view_sesi_kasir')->group(function () {
        Route::get('/kasir/sesi', [\App\Http\Controllers\KasirController::class, 'session'])->name('kasir.session');
    });

    Route::middleware('can:delete_sesi_kasir')->group(function () {
        Route::post('/kasir/cash-movement', [\App\Http\Controllers\KasirController::class, 'addCashMovement'])->name('kasir.cash_movement');
        Route::post('/kasir/open-session', [\App\Http\Controllers\KasirController::class, 'openSession'])->name('kasir.open_session');
        Route::post('/kasir/open-session-grosir', [\App\Http\Controllers\KasirController::class, 'openSessionGrosir'])->name('kasir.open_session_grosir');
        Route::post('/kasir/close-session', [\App\Http\Controllers\KasirController::class, 'closeSession'])->name('kasir.close_session');
        Route::post('/kasir/close-session-grosir', [\App\Http\Controllers\KasirController::class, 'closeSessionGrosir'])->name('kasir.close_session_grosir');
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

        // Store additional items to completed transaction
        Route::post('/kasir/transactions/{transaction}/add-items', [\App\Http\Controllers\KasirController::class, 'storeAdditionalItems'])->name('kasir.transactions.add_items');
    });

    // Pelanggan (Customer) — view: kasir, admin1, admin2 | create/edit: admin1, admin2 | delete: supervisor only
    Route::middleware('can:create_pelanggan')->group(function () {
        Route::get('/pelanggan/create', [\App\Http\Controllers\CustomerController::class, 'create'])->name('pelanggan.create');
        Route::post('/pelanggan', [\App\Http\Controllers\CustomerController::class, 'store'])->name('pelanggan.store');
    });
    Route::middleware('can:view_pelanggan')->group(function () {
        Route::get('/pelanggan', [\App\Http\Controllers\CustomerController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/{pelanggan}', [\App\Http\Controllers\CustomerController::class, 'show'])->name('pelanggan.show');
    });
    Route::middleware('can:edit_pelanggan')->group(function () {
        Route::get('/pelanggan/{pelanggan}/edit', [\App\Http\Controllers\CustomerController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/pelanggan/{pelanggan}', [\App\Http\Controllers\CustomerController::class, 'update'])->name('pelanggan.update');
    });
    Route::middleware('can:delete_pelanggan')->group(function () {
        Route::delete('/pelanggan/{pelanggan}', [\App\Http\Controllers\CustomerController::class, 'destroy'])->name('pelanggan.destroy');
    });

    // HUTANG PIUTANG — specific routes MUST come before {kredit} wildcard
    Route::middleware('can:view_hutang_piutang')->group(function () {
        Route::get('/hutang-piutang', [\App\Http\Controllers\CustomerCreditController::class, 'index'])->name('hutang.index');
        Route::get('/hutang-piutang/list', [\App\Http\Controllers\CustomerCreditController::class, 'index'])->name('pelanggan.kredit.index');
        Route::get('/hutang-piutang/konsolidasi', [\App\Http\Controllers\CustomerCreditController::class, 'consolidated'])->name('pelanggan.kredit.consolidated');
        Route::get('/hutang-piutang/pelanggan/{customer}', [\App\Http\Controllers\CustomerCreditController::class, 'customerDebt'])->name('pelanggan.kredit.customer');
    });

    Route::middleware('can:create_hutang_piutang')->group(function () {
        Route::get('/hutang-piutang/create', [\App\Http\Controllers\CustomerCreditController::class, 'create'])->name('pelanggan.kredit.create');
        Route::post('/hutang-piutang', [\App\Http\Controllers\CustomerCreditController::class, 'store'])->name('pelanggan.kredit.store');
        Route::post('/hutang-piutang/pelanggan/{customer}/bayar', [\App\Http\Controllers\CustomerCreditController::class, 'payConsolidated'])->name('pelanggan.kredit.pay_consolidated');
        Route::post('/hutang-piutang/{kredit}/pay', [\App\Http\Controllers\CustomerCreditController::class, 'pay'])->name('pelanggan.kredit.pay');
    });

    // Wildcard routes LAST
    Route::middleware('can:view_hutang_piutang')->group(function () {
        Route::get('/hutang-piutang/{kredit}', [\App\Http\Controllers\CustomerCreditController::class, 'show'])->name('pelanggan.kredit.show');
    });

    Route::middleware('can:delete_hutang_piutang')->group(function () {
        Route::delete('/hutang-piutang/pembayaran/{payment}', [\App\Http\Controllers\CustomerCreditController::class, 'deletePayment'])->name('pelanggan.kredit.delete_payment');
        Route::delete('/hutang-piutang/{kredit}', [\App\Http\Controllers\CustomerCreditController::class, 'destroy'])->name('pelanggan.kredit.destroy');
    });

    Route::middleware('can:view_daftar_harga')->group(function () {
        Route::get('/harga', [\App\Http\Controllers\HargaController::class, 'index'])->name('harga.index');
    });

    // =========================================================
    // MASTER DATA — admin, admin4
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
        Route::get('/gudang/stok-semua', [LaporanController::class, 'stok'])->name('gudang.stok-semua');

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
        Route::get('/gudang/penerimaan/export', [InboundController::class, 'export'])->name('gudang.penerimaan.export');
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
    Route::post('/gudang/opname/{session}/revise', [OpnameSessionController::class, 'reviseToDraft'])
        ->name('gudang.opname_sessions.revise')
        ->middleware('can:create_opname_stok');
    Route::post('/gudang/opname/{session}/cancel', [OpnameSessionController::class, 'cancel'])
        ->name('gudang.opname_sessions.cancel')
        ->middleware('can:create_opname_stok');
    Route::get('/gudang/opname/{session}/print', [OpnameSessionController::class, 'print'])
        ->name('gudang.opname_sessions.print')
        ->middleware('can:view_opname_stok');

    Route::prefix('/gudang/opname-approval')->middleware('role:supervisor')->group(function () {
        Route::get('/', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'index'])->name('gudang.opname_approval.index');
        Route::get('/{session}', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'show'])->name('gudang.opname_approval.show');
        Route::post('/{session}/approve', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'approve'])->name('gudang.opname_approval.approve');
        Route::post('/{session}/reject', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'reject'])->name('gudang.opname_approval.reject');
        Route::post('/{session}/reverse', [\App\Http\Controllers\Gudang\OpnameApprovalController::class, 'reverse'])->name('gudang.opname_approval.reverse');
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
    // SALES ORDER — admin, admin_sales
    // =========================================================
    Route::get('/penjualan/sales-order/products/search', [\App\Http\Controllers\SalesOrderController::class, 'searchProducts'])
        ->middleware('can:view_sales_order')
        ->name('sales-order.products.search');

    Route::get('/penjualan/sales-order', [\App\Http\Controllers\SalesOrderController::class, 'index'])
        ->middleware('can:view_sales_order')
        ->name('sales-order.index');

    Route::get('/penjualan/sales-order/create', [\App\Http\Controllers\SalesOrderController::class, 'create'])
        ->middleware('can:create_sales_order')
        ->name('sales-order.create');

    Route::post('/penjualan/sales-order', [\App\Http\Controllers\SalesOrderController::class, 'store'])
        ->middleware('can:create_sales_order')
        ->name('sales-order.store');

    Route::get('/penjualan/sales-order/{sales_order}/edit', [\App\Http\Controllers\SalesOrderController::class, 'edit'])
        ->whereNumber('sales_order')
        ->middleware('can:edit_sales_order')
        ->name('sales-order.edit');

    Route::put('/penjualan/sales-order/{sales_order}', [\App\Http\Controllers\SalesOrderController::class, 'update'])
        ->whereNumber('sales_order')
        ->middleware('can:edit_sales_order')
        ->name('sales-order.update');

    Route::delete('/penjualan/sales-order/{sales_order}', [\App\Http\Controllers\SalesOrderController::class, 'destroy'])
        ->whereNumber('sales_order')
        ->middleware('can:delete_sales_order')
        ->name('sales-order.destroy');

    Route::get('/penjualan/sales-order/{sales_order}', [\App\Http\Controllers\SalesOrderController::class, 'show'])
        ->whereNumber('sales_order')
        ->middleware('can:view_sales_order')
        ->name('sales-order.show');

    // =========================================================
    // PEMBELIAN / PURCHASE ORDER — admin, gudang
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
        // Dashboard
        Route::get('/operasional/dashboard', [\App\Http\Controllers\OperationalExpenseController::class, 'dashboard'])
            ->name('operasional_dashboard')
            ->middleware('can:view_riwayat_operasional');

        // Export
        Route::get('/operasional/riwayat/export', [\App\Http\Controllers\OperationalExpenseController::class, 'export'])
            ->name('riwayat.export')
            ->middleware('can:view_riwayat_operasional');

        Route::resource('/operasional/kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['index'])
            ->middleware('can:view_kategori_operasional');
        Route::get('/operasional/kategori/export', [\App\Http\Controllers\OperationalCategoryController::class, 'export'])
            ->name('kategori.export')
            ->middleware('can:view_kategori_operasional');
        Route::resource('/operasional/kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_kategori_operasional');
        Route::resource('/operasional/kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_kategori_operasional');
        Route::resource('/operasional/kategori', \App\Http\Controllers\OperationalCategoryController::class)
            ->only(['destroy'])
            ->middleware('can:delete_kategori_operasional');

        Route::resource('/operasional/kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['index'])
            ->middleware('can:view_kendaraan_operasional');
        Route::get('/operasional/kendaraan/export', [\App\Http\Controllers\VehicleController::class, 'export'])
            ->name('kendaraan.export')
            ->middleware('can:view_kendaraan_operasional');
        Route::resource('/operasional/kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['create', 'store'])
            ->middleware('can:create_kendaraan_operasional');
        Route::resource('/operasional/kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['edit', 'update'])
            ->middleware('can:edit_kendaraan_operasional');
        Route::resource('/operasional/kendaraan', \App\Http\Controllers\VehicleController::class)
            ->only(['destroy'])
            ->middleware('can:delete_kendaraan_operasional');

        Route::post('/operasional/open-session', [\App\Http\Controllers\OperationalExpenseController::class, 'openSession'])
            ->name('open_session')
            ->middleware('can:manage_sesi_operasional');
        Route::post('/operasional/close-session', [\App\Http\Controllers\OperationalExpenseController::class, 'closeSession'])
            ->name('close_session')
            ->middleware('can:manage_sesi_operasional');
        Route::get('/operasional/sesi', [\App\Http\Controllers\OperationalExpenseController::class, 'sessions'])
            ->name('sesi.index')
            ->middleware('can:view_sesi_operasional');

        Route::get('/operasional/riwayat', [\App\Http\Controllers\OperationalExpenseController::class, 'index'])
            ->name('riwayat.index')
            ->middleware('can:view_riwayat_operasional');

        Route::get('/operasional/pengeluaran/create', [\App\Http\Controllers\OperationalExpenseController::class, 'create'])
            ->name('pengeluaran.create')
            ->middleware('can:create_pengeluaran_operasional');
        Route::post('/operasional/pengeluaran', [\App\Http\Controllers\OperationalExpenseController::class, 'store'])
            ->name('pengeluaran.store')
            ->middleware('can:create_pengeluaran_operasional');
        Route::get('/operasional/pengeluaran/{pengeluaran}/edit', [\App\Http\Controllers\OperationalExpenseController::class, 'edit'])
            ->name('pengeluaran.edit')
            ->middleware('can:edit_pengeluaran_operasional');
        Route::put('/operasional/pengeluaran/{pengeluaran}', [\App\Http\Controllers\OperationalExpenseController::class, 'update'])
            ->name('pengeluaran.update')
            ->middleware('can:edit_pengeluaran_operasional');
        Route::delete('/operasional/pengeluaran/{pengeluaran}', [\App\Http\Controllers\OperationalExpenseController::class, 'destroy'])
            ->name('pengeluaran.destroy')
            ->middleware('can:delete_pengeluaran_operasional');
    });

    // =========================================================
    // SDM / HR — supervisor
    // =========================================================
    Route::prefix('sdm')->name('sdm.')->group(function () {
        Route::middleware('can:create_karyawan')->group(function () {
            Route::get('/karyawan/create', [\App\Http\Controllers\Sdm\EmployeeController::class, 'create'])->name('karyawan.create');
            Route::post('/karyawan', [\App\Http\Controllers\Sdm\EmployeeController::class, 'store'])->name('karyawan.store');
            Route::post('/karyawan/import', [\App\Http\Controllers\Sdm\EmployeeController::class, 'importFromAccounts'])->name('karyawan.import');
        });

        Route::middleware('can:view_karyawan')->group(function () {
            Route::get('/karyawan', [\App\Http\Controllers\Sdm\EmployeeController::class, 'index'])->name('karyawan.index');
            Route::get('/karyawan/export', [\App\Http\Controllers\Sdm\EmployeeController::class, 'export'])->name('karyawan.export');
            Route::get('/karyawan/{karyawan}', [\App\Http\Controllers\Sdm\EmployeeController::class, 'show'])->name('karyawan.show');
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
            Route::post('/absensi/jadwal', [\App\Http\Controllers\Sdm\AttendanceController::class, 'updateSchedule'])->name('absensi.update_schedule');
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
        });
    });


    // PENGATURAN (pengguna, toko, sdm, backup, activity-log, roles)
    require __DIR__.'/pengaturan.php';

    // Load Minyak module routes (separated for role-based access control)
    require __DIR__.'/minyak.php';

    // =========================================================
    // MODUL MINERAL - Sales & Distribution
    // =========================================================

    // --- Shared routes (all mineral roles: supervisor, admin4, sales_mineral) ---
    Route::prefix('mineral')->name('mineral.')->middleware('role:supervisor|admin4|sales_mineral|admin1')->group(function () {
        // Dashboard (controller handles role-based view + sales auto-redirect)
        Route::get('/dashboard', [\App\Http\Controllers\Mineral\DashboardController::class, 'index'])
            ->name('dashboard');

        // Pelanggan (sales can view + create, supervisor has full CRUD)
        Route::get('/pelanggan', [\App\Http\Controllers\Mineral\PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/create', [\App\Http\Controllers\Mineral\PelangganController::class, 'create'])->name('pelanggan.create');
        Route::post('/pelanggan', [\App\Http\Controllers\Mineral\PelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/pelanggan/{pelanggan}', [\App\Http\Controllers\Mineral\PelangganController::class, 'show'])->name('pelanggan.show');

        // Produk (view only for sales, full for supervisor)
        Route::get('/produk', [\App\Http\Controllers\Mineral\ProdukController::class, 'index'])->name('produk.index');

        // Stok Kendaraan
        Route::get('/stok', [\App\Http\Controllers\Mineral\StokController::class, 'index'])->name('stok.index');

        // Stok Masuk (Penerimaan & Koreksi)
        Route::get('/stok-masuk', [\App\Http\Controllers\Mineral\StokMasukController::class, 'index'])->name('stok-masuk.index');
        Route::get('/stok-masuk/create', [\App\Http\Controllers\Mineral\StokMasukController::class, 'create'])->name('stok-masuk.create');
        Route::post('/stok-masuk', [\App\Http\Controllers\Mineral\StokMasukController::class, 'store'])->name('stok-masuk.store');
        Route::get('/stok-masuk/{stokMasuk}', [\App\Http\Controllers\Mineral\StokMasukController::class, 'show'])->name('stok-masuk.show');
        Route::delete('/stok-masuk/{stokMasuk}', [\App\Http\Controllers\Mineral\StokMasukController::class, 'destroy'])->name('stok-masuk.destroy');

        // Penjualan
        Route::resource('penjualan', \App\Http\Controllers\Mineral\PenjualanController::class);
        Route::get('/penjualan/{penjualan}/print', [\App\Http\Controllers\Mineral\PenjualanController::class, 'printStruk'])
            ->name('penjualan.print');

        // Kunjungan
        Route::get('/kunjungan', [\App\Http\Controllers\Mineral\KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/checkin', [\App\Http\Controllers\Mineral\KunjunganController::class, 'checkinForm'])->name('kunjungan.checkin');
        Route::post('/kunjungan/checkin', [\App\Http\Controllers\Mineral\KunjunganController::class, 'storeCheckin'])->name('kunjungan.checkin.store');
        Route::post('/kunjungan/{kunjungan}/checkout', [\App\Http\Controllers\Mineral\KunjunganController::class, 'storeCheckout'])->name('kunjungan.checkout');
        Route::post('/kunjungan/{kunjungan}/cancel', [\App\Http\Controllers\Mineral\KunjunganController::class, 'cancel'])->name('kunjungan.cancel');
        Route::get('/kunjungan/{kunjungan}', [\App\Http\Controllers\Mineral\KunjunganController::class, 'show'])->name('kunjungan.show');

        // Setoran
        Route::resource('setoran', \App\Http\Controllers\Mineral\SetoranController::class);

        // Hutang (sales can view & pay, supervisor full access)
        Route::get('/hutang', [\App\Http\Controllers\Mineral\HutangController::class, 'index'])->name('hutang.index');
        Route::get('/hutang/{hutang}', [\App\Http\Controllers\Mineral\HutangController::class, 'show'])->name('hutang.show');
        Route::post('/hutang/{hutang}/bayar', [\App\Http\Controllers\Mineral\HutangController::class, 'bayar'])->name('hutang.bayar');
    });

    // --- Supervisor & Admin only (full access features) ---
    Route::prefix('mineral')->name('mineral.')->middleware('role:supervisor|admin4')->group(function () {
        // Hutang confirm/reject (supervisor only)
        Route::post('/hutang/{hutang}/payment/{payment}/confirm', [\App\Http\Controllers\Mineral\HutangController::class, 'confirmPayment'])->name('hutang.payment.confirm');
        Route::post('/hutang/{hutang}/payment/{payment}/reject', [\App\Http\Controllers\Mineral\HutangController::class, 'rejectPayment'])->name('hutang.payment.reject');

        // Master Data - Sales
        Route::resource('sales', \App\Http\Controllers\Mineral\SalesController::class)->parameters(['sales' => 'sales']);

        // Master Data - Pelanggan (edit/update/delete only)
        Route::resource('pelanggan', \App\Http\Controllers\Mineral\PelangganController::class)->except(['index', 'create', 'store', 'show']);

        // Master Data - Produk (CRUD)
        Route::resource('produk', \App\Http\Controllers\Mineral\ProdukController::class)->except(['index']);

        // Master Data - Regional (CRUD)
        Route::resource('regional', \App\Http\Controllers\Mineral\RegionalController::class);

        // Master Data - Setting (Jenis & Satuan)
        Route::prefix('setting')->name('setting.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Mineral\SettingController::class, 'index'])->name('index');
            Route::post('/jenis', [\App\Http\Controllers\Mineral\SettingController::class, 'storeJenis'])->name('jenis.store');
            Route::put('/jenis/{jenis}', [\App\Http\Controllers\Mineral\SettingController::class, 'updateJenis'])->name('jenis.update');
            Route::delete('/jenis/{jenis}', [\App\Http\Controllers\Mineral\SettingController::class, 'destroyJenis'])->name('jenis.destroy');
            Route::post('/satuan', [\App\Http\Controllers\Mineral\SettingController::class, 'storeSatuan'])->name('satuan.store');
            Route::put('/satuan/{satuan}', [\App\Http\Controllers\Mineral\SettingController::class, 'updateSatuan'])->name('satuan.update');
            Route::delete('/satuan/{satuan}', [\App\Http\Controllers\Mineral\SettingController::class, 'destroySatuan'])->name('satuan.destroy');
        });

        // Loading Harian
        Route::resource('loading', \App\Http\Controllers\Mineral\LoadingController::class);

        // Distribusi Stok (batch loading)
        Route::get('/loading-distribusi', [\App\Http\Controllers\Mineral\LoadingController::class, 'distribusi'])->name('loading.distribusi');
        Route::post('/loading-distribusi', [\App\Http\Controllers\Mineral\LoadingController::class, 'storeDistribusi'])->name('loading.distribusi.store');

        // Penjualan verify
        Route::post('/penjualan/{penjualan}/verify', [\App\Http\Controllers\Mineral\PenjualanController::class, 'verify'])
            ->name('penjualan.verify');

        // Setoran verify
        Route::post('/setoran/{setoran}/verify', [\App\Http\Controllers\Mineral\SetoranController::class, 'verify'])
            ->name('setoran.verify');

        // Rekonsiliasi (supervisor|admin4 only)
        Route::get('/rekonsiliasi', [\App\Http\Controllers\Mineral\RekonsiliasiController::class, 'index'])
            ->name('rekonsiliasi.index');
        Route::post('/rekonsiliasi', [\App\Http\Controllers\Mineral\RekonsiliasiController::class, 'store'])
            ->name('rekonsiliasi.store');
    });

    // Laporan Mineral — accessible by supervisor, admin4, admin1
    Route::prefix('mineral')->name('mineral.')->middleware('role:supervisor|admin4|admin1')->group(function () {
        Route::get('/laporan', [\App\Http\Controllers\Mineral\LaporanController::class, 'index'])
            ->name('laporan');
    });

    // admin1 sudah dimasukkan ke grup shared di atas (supervisor|admin4|sales_mineral|admin1)

    // =========================================================
    // MODUL GULA - Sales & Distribution
    // =========================================================

    // --- Shared routes (all gula roles: supervisor, admin4, sales_gula) ---
    Route::prefix('gula')->name('gula.')->middleware('role:supervisor|admin4|sales_gula|admin1')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Gula\DashboardController::class, 'index'])
            ->name('dashboard');

        // Pelanggan (full CRUD for all gula roles)
        Route::resource('pelanggan', \App\Http\Controllers\Gula\PelangganController::class)->parameters(['pelanggan' => 'pelanggan']);

        // Produk (view only for sales, full for supervisor)
        Route::get('/produk', [\App\Http\Controllers\Gula\ProdukController::class, 'index'])->name('produk.index');

        // Stok Kendaraan
        Route::get('/stok', [\App\Http\Controllers\Gula\StokController::class, 'index'])->name('stok.index');

        // Stok Masuk (penerimaan & koreksi)
        Route::get('/stok-masuk', [\App\Http\Controllers\Gula\StokMasukController::class, 'index'])->name('stok-masuk.index');
        Route::get('/stok-masuk/create', [\App\Http\Controllers\Gula\StokMasukController::class, 'create'])->name('stok-masuk.create');
        Route::post('/stok-masuk', [\App\Http\Controllers\Gula\StokMasukController::class, 'store'])->name('stok-masuk.store');
        Route::get('/stok-masuk/{stokMasuk}', [\App\Http\Controllers\Gula\StokMasukController::class, 'show'])->name('stok-masuk.show');
        Route::post('/stok-masuk/{stokMasuk}/cancel', [\App\Http\Controllers\Gula\StokMasukController::class, 'cancel'])->name('stok-masuk.cancel');

        // Penjualan
        Route::resource('penjualan', \App\Http\Controllers\Gula\PenjualanController::class);
        Route::post('/penjualan/{penjualan}/verify', [\App\Http\Controllers\Gula\PenjualanController::class, 'verify'])->name('penjualan.verify');
        Route::get('/penjualan/{penjualan}/print', [\App\Http\Controllers\Gula\PenjualanController::class, 'printStruk'])->name('penjualan.print');

        // Kunjungan
        Route::get('/kunjungan', [\App\Http\Controllers\Gula\KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/{kunjungan}', [\App\Http\Controllers\Gula\KunjunganController::class, 'show'])->name('kunjungan.show');

        // Setoran
        Route::resource('setoran', \App\Http\Controllers\Gula\SetoranController::class);

        // Hutang Pelanggan (shared — sales can view & pay)
        Route::get('/hutang', [\App\Http\Controllers\Gula\HutangController::class, 'index'])->name('hutang.index');
        Route::get('/hutang/{hutang}', [\App\Http\Controllers\Gula\HutangController::class, 'show'])->name('hutang.show');
        Route::post('/hutang/{hutang}/bayar', [\App\Http\Controllers\Gula\HutangController::class, 'bayar'])->name('hutang.bayar');
    });

    // --- Supervisor & Admin only (full access features) ---
    Route::prefix('gula')->name('gula.')->middleware('role:supervisor|admin4')->group(function () {
        // Master Data - Sales
        Route::resource('sales', \App\Http\Controllers\Gula\SalesController::class)->parameters(['sales' => 'sales']);

        // Master Data - Produk (CRUD)
        Route::resource('produk', \App\Http\Controllers\Gula\ProdukController::class)->except(['index']);

        // Setting - Jenis & Satuan (combined)
        Route::prefix('setting')->name('setting.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Gula\SettingController::class, 'index'])->name('index');
            Route::post('/jenis', [\App\Http\Controllers\Gula\SettingController::class, 'storeJenis'])->name('jenis.store');
            Route::put('/jenis/{jenis}', [\App\Http\Controllers\Gula\SettingController::class, 'updateJenis'])->name('jenis.update');
            Route::delete('/jenis/{jenis}', [\App\Http\Controllers\Gula\SettingController::class, 'destroyJenis'])->name('jenis.destroy');
            Route::post('/satuan', [\App\Http\Controllers\Gula\SettingController::class, 'storeSatuan'])->name('satuan.store');
            Route::put('/satuan/{satuan}', [\App\Http\Controllers\Gula\SettingController::class, 'updateSatuan'])->name('satuan.update');
            Route::delete('/satuan/{satuan}', [\App\Http\Controllers\Gula\SettingController::class, 'destroySatuan'])->name('satuan.destroy');
        });

        // Distribusi Stok (must be BEFORE resource to avoid {loading} wildcard catching 'distribusi')
        Route::get('/loading/distribusi', [\App\Http\Controllers\Gula\LoadingController::class, 'distribusi'])->name('loading.distribusi');
        Route::post('/loading/distribusi', [\App\Http\Controllers\Gula\LoadingController::class, 'storeDistribusi'])->name('loading.distribusi.store');

        // Loading Harian
        Route::resource('loading', \App\Http\Controllers\Gula\LoadingController::class);

        // Setoran verify
        Route::post('/setoran/{setoran}/verify', [\App\Http\Controllers\Gula\SetoranController::class, 'verify'])->name('setoran.verify');

        // Hutang payment workflow (supervisor only)
        Route::post('/hutang/{hutang}/payment/{payment}/confirm', [\App\Http\Controllers\Gula\HutangController::class, 'confirmPayment'])->name('hutang.confirm-payment');
        Route::post('/hutang/{hutang}/payment/{payment}/reject', [\App\Http\Controllers\Gula\HutangController::class, 'rejectPayment'])->name('hutang.reject-payment');

        // Rekonsiliasi (supervisor|admin4 only)
        Route::get('/rekonsiliasi', [\App\Http\Controllers\Gula\RekonsiliasiController::class, 'index'])->name('rekonsiliasi.index');
        Route::post('/rekonsiliasi', [\App\Http\Controllers\Gula\RekonsiliasiController::class, 'store'])->name('rekonsiliasi.store');
    });

    // Laporan Gula — accessible by supervisor, admin4, admin1
    Route::prefix('gula')->name('gula.')->middleware('role:supervisor|admin4|admin1')->group(function () {
        Route::get('/laporan', [\App\Http\Controllers\Gula\LaporanController::class, 'index'])->name('laporan');
        Route::get('/laporan/print', [\App\Http\Controllers\Gula\LaporanController::class, 'print'])->name('laporan.print');
    });

    // admin1 sudah dimasukkan ke grup shared di atas (supervisor|admin4|sales_gula|admin1)

    // =========================================================
    // MODUL PASGAR - Pasukan Garuda (Sales & Distribution)
    // =========================================================

    // --- Shared routes (all pasgar roles: supervisor, admin4, pasgar, sales_pasgar) ---
    Route::prefix('pasgar')->name('pasgar.')->middleware('role:supervisor|admin4|pasgar|sales_pasgar|admin1')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Pasgar\PasgarDashboardController::class, 'index'])
            ->name('dashboard');

        // Sales Dashboard (for sales_pasgar role)
        Route::get('/sales-dashboard', [\App\Http\Controllers\Pasgar\SalesDashboardController::class, 'index'])
            ->name('sales.dashboard');

        // Penjualan Lapangan
        Route::get('/penjualan', [\App\Http\Controllers\Pasgar\PasgarPenjualanController::class, 'index'])->name('penjualan.index');
        Route::get('/penjualan/create', [\App\Http\Controllers\Pasgar\PasgarPenjualanController::class, 'create'])->name('penjualan.create');
        Route::post('/penjualan', [\App\Http\Controllers\Pasgar\PasgarPenjualanController::class, 'store'])->name('penjualan.store');
        Route::get('/penjualan/{id}', [\App\Http\Controllers\Pasgar\PasgarPenjualanController::class, 'show'])->name('penjualan.show');
        Route::get('/penjualan/{id}/print', [\App\Http\Controllers\Pasgar\PasgarPenjualanController::class, 'printStruk'])->name('penjualan.print');

        // Setoran
        Route::get('/setoran', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'index'])->name('setoran.index');
        Route::get('/setoran/create', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'create'])->name('setoran.create');
        Route::post('/setoran', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'store'])->name('setoran.store');
        Route::get('/setoran/{id}', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'show'])->name('setoran.show');
        Route::get('/setoran/{id}/edit', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'edit'])->name('setoran.edit');
        Route::put('/setoran/{id}', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'update'])->name('setoran.update');
        Route::delete('/setoran/{id}', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'destroy'])->name('setoran.destroy');

        // Loading (shared: sales creates request, all can view)
        Route::get('/loading', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'index'])->name('loading.index');
        Route::get('/loading/create', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'create'])->name('loading.create');
        Route::post('/loading', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'store'])->name('loading.store');
        Route::get('/loading/{id}/edit', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'edit'])->name('loading.edit');
        Route::put('/loading/{id}', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'update'])->name('loading.update');
        Route::get('/loading/{id}', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'show'])->name('loading.show');
        Route::post('/loading/{id}/pickup', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'pickup'])->name('loading.pickup');
        Route::post('/loading/{id}/load-vehicle', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'loadIntoVehicle'])->name('loading.loadVehicle');

        // Pelanggan (shared: all pasgar roles can manage customers)
        Route::get('/pelanggan', [\App\Http\Controllers\Pasgar\PasgarPelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/pelanggan/create', [\App\Http\Controllers\Pasgar\PasgarPelangganController::class, 'create'])->name('pelanggan.create');
        Route::post('/pelanggan', [\App\Http\Controllers\Pasgar\PasgarPelangganController::class, 'store'])->name('pelanggan.store');
        Route::get('/pelanggan/{pelanggan}', [\App\Http\Controllers\Pasgar\PasgarPelangganController::class, 'show'])->name('pelanggan.show');
        Route::get('/pelanggan/{pelanggan}/edit', [\App\Http\Controllers\Pasgar\PasgarPelangganController::class, 'edit'])->name('pelanggan.edit');
        Route::put('/pelanggan/{pelanggan}', [\App\Http\Controllers\Pasgar\PasgarPelangganController::class, 'update'])->name('pelanggan.update');
        Route::delete('/pelanggan/{pelanggan}', [\App\Http\Controllers\Pasgar\PasgarPelangganController::class, 'destroy'])->name('pelanggan.destroy');

        // Opname (shared: all pasgar roles)
        Route::get('/opname', [\App\Http\Controllers\Pasgar\PasgarOpnameController::class, 'index'])->name('opname.index');
        Route::get('/opname/create', [\App\Http\Controllers\Pasgar\PasgarOpnameController::class, 'create'])->name('opname.create');
        Route::post('/opname', [\App\Http\Controllers\Pasgar\PasgarOpnameController::class, 'store'])->name('opname.store');
        Route::get('/opname/{id}', [\App\Http\Controllers\Pasgar\PasgarOpnameController::class, 'show'])->name('opname.show');
    });

    // --- Supervisor, Admin & Pasgar only (full access features) ---
    Route::prefix('pasgar')->name('pasgar.')->middleware('role:supervisor|admin4|pasgar')->group(function () {
        // Sales Pasgar (CRUD)
        Route::get('/sales', [\App\Http\Controllers\Pasgar\PasgarDashboardController::class, 'sales'])->name('sales.index');
        Route::get('/sales/create', [\App\Http\Controllers\Pasgar\PasgarDashboardController::class, 'salesCreate'])->name('sales.create');
        Route::post('/sales', [\App\Http\Controllers\Pasgar\PasgarDashboardController::class, 'salesStore'])->name('sales.store');
        Route::get('/sales/{sales}/edit', [\App\Http\Controllers\Pasgar\PasgarDashboardController::class, 'salesEdit'])->name('sales.edit');
        Route::put('/sales/{sales}', [\App\Http\Controllers\Pasgar\PasgarDashboardController::class, 'salesUpdate'])->name('sales.update');

        // Loading Barang (admin workflow actions)
        Route::post('/loading/{id}/approve', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'approve'])->name('loading.approve');
        Route::post('/loading/{id}/confirm-ready', [\App\Http\Controllers\Pasgar\PasgarLoadingController::class, 'confirmReady'])->name('loading.confirmReady');

        // Setoran verify
        Route::post('/setoran/{id}/verify', [\App\Http\Controllers\Pasgar\PasgarSetoranController::class, 'verify'])->name('setoran.verify');

        // Opname confirm (supervisor)
        Route::post('/opname/{id}/confirm', [\App\Http\Controllers\Pasgar\PasgarOpnameController::class, 'confirm'])->name('opname.confirm');
    });

    // Laporan Pasgar — accessible by supervisor, admin4, pasgar, admin1
    Route::prefix('pasgar')->name('pasgar.')->middleware('role:supervisor|admin4|pasgar|admin1')->group(function () {
        Route::get('/laporan/penjualan', [\App\Http\Controllers\Pasgar\PasgarLaporanController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/setoran', [\App\Http\Controllers\Pasgar\PasgarLaporanController::class, 'setoran'])->name('laporan.setoran');
    });

    // admin1 sudah dimasukkan ke grup shared di atas (supervisor|admin4|pasgar|sales_pasgar|admin1)

});

require __DIR__.'/auth.php';

// =========================================================
// ZKTECO ADMS / CLOUD PUSH RECEIVER
// =========================================================
// Rute ini tidak menggunakan perlindungan CSRF (telah dikecualikan di bootstrap/app.php)
// agar mesin ZKTeco dapat nge-push (POST) langsung ke server kita.
Route::prefix('iclock')->group(function () {
    Route::get('/cdata', [\App\Http\Controllers\Api\AdmsController::class, 'init']);
    Route::get('/getrequest', [\App\Http\Controllers\Api\AdmsController::class, 'getRequest']);
    Route::post('/cdata', [\App\Http\Controllers\Api\AdmsController::class, 'receive']);
});

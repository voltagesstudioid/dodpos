<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanDataCommand extends Command
{
    protected $signature = 'data:clean
                            {--force : Skip confirmation prompt}
                            {--backup : Create database backup before cleaning}';

    protected $description = 'Clean all sales (penjualan), opname, and transaction data from the database';

    /**
     * Tables to TRUNCATE (delete all rows, reset auto-increment).
     * Ordered to respect foreign key dependencies (children first).
     */
    private array $truncateTables = [
        // ── POS Transaction Details (children first) ──
        'transaction_details',
        'transactions',
        'pos_return_items',
        'pos_returns',
        'pos_cash_movements',
        'pos_sessions',
        'pos_pick_order_items',
        'pos_pick_orders',

        // ── Sales Orders ──
        'sales_order_items',
        'sales_orders',

        // ── Customer Credits ──
        'customer_credit_payments',
        'customer_credits',

        // ── Stock Opname ──
        'stock_opname_items',
        'stock_opname_sessions',

        // ── Stock Movements & Transfers ──
        'stock_movements',
        'stock_transfer_items',
        'stock_transfers',
        'transfer_receipt_items',
        'transfer_receipts',
        'product_requests',

        // ── Purchase Orders & Returns ──
        'purchase_order_receipt_items',
        'purchase_order_receipts',
        'purchase_order_shortage_reports',
        'purchase_order_items',
        'purchase_orders',
        'purchase_return_items',
        'purchase_returns',

        // ── Supplier Debts ──
        'supplier_debt_payments',
        'supplier_debts',

        // ── Operational ──
        'operational_expenses',
        'operational_sessions',

        // ── Module: Mineral ──
        'mineral_transaction_items',
        'mineral_transactions',
        'mineral_penjualan',
        'mineral_hutang_bayar',
        'mineral_hutang',
        'mineral_setorans',
        'mineral_setoran',
        'mineral_loading_items',
        'mineral_loadings',
        'mineral_loading',
        'mineral_kunjungan',
        'mineral_stok_masuk',
        'mineral_warehouse_mutations',
        'mineral_warehouse_stocks',

        // ── Module: Minyak ──
        'minyak_transaksis',
        'minyak_penjualan',
        'minyak_hutang_bayar',
        'minyak_hutang',
        'minyak_setorans',
        'minyak_setoran',
        'minyak_loading',
        'minyak_kunjungan',
        'minyak_stok_masuk',

        // ── Module: Pasgar ──
        'pasgar_penjualan_items',
        'pasgar_penjualans',
        'pasgar_hutang_bayar',
        'pasgar_hutang',
        'pasgar_setorans',
        'pasgar_loading_items',
        'pasgar_loadings',
        'pasgar_opname_items',
        'pasgar_opnames',
        'pasgar_deposits',
        'pasgar_visit_reports',
        'pasgar_visit_schedules',

        // ── Module: Gula ──
        'gula_transaction_items',
        'gula_transactions',
        'gula_penjualan',
        'gula_hutang_bayar',
        'gula_hutang',
        'gula_setorans',
        'gula_setoran',
        'gula_loading_items',
        'gula_loadings',
        'gula_loading',
        'gula_kunjungan',
        'gula_stok_masuk',
        'gula_warehouse_stocks',
        'gula_vehicle_stocks',
        'gula_returns',
        'gula_repackings',

        // ── Module: Kanvas ──
        'kanvas_transaction_items',
        'kanvas_transactions',
        'kanvas_loading_items',
        'kanvas_loadings',
        'kanvas_setorans',
        'kanvas_warehouse_mutations',
        'kanvas_warehouse_stocks',
        'kanvas_vehicle_stocks',

        // ── Activity Log ──
        'activity_log',
    ];

    /**
     * Tables where stock columns should be RESET to 0 (but rows preserved).
     */
    private array $resetStockTables = [
        'products',       // reset `stock` column
        'product_stocks', // TRUNCATE (per-warehouse stock records)
    ];

    public function handle(): int
    {
        $this->newLine();
        $this->warn('╔══════════════════════════════════════════════════╗');
        $this->warn('║          ⚠️  PERINGATAN: DATA CLEANING  ⚠️         ║');
        $this->warn('╠══════════════════════════════════════════════════╣');
        $this->warn('║  Perintah ini akan MENGHAPUS SEMUA:             ║');
        $this->warn('║  • Data Penjualan (POS, Sales Orders)           ║');
        $this->warn('║  • Data Transaksi & Retur                       ║');
        $this->warn('║  • Data Opname (Stock Audit)                    ║');
        $this->warn('║  • Data Transfer & Permintaan Barang            ║');
        $this->warn('║  • Data Purchase Order & Retur Pembelian        ║');
        $this->warn('║  • Data Hutang Supplier & Pelanggan             ║');
        $this->warn('║  • Data Modul: Mineral, Minyak, Pasgar, Gula    ║');
        $this->warn('║  • Stok semua produk direset ke 0               ║');
        $this->warn('║  • Activity Log                                 ║');
        $this->warn('╠══════════════════════════════════════════════════╣');
        $this->warn('║  YANG TIDAK TERHAPUS:                            ║');
        $this->warn('║  • Data Produk (master data)                    ║');
        $this->warn('║  • Data Pelanggan, Supplier, Sales              ║');
        $this->warn('║  • Data Karyawan & Penggajian                   ║');
        $this->warn('║  • Data User & Hak Akses                        ║');
        $this->warn('║  • Data Gudang, Kategori, Satuan, Merek         ║');
        $this->warn('║  • Pengaturan Toko                              ║');
        $this->warn('╚══════════════════════════════════════════════════╝');
        $this->newLine();

        // Backup if requested
        if ($this->option('backup')) {
            $this->info('📦 Membuat backup database...');
            $this->call('db:backup');
            $this->newLine();
        }

        // Confirmation
        if (!$this->option('force')) {
            if (!$this->confirm('Apakah Anda YAKIN ingin menghapus semua data di atas? Ketik "yes" untuk melanjutkan', false)) {
                $this->info('❌ Dibatalkan. Tidak ada data yang dihapus.');
                return Command::SUCCESS;
            }

            $doubleCheck = $this->ask('Ketik "HAPUS SEMUA" untuk konfirmasi final');
            if ($doubleCheck !== 'HAPUS SEMUA') {
                $this->info('❌ Dibatalkan. Konfirmasi tidak sesuai.');
                return Command::SUCCESS;
            }
        }

        $this->newLine();
        $this->info('🧹 Memulai pembersihan data...');
        $this->newLine();

        // Disable foreign key checks for truncation
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $totalDeleted = 0;
        $errors = [];

        // Step 1: Truncate transaction/operational tables
        $this->info('📦 Step 1: Truncate tabel transaksi & operasional');
        foreach ($this->truncateTables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            try {
                $count = DB::table($table)->count();
                DB::table($table)->truncate();
                $totalDeleted += $count;
                if ($count > 0) {
                    $this->line("  ✅ {$table}: {$count} baris dihapus");
                }
            } catch (\Throwable $e) {
                $errors[] = "{$table}: {$e->getMessage()}";
                $this->error("  ❌ {$table}: {$e->getMessage()}");
            }
        }

        // Step 2: Truncate product_stocks
        $this->newLine();
        $this->info('📦 Step 2: Reset stok per-gudang (product_stocks)');
        if (Schema::hasTable('product_stocks')) {
            try {
                $count = DB::table('product_stocks')->count();
                DB::table('product_stocks')->truncate();
                $totalDeleted += $count;
                $this->line("  ✅ product_stocks: {$count} baris dihapus");
            } catch (\Throwable $e) {
                $errors[] = "product_stocks: {$e->getMessage()}";
                $this->error("  ❌ product_stocks: {$e->getMessage()}");
            }
        }

        // Step 3: Reset products.stock to 0
        $this->newLine();
        $this->info('📦 Step 3: Reset stok global produk ke 0');
        if (Schema::hasTable('products')) {
            try {
                $affected = DB::table('products')->where('stock', '!=', 0)->update(['stock' => 0]);
                $this->line("  ✅ products: {$affected} produk direset stoknya ke 0");
            } catch (\Throwable $e) {
                $errors[] = "products.stock: {$e->getMessage()}";
                $this->error("  ❌ products.stock: {$e->getMessage()}");
            }
        }

        // Step 4: Reset customer total_hutang
        $this->newLine();
        $this->info('📦 Step 4: Reset hutang pelanggan');
        if (Schema::hasTable('customers')) {
            try {
                DB::table('customers')->update(['total_hutang' => 0]);
                $this->line('  ✅ customers: total_hutang direset ke 0');
            } catch (\Throwable $e) {
                $errors[] = "customers.total_hutang: {$e->getMessage()}";
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        // Summary
        $this->newLine(2);
        if (empty($errors)) {
            $this->info("✅ Pembersihan selesai! Total {$totalDeleted} baris data dihapus.");
        } else {
            $this->warn("⚠️ Pembersihan selesai dengan " . count($errors) . " error:");
            foreach ($errors as $err) {
                $this->error("   • {$err}");
            }
        }

        $this->newLine();
        $this->info('📋 Data yang tersisa: Master produk, pelanggan, supplier, karyawan, user, pengaturan toko.');
        $this->info('💡 Stok semua produk sekarang = 0. Silakan input ulang stok melalui PO atau stock adjustment.');
        $this->newLine();

        return empty($errors) ? Command::SUCCESS : Command::FAILURE;
    }
}

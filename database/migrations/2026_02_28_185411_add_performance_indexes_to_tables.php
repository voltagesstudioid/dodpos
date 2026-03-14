<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambahkan database indexes untuk performa query yang sering digunakan:
 * - Laporan penjualan (filter by status, date, user)
 * - Laporan pembelian (filter by date, supplier, status)
 * - Stok per gudang (filter by product + warehouse)
 * - Stock movements (audit trail)
 * - Customer credits (filter by status, customer)
 * - Sales orders (filter by user, status, date)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── transactions ─────────────────────────────────────────────
        // Sering di-query: WHERE status = 'completed' AND created_at BETWEEN ...
        Schema::table('transactions', function (Blueprint $table) {
            if (!$this->indexExists('transactions', 'transactions_status_created_at_index')) {
                $table->index(['status', 'created_at'], 'transactions_status_created_at_index');
            }
            if (!$this->indexExists('transactions', 'transactions_user_id_status_index')) {
                $table->index(['user_id', 'status'], 'transactions_user_id_status_index');
            }
            if (!$this->indexExists('transactions', 'transactions_payment_method_index')) {
                $table->index('payment_method', 'transactions_payment_method_index');
            }
            if (!$this->indexExists('transactions', 'transactions_customer_id_index')) {
                $table->index('customer_id', 'transactions_customer_id_index');
            }
        });

        // ── transaction_details ───────────────────────────────────────
        // Sering di-query: JOIN dengan transactions, GROUP BY product_id
        Schema::table('transaction_details', function (Blueprint $table) {
            if (!$this->indexExists('transaction_details', 'td_transaction_id_index')) {
                $table->index('transaction_id', 'td_transaction_id_index');
            }
            if (!$this->indexExists('transaction_details', 'td_product_id_index')) {
                $table->index('product_id', 'td_product_id_index');
            }
        });

        // ── purchase_orders ───────────────────────────────────────────
        // Sering di-query: WHERE order_date BETWEEN ... AND status IN (...)
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!$this->indexExists('purchase_orders', 'po_order_date_status_index')) {
                $table->index(['order_date', 'status'], 'po_order_date_status_index');
            }
            if (!$this->indexExists('purchase_orders', 'po_supplier_id_index')) {
                $table->index('supplier_id', 'po_supplier_id_index');
            }
        });

        // ── product_stocks ────────────────────────────────────────────
        // Sering di-query: WHERE product_id = ? AND warehouse_id = ?
        Schema::table('product_stocks', function (Blueprint $table) {
            if (!$this->indexExists('product_stocks', 'ps_product_warehouse_index')) {
                $table->index(['product_id', 'warehouse_id'], 'ps_product_warehouse_index');
            }
            if (!$this->indexExists('product_stocks', 'ps_warehouse_id_index')) {
                $table->index('warehouse_id', 'ps_warehouse_id_index');
            }
            if (!$this->indexExists('product_stocks', 'ps_expired_date_index')) {
                $table->index('expired_date', 'ps_expired_date_index');
            }
        });

        // ── stock_movements ───────────────────────────────────────────
        // Sering di-query: WHERE product_id = ? ORDER BY created_at DESC
        Schema::table('stock_movements', function (Blueprint $table) {
            if (!$this->indexExists('stock_movements', 'sm_product_id_created_at_index')) {
                $table->index(['product_id', 'created_at'], 'sm_product_id_created_at_index');
            }
            if (!$this->indexExists('stock_movements', 'sm_warehouse_id_index')) {
                $table->index('warehouse_id', 'sm_warehouse_id_index');
            }
            if (!$this->indexExists('stock_movements', 'sm_source_type_index')) {
                $table->index('source_type', 'sm_source_type_index');
            }
        });

        // ── customer_credits ──────────────────────────────────────────
        // Sering di-query: WHERE customer_id = ? AND status IN ('unpaid','partial')
        Schema::table('customer_credits', function (Blueprint $table) {
            if (!$this->indexExists('customer_credits', 'cc_customer_id_status_index')) {
                $table->index(['customer_id', 'status'], 'cc_customer_id_status_index');
            }
            if (!$this->indexExists('customer_credits', 'cc_transaction_id_index')) {
                $table->index('transaction_id', 'cc_transaction_id_index');
            }
        });

        // ── sales_orders ──────────────────────────────────────────────
        // Sering di-query: WHERE user_id = ? ORDER BY created_at DESC
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!$this->indexExists('sales_orders', 'so_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at'], 'so_user_id_created_at_index');
            }
            if (!$this->indexExists('sales_orders', 'so_customer_id_index')) {
                $table->index('customer_id', 'so_customer_id_index');
            }
            if (!$this->indexExists('sales_orders', 'so_status_index')) {
                $table->index('status', 'so_status_index');
            }
        });

        // ── products ──────────────────────────────────────────────────
        // Sering di-query: WHERE category_id = ?, WHERE stock <= min_stock
        Schema::table('products', function (Blueprint $table) {
            if (!$this->indexExists('products', 'products_category_id_index')) {
                $table->index('category_id', 'products_category_id_index');
            }
            if (!$this->indexExists('products', 'products_stock_min_stock_index')) {
                $table->index(['stock', 'min_stock'], 'products_stock_min_stock_index');
            }
        });

        // ── supplier_debts ────────────────────────────────────────────
        Schema::table('supplier_debts', function (Blueprint $table) {
            if (!$this->indexExists('supplier_debts', 'sd_supplier_id_status_index')) {
                $table->index(['supplier_id', 'status'], 'sd_supplier_id_status_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndexIfExists('transactions_status_created_at_index');
            $table->dropIndexIfExists('transactions_user_id_status_index');
            $table->dropIndexIfExists('transactions_payment_method_index');
            $table->dropIndexIfExists('transactions_customer_id_index');
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndexIfExists('td_transaction_id_index');
            $table->dropIndexIfExists('td_product_id_index');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropIndexIfExists('po_order_date_status_index');
            $table->dropIndexIfExists('po_supplier_id_index');
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropIndexIfExists('ps_product_warehouse_index');
            $table->dropIndexIfExists('ps_warehouse_id_index');
            $table->dropIndexIfExists('ps_expired_date_index');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndexIfExists('sm_product_id_created_at_index');
            $table->dropIndexIfExists('sm_warehouse_id_index');
            $table->dropIndexIfExists('sm_source_type_index');
        });

        Schema::table('customer_credits', function (Blueprint $table) {
            $table->dropIndexIfExists('cc_customer_id_status_index');
            $table->dropIndexIfExists('cc_transaction_id_index');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropIndexIfExists('so_user_id_created_at_index');
            $table->dropIndexIfExists('so_customer_id_index');
            $table->dropIndexIfExists('so_status_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndexIfExists('products_category_id_index');
            $table->dropIndexIfExists('products_stock_min_stock_index');
        });

        Schema::table('supplier_debts', function (Blueprint $table) {
            $table->dropIndexIfExists('sd_supplier_id_status_index');
        });
    }

    /**
     * Cek apakah index sudah ada — database-agnostic (MySQL & SQLite).
     * Menggunakan Schema::hasIndex() yang tersedia di Laravel 11.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        return Schema::hasIndex($table, $indexName);
    }
};

<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== STARTING FULL DATA CLEANUP ===\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

$tablesToTruncate = [
    // General Transactions & Inventory
    'transactions', 'transaction_details',
    'pos_sessions', 'pos_cash_movements', 'pos_pick_orders', 'pos_pick_order_items', 'pos_returns', 'pos_return_items',
    'stock_movements', 'product_stocks', 'stock_opname_sessions', 'stock_opname_items', 'stock_transfers', 'stock_transfer_items', 'transfer_receipts', 'transfer_receipt_items',
    'purchase_orders', 'purchase_order_items', 'purchase_order_receipts', 'purchase_order_receipt_items', 'purchase_returns', 'purchase_return_items', 'purchase_order_shortage_reports',
    'sales_orders', 'sales_order_items',
    'supplier_debts', 'supplier_debt_payments',
    'customer_credits', 'customer_credit_payments',
    'operational_sessions', 'operational_expenses', 'attendances',
    
    // Gula
    'gula_hutang', 'gula_hutang_bayar', 'gula_kunjungan', 'gula_loading', 'gula_loading_items', 'gula_loadings', 'gula_penjualan', 'gula_repackings', 'gula_returns', 'gula_sales', 'gula_setoran', 'gula_setorans', 'gula_stok_masuk', 'gula_transaction_items', 'gula_transactions', 'gula_vehicle_stocks', 'gula_warehouse_stocks',
    
    // Mineral
    'mineral_hutang', 'mineral_hutang_bayar', 'mineral_kunjungan', 'mineral_loading', 'mineral_loading_items', 'mineral_loadings', 'mineral_penjualan', 'mineral_sales', 'mineral_setoran', 'mineral_setorans', 'mineral_stok_masuk', 'mineral_transaction_items', 'mineral_transactions', 'mineral_vehicle_stocks', 'mineral_warehouse_mutations', 'mineral_warehouse_stocks',
    
    // Minyak
    'minyak_hutang', 'minyak_hutang_bayar', 'minyak_kunjungan', 'minyak_loading', 'minyak_penjualan', 'minyak_sales', 'minyak_setoran', 'minyak_setorans', 'minyak_stok_masuk', 'minyak_transaksis',
    
    // Kanvas
    'kanvas_loading_items', 'kanvas_loadings', 'kanvas_route_stores', 'kanvas_routes', 'kanvas_setorans', 'kanvas_transaction_items', 'kanvas_transactions', 'kanvas_vehicle_stocks', 'kanvas_warehouse_mutations', 'kanvas_warehouse_stocks',
    
    // Pasgar
    'pasgar_deposits', 'pasgar_hutang', 'pasgar_hutang_bayar', 'pasgar_loading_items', 'pasgar_loadings', 'pasgar_opname_items', 'pasgar_opnames', 'pasgar_penjualan_items', 'pasgar_penjualans', 'pasgar_sales', 'pasgar_setorans', 'pasgar_visit_reports', 'pasgar_visit_schedules',
    
    // SDM (HR)
    'sdm_bonuses', 'sdm_cash_advances', 'sdm_deductions', 'sdm_employee_allowances', 'sdm_holidays', 'sdm_leave_requests', 'sdm_payrolls'
];

foreach ($tablesToTruncate as $table) {
    try {
        if (Schema::hasTable($table)) {
            DB::table($table)->truncate();
            echo "Truncated: {$table}\n";
        }
    } catch (\Exception $e) {
        echo "Error truncating {$table}: " . $e->getMessage() . "\n";
    }
}

// Reset product stock
try {
    if (Schema::hasTable('products') && Schema::hasColumn('products', 'stock')) {
        DB::table('products')->update(['stock' => 0]);
        echo "Reset 'stock' to 0 in 'products' table\n";
    }
} catch (\Exception $e) {
    echo "Error updating products: " . $e->getMessage() . "\n";
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "=== CLEANUP COMPLETE ===\n";

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Stock Movements - critical for inventory queries
        Schema::table('stock_movements', function (Blueprint $table) {
            if (!$this->indexExists('stock_movements', 'idx_sm_type_ref')) {
                $table->index(['type', 'reference_number'], 'idx_sm_type_ref');
            }
            if (!$this->indexExists('stock_movements', 'idx_sm_product_created')) {
                $table->index(['product_id', 'created_at'], 'idx_sm_product_created');
            }
        });

        // Purchase Orders - critical for purchasing module
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!$this->indexExists('purchase_orders', 'idx_po_status_date')) {
                $table->index(['status', 'order_date'], 'idx_po_status_date');
            }
        });

        // Product Stocks - critical for stock checking
        Schema::table('product_stocks', function (Blueprint $table) {
            if (!$this->indexExists('product_stocks', 'idx_ps_stock_qty')) {
                $table->index(['product_id', 'warehouse_id', 'stock'], 'idx_ps_stock_qty');
            }
        });

        // SDM Payrolls - critical for payroll processing
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            if (!$this->indexExists('sdm_payrolls', 'idx_payroll_user_period')) {
                $table->index(['user_id', 'period_year', 'period_month'], 'idx_payroll_user_period');
            }
        });

        // Attendances - critical for attendance reports
        Schema::table('attendances', function (Blueprint $table) {
            if (!$this->indexExists('attendances', 'idx_attendance_user_date')) {
                $table->index(['user_id', 'date'], 'idx_attendance_user_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_sm_type_ref');
            $table->dropIndexIfExists('idx_sm_product_created');
        });

        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_po_status_date');
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_ps_stock_qty');
        });

        Schema::table('sdm_payrolls', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_payroll_user_period');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_attendance_user_date');
        });
    }

    /**
     * Check if index exists on table
     */
    private function indexExists(string $table, string $indexName): bool
    {
        $driver = \DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support SHOW INDEX, skip index check
            return false;
        }
        
        $indexes = \DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }
        return false;
    }
};

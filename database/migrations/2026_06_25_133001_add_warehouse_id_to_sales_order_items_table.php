<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove header-level warehouse_id (no longer needed — per-item)
        Schema::table('sales_orders', function (Blueprint $table) {
            if (Schema::hasColumn('sales_orders', 'warehouse_id')) {
                $table->dropForeign(['warehouse_id']);
                $table->dropColumn('warehouse_id');
            }
        });

        // Add per-item warehouse
        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->after('unit_factor')
                ->constrained('warehouses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sales_order_items', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });

        Schema::table('sales_orders', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()
                ->constrained('warehouses')->onDelete('set null');
        });
    }
};

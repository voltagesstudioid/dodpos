<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Source type for non-PO inbound movements (gudang direct receipt)
            $table->string('source_type')->nullable()->after('type');

            // Link to Purchase Order for PO-based receipts (pembelian)
            $table->foreignId('purchase_order_id')
                  ->nullable()
                  ->after('source_type')
                  ->constrained('purchase_orders')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_id']);
            $table->dropColumn(['source_type', 'purchase_order_id']);
        });
    }
};

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
        Schema::table('transactions', function (Blueprint $table) {
            // Status pengiriman dari gudang
            $table->string('delivery_status')->default('pending')->after('status')
                ->comment('pending, packing, packed, in_transit, delivered');
            
            // Gudang sumber (jika barang dari gudang)
            $table->foreignId('source_warehouse_id')->nullable()->after('delivery_status')
                ->constrained('warehouses')->onDelete('set null');
            
            // User yang melakukan packing
            $table->foreignId('packed_by')->nullable()->after('source_warehouse_id')
                ->constrained('users')->onDelete('set null');
            $table->timestamp('packed_at')->nullable()->after('packed_by');
            
            // User yang melakukan cross-check
            $table->foreignId('checked_by')->nullable()->after('packed_at')
                ->constrained('users')->onDelete('set null');
            $table->timestamp('checked_at')->nullable()->after('checked_by');
            
            // User yang mengantar
            $table->foreignId('delivered_by')->nullable()->after('checked_at')
                ->constrained('users')->onDelete('set null');
            $table->timestamp('delivered_at')->nullable()->after('delivered_by');
            
            // Catatan pengiriman
            $table->text('delivery_notes')->nullable()->after('delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['source_warehouse_id']);
            $table->dropForeign(['packed_by']);
            $table->dropForeign(['checked_by']);
            $table->dropForeign(['delivered_by']);
            
            $table->dropColumn([
                'delivery_status',
                'source_warehouse_id',
                'packed_by',
                'packed_at',
                'checked_by',
                'checked_at',
                'delivered_by',
                'delivered_at',
                'delivery_notes',
            ]);
        });
    }
};

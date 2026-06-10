<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add per-item source tracking to pasgar_loading_items
        Schema::table('pasgar_loading_items', function (Blueprint $table) {
            $table->enum('sumber', ['gudang', 'grosir'])->default('gudang')->after('product_id');
            $table->foreignId('warehouse_id')->nullable()->after('sumber')->constrained('warehouses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_loading_items', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['sumber', 'warehouse_id']);
        });
    }
};

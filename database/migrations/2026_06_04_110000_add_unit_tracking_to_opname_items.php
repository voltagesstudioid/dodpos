<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            // Satuan yang dipakai admin saat hitung fisik (misal "Bal", "Slop", "Bungkus")
            $table->string('counted_unit', 50)->nullable()->after('physical_qty');
            // Qty asli yang diinput admin dalam satuan tersebut (misal 10 Bal)
            $table->decimal('counted_qty', 12, 2)->nullable()->after('counted_unit');
        });
    }

    public function down(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->dropColumn(['counted_unit', 'counted_qty']);
        });
    }
};

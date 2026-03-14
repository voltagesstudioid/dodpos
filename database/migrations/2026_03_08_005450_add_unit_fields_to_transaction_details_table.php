<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            // Satuan yang dipakai saat transaksi grosir (nullable: eceran tidak pakai ini)
            $table->foreignId('unit_conversion_id')
                  ->nullable()
                  ->after('product_id')
                  ->constrained('product_unit_conversions')
                  ->onDelete('set null');

            // Qty dalam satuan user-facing (misal: 1 slop, 5 karton)
            $table->integer('unit_qty')->nullable()->after('unit_conversion_id');

            // Nama satuan di-snapshot saat transaksi (agar tidak hilang jika satuan dihapus)
            $table->string('unit_name', 50)->nullable()->after('unit_qty');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['unit_conversion_id']);
            $table->dropColumn(['unit_conversion_id', 'unit_qty', 'unit_name']);
        });
    }
};


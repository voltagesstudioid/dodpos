<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_unit_conversions', function (Blueprint $table) {
            // Tambah constraint/comment untuk dokumentasi
            $table->decimal('sell_price_ecer', 15, 2)->default(0)->comment('Harga jual eceran (default)')->change();
            $table->decimal('sell_price_grosir', 15, 2)->default(0)->comment('Harga grosir (harus <= eceran)')->change();
            $table->decimal('sell_price_jual1', 15, 2)->default(0)->comment('Harga premium (>= eceran)')->change();
            $table->decimal('sell_price_jual2', 15, 2)->default(0)->comment('Harga medium')->change();
            $table->decimal('sell_price_jual3', 15, 2)->default(0)->comment('Harga budget (<= grosir)')->change();
            $table->decimal('sell_price_minimal', 15, 2)->default(0)->comment('Harga minimum/batas bawah')->change();
        });
    }

    public function down(): void
    {
        // Tidak perlu rollback karena hanya comment
    }
};

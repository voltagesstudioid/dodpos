<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mineral_loading', function (Blueprint $table) {
            $table->decimal('jumlah_loading', 15, 2)->change();
            $table->decimal('sisa_stok', 15, 2)->default(0)->change();
            $table->decimal('terjual', 15, 2)->default(0)->change();
        });

        Schema::table('mineral_produk', function (Blueprint $table) {
            $table->decimal('stok_gudang', 15, 2)->default(0)->change();
            $table->decimal('stok_minimum', 15, 2)->default(10)->change();
        });
    }

    public function down(): void
    {
        Schema::table('mineral_loading', function (Blueprint $table) {
            $table->integer('jumlah_loading')->change();
            $table->integer('sisa_stok')->default(0)->change();
            $table->integer('terjual')->default(0)->change();
        });

        Schema::table('mineral_produk', function (Blueprint $table) {
            $table->integer('stok_gudang')->default(0)->change();
            $table->integer('stok_minimum')->default(10)->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add kunjungan_id to mineral_penjualan (nullable for backwards compatibility)
        Schema::table('mineral_penjualan', function (Blueprint $table) {
            $table->foreignId('kunjungan_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('mineral_kunjungan')
                  ->nullOnDelete();
        });

        // Add ada_penjualan flag to mineral_kunjungan
        Schema::table('mineral_kunjungan', function (Blueprint $table) {
            $table->boolean('ada_penjualan')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('mineral_penjualan', function (Blueprint $table) {
            $table->dropForeign(['kunjungan_id']);
            $table->dropColumn('kunjungan_id');
        });

        Schema::table('mineral_kunjungan', function (Blueprint $table) {
            $table->dropColumn('ada_penjualan');
        });
    }
};

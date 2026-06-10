<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasgar_penjualans', function (Blueprint $table) {
            $table->string('id_transaksi_transfer', 100)->nullable()->after('metode_bayar');
            $table->string('foto_bukti_transfer', 500)->nullable()->after('id_transaksi_transfer');
        });

        // Remove 'qris' from enum — no existing records use it
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer','hutang') DEFAULT 'tunai'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer','qris','hutang') DEFAULT 'tunai'");

        Schema::table('pasgar_penjualans', function (Blueprint $table) {
            $table->dropColumn(['id_transaksi_transfer', 'foto_bukti_transfer']);
        });
    }
};

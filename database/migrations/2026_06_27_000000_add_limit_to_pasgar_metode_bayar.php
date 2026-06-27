<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer','limit') DEFAULT 'tunai'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE pasgar_penjualans SET metode_bayar = 'tunai' WHERE metode_bayar = 'limit'");
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer') DEFAULT 'tunai'");
    }
};

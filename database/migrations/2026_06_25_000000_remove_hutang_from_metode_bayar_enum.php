<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, update any remaining 'hutang' records to 'tunai' to avoid data loss
        DB::statement("UPDATE pasgar_penjualans SET metode_bayar = 'tunai' WHERE metode_bayar = 'hutang'");

        // Remove 'hutang' from metode_bayar enum
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer') DEFAULT 'tunai'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer','hutang') DEFAULT 'tunai'");
    }
};

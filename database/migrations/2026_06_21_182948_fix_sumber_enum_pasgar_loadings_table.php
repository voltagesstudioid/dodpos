<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Expand sumber enum to include 'grosir' and 'mixed'
        DB::statement("ALTER TABLE pasgar_loadings MODIFY COLUMN sumber ENUM('toko','gudang','grosir','mixed') DEFAULT 'gudang'");
    }

    public function down(): void
    {
        // Revert — but only safe if no rows use the new values
        DB::statement("ALTER TABLE pasgar_loadings MODIFY COLUMN sumber ENUM('toko','gudang') DEFAULT 'gudang'");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE customers MODIFY COLUMN category ENUM('pos','eceran','grosir','pasgar','minyak') NOT NULL DEFAULT 'pos'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE customers MODIFY COLUMN category ENUM('pos','pasgar','minyak') NOT NULL DEFAULT 'pos'");
    }
};

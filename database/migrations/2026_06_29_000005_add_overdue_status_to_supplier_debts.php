<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE supplier_debts MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid', 'overdue') DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE supplier_debts MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid'");
    }
};

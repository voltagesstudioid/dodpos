<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_sessions', function (Blueprint $table) {
            $table->decimal('expected_cash', 15, 2)->nullable()->after('closing_amount');
            $table->decimal('actual_cash', 15, 2)->nullable()->after('expected_cash');
            $table->decimal('cash_variance', 15, 2)->nullable()->after('actual_cash');
        });
    }

    public function down(): void
    {
        Schema::table('pos_sessions', function (Blueprint $table) {
            $table->dropColumn(['expected_cash', 'actual_cash', 'cash_variance']);
        });
    }
};

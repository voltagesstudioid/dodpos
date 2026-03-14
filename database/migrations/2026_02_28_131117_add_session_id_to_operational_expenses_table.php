<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('operational_expenses', function (Blueprint $table) {
            $table->foreignId('operational_session_id')->nullable()->constrained('operational_sessions')->nullOnDelete()->after('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operational_expenses', function (Blueprint $table) {
            $table->dropForeign(['operational_session_id']);
            $table->dropColumn('operational_session_id');
        });
    }
};

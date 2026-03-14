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
        Schema::table('sdm_employees', function (Blueprint $table) {
            $table->decimal('basic_salary', 15, 2)->default(0)->after('user_id');
            $table->decimal('daily_allowance', 15, 2)->default(0)->after('basic_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sdm_employees', function (Blueprint $table) {
            $table->dropColumn(['basic_salary', 'daily_allowance']);
        });
    }
};

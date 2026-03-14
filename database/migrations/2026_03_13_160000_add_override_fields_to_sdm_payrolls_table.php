<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            if (! Schema::hasColumn('sdm_payrolls', 'override_total_basic_salary')) {
                $table->decimal('override_total_basic_salary', 15, 2)->nullable()->after('total_basic_salary');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'override_late_meal_penalty')) {
                $table->decimal('override_late_meal_penalty', 15, 2)->nullable()->after('late_meal_penalty');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'override_absence_deduction')) {
                $table->decimal('override_absence_deduction', 15, 2)->nullable()->after('absence_deduction');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'override_total_basic_salary',
                'override_late_meal_penalty',
                'override_absence_deduction',
            ]);
        });
    }
};


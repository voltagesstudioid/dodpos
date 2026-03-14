<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            if (! Schema::hasColumn('sdm_payrolls', 'working_days')) {
                $table->unsignedInteger('working_days')->default(0)->after('period_year');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'present_days')) {
                $table->unsignedInteger('present_days')->default(0)->after('working_days');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'late_days')) {
                $table->unsignedInteger('late_days')->default(0)->after('present_days');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'izin_days')) {
                $table->unsignedInteger('izin_days')->default(0)->after('late_days');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'sakit_days')) {
                $table->unsignedInteger('sakit_days')->default(0)->after('izin_days');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'absent_days')) {
                $table->unsignedInteger('absent_days')->default(0)->after('sakit_days');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'missing_days')) {
                $table->unsignedInteger('missing_days')->default(0)->after('absent_days');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'unpaid_leave_days')) {
                $table->unsignedInteger('unpaid_leave_days')->default(0)->after('missing_days');
            }

            if (! Schema::hasColumn('sdm_payrolls', 'meal_allowance_per_day')) {
                $table->decimal('meal_allowance_per_day', 15, 2)->default(0)->after('total_allowance');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'meal_allowance_gross')) {
                $table->decimal('meal_allowance_gross', 15, 2)->default(0)->after('meal_allowance_per_day');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'late_meal_penalty')) {
                $table->decimal('late_meal_penalty', 15, 2)->default(0)->after('meal_allowance_gross');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'overtime_minutes')) {
                $table->unsignedInteger('overtime_minutes')->default(0)->after('late_meal_penalty');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'overtime_pay')) {
                $table->decimal('overtime_pay', 15, 2)->default(0)->after('overtime_minutes');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'incentive_amount')) {
                $table->decimal('incentive_amount', 15, 2)->default(0)->after('overtime_pay');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'performance_bonus')) {
                $table->decimal('performance_bonus', 15, 2)->default(0)->after('incentive_amount');
            }
            if (! Schema::hasColumn('sdm_payrolls', 'absence_deduction')) {
                $table->decimal('absence_deduction', 15, 2)->default(0)->after('total_deductions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'working_days',
                'present_days',
                'late_days',
                'izin_days',
                'sakit_days',
                'absent_days',
                'missing_days',
                'unpaid_leave_days',
                'meal_allowance_per_day',
                'meal_allowance_gross',
                'late_meal_penalty',
                'overtime_minutes',
                'overtime_pay',
                'incentive_amount',
                'performance_bonus',
                'absence_deduction',
            ]);
        });
    }
};

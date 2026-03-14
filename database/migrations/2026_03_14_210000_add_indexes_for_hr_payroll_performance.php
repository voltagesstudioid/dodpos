<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(['date', 'user_id'], 'att_date_user_idx');
            $table->index(['user_id', 'date'], 'att_user_date_idx');
            $table->index(['fingerprint_id', 'date'], 'att_fp_date_idx');
        });

        Schema::table('sdm_payrolls', function (Blueprint $table) {
            $table->index(['period_year', 'period_month'], 'payroll_period_idx');
            $table->index(['user_id', 'period_year', 'period_month'], 'payroll_user_period_idx');
        });

        Schema::table('sdm_deductions', function (Blueprint $table) {
            $table->index(['user_id', 'date'], 'ded_user_date_idx');
            $table->index(['date', 'user_id'], 'ded_date_user_idx');
        });

        Schema::table('sdm_bonuses', function (Blueprint $table) {
            $table->index(['user_id', 'date'], 'bonus_user_date_idx');
            $table->index(['date', 'user_id'], 'bonus_date_user_idx');
        });

        Schema::table('sdm_leave_requests', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'leave_user_status_idx');
            $table->index(['start_date', 'end_date'], 'leave_date_range_idx');
        });

        Schema::table('sdm_holidays', function (Blueprint $table) {
            $table->index(['date'], 'holiday_date_idx');
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('att_date_user_idx');
            $table->dropIndex('att_user_date_idx');
            $table->dropIndex('att_fp_date_idx');
        });

        Schema::table('sdm_payrolls', function (Blueprint $table) {
            $table->dropIndex('payroll_period_idx');
            $table->dropIndex('payroll_user_period_idx');
        });

        Schema::table('sdm_deductions', function (Blueprint $table) {
            $table->dropIndex('ded_user_date_idx');
            $table->dropIndex('ded_date_user_idx');
        });

        Schema::table('sdm_bonuses', function (Blueprint $table) {
            $table->dropIndex('bonus_user_date_idx');
            $table->dropIndex('bonus_date_user_idx');
        });

        Schema::table('sdm_leave_requests', function (Blueprint $table) {
            $table->dropIndex('leave_user_status_idx');
            $table->dropIndex('leave_date_range_idx');
        });

        Schema::table('sdm_holidays', function (Blueprint $table) {
            $table->dropIndex('holiday_date_idx');
        });
    }
};

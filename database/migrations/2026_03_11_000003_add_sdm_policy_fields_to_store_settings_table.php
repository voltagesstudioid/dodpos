<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('store_settings', 'sdm_work_start_time')) {
                $table->string('sdm_work_start_time', 5)->default('08:00')->after('fingerprint_port');
            }
            if (! Schema::hasColumn('store_settings', 'sdm_late_grace_minutes')) {
                $table->unsignedInteger('sdm_late_grace_minutes')->default(10)->after('sdm_work_start_time');
            }
            if (! Schema::hasColumn('store_settings', 'sdm_overtime_rate_per_hour')) {
                $table->decimal('sdm_overtime_rate_per_hour', 15, 2)->default(0)->after('sdm_late_grace_minutes');
            }
            if (! Schema::hasColumn('store_settings', 'sdm_late_meal_cut_mode')) {
                $table->enum('sdm_late_meal_cut_mode', ['none', 'full', 'percent', 'fixed'])->default('full')->after('sdm_overtime_rate_per_hour');
            }
            if (! Schema::hasColumn('store_settings', 'sdm_late_meal_cut_value')) {
                $table->decimal('sdm_late_meal_cut_value', 15, 2)->default(0)->after('sdm_late_meal_cut_mode');
            }
            if (! Schema::hasColumn('store_settings', 'sdm_working_days_mode')) {
                $table->enum('sdm_working_days_mode', ['mon_sat', 'mon_fri'])->default('mon_sat')->after('sdm_late_meal_cut_value');
            }
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn([
                'sdm_work_start_time',
                'sdm_late_grace_minutes',
                'sdm_overtime_rate_per_hour',
                'sdm_late_meal_cut_mode',
                'sdm_late_meal_cut_value',
                'sdm_working_days_mode',
            ]);
        });
    }
};

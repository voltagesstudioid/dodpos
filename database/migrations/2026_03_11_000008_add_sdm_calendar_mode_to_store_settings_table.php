<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('store_settings', 'sdm_calendar_mode')) {
                $table->enum('sdm_calendar_mode', ['auto', 'manual'])->default('auto')->after('sdm_working_days_mode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            if (Schema::hasColumn('store_settings', 'sdm_calendar_mode')) {
                $table->dropColumn('sdm_calendar_mode');
            }
        });
    }
};

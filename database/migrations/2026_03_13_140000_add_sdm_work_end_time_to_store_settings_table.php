<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('store_settings', 'sdm_work_end_time')) {
                $table->string('sdm_work_end_time', 5)->default('17:00')->after('sdm_work_start_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            if (Schema::hasColumn('store_settings', 'sdm_work_end_time')) {
                $table->dropColumn('sdm_work_end_time');
            }
        });
    }
};


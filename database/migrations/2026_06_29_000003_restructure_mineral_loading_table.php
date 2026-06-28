<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mineral_loading', function (Blueprint $table) {
            if (!Schema::hasColumn('mineral_loading', 'vehicle_inti_id')) {
                $table->foreignId('vehicle_inti_id')->nullable()->after('id')->constrained('vehicles')->nullOnDelete();
            }
            if (!Schema::hasColumn('mineral_loading', 'vehicle_sub_id')) {
                $table->foreignId('vehicle_sub_id')->nullable()->after('vehicle_inti_id')->constrained('vehicles')->nullOnDelete();
            }
        });

        if (Schema::hasColumn('mineral_loading', 'mobil_inti_id')) {
            Schema::table('mineral_loading', function (Blueprint $table) {
                $table->dropForeign(['mobil_inti_id']);
                $table->dropColumn('mobil_inti_id');
            });
        }

        if (Schema::hasColumn('mineral_loading', 'mobil_inti_id_old')) {
            try {
                DB::statement('ALTER TABLE mineral_loading DROP COLUMN mobil_inti_id_old');
            } catch (\Exception $e) {
                // Column might have foreign key constraint, try to drop it first
                try {
                    DB::statement('ALTER TABLE mineral_loading DROP FOREIGN KEY mineral_loading_mobil_inti_id_foreign');
                    DB::statement('ALTER TABLE mineral_loading DROP COLUMN mobil_inti_id_old');
                } catch (\Exception $e2) {
                    // Ignore if we can't drop it
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('mineral_loading', function (Blueprint $table) {
            $table->foreignId('mobil_inti_id')->nullable()->after('sales_id')->constrained('mineral_sales')->nullOnDelete();
            if (Schema::hasColumn('mineral_loading', 'vehicle_inti_id')) {
                $table->dropForeign(['vehicle_inti_id']);
                $table->dropColumn('vehicle_inti_id');
            }
            if (Schema::hasColumn('mineral_loading', 'vehicle_sub_id')) {
                $table->dropForeign(['vehicle_sub_id']);
                $table->dropColumn('vehicle_sub_id');
            }
        });
    }
};

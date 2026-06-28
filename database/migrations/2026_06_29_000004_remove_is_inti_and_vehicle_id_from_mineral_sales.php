<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('mineral_sales', 'vehicle_id')) {
            try {
                DB::statement('ALTER TABLE mineral_sales DROP FOREIGN KEY mineral_sales_vehicle_id_foreign');
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist
            }
        }

        Schema::table('mineral_sales', function (Blueprint $table) {
            if (Schema::hasColumn('mineral_sales', 'is_inti')) {
                $table->dropColumn('is_inti');
            }
            if (Schema::hasColumn('mineral_sales', 'vehicle_id')) {
                $table->dropColumn('vehicle_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mineral_sales', function (Blueprint $table) {
            $table->boolean('is_inti')->default(false)->after('keterangan');
            $table->foreignId('vehicle_id')->nullable()->after('user_id')->constrained('vehicles')->nullOnDelete();
        });
    }
};

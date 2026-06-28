<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->decimal('capacity', 15, 2)->default(0)->after('type');
            $table->enum('status', ['aktif', 'maintenance', 'standby', 'nonaktif'])->default('aktif')->after('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'status']);
        });
    }
};

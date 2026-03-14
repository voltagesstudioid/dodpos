<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('attendances', 'late_minutes')) {
                $table->unsignedInteger('late_minutes')->nullable()->after('status');
            }
            if (! Schema::hasColumn('attendances', 'overtime_minutes')) {
                $table->unsignedInteger('overtime_minutes')->nullable()->after('late_minutes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['late_minutes', 'overtime_minutes']);
        });
    }
};

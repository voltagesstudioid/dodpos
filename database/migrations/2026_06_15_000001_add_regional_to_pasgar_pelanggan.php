<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasgar_pelanggan', function (Blueprint $table) {
            $table->foreignId('regional_id')->nullable()->after('id')->constrained('pasgar_regional')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_pelanggan', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            $table->dropColumn('regional_id');
        });
    }
};

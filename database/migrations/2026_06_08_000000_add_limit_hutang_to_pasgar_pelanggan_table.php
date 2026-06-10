<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasgar_pelanggan', function (Blueprint $table) {
            $table->decimal('limit_hutang', 15, 2)->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_pelanggan', function (Blueprint $table) {
            $table->dropColumn('limit_hutang');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mineral_sales', function (Blueprint $table) {
            $table->boolean('is_inti')->default(false)->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('mineral_sales', function (Blueprint $table) {
            $table->dropColumn('is_inti');
        });
    }
};

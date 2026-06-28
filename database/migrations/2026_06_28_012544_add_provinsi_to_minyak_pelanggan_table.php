<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('minyak_pelanggan', function (Blueprint $table) {
            $table->string('provinsi', 100)->nullable()->after('kota');
        });
    }

    public function down(): void
    {
        Schema::table('minyak_pelanggan', function (Blueprint $table) {
            $table->dropColumn('provinsi');
        });
    }
};

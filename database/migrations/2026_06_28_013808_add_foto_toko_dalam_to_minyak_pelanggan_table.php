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
            $table->string('foto_toko_dalam', 255)->nullable()->after('foto_toko');
        });
    }

    public function down(): void
    {
        Schema::table('minyak_pelanggan', function (Blueprint $table) {
            $table->dropColumn('foto_toko_dalam');
        });
    }
};

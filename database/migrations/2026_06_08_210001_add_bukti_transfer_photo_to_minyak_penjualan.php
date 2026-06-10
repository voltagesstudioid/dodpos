<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('minyak_penjualan', function (Blueprint $table) {
            $table->string('bukti_transfer', 255)->nullable()->after('no_bukti_transfer');
        });
    }

    public function down(): void
    {
        Schema::table('minyak_penjualan', function (Blueprint $table) {
            $table->dropColumn('bukti_transfer');
        });
    }
};

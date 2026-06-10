<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('minyak_penjualan', function (Blueprint $table) {
            $table->string('no_bukti_transfer', 100)->nullable()->after('tipe_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('minyak_penjualan', function (Blueprint $table) {
            $table->dropColumn('no_bukti_transfer');
        });
    }
};

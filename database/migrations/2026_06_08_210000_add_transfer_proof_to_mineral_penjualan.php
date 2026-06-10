<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mineral_penjualan', function (Blueprint $table) {
            $table->string('no_bukti_transfer', 100)->nullable()->after('tipe_bayar');
            $table->string('bukti_transfer', 255)->nullable()->after('no_bukti_transfer');
        });
    }

    public function down(): void
    {
        Schema::table('mineral_penjualan', function (Blueprint $table) {
            $table->dropColumn(['no_bukti_transfer', 'bukti_transfer']);
        });
    }
};

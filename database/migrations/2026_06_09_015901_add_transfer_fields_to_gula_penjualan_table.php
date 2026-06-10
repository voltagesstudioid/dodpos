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
        Schema::table('gula_penjualan', function (Blueprint $table) {
            $table->string('transfer_ref')->nullable()->after('tipe_bayar');
            $table->string('foto_bukti_transfer')->nullable()->after('transfer_ref');
        });
    }

    public function down(): void
    {
        Schema::table('gula_penjualan', function (Blueprint $table) {
            $table->dropColumn(['transfer_ref', 'foto_bukti_transfer']);
        });
    }
};

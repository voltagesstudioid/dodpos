<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gula_setoran', function (Blueprint $table) {
            $table->string('bukti_setor', 255)->nullable()->after('total_hutang_baru');
            $table->decimal('total_tunai', 15, 2)->default(0)->after('total_penjualan');
            $table->decimal('total_transfer', 15, 2)->default(0)->after('total_tunai');
        });
    }

    public function down(): void
    {
        Schema::table('gula_setoran', function (Blueprint $table) {
            $table->dropColumn(['bukti_setor', 'total_tunai', 'total_transfer']);
        });
    }
};

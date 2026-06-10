<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('minyak_hutang_bayar', function (Blueprint $table) {
            $table->string('id_transaksi', 100)->nullable()->after('cara_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('minyak_hutang_bayar', function (Blueprint $table) {
            $table->dropColumn('id_transaksi');
        });
    }
};

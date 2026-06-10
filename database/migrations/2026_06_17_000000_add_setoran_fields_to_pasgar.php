<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasgar_setorans', function (Blueprint $table) {
            $table->string('bukti_setor', 255)->nullable()->after('selisih');
            $table->decimal('total_tunai', 15, 2)->default(0)->after('total_penjualan');
            $table->decimal('total_transfer', 15, 2)->default(0)->after('total_tunai');
            $table->integer('jumlah_transaksi')->default(0)->after('total_setor');
            $table->integer('jumlah_hutang_baru')->default(0)->after('jumlah_transaksi');
            $table->decimal('total_hutang_baru', 15, 2)->default(0)->after('jumlah_hutang_baru');
            $table->text('catatan_sales')->nullable()->after('total_hutang_baru');
            $table->text('catatan_verifikasi')->nullable()->after('catatan_sales');
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_setorans', function (Blueprint $table) {
            $table->dropColumn([
                'bukti_setor', 'total_tunai', 'total_transfer',
                'jumlah_transaksi', 'jumlah_hutang_baru', 'total_hutang_baru',
                'catatan_sales', 'catatan_verifikasi',
            ]);
        });
    }
};

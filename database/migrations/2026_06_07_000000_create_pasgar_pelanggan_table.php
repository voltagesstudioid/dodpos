<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasgar_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelanggan', 20)->unique();
            $table->string('nama_toko', 100);
            $table->string('nama_pemilik', 100);
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kecamatan', 50)->nullable();
            $table->string('kota', 50)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto_toko', 255)->nullable();
            $table->enum('tipe', ['warung', 'toko', 'kios'])->default('warung');
            $table->enum('status', ['aktif', 'nonaktif', 'blacklist'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('pasgar_penjualans', function (Blueprint $table) {
            $table->foreignId('pelanggan_id')
                ->nullable()
                ->after('sales_id')
                ->constrained('pasgar_pelanggan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_penjualans', function (Blueprint $table) {
            $table->dropForeign(['pelanggan_id']);
            $table->dropColumn('pelanggan_id');
        });

        Schema::dropIfExists('pasgar_pelanggan');
    }
};

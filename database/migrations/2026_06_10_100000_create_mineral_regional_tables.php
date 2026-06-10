<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Regional (area kerja sales mineral)
        Schema::create('mineral_regional', function (Blueprint $table) {
            $table->id();
            $table->string('kode_regional', 20)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Harga per Regional per Produk
        Schema::create('mineral_regional_harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_id')->constrained('mineral_regional')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('mineral_produk')->cascadeOnDelete();
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['regional_id', 'produk_id'], 'mineral_regional_produk_unique');
        });

        // Tambah kolom regional_id ke mineral_sales
        Schema::table('mineral_sales', function (Blueprint $table) {
            $table->foreignId('regional_id')->nullable()->after('user_id')->constrained('mineral_regional')->nullOnDelete();
        });

        // Tambah kolom regional_id ke mineral_pelanggan
        Schema::table('mineral_pelanggan', function (Blueprint $table) {
            $table->foreignId('regional_id')->nullable()->after('id')->constrained('mineral_regional')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mineral_pelanggan', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            $table->dropColumn('regional_id');
        });

        Schema::table('mineral_sales', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            $table->dropColumn('regional_id');
        });

        Schema::dropIfExists('mineral_regional_harga');
        Schema::dropIfExists('mineral_regional');
    }
};

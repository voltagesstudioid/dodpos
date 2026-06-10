<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Regional (area kerja sales minyak)
        Schema::create('minyak_regional', function (Blueprint $table) {
            $table->id();
            $table->string('kode_regional', 20)->unique();
            $table->string('nama', 100);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Harga per Regional per Produk
        Schema::create('minyak_regional_harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_id')->constrained('minyak_regional')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('minyak_produk')->cascadeOnDelete();
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['regional_id', 'produk_id'], 'regional_produk_unique');
        });

        // Tambah kolom regional_id ke minyak_sales
        Schema::table('minyak_sales', function (Blueprint $table) {
            $table->foreignId('regional_id')->nullable()->after('user_id')->constrained('minyak_regional')->nullOnDelete();
        });

        // Tambah kolom regional_id ke minyak_pelanggan
        Schema::table('minyak_pelanggan', function (Blueprint $table) {
            $table->foreignId('regional_id')->nullable()->after('id')->constrained('minyak_regional')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('minyak_pelanggan', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            $table->dropColumn('regional_id');
        });

        Schema::table('minyak_sales', function (Blueprint $table) {
            $table->dropForeign(['regional_id']);
            $table->dropColumn('regional_id');
        });

        Schema::dropIfExists('minyak_regional_harga');
        Schema::dropIfExists('minyak_regional');
    }
};

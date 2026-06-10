<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mineral_stok_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_referensi', 50)->unique();
            $table->foreignId('produk_id')->constrained('mineral_produk');
            $table->enum('tipe', ['penerimaan', 'koreksi']);
            $table->decimal('jumlah', 15, 2);
            $table->decimal('stok_sebelum', 15, 2)->default(0);
            $table->decimal('stok_sesudah', 15, 2)->default(0);
            $table->string('sumber', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'batal'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mineral_stok_masuk');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('minyak_loading');
        Schema::dropIfExists('minyak_stok_masuk');

        Schema::table('minyak_produk', function (Blueprint $table) {
            $table->dropColumn(['stok_gudang', 'stok_minimum']);
        });
    }

    public function down(): void
    {
        Schema::table('minyak_produk', function (Blueprint $table) {
            $table->integer('stok_gudang')->default(0)->after('harga_modal');
            $table->integer('stok_minimum')->default(100)->after('stok_gudang');
        });

        Schema::create('minyak_stok_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('no_referensi', 50)->unique();
            $table->foreignId('produk_id')->constrained('minyak_produk');
            $table->enum('tipe', ['penerimaan', 'koreksi']);
            $table->decimal('jumlah', 15, 2);
            $table->decimal('stok_sebelum', 15, 2)->default(0);
            $table->decimal('stok_sesudah', 15, 2)->default(0);
            $table->string('sumber', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'batal'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('minyak_loading', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('sales_id')->constrained('minyak_sales');
            $table->foreignId('produk_id')->constrained('minyak_produk');
            $table->integer('jumlah_loading');
            $table->integer('sisa_stok')->default(0);
            $table->integer('terjual')->default(0);
            $table->enum('status', ['loading', 'proses', 'selesai'])->default('loading');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }
};

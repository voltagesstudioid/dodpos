<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Sales Pasgar
        Schema::create('pasgar_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kode_sales', 20)->unique();
            $table->string('nama', 100);
            $table->string('no_hp', 20)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('no_kendaraan', 20)->nullable();
            $table->decimal('target_harian', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Tabel Loading Barang Pasgar
        Schema::create('pasgar_loadings', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_loading', 30)->unique();
            $table->foreignId('sales_id')->constrained('pasgar_sales');
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->date('tanggal');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Item Loading Pasgar
        Schema::create('pasgar_loading_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loading_id')->constrained('pasgar_loadings')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('unit_conversion_id')->nullable()->constrained('product_unit_conversions')->nullOnDelete();
            $table->integer('qty_diminta');
            $table->integer('qty_disetujui')->default(0);
            $table->integer('qty_terjual')->default(0);
            $table->integer('qty_sisa')->default(0);
            $table->timestamps();
        });

        // 4. Tabel Penjualan Pasgar
        Schema::create('pasgar_penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi', 30)->unique();
            $table->foreignId('loading_id')->constrained('pasgar_loadings');
            $table->foreignId('sales_id')->constrained('pasgar_sales');
            $table->string('nama_pelanggan', 100)->nullable();
            $table->string('telepon_pelanggan', 20)->nullable();
            $table->text('alamat_pelanggan')->nullable();
            $table->dateTime('tanggal');
            $table->decimal('total', 15, 2);
            $table->enum('metode_bayar', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 5. Tabel Item Penjualan Pasgar
        Schema::create('pasgar_penjualan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('pasgar_penjualans')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('unit_conversion_id')->nullable()->constrained('product_unit_conversions')->nullOnDelete();
            $table->integer('qty');
            $table->decimal('harga', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 6. Tabel Setoran Pasgar
        Schema::create('pasgar_setorans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_setoran', 30)->unique();
            $table->foreignId('loading_id')->constrained('pasgar_loadings');
            $table->foreignId('sales_id')->constrained('pasgar_sales');
            $table->date('tanggal');
            $table->decimal('total_penjualan', 15, 2)->default(0);
            $table->decimal('total_setor', 15, 2)->default(0);
            $table->decimal('selisih', 15, 2)->default(0);
            $table->enum('status', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasgar_setorans');
        Schema::dropIfExists('pasgar_penjualan_items');
        Schema::dropIfExists('pasgar_penjualans');
        Schema::dropIfExists('pasgar_loading_items');
        Schema::dropIfExists('pasgar_loadings');
        Schema::dropIfExists('pasgar_sales');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Sales (Salesman Minyak)
        Schema::create('minyak_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kode_sales', 20)->unique();
            $table->string('nama', 100);
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('no_kendaraan', 20)->nullable();
            $table->string('jenis_kendaraan', 50)->nullable();
            $table->decimal('target_harian', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Pelanggan Minyak
        Schema::create('minyak_pelanggan', function (Blueprint $table) {
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
            $table->enum('tipe', ['eceran', 'grosir', 'agen'])->default('eceran');
            $table->decimal('limit_hutang', 15, 2)->default(0);
            $table->decimal('total_hutang', 15, 2)->default(0);
            $table->enum('status', ['aktif', 'nonaktif', 'blacklist'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Produk Minyak
        Schema::create('minyak_produk', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk', 20)->unique();
            $table->string('nama', 100);
            $table->string('jenis', 50)->nullable(); // Premium, Solar, Pertalite, dll
            $table->string('satuan', 20)->default('liter');
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->decimal('harga_modal', 15, 2)->default(0);
            $table->integer('stok_gudang')->default(0);
            $table->integer('stok_minimum')->default(100);
            $table->text('keterangan')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Loading Harian
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
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel Penjualan Minyak
        Schema::create('minyak_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur', 30)->unique();
            $table->dateTime('tanggal_jual');
            $table->foreignId('sales_id')->constrained('minyak_sales');
            $table->foreignId('pelanggan_id')->constrained('minyak_pelanggan');
            $table->foreignId('produk_id')->constrained('minyak_produk');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total', 15, 2);
            $table->enum('tipe_bayar', ['tunai', 'hutang', 'transfer'])->default('tunai');
            $table->decimal('bayar', 15, 2)->default(0);
            $table->decimal('kembali', 15, 2)->default(0);
            $table->decimal('hutang', 15, 2)->default(0);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto_nota', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'terverifikasi', 'batal'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // Tabel Hutang Pelanggan
        Schema::create('minyak_hutang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('minyak_pelanggan');
            $table->foreignId('penjualan_id')->constrained('minyak_penjualan');
            $table->decimal('total_hutang', 15, 2);
            $table->decimal('dibayar', 15, 2)->default(0);
            $table->decimal('sisa', 15, 2);
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_lunas', 'lunas', 'overdue'])->default('belum_lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Pembayaran Hutang
        Schema::create('minyak_hutang_bayar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hutang_id')->constrained('minyak_hutang');
            $table->dateTime('tanggal_bayar');
            $table->decimal('jumlah', 15, 2);
            $table->enum('cara_bayar', ['tunai', 'transfer'])->default('tunai');
            $table->string('bukti_transfer', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel Setoran Sales
        Schema::create('minyak_setoran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('sales_id')->constrained('minyak_sales');
            $table->decimal('total_penjualan', 15, 2)->default(0);
            $table->decimal('total_setor', 15, 2)->default(0);
            $table->decimal('selisih', 15, 2)->default(0);
            $table->integer('jumlah_transaksi')->default(0);
            $table->integer('jumlah_hutang_baru')->default(0);
            $table->decimal('total_hutang_baru', 15, 2)->default(0);
            $table->enum('status', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->text('catatan_sales')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // Tabel Kunjungan Sales
        Schema::create('minyak_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('minyak_sales');
            $table->foreignId('pelanggan_id')->constrained('minyak_pelanggan');
            $table->dateTime('waktu_checkin');
            $table->dateTime('waktu_checkout')->nullable();
            $table->decimal('latitude_checkin', 10, 8)->nullable();
            $table->decimal('longitude_checkin', 11, 8)->nullable();
            $table->decimal('latitude_checkout', 10, 8)->nullable();
            $table->decimal('longitude_checkout', 11, 8)->nullable();
            $table->string('foto_checkin', 255)->nullable();
            $table->string('foto_checkout', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->enum('status', ['checkin', 'checkout', 'cancel'])->default('checkin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minyak_kunjungan');
        Schema::dropIfExists('minyak_setoran');
        Schema::dropIfExists('minyak_hutang_bayar');
        Schema::dropIfExists('minyak_hutang');
        Schema::dropIfExists('minyak_penjualan');
        Schema::dropIfExists('minyak_loading');
        Schema::dropIfExists('minyak_produk');
        Schema::dropIfExists('minyak_pelanggan');
        Schema::dropIfExists('minyak_sales');
    }
};

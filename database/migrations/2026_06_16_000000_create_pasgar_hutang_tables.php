<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create pasgar_hutang table
        Schema::create('pasgar_hutang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pasgar_pelanggan')->cascadeOnDelete();
            $table->foreignId('penjualan_id')->constrained('pasgar_penjualans')->cascadeOnDelete();
            $table->decimal('total_hutang', 15, 2)->default(0);
            $table->decimal('dibayar', 15, 2)->default(0);
            $table->decimal('sisa', 15, 2)->default(0);
            $table->date('jatuh_tempo')->nullable();
            $table->enum('status', ['belum_lunas', 'lunas', 'overdue'])->default('belum_lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 2. Create pasgar_hutang_bayar table
        Schema::create('pasgar_hutang_bayar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hutang_id')->constrained('pasgar_hutang')->cascadeOnDelete();
            $table->dateTime('tanggal_bayar');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->enum('cara_bayar', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->string('bukti_transfer', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });

        // 3. Alter pasgar_penjualans.metode_bayar to add 'hutang' option
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer','qris','hutang') DEFAULT 'tunai'");

        // 4. Add uang_muka column to pasgar_penjualans
        Schema::table('pasgar_penjualans', function (Blueprint $table) {
            $table->decimal('uang_muka', 15, 2)->default(0)->after('total');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasgar_hutang_bayar');
        Schema::dropIfExists('pasgar_hutang');

        // Revert metode_bayar enum
        DB::statement("ALTER TABLE pasgar_penjualans MODIFY COLUMN metode_bayar ENUM('tunai','transfer','qris') DEFAULT 'tunai'");

        // Remove uang_muka
        Schema::table('pasgar_penjualans', function (Blueprint $table) {
            $table->dropColumn('uang_muka');
        });
    }
};

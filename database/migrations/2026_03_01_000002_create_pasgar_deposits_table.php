<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasgar_deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_number')->unique()->comment('Nomor setoran, e.g. SET-20260301-001');
            $table->foreignId('pasgar_member_id')->constrained('pasgar_members')->onDelete('cascade');
            $table->date('deposit_date');
            $table->decimal('sales_amount', 15, 2)->default(0)->comment('Hasil penjualan kanvas');
            $table->decimal('collection_amount', 15, 2)->default(0)->comment('Hasil penagihan piutang');
            $table->decimal('expense_amount', 15, 2)->default(0)->comment('Pengeluaran operasional lapangan');
            $table->decimal('total_amount', 15, 2)->default(0)->comment('Total setoran bersih');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasgar_deposits');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasgar_visit_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->nullable()->constrained('pasgar_visit_schedules')->onDelete('set null');
            $table->foreignId('pasgar_member_id')->constrained('pasgar_members')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('visit_date');
            $table->enum('status', ['order', 'no_order', 'closed', 'not_found'])->default('order')
                ->comment('order=ada transaksi, no_order=kunjungan tanpa order, closed=toko tutup, not_found=tidak ditemukan');
            $table->decimal('order_amount', 15, 2)->default(0)->comment('Nilai order jika ada');
            $table->decimal('collection_amount', 15, 2)->default(0)->comment('Nilai tagihan yang berhasil ditagih');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['pasgar_member_id', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasgar_visit_reports');
    }
};

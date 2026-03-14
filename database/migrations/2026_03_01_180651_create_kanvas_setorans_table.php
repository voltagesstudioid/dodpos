<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rekonsiliasi setoran shift akhir (Sore / Malam saat Sales Kanvas pulang)
        Schema::create('kanvas_setorans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('users')->onDelete('cascade'); // Yg setor
            $table->foreignId('verifier_id')->nullable()->constrained('users')->onDelete('set null'); // SPV yang hitung uang

            // Expected Value (Menurut Sistem Komputer/Aplikasi)
            $table->decimal('expected_cash', 15, 2)->default(0);  // Jualan hari ini tunai + Tagihan cicilan lama tunai
            $table->decimal('expected_tempo', 15, 2)->default(0); // Jualan nota baru hari ini yg ngutang

            // Actual Value (Realita di atas meja setor)
            $table->decimal('actual_cash', 15, 2)->default(0);

            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_setorans');
    }
};

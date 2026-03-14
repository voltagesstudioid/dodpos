<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gula_repackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gula_product_id')->constrained('gula_products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Admin Gudang
            $table->date('date');
            
            // Konversi
            $table->decimal('minus_qty_karung', 10, 2)->default(0);
            $table->decimal('plus_qty_eceran', 10, 2)->default(0);
            
            // Susut / Loss
            $table->decimal('loss_qty_eceran', 10, 2)->default(0); // Gula yang hilang/rusak
            $table->text('notes')->nullable(); // Alasan susut (Semut/Bocor dll)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_repackings');
    }
};

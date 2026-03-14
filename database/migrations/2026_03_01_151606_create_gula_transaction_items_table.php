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
        Schema::create('gula_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gula_transaction_id')->constrained('gula_transactions')->cascadeOnDelete();
            $table->foreignId('gula_product_id')->constrained('gula_products')->cascadeOnDelete();
            $table->enum('unit_type', ['karung', 'bal', 'eceran']); // Satuan yang dipilih saat transaksi
            $table->decimal('qty', 10, 2)->default(1);
            $table->decimal('price', 15, 2)->default(0); // Harga per unit saat itu
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_transaction_items');
    }
};

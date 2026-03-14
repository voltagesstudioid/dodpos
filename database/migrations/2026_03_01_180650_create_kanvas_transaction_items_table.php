<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rincian barang yang dibeli toko dari mobil kanvas
        Schema::create('kanvas_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('kanvas_transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('kanvas_products')->onDelete('cascade');
            
            $table->integer('qty');
            $table->decimal('price', 15, 2)->default(0); // Harga saat di tap (Mungkin sales pilih harga cash vs tempo yg beda)
            $table->decimal('subtotal', 15, 2)->default(0); // qty * price

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_transaction_items');
    }
};

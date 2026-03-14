<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Item (Detail dari SJ Kanvas). Di sinilah 1 SJ bisa punya ratusan record product.
        Schema::create('kanvas_loading_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loading_id')->constrained('kanvas_loadings')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('kanvas_products')->onDelete('cascade');
            
            $table->integer('qty'); // Qty diberangkatkan
            // Opsional: Jika harga bawaan saat loading ingin dikirim hardcoded
            $table->decimal('price_snapshot', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_loading_items');
    }
};

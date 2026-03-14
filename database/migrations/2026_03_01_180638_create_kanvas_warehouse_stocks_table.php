<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kanvas_warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('kanvas_products')->onDelete('cascade');
            
            // Satuan mengikuti 'unit' di tabel produk
            $table->integer('qty_tersedia');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_warehouse_stocks');
    }
};

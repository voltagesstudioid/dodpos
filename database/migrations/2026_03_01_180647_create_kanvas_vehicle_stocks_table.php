<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stok barang yang ada di dalam box mobil Sales Kanvas saat ini
        Schema::create('kanvas_vehicle_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('kanvas_products')->onDelete('cascade');
            
            $table->integer('initial_qty')->default(0); // Total Pagi 
            $table->integer('sold_qty')->default(0);    // Laku terjual
            $table->integer('leftover_qty')->default(0);// Sisa di mobil
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_vehicle_stocks');
    }
};

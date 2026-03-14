<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kanvas_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('barcode')->unique()->nullable();
            
            // Satuan default (misal: Renceng, Dus, Pcs, Bal)
            $table->string('unit'); 
            
            // Harga
            $table->decimal('price_cash', 15, 2)->default(0);
            $table->decimal('price_tempo', 15, 2)->default(0); // Harga kredit biasanya sedikit dinaikkan
            
            // Konversi dus/bal ke pcs (untuk kepentingan inventory jika diperlukan)
            $table->integer('qty_per_carton')->default(1); 
            
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_products');
    }
};

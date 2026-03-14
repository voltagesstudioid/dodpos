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
        Schema::create('gula_loading_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gula_loading_id')->constrained('gula_loadings')->cascadeOnDelete();
            $table->foreignId('gula_product_id')->constrained('gula_products')->cascadeOnDelete();
            $table->decimal('qty_karung', 10, 2)->default(0); // Berapa karung dibawa
            $table->decimal('qty_bal', 10, 2)->default(0);
            $table->decimal('qty_eceran', 10, 2)->default(0); // Berapa bungkus 1kg dibawa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_loading_items');
    }
};

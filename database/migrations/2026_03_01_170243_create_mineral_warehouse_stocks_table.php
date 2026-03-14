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
        Schema::create('mineral_warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('mineral_products')->cascadeOnDelete();
            $table->integer('qty_dus')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mineral_warehouse_stocks');
    }
};

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
        Schema::create('gula_warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gula_product_id')->constrained('gula_products')->onDelete('cascade');
            $table->decimal('qty_karung', 10, 2)->default(0);
            $table->decimal('qty_bal', 10, 2)->default(0);
            $table->decimal('qty_eceran', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_warehouse_stocks');
    }
};

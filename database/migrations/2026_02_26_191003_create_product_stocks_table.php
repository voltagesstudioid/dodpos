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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('batch_number')->nullable();
            $table->date('expired_date')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();
            
            // Per kombinasi product, warehouse, location, batch dan expired_date harus unik
            $table->unique(['product_id', 'warehouse_id', 'location_id', 'batch_number', 'expired_date'], 'prod_stock_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};

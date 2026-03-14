<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('stock_opname_sessions')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('system_qty')->default(0);
            $table->integer('physical_qty')->default(0);
            $table->integer('difference_qty')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'product_id']);
            $table->index(['product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_items');
    }
};


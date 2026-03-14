<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('transfer_receipts')->cascadeOnDelete();
            $table->foreignId('stock_movement_id')->constrained('stock_movements')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('expected_qty')->default(0);
            $table->integer('received_qty')->default(0);
            $table->integer('rejected_qty')->default(0);
            $table->boolean('qty_ok')->default(true);
            $table->boolean('quality_ok')->default(true);
            $table->boolean('spec_ok')->default(true);
            $table->boolean('packaging_ok')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['receipt_id']);
            $table->index(['stock_movement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_receipt_items');
    }
};


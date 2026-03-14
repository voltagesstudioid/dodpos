<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('purchase_order_receipts')->cascadeOnDelete();
            $table->foreignId('purchase_order_item_id')->constrained('purchase_order_items')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->integer('qty_remaining_before')->default(0);
            $table->integer('qty_received_po_unit')->default(0);
            $table->integer('qty_received_base')->default(0);
            $table->enum('result', ['accepted', 'partial', 'rejected'])->default('accepted');

            $table->string('batch_number', 100)->nullable();
            $table->date('expired_date')->nullable();

            $table->boolean('quality_ok')->default(true);
            $table->boolean('spec_ok')->default(true);
            $table->boolean('packaging_ok')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['receipt_id']);
            $table->index(['purchase_order_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_receipt_items');
    }
};


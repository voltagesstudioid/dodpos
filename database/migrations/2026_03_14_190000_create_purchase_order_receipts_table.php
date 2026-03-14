<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['partial', 'completed'])->default('completed');
            $table->json('photos')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['purchase_order_id', 'created_at']);
            $table->index(['warehouse_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_receipts');
    }
};


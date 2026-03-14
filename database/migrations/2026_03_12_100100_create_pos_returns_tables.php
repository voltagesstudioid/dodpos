<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 40)->unique();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('return_date');
            $table->string('refund_method', 20)->default('tunai');
            $table->string('refund_reference', 100)->nullable();
            $table->decimal('refund_amount', 15, 2)->default(0);
            $table->string('status', 20)->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_return_id')->constrained('pos_returns')->cascadeOnDelete();
            $table->foreignId('transaction_detail_id')->nullable()->constrained('transaction_details')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_return_items');
        Schema::dropIfExists('pos_returns');
    }
};

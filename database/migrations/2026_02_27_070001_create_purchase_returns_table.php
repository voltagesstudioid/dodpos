<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->date('return_date');
            $table->enum('status', ['draft', 'approved', 'returned'])->default('draft');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_return_id')->constrained('purchase_returns')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('unit_id')->constrained('units');
            $table->integer('quantity');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('subtotal', 15, 2)->storedAs('quantity * purchase_price');
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
        Schema::dropIfExists('purchase_returns');
    }
};

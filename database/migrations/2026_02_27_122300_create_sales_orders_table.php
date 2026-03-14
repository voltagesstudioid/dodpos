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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('so_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('user_id')->constrained('users')->comment('Sales / Kasir');
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'confirmed', 'processing', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};

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
        Schema::create('mineral_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->enum('payment_method', ['cash', 'tempo'])->default('cash');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['paid', 'unpaid'])->default('paid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mineral_transactions');
    }
};

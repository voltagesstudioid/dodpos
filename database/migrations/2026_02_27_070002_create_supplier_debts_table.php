<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_debts', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->date('transaction_date');
            $table->date('due_date')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_debt_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_debt_id')->constrained('supplier_debts')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'check', 'other'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_debt_payments');
        Schema::dropIfExists('supplier_debts');
    }
};

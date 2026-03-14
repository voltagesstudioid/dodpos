<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_credits', function (Blueprint $table) {
            $table->id();
            $table->string('credit_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->enum('type', ['debt', 'credit'])->default('debt');
            // debt   = pelanggan beli kredit (piutang toko terhadap pelanggan)
            // credit = kelebihan bayar / retur ke pelanggan (hutang toko terhadap pelanggan)
            $table->date('transaction_date');
            $table->date('due_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('customer_credit_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_credit_id')->constrained('customer_credits')->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'qris', 'other'])->default('cash');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_credit_payments');
        Schema::dropIfExists('customer_credits');
    }
};

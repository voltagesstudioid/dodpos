<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Transaksi Penjualan Kanvas (Faktur/Nota)
        Schema::create('kanvas_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            
            $table->foreignId('sales_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            
            $table->enum('payment_method', ['cash', 'tempo']);
            $table->date('due_date')->nullable(); // Jika tempo
            
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0); // Potongan Rp dari admin/sales di jalan
            $table->decimal('total_amount', 15, 2)->default(0); // subtotal - discount
            
            $table->enum('status', ['paid', 'unpaid', 'partial'])->default('paid');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_transactions');
    }
};

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
        Schema::create('gula_returns', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('sales_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('gula_product_id')->constrained('gula_products')->cascadeOnDelete();
            $table->enum('unit_type', ['karung', 'bal', 'eceran']);
            $table->decimal('qty', 10, 2)->default(0);
            $table->text('reason')->nullable(); // Alasan retur (robek, basah)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Disetujui admin atau tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_returns');
    }
};

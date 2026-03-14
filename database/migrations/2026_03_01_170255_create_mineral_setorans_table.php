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
        Schema::create('mineral_setorans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('total_cash_expected', 15, 2)->default(0);
            $table->decimal('actual_cash', 15, 2)->nullable();
            $table->decimal('total_piutang_expected', 15, 2)->default(0);
            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mineral_setorans');
    }
};

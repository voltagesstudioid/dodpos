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
        Schema::create('gula_setorans', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('sales_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_cash', 15, 2)->default(0); // Uang tunai riil disetor sales
            $table->decimal('total_piutang', 15, 2)->default(0); // Total nilai nota tempo
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending'); // Divalidasi admin
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_setorans');
    }
};

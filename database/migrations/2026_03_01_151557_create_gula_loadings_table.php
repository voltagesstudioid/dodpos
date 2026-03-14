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
        Schema::create('gula_loadings', function (Blueprint $table) {
            $table->id();
            $table->string('loading_number')->unique(); // Nomor Surat Jalan
            $table->date('date');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Admin yang buat
            $table->foreignId('sales_id')->constrained('users')->cascadeOnDelete(); // Sales Gula
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete(); // Armada Truk
            $table->enum('status', ['draft', 'loaded', 'returned'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_loadings');
    }
};

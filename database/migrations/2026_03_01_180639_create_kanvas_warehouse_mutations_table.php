<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kanvas_warehouse_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('kanvas_products')->onDelete('cascade');
            $table->enum('type', ['in', 'in_return', 'out_loading', 'out_damage']);
            $table->integer('qty');
            
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin yang input
            $table->text('notes')->nullable(); // Misal: SJ-IN-001 atau Truk A Batal

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_warehouse_mutations');
    }
};

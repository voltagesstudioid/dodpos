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
        Schema::create('mineral_warehouse_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('mineral_products')->cascadeOnDelete();
            $table->enum('type', ['in', 'out_damage', 'out_loading', 'in_return']);
            $table->integer('qty_dus');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mineral_warehouse_mutations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained('mineral_produk')->cascadeOnDelete();
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['vehicle_id', 'produk_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_stocks');
    }
};

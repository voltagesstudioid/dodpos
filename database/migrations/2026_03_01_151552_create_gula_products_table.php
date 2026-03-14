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
        Schema::create('gula_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['karungan', 'eceran'])->default('karungan');
            $table->decimal('base_price', 12, 2)->default(0); // Harga beli dasar
            $table->decimal('price_karungan', 12, 2)->nullable(); // Jual jika beli per karung
            $table->decimal('price_bal', 12, 2)->nullable(); // Jual jika beli per bal
            $table->decimal('price_eceran', 12, 2)->nullable(); // Jual jika beli per bungkus/eceran
            $table->integer('qty_per_karung')->default(50); // 1 Karung = 50 kg / eceran
            $table->integer('qty_per_bal')->default(10); // 1 Bal = 10 eceran (opsional)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gula_products');
    }
};

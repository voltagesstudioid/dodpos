<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units');
            $table->integer('conversion_factor')->default(1)->comment('Berapa satuan terkecil per unit ini. Pcs=1, Karton=40, dll.');
            $table->decimal('purchase_price', 15, 2)->default(0)->comment('Harga beli per unit ini');
            $table->decimal('sell_price_ecer', 15, 2)->default(0)->comment('Harga jual eceran per unit ini');
            $table->decimal('sell_price_grosir', 15, 2)->default(0)->comment('Harga jual grosir per unit ini');
            $table->boolean('is_base_unit')->default(false)->comment('Apakah ini satuan terkecil/basis?');
            $table->timestamps();

            // One unit per product
            $table->unique(['product_id', 'unit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_unit_conversions');
    }
};

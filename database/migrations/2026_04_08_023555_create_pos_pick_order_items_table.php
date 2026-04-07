<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_pick_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pick_order_id')->constrained('pos_pick_orders')->cascadeOnDelete();
            $table->foreignId('transaction_detail_id')->nullable()->constrained('transaction_details')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity'); // base unit qty
            $table->integer('unit_qty'); // qty dalam satuan terpilih
            $table->string('unit_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_pick_order_items');
    }
};

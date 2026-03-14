<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null')->after('category_id');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null')->after('unit_id');
            $table->decimal('purchase_price', 15, 2)->default(0)->after('price');  // harga beli
            $table->string('barcode')->nullable()->after('sku');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['brand_id']);
            $table->dropColumn(['unit_id', 'brand_id', 'purchase_price', 'barcode']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_unit_conversions', function (Blueprint $table) {
            $table->decimal('sell_price_jual1', 15, 2)->default(0)->after('sell_price_grosir');
            $table->decimal('sell_price_jual2', 15, 2)->default(0)->after('sell_price_jual1');
            $table->decimal('sell_price_jual3', 15, 2)->default(0)->after('sell_price_jual2');
        });
    }

    public function down(): void
    {
        Schema::table('product_unit_conversions', function (Blueprint $table) {
            $table->dropColumn(['sell_price_jual1', 'sell_price_jual2', 'sell_price_jual3']);
        });
    }
};


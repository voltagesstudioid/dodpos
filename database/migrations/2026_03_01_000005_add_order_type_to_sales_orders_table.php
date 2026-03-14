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
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->enum('order_type', ['regular', 'canvas', 'preorder'])
                  ->default('regular')
                  ->after('status')
                  ->comment('Tipe order: regular (kasir), canvas (pasgar kanvas), preorder');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn('order_type');
        });
    }
};

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
        // Add unit_id to product_requests table
        Schema::table('product_requests', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('quantity')->constrained('units')->nullOnDelete();
            $table->decimal('conversion_factor', 10, 4)->default(1)->after('unit_id')->comment('Conversion factor to base unit');
        });

        // Add unit_id to stock_movements table
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('quantity')->constrained('units')->nullOnDelete();
            $table->decimal('conversion_factor', 10, 4)->default(1)->after('unit_id')->comment('Conversion factor to base unit');
            $table->decimal('quantity_in_unit', 15, 4)->nullable()->after('conversion_factor')->comment('Quantity in requested unit (before conversion)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['unit_id', 'conversion_factor']);
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['unit_id', 'conversion_factor', 'quantity_in_unit']);
        });
    }
};

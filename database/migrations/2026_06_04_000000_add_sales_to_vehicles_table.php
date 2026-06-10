<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('sales_type')->nullable()->after('warehouse_id');
            $table->unsignedBigInteger('sales_id')->nullable()->after('sales_type');
            $table->index(['sales_type', 'sales_id']);
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['sales_type', 'sales_id']);
            $table->dropColumn(['sales_type', 'sales_id']);
        });
    }
};

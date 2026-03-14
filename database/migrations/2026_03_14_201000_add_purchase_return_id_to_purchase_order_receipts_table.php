<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order_receipts', function (Blueprint $table) {
            if (! Schema::hasColumn('purchase_order_receipts', 'purchase_return_id')) {
                $table->foreignId('purchase_return_id')->nullable()->after('purchase_order_id')->constrained('purchase_returns')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_order_receipts', function (Blueprint $table) {
            $table->dropColumn(['purchase_return_id']);
        });
    }
};

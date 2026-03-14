<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'payment_term')) {
                $table->string('payment_term', 10)->default('credit')->after('notes'); // cash|credit
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_orders', 'payment_term')) {
                $table->dropColumn('payment_term');
            }
        });
    }
};


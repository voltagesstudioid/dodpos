<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('purchase_order_shortage_reports')) {
            Schema::table('purchase_order_shortage_reports', function (Blueprint $table) {
                if (! Schema::hasIndex('purchase_order_shortage_reports', 'posr_po_created_idx')) {
                    $table->index(['purchase_order_id', 'created_at'], 'posr_po_created_idx');
                }
            });

            return;
        }

        Schema::create('purchase_order_shortage_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('items');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['purchase_order_id', 'created_at'], 'posr_po_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_shortage_reports');
    }
};

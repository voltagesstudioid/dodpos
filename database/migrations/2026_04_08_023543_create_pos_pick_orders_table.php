<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_pick_orders', function (Blueprint $table) {
            $table->id();
            $table->string('pick_number')->unique(); // e.g. PO-20260408-001
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses'); // Gudang Utama sumber
            $table->enum('status', ['pending', 'processing', 'ready', 'completed'])->default('pending');
            $table->string('pos_type')->default('eceran'); // 'eceran' | 'grosir'
            $table->foreignId('requested_by')->constrained('users'); // kasir
            $table->foreignId('processed_by')->nullable()->constrained('users'); // Admin 3
            $table->foreignId('confirmed_by')->nullable()->constrained('users'); // Admin 4
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_pick_orders');
    }
};

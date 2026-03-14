<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 120);
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['partial', 'completed'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['reference_number']);
            $table->index(['warehouse_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_receipts');
    }
};


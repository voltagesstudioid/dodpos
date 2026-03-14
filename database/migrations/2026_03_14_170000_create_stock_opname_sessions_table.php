<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'cancelled'])->default('draft');
            $table->string('reference_number', 120)->nullable()->unique();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamps();

            $table->index(['warehouse_id', 'status']);
            $table->index(['created_by', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname_sessions');
    }
};


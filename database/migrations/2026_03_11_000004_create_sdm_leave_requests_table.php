<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdm_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['cuti', 'izin', 'sakit'])->default('cuti');
            $table->boolean('paid')->default(true);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'start_date', 'end_date'], 'slr_user_date_range_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm_leave_requests');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasgar_visit_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasgar_member_id')->constrained('pasgar_members')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('scheduled_date');
            $table->enum('status', ['scheduled', 'visited', 'skipped'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['pasgar_member_id', 'scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasgar_visit_schedules');
    }
};

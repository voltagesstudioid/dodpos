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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('fingerprint_id')->index();
            $table->date('date')->index();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('status')->default('present');
            $table->decimal('work_hours', 5, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['fingerprint_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdm_employee_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('sdm_employees')->cascadeOnDelete();
            $table->string('label', 100);
            $table->decimal('amount', 15, 2)->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm_employee_allowances');
    }
};

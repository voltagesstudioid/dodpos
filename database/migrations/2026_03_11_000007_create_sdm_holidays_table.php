<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdm_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('name')->nullable();
            $table->boolean('is_working_day')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm_holidays');
    }
};

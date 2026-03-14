<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kanvas_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal: Rute Senin Blok M
            $table->string('day_of_week')->nullable(); // Senin, Selasa, dst
            $table->string('area_description')->nullable();
            
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_routes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kanvas_route_stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('kanvas_routes')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            
            $table->integer('sequence')->default(0); // Urutan kunjungan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_route_stores');
    }
};

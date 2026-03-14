<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasgar_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->string('area')->nullable()->comment('Area kerja / wilayah kanvas');
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('user_id'); // 1 user = 1 profil pasgar
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasgar_members');
    }
};

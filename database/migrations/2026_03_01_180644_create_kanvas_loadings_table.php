<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Header Surat Jalan Kanvas
        Schema::create('kanvas_loadings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->constrained('users')->onDelete('cascade'); // Sales Kanvas
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null'); // Admin Pembuat SJ
            
            $table->date('date');
            $table->enum('status', ['loading', 'completed', 'unloaded'])->default('loading');
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kanvas_loadings');
    }
};

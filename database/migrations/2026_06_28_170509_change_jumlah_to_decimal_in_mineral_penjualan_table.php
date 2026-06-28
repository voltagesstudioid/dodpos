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
        Schema::table('mineral_penjualan', function (Blueprint $table) {
            $table->decimal('jumlah', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('mineral_penjualan', function (Blueprint $table) {
            $table->integer('jumlah')->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasgar_loading_items', function (Blueprint $table) {
            $table->decimal('qty_diminta', 10, 3)->default(0)->change();
            $table->decimal('qty_disetujui', 10, 3)->default(0)->change();
            $table->decimal('qty_dikirim', 10, 3)->default(0)->change();
            $table->decimal('qty_terjual', 10, 3)->default(0)->change();
            $table->decimal('qty_sisa', 10, 3)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('pasgar_loading_items', function (Blueprint $table) {
            $table->integer('qty_diminta')->default(0)->change();
            $table->integer('qty_disetujui')->default(0)->change();
            $table->integer('qty_dikirim')->default(0)->change();
            $table->integer('qty_terjual')->default(0)->change();
            $table->integer('qty_sisa')->default(0)->change();
        });
    }
};

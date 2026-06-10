<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create pasgar_opnames table
        Schema::create('pasgar_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_opname', 30)->unique();
            $table->foreignId('loading_id')->unique()->constrained('pasgar_loadings');
            $table->foreignId('sales_id')->constrained('pasgar_sales');
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'confirmed'])->default('pending');
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        // 2. Create pasgar_opname_items table
        Schema::create('pasgar_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opname_id')->constrained('pasgar_opnames')->cascadeOnDelete();
            $table->foreignId('loading_item_id')->constrained('pasgar_loading_items');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('qty_sisa_sistem')->default(0);
            $table->integer('qty_fisik')->default(0);
            $table->integer('qty_selisih')->default(0);
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Add 'opnamed' to loading status enum
        DB::statement("ALTER TABLE pasgar_loadings MODIFY COLUMN status ENUM('pending','preparing','ready','picked_up','loaded','completed','opnamed','rejected') DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Revert loading status enum (remove 'opnamed')
        DB::statement("ALTER TABLE pasgar_loadings MODIFY COLUMN status ENUM('pending','preparing','ready','picked_up','loaded','completed','rejected') DEFAULT 'pending'");

        Schema::dropIfExists('pasgar_opname_items');
        Schema::dropIfExists('pasgar_opnames');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('DODPOS');
            $table->string('store_phone')->nullable();
            $table->string('store_email')->nullable();
            $table->text('store_address')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('currency_symbol')->default('Rp');
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->enum('rounding_mode', ['none', 'nearest_100', 'nearest_500', 'nearest_1000'])->default('none');
            $table->text('receipt_header')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};

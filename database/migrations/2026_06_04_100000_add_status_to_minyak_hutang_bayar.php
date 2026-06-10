<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('minyak_hutang_bayar', function (Blueprint $table) {
            $table->enum('status', ['pending', 'confirmed', 'rejected'])
                  ->default('confirmed')
                  ->after('keterangan');
            $table->foreignId('confirmed_by')
                  ->nullable()
                  ->after('status')
                  ->constrained('users')
                  ->nullOnDelete();
            $table->dateTime('confirmed_at')->nullable()->after('confirmed_by');
            $table->text('reject_reason')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('minyak_hutang_bayar', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['status', 'confirmed_by', 'confirmed_at', 'reject_reason']);
        });
    }
};

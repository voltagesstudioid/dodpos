<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gula_hutang_bayar', function (Blueprint $table) {
            $table->string('status', 20)->default('confirmed')->after('created_by');
            $table->unsignedBigInteger('confirmed_by')->nullable()->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');
            $table->string('reject_reason', 500)->nullable()->after('confirmed_at');

            $table->foreign('confirmed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('gula_hutang_bayar', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn(['status', 'confirmed_by', 'confirmed_at', 'reject_reason']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mineral_loading', function (Blueprint $table) {
            $table->foreignId('mobil_inti_id')->nullable()->constrained('mineral_sales')->after('sales_id');
            $table->string('status_approval', 20)->default('approved')->after('keterangan');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('status_approval');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('alasan')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('mineral_loading', function (Blueprint $table) {
            $table->dropForeign(['mobil_inti_id']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['mobil_inti_id', 'status_approval', 'approved_by', 'approved_at', 'alasan']);
        });
    }
};

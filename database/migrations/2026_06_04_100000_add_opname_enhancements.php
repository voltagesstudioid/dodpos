<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Timestamp kapan item dihitung
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->timestamp('counted_at')->nullable()->after('notes');
        });

        // Deadline & reversal tracking
        Schema::table('stock_opname_sessions', function (Blueprint $table) {
            $table->timestamp('deadline_at')->nullable()->after('approval_notes');
            $table->timestamp('reversed_at')->nullable()->after('deadline_at');
            $table->foreignId('reversed_by')->nullable()->after('reversed_at')->constrained('users')->nullOnDelete();
            $table->text('reversal_notes')->nullable()->after('reversed_by');
        });
    }

    public function down(): void
    {
        Schema::table('stock_opname_items', function (Blueprint $table) {
            $table->dropColumn('counted_at');
        });

        Schema::table('stock_opname_sessions', function (Blueprint $table) {
            $table->dropColumn(['deadline_at', 'reversed_at', 'reversal_notes']);
            $table->dropForeign(['reversed_by']);
            $table->dropColumn('reversed_by');
        });
    }
};

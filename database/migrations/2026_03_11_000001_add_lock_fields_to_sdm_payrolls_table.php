<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            if (! Schema::hasColumn('sdm_payrolls', 'locked_at')) {
                $table->timestamp('locked_at')->nullable()->after('net_salary')->index();
            }
            if (! Schema::hasColumn('sdm_payrolls', 'locked_by')) {
                $table->foreignId('locked_by')->nullable()->after('locked_at')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            if (Schema::hasColumn('sdm_payrolls', 'locked_by')) {
                $table->dropForeign(['locked_by']);
                $table->dropColumn('locked_by');
            }
            if (Schema::hasColumn('sdm_payrolls', 'locked_at')) {
                $table->dropColumn('locked_at');
            }
        });
    }
};

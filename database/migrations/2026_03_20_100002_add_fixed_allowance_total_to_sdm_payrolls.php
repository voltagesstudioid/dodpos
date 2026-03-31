<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            if (! Schema::hasColumn('sdm_payrolls', 'fixed_allowance_total')) {
                $table->decimal('fixed_allowance_total', 15, 2)->default(0)->after('total_allowance');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sdm_payrolls', function (Blueprint $table) {
            if (Schema::hasColumn('sdm_payrolls', 'fixed_allowance_total')) {
                $table->dropColumn('fixed_allowance_total');
            }
        });
    }
};

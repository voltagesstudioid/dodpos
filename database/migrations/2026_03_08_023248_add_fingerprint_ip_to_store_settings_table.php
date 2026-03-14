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
        Schema::table('store_settings', function (Blueprint $table) {
            $table->string('fingerprint_ip')->nullable()->after('timezone')->comment('IP Address of the ZKTeco Fingerprint Machine');
            $table->string('fingerprint_port')->default('4370')->after('fingerprint_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_settings', function (Blueprint $table) {
            $table->dropColumn(['fingerprint_ip', 'fingerprint_port']);
        });
    }
};

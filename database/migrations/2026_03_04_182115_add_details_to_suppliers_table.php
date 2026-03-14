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
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
            $table->string('city')->nullable()->after('address');
            $table->string('npwp')->nullable()->after('city');
            $table->string('bank_name')->nullable()->after('npwp');
            $table->string('bank_account')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account');
            $table->integer('term_days')->default(0)->after('bank_account_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            //
        });
    }
};

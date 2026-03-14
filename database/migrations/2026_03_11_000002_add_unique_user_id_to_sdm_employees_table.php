<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateUserIds = DB::table('sdm_employees')
            ->select('user_id')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('user_id');

        foreach ($duplicateUserIds as $userId) {
            $ids = DB::table('sdm_employees')
                ->where('user_id', $userId)
                ->orderBy('id')
                ->pluck('id')
                ->all();

            $keepId = array_shift($ids);
            if (! $keepId) {
                continue;
            }

            if (count($ids) > 0) {
                DB::table('sdm_employees')
                    ->whereIn('id', $ids)
                    ->update(['user_id' => null]);
            }
        }

        Schema::table('sdm_employees', function (Blueprint $table) {
            $table->unique('user_id', 'sdm_employees_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('sdm_employees', function (Blueprint $table) {
            $table->dropUnique('sdm_employees_user_id_unique');
        });
    }
};

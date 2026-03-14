<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();
        if ($driver !== 'mysql') {
            return;
        }

        if (! Schema::hasTable('kanvas_setorans')) {
            return;
        }

        $cols = ['expected_cash', 'expected_tempo', 'actual_cash'];
        foreach ($cols as $col) {
            if (! Schema::hasColumn('kanvas_setorans', $col)) {
                continue;
            }
            $safeCol = str_replace('`', '``', $col);
            DB::statement("ALTER TABLE `kanvas_setorans` MODIFY `{$safeCol}` DECIMAL(15,2) NOT NULL DEFAULT 0");
        }
    }
};

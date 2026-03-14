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

        $ops = [
            ['operational_expenses', 'amount', false],

            ['mineral_products', 'price_cash', false],
            ['mineral_products', 'price_tempo', false],
            ['mineral_transactions', 'total_amount', false],
            ['mineral_transaction_items', 'price', false],
            ['mineral_setorans', 'total_cash_expected', false],
            ['mineral_setorans', 'actual_cash', true],
            ['mineral_setorans', 'total_piutang_expected', false],

            ['kanvas_products', 'price_cash', false],
            ['kanvas_products', 'price_tempo', false],
            ['kanvas_loading_items', 'price_snapshot', true],
            ['kanvas_transactions', 'subtotal', false],
            ['kanvas_transactions', 'discount_amount', false],
            ['kanvas_transactions', 'total_amount', false],
            ['kanvas_transaction_items', 'price', false],
            ['kanvas_transaction_items', 'subtotal', false],
        ];

        foreach ($ops as [$table, $column, $nullable]) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                continue;
            }
            $safeTable = str_replace('`', '``', $table);
            $safeColumn = str_replace('`', '``', $column);
            if ($nullable) {
                DB::statement("ALTER TABLE `{$safeTable}` MODIFY `{$safeColumn}` DECIMAL(15,2) NULL");
            } else {
                DB::statement("ALTER TABLE `{$safeTable}` MODIFY `{$safeColumn}` DECIMAL(15,2) NOT NULL DEFAULT 0");
            }
        }
    }
};

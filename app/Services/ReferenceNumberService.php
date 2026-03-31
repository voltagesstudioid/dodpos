<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReferenceNumberService
{
    /**
     * Generate a sequential reference number with format: PREFIX-DATE-SEQUENCE
     * 
     * @param string $modelClass The model class to query
     * @param string $column The column containing the reference number
     * @param string $prefix The prefix for the reference (e.g., 'TRF', 'SO', 'PRD')
     * @param int $sequenceLength Length of the sequence number (default 3)
     * @return string
     */
    public static function generate(
        string $modelClass,
        string $column,
        string $prefix,
        int $sequenceLength = 3
    ): string {
        $date = date('Ymd');
        $base = "{$prefix}-{$date}";
        $pattern = $base . '-%';

        /** @var Model $model */
        $last = $modelClass::where($column, 'like', $pattern)
            ->lockForUpdate()
            ->orderByRaw("CAST(SUBSTRING({$column}, -{$sequenceLength}) AS UNSIGNED) DESC")
            ->first();

        if (! $last) {
            return $base . '-' . str_pad('1', $sequenceLength, '0', STR_PAD_LEFT);
        }

        $lastNum = (int) substr($last->{$column}, -$sequenceLength);
        $nextNum = $lastNum + 1;

        return $base . '-' . str_pad((string) $nextNum, $sequenceLength, '0', STR_PAD_LEFT);
    }

    /**
     * Generate transfer reference number (TRF-YYYYMMDD-XXX)
     */
    public static function generateTransferRef(): string
    {
        return self::generate(\App\Models\StockMovement::class, 'reference_number', 'TRF', 3);
    }

    /**
     * Generate Sales Order number (SO-YYYYMMDD-XXXX)
     */
    public static function generateSoNumber(): string
    {
        return self::generate(\App\Models\SalesOrder::class, 'so_number', 'SO', 4);
    }

    /**
     * Generate Product SKU (PRD-XXXX)
     */
    public static function generateSku(): string
    {
        $lastProduct = \App\Models\Product::where('sku', 'like', 'PRD-%')
            ->orderBy('id', 'desc')
            ->first();

        if (! $lastProduct) {
            return 'PRD-0001';
        }

        $number = (int) str_replace('PRD-', '', $lastProduct->sku);

        return 'PRD-' . str_pad((string) ($number + 1), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate Customer Credit number (HTG-YYYYMMDD-XXX)
     */
    public static function generateCreditNumber(string $type = 'debt'): string
    {
        $prefix = $type === 'payment' ? 'BYR' : 'HTG';
        $date = date('Ymd');
        $base = "{$prefix}-{$date}";
        $pattern = $base . '-%';

        $last = \App\Models\CustomerCredit::where('credit_number', 'like', $pattern)
            ->lockForUpdate()
            ->orderByRaw("CAST(SUBSTRING(credit_number, -3) AS UNSIGNED) DESC")
            ->first();

        if (! $last) {
            return $base . '-001';
        }

        $lastNum = (int) substr($last->credit_number, -3);
        $nextNum = $lastNum + 1;

        return $base . '-' . str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);
    }
}

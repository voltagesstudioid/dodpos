<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name',
        'store_phone',
        'store_email',
        'store_address',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'logo_path',
        'timezone',
        'currency_symbol',
        'tax_rate',
        'rounding_mode',
        'receipt_header',
        'receipt_footer',
        'fingerprint_ip',
        'fingerprint_port',
        'sdm_work_start_time',
        'sdm_work_end_time',
        'sdm_late_grace_minutes',
        'sdm_overtime_rate_per_hour',
        'sdm_late_meal_cut_mode',
        'sdm_late_meal_cut_value',
        'sdm_working_days_mode',
        'sdm_calendar_mode',
    ];

    protected $casts = [
        'tax_rate' => 'decimal:2',
        'sdm_overtime_rate_per_hour' => 'decimal:2',
        'sdm_late_meal_cut_value' => 'decimal:2',
    ];

    public static function current(): self
    {
        $existing = self::query()->first();
        if ($existing) {
            return $existing;
        }

        return self::query()->create([
            'store_name' => config('app.name', 'DODPOS'),
            'timezone' => config('app.timezone', 'Asia/Jakarta'),
        ]);
    }
}

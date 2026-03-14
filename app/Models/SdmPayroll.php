<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdmPayroll extends Model
{
    protected $fillable = [
        'user_id',
        'period_month',
        'period_year',
        'working_days',
        'present_days',
        'late_days',
        'izin_days',
        'sakit_days',
        'absent_days',
        'missing_days',
        'unpaid_leave_days',
        'total_attendance',
        'total_basic_salary',
        'override_total_basic_salary',
        'total_allowance',
        'meal_allowance_per_day',
        'meal_allowance_gross',
        'late_meal_penalty',
        'override_late_meal_penalty',
        'overtime_minutes',
        'overtime_pay',
        'incentive_amount',
        'performance_bonus',
        'total_deductions',
        'absence_deduction',
        'override_absence_deduction',
        'net_salary',
        'locked_at',
        'locked_by',
    ];

    protected $casts = [
        'total_basic_salary' => 'decimal:2',
        'override_total_basic_salary' => 'decimal:2',
        'total_allowance' => 'decimal:2',
        'meal_allowance_per_day' => 'decimal:2',
        'meal_allowance_gross' => 'decimal:2',
        'late_meal_penalty' => 'decimal:2',
        'override_late_meal_penalty' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'incentive_amount' => 'decimal:2',
        'performance_bonus' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'absence_deduction' => 'decimal:2',
        'override_absence_deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'locked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Customer extends Model
{
    use SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Customer has been {$eventName}");
    }

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'category',
        'credit_limit', 'current_debt', 'is_active', 'notes',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_debt' => 'decimal:2',
        'is_active'    => 'boolean',
    ];

    public function credits()
    {
        return $this->hasMany(CustomerCredit::class);
    }

    public function activeDebts()
    {
        return $this->hasMany(CustomerCredit::class)
            ->where('type', 'debt')
            ->whereIn('status', ['unpaid', 'partial']);
    }

    public function getRemainingCreditLimitAttribute(): float
    {
        return max(0, $this->credit_limit - $this->current_debt);
    }

    public function refreshDebt(): void
    {
        $total = $this->credits()
            ->where('type', 'debt')
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum(\Illuminate\Support\Facades\DB::raw('amount - paid_amount'));
        $this->update(['current_debt' => $total]);
    }
}

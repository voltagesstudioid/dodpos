<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SdmEmployee extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'position',
        'join_date',
        'active',
        'user_id',
        'notes',
        'basic_salary',
        'daily_allowance',
    ];

    protected $casts = [
        'join_date' => 'date',
        'active' => 'boolean',
        'basic_salary' => 'decimal:2',
        'daily_allowance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function allowances(): HasMany
    {
        return $this->hasMany(SdmEmployeeAllowance::class, 'employee_id');
    }
}


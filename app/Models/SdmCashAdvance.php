<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdmCashAdvance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'amount',
        'purpose',
        'status',
        'deduction_month',
        'approved_by',
        'approved_at',
        'deduction_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deduction(): BelongsTo
    {
        return $this->belongsTo(SdmDeduction::class, 'deduction_id');
    }
}

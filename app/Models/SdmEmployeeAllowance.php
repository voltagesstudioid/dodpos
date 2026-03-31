<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdmEmployeeAllowance extends Model
{
    protected $fillable = [
        'employee_id',
        'label',
        'amount',
        'active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(SdmEmployee::class);
    }
}

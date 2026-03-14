<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSession extends Model
{
    protected $fillable = [
        'user_id',
        'opening_amount',
        'payment_method',
        'closing_amount',
        'expected_cash',
        'actual_cash',
        'cash_variance',
        'opened_at',
        'closed_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_amount' => 'decimal:2',
        'closing_amount' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'cash_variance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashMovements()
    {
        return $this->hasMany(PosCashMovement::class, 'pos_session_id')->latest();
    }
}

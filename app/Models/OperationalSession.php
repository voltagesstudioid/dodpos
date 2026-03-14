<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalSession extends Model
{
    protected $fillable = [
        'user_id',
        'opening_amount',
        'closing_amount',
        'payment_method',
        'status',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function expenses()
    {
        return $this->hasMany(\App\Models\OperationalExpense::class, 'operational_session_id');
    }
}

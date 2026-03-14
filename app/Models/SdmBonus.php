<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdmBonus extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'description',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

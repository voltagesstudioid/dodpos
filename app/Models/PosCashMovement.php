<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosCashMovement extends Model
{
    protected $fillable = [
        'pos_session_id',
        'type',
        'amount',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(PosSession::class, 'pos_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

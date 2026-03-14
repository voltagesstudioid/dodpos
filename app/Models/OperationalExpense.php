<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalExpense extends Model
{
    protected $fillable = [
        'date',
        'category_id',
        'vehicle_id',
        'operational_session_id',
        'amount',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function operationalSession()
    {
        return $this->belongsTo(OperationalSession::class, 'operational_session_id');
    }

    public function category()
    {
        return $this->belongsTo(OperationalCategory::class, 'category_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

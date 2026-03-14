<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaLoading extends Model
{
    protected $fillable = [
        'loading_number', 'date', 'user_id', 'sales_id', 'vehicle_id', 'status', 'notes'
    ];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function sales() { return $this->belongsTo(User::class, 'sales_id'); }
    public function vehicle() { return $this->belongsTo(Vehicle::class, 'vehicle_id'); }
    public function items() { return $this->hasMany(GulaLoadingItem::class); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = [
        'so_number',
        'customer_id',
        'user_id',
        'order_date',
        'delivery_date',
        'total_amount',
        'status',
        'order_type',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

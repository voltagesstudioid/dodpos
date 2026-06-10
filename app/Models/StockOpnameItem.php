<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    protected $fillable = [
        'session_id',
        'product_id',
        'system_qty',
        'physical_qty',
        'counted_unit',
        'counted_qty',
        'difference_qty',
        'notes',
        'counted_at',
    ];

    protected $casts = [
        'counted_at' => 'datetime',
        'counted_qty' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(StockOpnameSession::class, 'session_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

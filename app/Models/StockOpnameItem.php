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
        'difference_qty',
        'notes',
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

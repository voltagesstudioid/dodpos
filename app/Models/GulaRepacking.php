<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaRepacking extends Model
{
    protected $fillable = [
        'gula_product_id', 'user_id', 'date', 'minus_qty_karung', 'plus_qty_eceran',
        'loss_qty_eceran', 'notes'
    ];

    public function product()
    {
        return $this->belongsTo(GulaProduct::class, 'gula_product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

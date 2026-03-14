<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaWarehouseStock extends Model
{
    protected $fillable = [
        'gula_product_id', 'qty_karung', 'qty_bal', 'qty_eceran'
    ];

    public function product()
    {
        return $this->belongsTo(GulaProduct::class, 'gula_product_id');
    }
}

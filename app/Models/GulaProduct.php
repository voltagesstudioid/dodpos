<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaProduct extends Model
{
    protected $fillable = [
        'name', 'type', 'base_price', 'price_karungan', 'price_bal', 'price_eceran',
        'qty_per_karung', 'qty_per_bal', 'is_active'
    ];

    public function warehouseStocks()
    {
        return $this->hasMany(GulaWarehouseStock::class);
    }
}

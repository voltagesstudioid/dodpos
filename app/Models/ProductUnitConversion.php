<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnitConversion extends Model
{
    protected $fillable = [
        'product_id',
        'unit_id',
        'conversion_factor',
        'purchase_price',
        'sell_price_ecer',
        'sell_price_grosir',
        'sell_price_jual1',
        'sell_price_jual2',
        'sell_price_jual3',
        'sell_price_minimal',
        'is_base_unit',
    ];

    protected $casts = [
        'is_base_unit'     => 'boolean',
        'conversion_factor'=> 'integer',
        'purchase_price'   => 'decimal:2',
        'sell_price_ecer'  => 'decimal:2',
        'sell_price_grosir'=> 'decimal:2',
        'sell_price_jual1' => 'decimal:2',
        'sell_price_jual2' => 'decimal:2',
        'sell_price_jual3' => 'decimal:2',
        'sell_price_minimal' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

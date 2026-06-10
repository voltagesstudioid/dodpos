<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarPenjualanItem extends Model
{
    protected $table = 'pasgar_penjualan_items';

    protected $fillable = [
        'penjualan_id',
        'product_id',
        'unit_conversion_id',
        'qty',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(PasgarPenjualan::class, 'penjualan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function unitConversion()
    {
        return $this->belongsTo(ProductUnitConversion::class, 'unit_conversion_id');
    }
}

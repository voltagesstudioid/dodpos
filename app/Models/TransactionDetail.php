<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id', 'product_id',
        'warehouse_id',
        'unit_conversion_id', 'unit_qty', 'unit_name',
        'quantity', 'price', 'subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unitConversion()
    {
        return $this->belongsTo(ProductUnitConversion::class, 'unit_conversion_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

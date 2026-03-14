<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    protected $fillable = [
        'purchase_return_id', 'product_id', 'unit_id',
        'quantity', 'purchase_price', 'reason',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
    ];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->purchase_price;
    }
}

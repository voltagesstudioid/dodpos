<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'product_id', 'unit_id', 'conversion_factor',
        'qty_ordered', 'qty_received',
        'unit_price', 'subtotal', 'notes',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Remaining quantity to receive.
     */
    public function getRemainingQtyAttribute(): int
    {
        return max(0, $this->qty_ordered - $this->qty_received);
    }
}

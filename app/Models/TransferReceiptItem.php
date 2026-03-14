<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferReceiptItem extends Model
{
    protected $fillable = [
        'receipt_id',
        'stock_movement_id',
        'product_id',
        'expected_qty',
        'received_qty',
        'rejected_qty',
        'qty_ok',
        'quality_ok',
        'spec_ok',
        'packaging_ok',
        'notes',
    ];

    public function receipt()
    {
        return $this->belongsTo(TransferReceipt::class, 'receipt_id');
    }

    public function movement()
    {
        return $this->belongsTo(StockMovement::class, 'stock_movement_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

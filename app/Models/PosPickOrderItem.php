<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPickOrderItem extends Model
{
    protected $fillable = [
        'pick_order_id', 'transaction_detail_id', 'product_id',
        'quantity', 'unit_qty', 'unit_name',
    ];

    public function pickOrder()
    {
        return $this->belongsTo(PosPickOrder::class, 'pick_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosReturnItem extends Model
{
    protected $fillable = [
        'pos_return_id',
        'transaction_detail_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function posReturn()
    {
        return $this->belongsTo(PosReturn::class);
    }

    public function transactionDetail()
    {
        return $this->belongsTo(TransactionDetail::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

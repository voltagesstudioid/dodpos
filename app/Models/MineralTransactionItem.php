<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralTransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty_dus',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(MineralTransaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(MineralProduct::class, 'product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasTransactionItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(KanvasTransaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(KanvasProduct::class, 'product_id');
    }
}

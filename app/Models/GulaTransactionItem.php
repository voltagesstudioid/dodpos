<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaTransactionItem extends Model
{
    protected $fillable = [
        'gula_transaction_id', 'gula_product_id', 'unit_type', 'qty', 'price', 'subtotal'
    ];

    public function transaction() { return $this->belongsTo(GulaTransaction::class, 'gula_transaction_id'); }
    public function product() { return $this->belongsTo(GulaProduct::class, 'gula_product_id'); }
}

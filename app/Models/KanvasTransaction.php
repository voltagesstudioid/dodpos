<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(KanvasTransactionItem::class, 'transaction_id');
    }
}

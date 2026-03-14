<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'customer_id',
        'receipt_number',
        'payment_method', // 'cash', 'tempo'
        'due_date',
        'total_amount',
        'status', // 'paid', 'unpaid'
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
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
        return $this->hasMany(MineralTransactionItem::class, 'transaction_id');
    }
}

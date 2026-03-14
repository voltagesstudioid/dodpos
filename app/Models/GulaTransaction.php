<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaTransaction extends Model
{
    protected $fillable = [
        'invoice_number', 'date', 'sales_id', 'customer_id', 'subtotal', 'discount', 
        'total', 'payment_method', 'due_date', 'paid_amount', 'payment_status', 'notes'
    ];

    public function sales() { return $this->belongsTo(User::class, 'sales_id'); }
    public function customer() { return $this->belongsTo(Customer::class, 'customer_id'); }
    public function items() { return $this->hasMany(GulaTransactionItem::class); }
}

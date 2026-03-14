<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCreditPayment extends Model
{
    protected $fillable = [
        'customer_credit_id', 'payment_date', 'amount',
        'payment_method', 'reference_number', 'notes', 'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function customerCredit()
    {
        return $this->belongsTo(CustomerCredit::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash'     => '💵 Tunai',
            'transfer' => '🏦 Transfer',
            'qris'     => '📱 QRIS',
            'other'    => 'Lainnya',
            default    => ucfirst($this->payment_method),
        };
    }
}

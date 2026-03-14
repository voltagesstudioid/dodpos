<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierDebtPayment extends Model
{
    protected $fillable = [
        'supplier_debt_id', 'payment_date', 'amount',
        'payment_method', 'reference_number', 'notes', 'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function supplierDebt()
    {
        return $this->belongsTo(SupplierDebt::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash'     => 'Tunai',
            'transfer' => 'Transfer Bank',
            'check'    => 'Cek/Giro',
            'other'    => 'Lainnya',
            default    => $this->payment_method,
        };
    }
}

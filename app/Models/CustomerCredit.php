<?php

namespace App\Models;

use App\Services\ReferenceNumberService;
use Illuminate\Database\Eloquent\Model;

class CustomerCredit extends Model
{
    protected $fillable = [
        'credit_number', 'customer_id', 'transaction_id', 'type',
        'transaction_date', 'due_date', 'amount', 'paid_amount',
        'status', 'description', 'notes', 'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'due_date'         => 'date',
        'amount'           => 'decimal:2',
        'paid_amount'      => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payments()
    {
        return $this->hasMany(CustomerCreditPayment::class)->latest();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->amount - $this->paid_amount);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'paid';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'debt' ? 'Hutang Pelanggan' : 'Piutang / Kredit';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'unpaid'  => '<span class="badge-danger">Belum Lunas</span>',
            'partial' => '<span class="badge-indigo">Sebagian</span>',
            'paid'    => '<span class="badge-success">Lunas</span>',
            default   => '<span class="badge-indigo">' . $this->status . '</span>',
        };
    }

    /**
     * @deprecated Use ReferenceNumberService::generateCreditNumber()
     */
    public static function generateNumber(string $type = 'debt'): string
    {
        return ReferenceNumberService::generateCreditNumber($type);
    }
}

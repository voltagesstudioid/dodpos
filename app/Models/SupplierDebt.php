<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierDebt extends Model
{
    protected $fillable = [
        'invoice_number', 'supplier_id', 'purchase_order_id',
        'transaction_date', 'due_date', 'total_amount', 'paid_amount', 'status', 'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'due_date'         => 'date',
        'total_amount'     => 'decimal:2',
        'paid_amount'      => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierDebtPayment::class)->latest();
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'unpaid'  => '<span class="badge-danger">Belum Bayar</span>',
            'partial' => '<span class="badge-indigo">Sebagian</span>',
            'paid'    => '<span class="badge-success">Lunas</span>',
            default   => '<span class="badge-indigo">' . $this->status . '</span>',
        };
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'paid';
    }

    public static function generateInvoiceNumber(): string
    {
        $last = static::latest()->first();
        $num  = $last ? (int) substr($last->invoice_number, -4) + 1 : 1;
        return 'HUT-' . date('Ymd') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}

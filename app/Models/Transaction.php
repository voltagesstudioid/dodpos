<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Transaction extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Transaction has been {$eventName}");
    }

    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'change_amount',
        'payment_method',
        'payment_reference',
        'status',
        'print_count',
        'last_printed_at',
        'sale_type',
        'parent_transaction_id',
        'transaction_type',
        'additional_notes',
        'delivery_status',
        'source_warehouse_id',
        'vehicle_id',
        'driver_name',
        'packed_by',
        'packed_at',
        'checked_by',
        'checked_at',
        'delivered_by',
        'delivered_at',
        'delivery_notes',
    ];

    protected $casts = [
        'packed_at' => 'datetime',
        'checked_at' => 'datetime',
        'delivered_at' => 'datetime',
        'last_printed_at' => 'datetime',
    ];

    public function getInvoiceNumberAttribute($value): string
    {
        if ($value) {
            return $value;
        }
        $prefix = ($this->sale_type ?? 'eceran') === 'grosir' ? 'INV-GRS-' : 'TRX-';
        return $prefix . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function sourceWarehouse()
    {
        return $this->belongsTo(\App\Models\Warehouse::class, 'source_warehouse_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function packedBy()
    {
        return $this->belongsTo(User::class, 'packed_by');
    }

    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function deliveredBy()
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function parentTransaction()
    {
        return $this->belongsTo(Transaction::class, 'parent_transaction_id');
    }

    public function additionalTransactions()
    {
        return $this->hasMany(Transaction::class, 'parent_transaction_id');
    }

    public function returns()
    {
        return $this->hasMany(PosReturn::class, 'transaction_id');
    }

    public function hasReturns(): bool
    {
        return $this->returns->where('status', 'completed')->isNotEmpty();
    }

    public function isAdditional(): bool
    {
        return $this->transaction_type === 'additional';
    }

    public function hasAdditionalItems(): bool
    {
        return $this->additionalTransactions->isNotEmpty();
    }

    public function getAllDetailsAttribute()
    {
        // Get all details from parent and all children
        $allDetails = collect($this->details);
        foreach ($this->additionalTransactions as $addTrans) {
            $allDetails = $allDetails->merge($addTrans->details);
        }
        return $allDetails;
    }

    public function getGrandTotalAttribute(): float
    {
        $additionalTotal = $this->additionalTransactions->sum('total_amount');
        return (float) $this->total_amount + $additionalTotal;
    }

    public function getTotalPaidAttribute(): float
    {
        $additionalPaid = $this->additionalTransactions->sum('paid_amount');
        return (float) $this->paid_amount + $additionalPaid;
    }
}

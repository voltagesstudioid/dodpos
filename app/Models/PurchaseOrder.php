<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrder extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "Purchase Order has been {$eventName}");
    }

    protected $fillable = [
        'po_number', 'supplier_id', 'status',
        'order_date', 'expected_date',
        'due_date',
        'total_amount', 'notes', 'user_id',
        'payment_term',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function shortageReports()
    {
        return $this->hasMany(PurchaseOrderShortageReport::class)->latest();
    }

    public function receipts()
    {
        return $this->hasMany(PurchaseOrderReceipt::class)->latest();
    }

    /**
     * Get human-readable status label + color.
     */
    public function getStatusLabelAttribute(): array
    {
        return match ($this->status) {
            'draft' => ['label' => 'Draft',          'color' => '#94a3b8',  'bg' => '#f1f5f9'],
            'ordered' => ['label' => 'Dipesan',        'color' => '#2563eb',  'bg' => '#dbeafe'],
            'partial' => ['label' => 'Diterima Sebagian', 'color' => '#d97706', 'bg' => '#fef3c7'],
            'received' => ['label' => 'Diterima Penuh', 'color' => '#16a34a',  'bg' => '#dcfce7'],
            'cancelled' => ['label' => 'Dibatalkan',     'color' => '#dc2626',  'bg' => '#fee2e2'],
            default => ['label' => $this->status,    'color' => '#64748b',  'bg' => '#f8fafc'],
        };
    }

    /**
     * Auto-generate PO number.
     */
    public static function generatePoNumber(): string
    {
        $prefix = 'PO-'.date('Ymd');
        $last = static::where('po_number', 'like', $prefix.'%')
            ->orderBy('po_number', 'desc')
            ->first();

        if (! $last) {
            return $prefix.'-001';
        }

        $lastNum = (int) substr($last->po_number, -3);

        return $prefix.'-'.str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}

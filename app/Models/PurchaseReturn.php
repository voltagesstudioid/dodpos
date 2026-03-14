<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $fillable = [
        'return_number', 'supplier_id', 'purchase_order_id',
        'warehouse_id',
        'return_date', 'status', 'total_amount', 'reason', 'notes', 'created_by',
    ];

    protected $casts = [
        'return_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNumber(): string
    {
        $last = static::latest()->first();
        $num = $last ? (int) substr($last->return_number, -4) + 1 : 1;

        return 'RTN-'.date('Ymd').'-'.str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'draft' => '<span class="badge-indigo">Draft</span>',
            'approved' => '<span class="badge-blue">Disetujui</span>',
            'returned' => '<span class="badge-success">Selesai</span>',
            default => '<span class="badge-indigo">'.$this->status.'</span>',
        };
    }
}

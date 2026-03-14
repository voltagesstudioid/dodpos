<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderReceipt extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'purchase_return_id',
        'reorder_purchase_order_id',
        'warehouse_id',
        'received_by',
        'status',
        'needs_followup',
        'followup_status',
        'followup_action',
        'followup_notes',
        'resolved_by',
        'resolved_at',
        'photos',
        'notes',
    ];

    protected $casts = [
        'photos' => 'array',
        'needs_followup' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function reorderPurchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'reorder_purchase_order_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderReceiptItem::class, 'receipt_id');
    }
}

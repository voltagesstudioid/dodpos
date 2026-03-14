<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderShortageReport extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'reported_by',
        'items',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}

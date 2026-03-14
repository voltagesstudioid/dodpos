<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderReceiptItem extends Model
{
    protected $fillable = [
        'receipt_id',
        'purchase_order_item_id',
        'product_id',
        'qty_remaining_before',
        'qty_received_po_unit',
        'qty_received_base',
        'result',
        'batch_number',
        'expired_date',
        'quality_ok',
        'spec_ok',
        'packaging_ok',
        'notes',
    ];

    protected $casts = [
        'expired_date' => 'date',
        'quality_ok' => 'boolean',
        'spec_ok' => 'boolean',
        'packaging_ok' => 'boolean',
    ];

    public function receipt()
    {
        return $this->belongsTo(PurchaseOrderReceipt::class, 'receipt_id');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

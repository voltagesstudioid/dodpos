<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferReceipt extends Model
{
    protected $fillable = [
        'reference_number',
        'warehouse_id',
        'received_by',
        'status',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(TransferReceiptItem::class, 'receipt_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'quantity',
        'unit_id',
        'conversion_factor',
        'type',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'supervisor_notes',
        'transfer_reference',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'conversion_factor' => 'decimal:4',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

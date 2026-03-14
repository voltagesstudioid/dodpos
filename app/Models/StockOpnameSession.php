<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameSession extends Model
{
    protected $fillable = [
        'warehouse_id',
        'created_by',
        'status',
        'reference_number',
        'notes',
        'submitted_at',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items()
    {
        return $this->hasMany(StockOpnameItem::class, 'session_id');
    }
}

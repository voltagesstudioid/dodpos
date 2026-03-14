<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockTransfer extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Stock Transfer has been {$eventName}");
    }

    protected $fillable = [
        'transfer_number',
        'date',
        'from_warehouse_id',
        'to_warehouse_id',
        'notes',
        'status', // pending, approved, rejected, completed
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Generate unique transfer number (e.g. TRF-260301-001)
     */
    public static function generateNumber()
    {
        $prefix = 'TRF-';
        $date = now()->format('yymd');
        
        $lastRecord = self::where('transfer_number', 'LIKE', $prefix . $date . '-%')
                          ->orderBy('id', 'desc')
                          ->first();
                          
        if (!$lastRecord) {
            $sequence = '001';
        } else {
            // Extract the sequence
            $lastNumber = explode('-', $lastRecord->transfer_number)[2];
            $sequence = str_pad(intval($lastNumber) + 1, 3, '0', STR_PAD_LEFT);
        }
        
        return $prefix . $date . '-' . $sequence;
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class);
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

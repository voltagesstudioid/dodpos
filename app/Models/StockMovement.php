<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StockMovement extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Stock Movement has been {$eventName}");
    }

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'location_id',
        'type',
        'status',
        'source_type',
        'purchase_order_id',
        'reference_number',
        'batch_number',
        'expired_date',
        'quantity',
        'balance',
        'notes',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

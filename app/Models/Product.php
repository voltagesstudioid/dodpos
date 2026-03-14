<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Product has been {$eventName}");
    }

    protected $fillable = [
        'category_id', 'unit_id',
        'name', 'description', 'sku', 'barcode',
        'price', 'purchase_price', 'stock', 'min_stock', 'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function unitConversions()
    {
        return $this->hasMany(ProductUnitConversion::class)->orderBy('conversion_factor');
    }

    public function baseUnit()
    {
        return $this->hasOne(ProductUnitConversion::class)->where('is_base_unit', true);
    }
}

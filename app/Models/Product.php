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

    public function getBaseUnitNameAttribute()
    {
        // Find base unit in conversions
        $baseConv = $this->unitConversions->firstWhere('is_base_unit', true);
        if ($baseConv && $baseConv->unit) {
            return $baseConv->unit->abbreviation ?? $baseConv->unit->name;
        }

        // Fallback to product's unit_id
        if ($this->unit) {
            return $this->unit->abbreviation ?? $this->unit->name;
        }

        return '';
    }

    /**
     * Break down a stock quantity (in base unit) into compound units.
     * e.g. 1987 Bungkus → "9 Dus 18 Slop 7 Bungkus"
     *
     * @param int|float $stockInBaseUnit Stock quantity in base unit
     * @return string Human-readable compound unit string
     */
    public function breakdownStock($stockInBaseUnit): string
    {
        $conversions = $this->unitConversions;
        if (!$conversions || $conversions->count() <= 1) {
            return number_format($stockInBaseUnit) . ' ' . ($this->base_unit_name ?: '');
        }

        // Sort by conversion_factor DESC (largest unit first), then base unit last
        $sorted = $conversions->sortByDesc('conversion_factor')->values();

        $remaining = (int) $stockInBaseUnit;
        $parts = [];

        foreach ($sorted as $conv) {
            $factor = (int) $conv->conversion_factor;
            if ($factor <= 1) {
                // This is the base unit — whatever remains goes here
                if ($remaining > 0) {
                    $unitName = $conv->unit->abbreviation ?? $conv->unit->name ?? '';
                    $parts[] = $remaining . ' ' . $unitName;
                }
                continue;
            }

            if ($remaining >= $factor) {
                $count = intdiv($remaining, $factor);
                $remaining = $remaining % $factor;
                $unitName = $conv->unit->abbreviation ?? $conv->unit->name ?? '';
                $parts[] = $count . ' ' . $unitName;
            }
        }

        // If no parts generated (e.g. stock = 0 or no matching conversions)
        if (empty($parts)) {
            return number_format($stockInBaseUnit) . ' ' . ($this->base_unit_name ?: '');
        }

        return implode(' ', $parts);
    }
}

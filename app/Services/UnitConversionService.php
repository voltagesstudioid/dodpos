<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\Unit;

class UnitConversionService
{
    /**
     * Convert quantity from one unit to base unit.
     *
     * @param  float  $quantity  Quantity in source unit
     * @param  float  $conversionFactor  Factor to convert to base unit
     * @return int Quantity in base unit (rounded)
     */
    public static function toBaseUnit(float $quantity, float $conversionFactor): int
    {
        return (int) round($quantity * $conversionFactor);
    }

    /**
     * Convert quantity from base unit to target unit.
     *
     * @param  int  $baseQuantity  Quantity in base unit
     * @param  float  $conversionFactor  Factor of target unit
     * @return float Quantity in target unit
     */
    public static function fromBaseUnit(int $baseQuantity, float $conversionFactor): float
    {
        if ($conversionFactor <= 0) {
            return (float) $baseQuantity;
        }

        return round($baseQuantity / $conversionFactor, 4);
    }

    /**
     * Get conversion factor for a product's unit.
     *
     * @param  int  $productId
     * @param  int|null  $unitId
     * @return float Conversion factor (1.0 if not found or base unit)
     */
    public static function getConversionFactor(int $productId, ?int $unitId): float
    {
        if (! $unitId) {
            return 1.0;
        }

        $conversion = ProductUnitConversion::where('product_id', $productId)
            ->where('unit_id', $unitId)
            ->first();

        return $conversion?->conversion_factor ?? 1.0;
    }

    /**
     * Get available units for a product.
     *
     * @param  int  $productId
     * @return array Array of units with conversion factors
     */
    public static function getProductUnits(int $productId): array
    {
        $product = Product::with(['unit', 'unitConversions.unit'])->find($productId);

        if (! $product) {
            return [];
        }

        $units = [];

        // Add base unit
        if ($product->unit) {
            $units[] = [
                'id' => $product->unit->id,
                'name' => $product->unit->name,
                'abbreviation' => $product->unit->abbreviation,
                'conversion_factor' => 1.0,
                'is_base' => true,
            ];
        }

        // Add conversion units
        foreach ($product->unitConversions as $uc) {
            $units[] = [
                'id' => $uc->unit->id,
                'name' => $uc->unit->name,
                'abbreviation' => $uc->unit->abbreviation,
                'conversion_factor' => (float) $uc->conversion_factor,
                'is_base' => (bool) $uc->is_base_unit,
            ];
        }

        return $units;
    }

    /**
     * Format quantity with unit name.
     *
     * @param  float|int  $quantity
     * @param  string|null  $unitName
     * @param  bool  $showBaseEquivalent  Show "(= X base_unit)" suffix
     * @param  float|null  $baseQuantity  Base quantity for equivalent display
     * @param  string|null  $baseUnitName  Base unit name
     * @return string Formatted quantity string
     */
    public static function formatQuantity(
        float|int $quantity,
        ?string $unitName = null,
        bool $showBaseEquivalent = false,
        ?float $baseQuantity = null,
        ?string $baseUnitName = null
    ): string {
        $unitName = $unitName ?? 'satuan dasar';

        if ($quantity == (int) $quantity) {
            $qtyStr = (string) (int) $quantity;
        } else {
            $qtyStr = number_format($quantity, 2);
        }

        $result = "{$qtyStr} {$unitName}";

        if ($showBaseEquivalent && $baseQuantity !== null && $baseUnitName) {
            $baseQtyStr = $baseQuantity == (int) $baseQuantity
                ? (string) (int) $baseQuantity
                : number_format($baseQuantity, 2);
            $result .= " (= {$baseQtyStr} {$baseUnitName})";
        }

        return $result;
    }
}

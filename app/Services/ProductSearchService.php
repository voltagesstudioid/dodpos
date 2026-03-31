<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Customer;
use App\Support\SearchSanitizer;
use Illuminate\Database\Eloquent\Collection;

class ProductSearchService
{
    /**
     * Get base product query with eager loaded relations.
     */
    public function getProductsQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Product::with(['category', 'unitConversions.unit', 'productStocks.warehouse'])
            ->orderBy('name');
    }

    /**
     * Search products by query string.
     */
    public function searchProducts(?string $query, int $limit = 20): Collection
    {
        $products = $this->getProductsQuery();

        if ($query) {
            $sanitizedQuery = SearchSanitizer::sanitize($query);
            $products->where(function ($q) use ($sanitizedQuery) {
                $q->where('name', 'like', "%{$sanitizedQuery}%")
                    ->orWhere('sku', 'like', "%{$sanitizedQuery}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$sanitizedQuery}%"));
            });
        }

        return $products->limit($limit)->get();
    }

    /**
     * Search customers by query string.
     */
    public function searchCustomers(?string $query, int $limit = 20): Collection
    {
        $customers = Customer::orderBy('name');

        if ($query) {
            $sanitizedQuery = SearchSanitizer::sanitize($query);
            $customers->where(function ($q) use ($sanitizedQuery) {
                $q->where('name', 'like', "%{$sanitizedQuery}%")
                    ->orWhere('phone', 'like', "%{$sanitizedQuery}%");
            });
        }

        return $customers->limit($limit)->get(['id', 'name', 'phone', 'credit_limit', 'current_debt']);
    }

    /**
     * Format product for eceran response.
     */
    public function formatProductEceran(Product $product): array
    {
        $base = $product->unitConversions->firstWhere('is_base_unit', true)
              ?? $product->unitConversions->sortBy('conversion_factor')->first();

        // Jumlahkan stok per-gudang (multi-batch/lokasi dalam 1 gudang dijumlah)
        $stockBreakdown = $product->productStocks
            ->groupBy('warehouse_id')
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'warehouse_id' => $first->warehouse_id,
                    'warehouse' => $first->warehouse?->name ?? 'Gudang',
                    'qty' => $rows->sum('stock'),
                ];
            })
            ->filter(fn ($ps) => $ps['qty'] > 0)
            ->values()
            ->toArray();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'category' => $product->category?->name ?? '-',
            'stock' => $product->stock,
            'stock_breakdown' => $stockBreakdown,
            'unit' => $base?->unit->name ?? 'pcs',
            'prices' => [
                'eceran' => $base ? (float) $base->sell_price_ecer : (float) $product->price,
                'grosir' => $base ? (float) ($base->sell_price_grosir > 0 ? $base->sell_price_grosir : $base->sell_price_ecer) : (float) $product->price,
                'jual1' => $base ? (float) (($base->sell_price_jual1 ?? 0) > 0 ? $base->sell_price_jual1 : $base->sell_price_ecer) : (float) $product->price,
                'jual2' => $base ? (float) (($base->sell_price_jual2 ?? 0) > 0 ? $base->sell_price_jual2 : $base->sell_price_ecer) : (float) $product->price,
                'jual3' => $base ? (float) (($base->sell_price_jual3 ?? 0) > 0 ? $base->sell_price_jual3 : $base->sell_price_ecer) : (float) $product->price,
            ],
        ];
    }

    /**
     * Format product for grosir response.
     */
    public function formatProductGrosir(Product $product): array
    {
        $units = $product->unitConversions
            ->sortBy('conversion_factor')
            ->map(fn ($uc) => [
                'id' => $uc->id,
                'name' => $uc->unit->name,
                'factor' => $uc->conversion_factor,
                'prices' => [
                    'eceran' => (float) $uc->sell_price_ecer,
                    'grosir' => (float) ($uc->sell_price_grosir > 0 ? $uc->sell_price_grosir : $uc->sell_price_ecer),
                    'jual1' => (float) (($uc->sell_price_jual1 ?? 0) > 0 ? $uc->sell_price_jual1 : $uc->sell_price_ecer),
                    'jual2' => (float) (($uc->sell_price_jual2 ?? 0) > 0 ? $uc->sell_price_jual2 : $uc->sell_price_ecer),
                    'jual3' => (float) (($uc->sell_price_jual3 ?? 0) > 0 ? $uc->sell_price_jual3 : $uc->sell_price_ecer),
                ],
                'is_base' => $uc->is_base_unit,
            ])->values()->toArray();

        $base = collect($units)->firstWhere('is_base', true) ?? collect($units)->first();

        // Jumlahkan stok per-gudang (multi-batch/lokasi dalam 1 gudang dijumlah)
        $stockBreakdown = $product->productStocks
            ->groupBy('warehouse_id')
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'warehouse_id' => $first->warehouse_id,
                    'warehouse' => $first->warehouse?->name ?? 'Gudang',
                    'qty' => $rows->sum('stock'),
                ];
            })
            ->filter(fn ($ps) => $ps['qty'] > 0)
            ->values()
            ->toArray();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'category' => $product->category?->name ?? '-',
            'stock' => $product->stock,
            'stock_breakdown' => $stockBreakdown,
            'unit' => $base['name'] ?? 'pcs',
            'units' => $units,
            'prices' => $base['prices'] ?? [
                'eceran' => (float) $product->price,
                'grosir' => (float) $product->price,
                'jual1' => (float) $product->price,
                'jual2' => (float) $product->price,
                'jual3' => (float) $product->price,
            ],
        ];
    }
}

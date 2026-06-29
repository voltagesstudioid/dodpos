<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Customer;
use App\Support\SearchSanitizer;
use Illuminate\Database\Eloquent\Collection;

class ProductSearchService
{
    private PriceService $priceService;

    public function __construct(PriceService $priceService)
    {
        $this->priceService = $priceService;
    }
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
        return $this->formatProduct($product);
    }

    /**
     * Format product for grosir response.
     */
    public function formatProductGrosir(Product $product): array
    {
        return $this->formatProduct($product);
    }

    /**
     * Format product for POS response (shared logic).
     */
    private function formatProduct(Product $product): array
    {
        $productDefaultPrice = $this->priceService->parseNumber($product->price ?? 0);

        $units = $product->unitConversions
            ->sortBy('conversion_factor')
            ->map(fn ($uc) => [
                'id'      => $uc->id,
                'name'    => $uc->unit->name,
                'factor'  => $uc->conversion_factor,
                'prices'         => $this->priceService->getAllPrices($uc, $productDefaultPrice),
                'purchase_price' => $this->priceService->parseNumber($uc->purchase_price ?? 0),
                'minimal_price'  => max(0, $this->priceService->parseNumber($uc->sell_price_minimal ?? 0)),
                'is_base'        => $uc->is_base_unit,
            ])->values()->toArray();

        // Jika tidak ada unit konversi, buat virtual unit dari kolom price
        if (empty($units)) {
            $basePrice = $productDefaultPrice;
            $unitName  = $product->unit?->name ?? 'pcs';
            $productPurchasePrice = $this->priceService->parseNumber($product->purchase_price ?? 0);
            $units = [[
                'id'      => null,
                'name'    => $unitName,
                'factor'  => 1,
                'prices'  => [
                    'eceran' => $basePrice,
                    'grosir' => $basePrice,
                    'jual1'  => $basePrice,
                    'jual2'  => $basePrice,
                    'jual3'  => $basePrice,
                    'minimal'=> $basePrice,
                ],
                'purchase_price' => $productPurchasePrice,
                'minimal_price'  => max(0, $basePrice),
                'is_base' => true,
            ]];
        }

        $base = collect($units)->firstWhere('is_base', true) ?? collect($units)->first();

        // Jumlahkan stok per-gudang
        $stockBreakdown = $product->productStocks
            ->groupBy('warehouse_id')
            ->map(function ($rows) {
                $first = $rows->first();
                return [
                    'warehouse_id' => $first->warehouse_id,
                    'warehouse'    => $first->warehouse?->name ?? 'Gudang',
                    'qty'          => $rows->sum('stock'),
                ];
            })
            ->filter(fn ($ps) => $ps['qty'] > 0)
            ->values()
            ->toArray();

        return [
            'id'              => $product->id,
            'name'            => $product->name,
            'sku'             => $product->sku,
            'category'        => $product->category?->name ?? '-',
            'stock'           => $product->productStocks->sum('stock'),
            'stock_breakdown' => $stockBreakdown,
            'unit'            => $base['name'] ?? 'pcs',
            'units'           => $units,
            'prices'          => $base['prices'],
        ];
    }

}


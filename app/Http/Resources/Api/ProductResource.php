<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transformasi data produk untuk API response.
 * Menyembunyikan field internal (purchase_price, dll) dari mobile client.
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Bangun daftar unit konversi jika sudah di-load
        $units = [];
        if ($this->relationLoaded('unitConversions')) {
            $units = $this->unitConversions
                ->sortBy('conversion_factor')
                ->map(fn($uc) => [
                    'id'              => $uc->id,
                    'unit_name'       => $uc->unit?->name ?? '-',
                    'unit_abbr'       => $uc->unit?->abbreviation ?? '-',
                    'conversion_factor' => (float) $uc->conversion_factor,
                    'sell_price_ecer' => (float) $uc->sell_price_ecer,
                    'sell_price_grosir' => (float) ($uc->sell_price_grosir > 0 ? $uc->sell_price_grosir : $uc->sell_price_ecer),
                    'is_base_unit'    => (bool) $uc->is_base_unit,
                ])->values()->toArray();
        }

        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'sku'          => $this->sku,
            'barcode'      => $this->barcode,
            'category'     => $this->when(
                $this->relationLoaded('category'),
                fn() => $this->category?->name
            ),
            'brand'        => $this->when(
                $this->relationLoaded('brand'),
                fn() => $this->brand?->name
            ),
            'unit'         => $this->when(
                $this->relationLoaded('unit'),
                fn() => $this->unit?->name
            ),
            // stock sengaja TIDAK disertakan — hanya admin DodPOS yang boleh lihat stok
            'price'        => (float) $this->price,
            'units'        => $units,
            'is_active'    => (bool) ($this->active ?? true),
        ];
    }
}

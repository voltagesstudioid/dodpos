<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductUnitConversion;

/**
 * Service untuk mengatur logika harga produk
 * 
 * Hierarki Harga:
 * 1. sell_price_ecer     - Harga default (Kasir Eceran)
 * 2. sell_price_grosir   - Harga grosir (Kasir Grosir)
 * 3. sell_price_jual1    - Premium (>= eceran)
 * 4. sell_price_jual2    - Medium
 * 5. sell_price_jual3    - Budget (<= grosir)
 * 6. sell_price_minimal  - Safety net (>= modal + margin)
 */
class PriceService
{
    /**
     * Tier harga yang tersedia
     */
    public const TIERS = [
        'eceran'  => ['label' => 'Eceran',  'priority' => 1, 'color' => 'blue'],
        'grosir'  => ['label' => 'Grosir',  'priority' => 2, 'color' => 'purple'],
        'jual1'   => ['label' => 'Jual 1',  'priority' => 3, 'color' => 'emerald'],
        'jual2'   => ['label' => 'Jual 2',  'priority' => 4, 'color' => 'amber'],
        'jual3'   => ['label' => 'Jual 3',  'priority' => 5, 'color' => 'orange'],
        'minimal' => ['label' => 'Minimal', 'priority' => 6, 'color' => 'red'],
    ];

    /**
     * Parse format angka Indonesia ke float
     * Contoh: "28.600" -> 28600, "1.234,56" -> 1234.56
     */
    public function parseNumber($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        if (is_string($value)) {
            // Hapus titik (ribuan), ubah koma (desimal) jadi titik
            $cleaned = str_replace('.', '', $value);
            $cleaned = str_replace(',', '.', $cleaned);
            return (float) $cleaned;
        }
        return 0.0;
    }

    /**
     * Format angka ke format Indonesia
     * Contoh: 28600 -> "28.600"
     */
    public function formatNumber(float $value, int $decimals = 0): string
    {
        return number_format($value, $decimals, ',', '.');
    }

    /**
     * Ambil harga berdasarkan tier dengan fallback logic
     * 
     * Priority fallback:
     * - eceran: fallback ke harga produk default
     * - grosir: fallback ke eceran
     * - jual1-3: fallback ke eceran
     */
    public function getPrice(ProductUnitConversion $conversion, string $tier, float $productDefaultPrice = 0): float
    {
        // Parse semua harga
        $eceran   = $this->parseNumber($conversion->sell_price_ecer);
        $grosir   = $this->parseNumber($conversion->sell_price_grosir);
        $jual1    = $this->parseNumber($conversion->sell_price_jual1);
        $jual2    = $this->parseNumber($conversion->sell_price_jual2);
        $jual3    = $this->parseNumber($conversion->sell_price_jual3);
        $minimal  = $this->parseNumber($conversion->sell_price_minimal);
        $modal    = $this->parseNumber($conversion->purchase_price);

        // Fallback harga eceran
        $basePrice = $eceran > 0 ? $eceran : $productDefaultPrice;
        
        $price = match($tier) {
            'eceran'  => $basePrice,
            'grosir'  => $grosir > 0 ? $grosir : $basePrice,
            'jual1'   => $jual1 > 0 ? $jual1 : $basePrice,
            'jual2'   => $jual2 > 0 ? $jual2 : $basePrice,
            'jual3'   => $jual3 > 0 ? $jual3 : $basePrice,
            'minimal' => $minimal > 0 ? $minimal : $modal * 1.1, // Minimal 10% diatas modal
            default   => $basePrice,
        };

        // Safety: harga tidak boleh dibawah minimal
        if ($minimal > 0 && $price < $minimal) {
            $price = $minimal;
        }

        // Safety: harga tidak boleh negatif
        if ($price < 0) {
            $price = 0;
        }

        return round($price, 2);
    }

    /**
     * Dapatkan semua harga untuk satu unit konversi
     */
    public function getAllPrices(ProductUnitConversion $conversion, float $productDefaultPrice = 0): array
    {
        $prices = [];
        foreach (array_keys(self::TIERS) as $tier) {
            $prices[$tier] = $this->getPrice($conversion, $tier, $productDefaultPrice);
        }
        return $prices;
    }

    /**
     * Validasi harga (untuk digunakan saat save/update produk)
     * 
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validatePrices(array $data): array
    {
        $errors = [];
        
        $modal   = $this->parseNumber($data['purchase_price'] ?? 0);
        $minimal = $this->parseNumber($data['sell_price_minimal'] ?? 0);
        $eceran  = $this->parseNumber($data['sell_price_ecer'] ?? 0);
        $grosir  = $this->parseNumber($data['sell_price_grosir'] ?? 0);
        $jual1   = $this->parseNumber($data['sell_price_jual1'] ?? 0);
        $jual2   = $this->parseNumber($data['sell_price_jual2'] ?? 0);
        $jual3   = $this->parseNumber($data['sell_price_jual3'] ?? 0);

        // Rule 1: Eceran harus >= minimal
        if ($eceran > 0 && $minimal > 0 && $eceran < $minimal) {
            $errors[] = 'Harga eceran tidak boleh lebih rendah dari harga minimal';
        }

        // Rule 2: Grosir harus <= eceran (diskon grosir)
        if ($grosir > 0 && $eceran > 0 && $grosir > $eceran) {
            $errors[] = 'Harga grosir harus lebih murah atau sama dengan harga eceran';
        }

        // Rule 3: Grosir harus >= minimal
        if ($grosir > 0 && $minimal > 0 && $grosir < $minimal) {
            $errors[] = 'Harga grosir tidak boleh lebih rendah dari harga minimal';
        }

        // Rule 4: Jual 1 (premium) harus >= eceran
        if ($jual1 > 0 && $eceran > 0 && $jual1 < $eceran) {
            $errors[] = 'Harga jual 1 harus lebih mahal atau sama dengan harga eceran';
        }

        // Rule 5: Jual 3 (budget) harus <= grosir
        if ($jual3 > 0 && $grosir > 0 && $jual3 > $grosir) {
            $errors[] = 'Harga jual 3 harus lebih murah atau sama dengan harga grosir';
        }

        // Rule 6: Urutan: Jual 1 >= Jual 2 >= Jual 3
        if ($jual1 > 0 && $jual2 > 0 && $jual1 < $jual2) {
            $errors[] = 'Harga jual 1 harus lebih mahal dari jual 2';
        }
        if ($jual2 > 0 && $jual3 > 0 && $jual2 < $jual3) {
            $errors[] = 'Harga jual 2 harus lebih mahal dari jual 3';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Auto-calculate prices based on base unit
     * Jika harga satuan kecil sudah diisi, hitung otomatis untuk satuan besar
     */
    public function calculateDerivedPrices(Product $product, ProductUnitConversion $baseUnit): array
    {
        $basePrices = $this->getAllPrices($baseUnit, $this->parseNumber($product->price));
        $factor = $baseUnit->conversion_factor ?: 1;

        $derived = [];
        foreach ($product->unitConversions as $unit) {
            if ($unit->id === $baseUnit->id) continue;
            
            $unitFactor = $unit->conversion_factor ?: 1;
            $ratio = $unitFactor / $factor;

            foreach ($basePrices as $tier => $basePrice) {
                $derived[$unit->id][$tier] = round($basePrice * $ratio, 2);
            }
        }

        return $derived;
    }
}

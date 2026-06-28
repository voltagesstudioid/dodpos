<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MineralRegional extends Model
{
    use SoftDeletes;

    protected $table = 'mineral_regional';

    protected $fillable = [
        'kode_regional',
        'nama',
        'deskripsi',
        'status',
    ];

    public function hargaProduk()
    {
        return $this->hasMany(MineralRegionalHarga::class, 'regional_id');
    }

    public function sales()
    {
        return $this->hasMany(MineralSales::class, 'regional_id');
    }

    public function pelanggans()
    {
        return $this->hasMany(MineralPelanggan::class, 'regional_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Get harga jual for a specific product in this regional.
     * Falls back to produk's default harga_jual if not set.
     */
    public function getHargaForProduk(int $produkId): float
    {
        $harga = $this->hargaProduk()->where('produk_id', $produkId)->first();
        if ($harga && $harga->harga_jual > 0) {
            return (float) $harga->harga_jual;
        }

        // Fallback to default product price
        $produk = MineralProduk::find($produkId);
        return $produk ? (float) $produk->harga_jual : 0;
    }

    /**
     * Set harga for a product in this regional (upsert).
     */
    public function setHargaForProduk(int $produkId, float $harga): MineralRegionalHarga
    {
        return MineralRegionalHarga::updateOrCreate(
            ['regional_id' => $this->id, 'produk_id' => $produkId],
            ['harga_jual' => $harga]
        );
    }

    public static function generateKode()
    {
        $prefix = 'RGM';
        $date = date('Ymd');
        $last = self::where('kode_regional', 'like', "{$prefix}{$date}%")
            ->lockForUpdate()
            ->orderBy('kode_regional', 'desc')
            ->first();

        if (!$last) {
            return "{$prefix}{$date}001";
        }

        $lastNum = (int) substr($last->kode_regional, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}

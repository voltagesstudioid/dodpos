<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineralStokMasuk extends Model
{
    protected $table = 'mineral_stok_masuk';

    protected $fillable = [
        'no_referensi', 'produk_id', 'vehicle_id', 'tipe', 'jumlah', 'stok_sebelum', 'stok_sesudah',
        'sumber', 'keterangan', 'status', 'created_by',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'stok_sebelum' => 'decimal:2',
        'stok_sesudah' => 'decimal:2',
    ];

    public function produk()
    {
        return $this->belongsTo(MineralProduk::class, 'produk_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateReferensi(string $tipe): string
    {
        $prefix = $tipe === 'penerimaan' ? 'TRM' : 'KRS';
        $date = date('Ymd');
        $last = self::where('no_referensi', 'like', "{$prefix}{$date}%")
            ->orderBy('no_referensi', 'desc')
            ->first();

        if (!$last) {
            return "{$prefix}{$date}0001";
        }

        $lastNum = (int) substr($last->no_referensi, -4);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
    }
}

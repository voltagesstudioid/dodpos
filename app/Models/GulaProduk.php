<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GulaProduk extends Model
{
    use SoftDeletes;

    protected $table = 'gula_produk';

    protected $fillable = [
        'kode_produk', 'nama', 'jenis', 'jenis_id', 'satuan', 'satuan_id',
        'harga_jual', 'harga_modal', 'stok_gudang', 'stok_minimum', 'keterangan', 'status',
    ];

    protected $casts = ['harga_jual' => 'decimal:2', 'harga_modal' => 'decimal:2'];

    public function jenisRel() { return $this->belongsTo(GulaJenis::class, 'jenis_id'); }
    public function satuanRel() { return $this->belongsTo(GulaSatuan::class, 'satuan_id'); }
    public function loadings() { return $this->hasMany(GulaLoading::class, 'produk_id'); }
    public function penjualans() { return $this->hasMany(GulaPenjualan::class, 'produk_id'); }

    public static function generateKode()
    {
        $prefix = 'PRG'; // Produk Gula
        $date = date('Ymd');
        $last = self::where('kode_produk', 'like', "{$prefix}{$date}%")->orderBy('kode_produk', 'desc')->first();
        if (!$last) return "{$prefix}{$date}001";
        $lastNum = (int) substr($last->kode_produk, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}

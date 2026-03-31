<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MinyakPelanggan extends Model
{
    use SoftDeletes;

    protected $table = 'minyak_pelanggan';

    protected $fillable = [
        'kode_pelanggan', 'nama_toko', 'nama_pemilik', 'no_hp', 'email',
        'alamat', 'kecamatan', 'kota', 'latitude', 'longitude',
        'tipe', 'limit_hutang', 'total_hutang', 'status',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'limit_hutang' => 'decimal:2',
        'total_hutang' => 'decimal:2',
    ];

    public function penjualans()
    {
        return $this->hasMany(MinyakPenjualan::class, 'pelanggan_id');
    }

    public function hutangs()
    {
        return $this->hasMany(MinyakHutang::class, 'pelanggan_id');
    }

    public function kunjungans()
    {
        return $this->hasMany(MinyakKunjungan::class, 'pelanggan_id');
    }

    public static function generateKode()
    {
        $prefix = 'PLG';
        $date = date('Ymd');
        $last = self::where('kode_pelanggan', 'like', "{$prefix}{$date}%")
            ->orderBy('kode_pelanggan', 'desc')
            ->first();
        
        if (!$last) {
            return "{$prefix}{$date}001";
        }
        
        $lastNum = (int) substr($last->kode_pelanggan, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}

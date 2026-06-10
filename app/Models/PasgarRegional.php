<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasgarRegional extends Model
{
    use SoftDeletes;

    protected $table = 'pasgar_regional';

    protected $fillable = [
        'kode_regional',
        'nama',
        'deskripsi',
        'status',
    ];

    public function sales()
    {
        return $this->hasMany(PasgarSales::class, 'regional_id');
    }

    public function pelanggans()
    {
        return $this->hasMany(PasgarPelanggan::class, 'regional_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public static function generateKode()
    {
        $prefix = 'RGP';
        $date = date('Ymd');
        $last = self::where('kode_regional', 'like', "{$prefix}{$date}%")
            ->orderBy('kode_regional', 'desc')
            ->first();

        if (!$last) {
            return "{$prefix}{$date}001";
        }

        $lastNum = (int) substr($last->kode_regional, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}

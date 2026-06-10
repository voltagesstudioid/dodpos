<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MineralSales extends Model
{
    use SoftDeletes;

    protected $table = 'mineral_sales';

    protected $fillable = [
        'user_id',
        'regional_id',
        'kode_sales',
        'nama',
        'no_hp',
        'email',
        'alamat',
        'no_kendaraan',
        'jenis_kendaraan',
        'target_harian',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'target_harian' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function regional()
    {
        return $this->belongsTo(MineralRegional::class, 'regional_id');
    }

    public function loadings()
    {
        return $this->hasMany(MineralLoading::class, 'sales_id');
    }

    public function penjualans()
    {
        return $this->hasMany(MineralPenjualan::class, 'sales_id');
    }

    public function setorans()
    {
        return $this->hasMany(MineralSetoran::class, 'sales_id');
    }

    public function kunjungans()
    {
        return $this->hasMany(MineralKunjungan::class, 'sales_id');
    }

    // Scope untuk sales aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Generate kode sales otomatis
    public static function generateKode()
    {
        $prefix = 'SLM'; // Sales Mineral
        $date = date('Ymd');
        $last = self::where('kode_sales', 'like', "{$prefix}{$date}%")
            ->orderBy('kode_sales', 'desc')
            ->first();
        
        if (!$last) {
            return "{$prefix}{$date}001";
        }
        
        $lastNum = (int) substr($last->kode_sales, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}

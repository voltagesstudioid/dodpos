<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasgarSales extends Model
{
    use SoftDeletes;

    protected $table = 'pasgar_sales';

    protected $fillable = [
        'user_id',
        'regional_id',
        'kode_sales',
        'nama',
        'no_hp',
        'alamat',
        'no_kendaraan',
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
        return $this->belongsTo(PasgarRegional::class, 'regional_id');
    }

    public function loadings()
    {
        return $this->hasMany(PasgarLoading::class, 'sales_id');
    }

    public function penjualans()
    {
        return $this->hasMany(PasgarPenjualan::class, 'sales_id');
    }

    public function setorans()
    {
        return $this->hasMany(PasgarSetoran::class, 'sales_id');
    }

    public function vehicle()
    {
        return $this->morphOne(Vehicle::class, 'sales');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public static function generateKode()
    {
        $prefix = 'PSG';
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

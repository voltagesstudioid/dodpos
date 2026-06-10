<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GulaSales extends Model
{
    use SoftDeletes;

    protected $table = 'gula_sales';

    protected $fillable = [
        'user_id', 'kode_sales', 'nama', 'no_hp', 'email', 'alamat',
        'no_kendaraan', 'jenis_kendaraan', 'target_harian', 'status', 'keterangan',
    ];

    protected $casts = ['target_harian' => 'decimal:2'];

    public function user() { return $this->belongsTo(User::class); }
    public function vehicle() { return $this->morphOne(Vehicle::class, 'sales'); }
    public function loadings() { return $this->hasMany(GulaLoading::class, 'sales_id'); }
    public function penjualans() { return $this->hasMany(GulaPenjualan::class, 'sales_id'); }
    public function setorans() { return $this->hasMany(GulaSetoran::class, 'sales_id'); }
    public function kunjungans() { return $this->hasMany(GulaKunjungan::class, 'sales_id'); }

    public function scopeAktif($query) { return $query->where('status', 'aktif'); }

    public static function generateKode()
    {
        $prefix = 'SLG'; // Sales Gula
        $date = date('Ymd');
        $last = self::where('kode_sales', 'like', "{$prefix}{$date}%")->orderBy('kode_sales', 'desc')->first();
        if (!$last) return "{$prefix}{$date}001";
        $lastNum = (int) substr($last->kode_sales, -3);
        return "{$prefix}{$date}" . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
    }
}

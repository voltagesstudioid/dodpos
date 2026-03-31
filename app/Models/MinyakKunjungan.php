<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakKunjungan extends Model
{
    protected $table = 'minyak_kunjungan';

    protected $fillable = [
        'sales_id', 'pelanggan_id', 'waktu_checkin', 'waktu_checkout',
        'latitude_checkin', 'longitude_checkin', 'latitude_checkout', 'longitude_checkout',
        'foto_checkin', 'foto_checkout', 'catatan', 'status',
    ];

    protected $casts = [
        'waktu_checkin' => 'datetime',
        'waktu_checkout' => 'datetime',
        'latitude_checkin' => 'decimal:8',
        'longitude_checkin' => 'decimal:8',
        'latitude_checkout' => 'decimal:8',
        'longitude_checkout' => 'decimal:8',
    ];

    public function sales()
    {
        return $this->belongsTo(MinyakSales::class, 'sales_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(MinyakPelanggan::class, 'pelanggan_id');
    }
}

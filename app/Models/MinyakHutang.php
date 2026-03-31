<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakHutang extends Model
{
    protected $table = 'minyak_hutang';

    protected $fillable = [
        'pelanggan_id', 'penjualan_id', 'total_hutang', 'dibayar', 'sisa',
        'jatuh_tempo', 'status', 'keterangan',
    ];

    protected $casts = [
        'total_hutang' => 'decimal:2',
        'dibayar' => 'decimal:2',
        'sisa' => 'decimal:2',
        'jatuh_tempo' => 'date',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(MinyakPelanggan::class, 'pelanggan_id');
    }

    public function penjualan()
    {
        return $this->belongsTo(MinyakPenjualan::class, 'penjualan_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(MinyakHutangBayar::class, 'hutang_id');
    }

    public function scopeBelumLunas($query)
    {
        return $query->where('status', 'belum_lunas');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                     ->orWhere(function($q) {
                         $q->where('status', 'belum_lunas')
                           ->where('jatuh_tempo', '<', now());
                     });
    }
}

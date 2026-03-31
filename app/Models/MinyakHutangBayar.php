<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakHutangBayar extends Model
{
    protected $table = 'minyak_hutang_bayar';

    protected $fillable = [
        'hutang_id', 'tanggal_bayar', 'jumlah', 'cara_bayar',
        'bukti_transfer', 'keterangan', 'created_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah' => 'decimal:2',
    ];

    public function hutang()
    {
        return $this->belongsTo(MinyakHutang::class, 'hutang_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

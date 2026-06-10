<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakHutangBayar extends Model
{
    protected $table = 'minyak_hutang_bayar';

    protected $fillable = [
        'hutang_id', 'tanggal_bayar', 'jumlah', 'cara_bayar',
        'id_transaksi', 'bukti_transfer', 'keterangan', 'created_by',
        'status', 'confirmed_by', 'confirmed_at', 'reject_reason',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah' => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    public function hutang()
    {
        return $this->belongsTo(MinyakHutang::class, 'hutang_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function confirmer()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}

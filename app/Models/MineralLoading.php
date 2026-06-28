<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineralLoading extends Model
{
    protected $table = 'mineral_loading';

    protected $fillable = [
        'tanggal', 'sales_id', 'produk_id', 'jumlah_loading', 'sisa_stok',
        'terjual', 'status', 'keterangan', 'created_by',
        'mobil_inti_id', 'status_approval', 'approved_by', 'approved_at', 'alasan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->belongsTo(MineralSales::class, 'sales_id');
    }

    public function mobilInti()
    {
        return $this->belongsTo(MineralSales::class, 'mobil_inti_id');
    }

    public function produk()
    {
        return $this->belongsTo(MineralProduk::class, 'produk_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

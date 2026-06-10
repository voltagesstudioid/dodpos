<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakSetoran extends Model
{
    protected $table = 'minyak_setoran';

    protected $fillable = [
        'tanggal', 'sales_id', 'total_penjualan', 'total_tunai', 'total_transfer',
        'total_setor', 'selisih',
        'jumlah_transaksi', 'jumlah_hutang_baru', 'total_hutang_baru',
        'bukti_setor',
        'status', 'catatan_sales', 'catatan_verifikasi',
        'verified_by', 'verified_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_penjualan' => 'decimal:2',
        'total_tunai' => 'decimal:2',
        'total_transfer' => 'decimal:2',
        'total_setor' => 'decimal:2',
        'selisih' => 'decimal:2',
        'total_hutang_baru' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->belongsTo(MinyakSales::class, 'sales_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

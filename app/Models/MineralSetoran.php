<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralSetoran extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'date',
        'total_cash_expected',
        'actual_cash',
        'total_piutang_expected',
        'status', // 'pending', 'verified'
        'verified_by',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_cash_expected' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'total_piutang_expected' => 'decimal:2',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaSetoran extends Model
{
    protected $fillable = [
        'date', 'sales_id', 'total_cash', 'total_piutang', 'notes', 'status', 'verified_by'
    ];

    public function sales() { return $this->belongsTo(User::class, 'sales_id'); }
    public function verifiedBy() { return $this->belongsTo(User::class, 'verified_by'); }
}

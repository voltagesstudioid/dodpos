<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasSetoran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'expected_cash' => 'decimal:2',
        'expected_tempo' => 'decimal:2',
        'actual_cash' => 'decimal:2',
    ];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}

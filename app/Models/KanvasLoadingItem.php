<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasLoadingItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
    ];

    public function loading()
    {
        return $this->belongsTo(KanvasLoading::class, 'loading_id');
    }

    public function product()
    {
        return $this->belongsTo(KanvasProduct::class, 'product_id');
    }
}

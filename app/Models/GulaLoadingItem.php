<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaLoadingItem extends Model
{
    protected $fillable = [
        'gula_loading_id', 'gula_product_id', 'qty_karung', 'qty_bal', 'qty_eceran'
    ];

    public function loading() { return $this->belongsTo(GulaLoading::class, 'gula_loading_id'); }
    public function product() { return $this->belongsTo(GulaProduct::class, 'gula_product_id'); }
}

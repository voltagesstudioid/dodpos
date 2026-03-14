<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaReturn extends Model
{
    protected $fillable = [
        'date', 'sales_id', 'customer_id', 'gula_product_id', 'unit_type', 'qty', 'reason', 'status'
    ];

    public function sales() { return $this->belongsTo(User::class, 'sales_id'); }
    public function customer() { return $this->belongsTo(Customer::class, 'customer_id'); }
    public function product() { return $this->belongsTo(GulaProduct::class, 'gula_product_id'); }
}

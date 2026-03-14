<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasRouteStore extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function route()
    {
        return $this->belongsTo(KanvasRoute::class, 'route_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}

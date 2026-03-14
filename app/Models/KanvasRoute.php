<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasRoute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function stores()
    {
        return $this->hasMany(KanvasRouteStore::class, 'route_id')->orderBy('sequence');
    }
}

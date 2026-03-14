<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanvasLoading extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function items()
    {
        return $this->hasMany(KanvasLoadingItem::class, 'loading_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MineralLoading extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'sales_id',
        'date',
        'status', // 'loading', 'verified'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function items()
    {
        return $this->hasMany(MineralLoadingItem::class, 'loading_id');
    }
}

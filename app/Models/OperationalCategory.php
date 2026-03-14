<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function expenses()
    {
        return $this->hasMany(OperationalExpense::class, 'category_id');
    }
}

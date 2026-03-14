<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AppRole extends Model
{
    protected $table = 'app_roles';

    protected $fillable = [
        'key',
        'label',
        'description',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}

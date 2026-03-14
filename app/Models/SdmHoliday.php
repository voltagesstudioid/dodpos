<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdmHoliday extends Model
{
    protected $fillable = [
        'date',
        'name',
        'is_working_day',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'is_working_day' => 'boolean',
    ];
}

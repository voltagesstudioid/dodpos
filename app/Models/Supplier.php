<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Supplier extends Model
{
    use SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $eventName) => "Supplier has been {$eventName}");
    }

    protected $fillable = [
        'name', 'contact_person', 'phone', 'email',
        'address', 'notes', 'active',
        'code', 'city', 'npwp', 'bank_name', 
        'bank_account', 'bank_account_name', 'term_days'
    ];

    protected $casts = ['active' => 'boolean'];
    public function debts()
    {
        return $this->hasMany(SupplierDebt::class);
    }
}

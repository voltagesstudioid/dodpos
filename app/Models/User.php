<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, LogsActivity, Notifiable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $eventName) => "User has been {$eventName}");
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'role',
        'requested_role',
        'active',
        'email_verified_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'fingerprint_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    public const ROLES = [
        'pending',
        'supervisor',
        'admin_sales',
        'admin1',
        'admin2',
        'admin3',
        'admin4',
        'kasir',
        'gudang',
        'pasgar',
        'sales',
        'sales_gula',
        'sales_kanvas',
        'sales_minyak',
        'sales_mineral',
    ];

    public static function isValidRole(?string $role): bool
    {
        if ($role === null) {
            return false;
        }

        $role = strtolower(trim(str_replace(' ', '', $role)));

        try {
            if (Schema::hasTable('app_roles')) {
                $exists = \App\Models\AppRole::query()
                    ->where('key', $role)
                    ->where('active', true)
                    ->exists();

                if ($exists) {
                    return true;
                }

                if (\App\Models\AppRole::query()->count() === 0) {
                    return in_array($role, self::ROLES, true);
                }

                return false;
            }
        } catch (\Throwable) {
        }

        return in_array($role, self::ROLES, true);
    }

    public function setRoleAttribute($value)
    {
        $this->attributes['role'] = $value === null ? null : strtolower(trim(str_replace(' ', '', (string) $value)));
    }

    public function employee()
    {
        return $this->hasOne(SdmEmployee::class);
    }
}

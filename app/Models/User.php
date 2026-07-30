<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'member_number',
        'phone',
        'alternative_phone',
        'gender',
        'date_of_birth',
        'national_id',
        'passport_license',
        'address',
        'physical_address',
        'region',
        'district',
        'ward',
        'street_village',
        'occupation',
        'employer',
        'employer_business',
        'branch',
        'membership_category',
        'monthly_income',
        'introduced_by',
        'joining_fee',
        'shares_purchased',
        'initial_savings',
        'username',
        'photo',
        'status',
        'member_type_id',
        'registration_date',
        'email_verified_at',
        'email_verified',
        'phone_verified',
        'notes',
        'tags',
        'custom_fields',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'registration_date' => 'date',
            'date_of_birth' => 'date',
            'monthly_income' => 'decimal:2',
            'joining_fee' => 'decimal:2',
            'shares_purchased' => 'decimal:2',
            'initial_savings' => 'decimal:2',
            'email_verified' => 'boolean',
            'phone_verified' => 'boolean',
            'tags' => 'array',
            'custom_fields' => 'array',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function loginHistories(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable');
    }

    public function memberType()
    {
        return $this->belongsTo(MemberType::class);
    }

    public function nextOfKin()
    {
        return $this->hasOne(NextOfKin::class);
    }

    public function bankingDetails()
    {
        return $this->hasOne(BankingDetail::class);
    }

    public function documents()
    {
        return $this->hasOne(MemberDocument::class);
    }

    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }
            return false;
        }

        return $role->intersect($this->roles)->isNotEmpty();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->hasRole('member') || $this->role === 'member';
    }

    public function assignRole($role): self
    {
        if (is_string($role)) {
            $roleModel = Role::where('name', $role)->firstOrFail();
            $this->roles()->syncWithoutDetaching([$roleModel->id]);
        } elseif (is_int($role)) {
            $this->roles()->syncWithoutDetaching([$role]);
        } else {
            $this->roles()->syncWithoutDetaching([$role->id]);
        }

        return $this;
    }
}

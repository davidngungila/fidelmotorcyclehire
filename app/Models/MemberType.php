<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'registration_fee',
        'monthly_contribution',
        'min_savings',
        'max_loan_multiplier',
        'interest_rate_discount',
        'can_vote',
        'can_hold_office',
        'priority',
        'status',
    ];

    protected $casts = [
        'registration_fee' => 'decimal:2',
        'monthly_contribution' => 'decimal:2',
        'min_savings' => 'decimal:2',
        'max_loan_multiplier' => 'integer',
        'interest_rate_discount' => 'decimal:2',
        'can_vote' => 'boolean',
        'can_hold_office' => 'boolean',
        'priority' => 'integer',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }
}

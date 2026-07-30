<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingsProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'interest_rate',
        'min_balance',
        'min_deposit',
        'max_deposit',
        'min_withdrawal_period_days',
        'premature_withdrawal_fee',
        'auto_interest_credit',
        'interest_frequency',
        'requires_notice',
        'notice_period_days',
        'status',
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'min_balance' => 'decimal:2',
        'min_deposit' => 'decimal:2',
        'max_deposit' => 'decimal:2',
        'premature_withdrawal_fee' => 'decimal:2',
        'auto_interest_credit' => 'boolean',
        'requires_notice' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }
}

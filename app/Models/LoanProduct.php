<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanProduct extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'min_amount',
        'max_amount',
        'interest_rate',
        'min_term_months',
        'max_term_months',
        'processing_fee',
        'late_fee',
        'interest_type',
        'repayment_frequency',
        'requires_collateral',
        'requires_guarantor',
        'status',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'requires_collateral' => 'boolean',
        'requires_guarantor' => 'boolean',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanDisbursement extends Model
{
    protected $fillable = [
        'disbursement_number',
        'loan_id',
        'loan_number',
        'member_number',
        'member_name',
        'loan_product',
        'approved_amount',
        'disbursed_amount',
        'disbursement_date',
        'disbursement_method',
        'account_wallet',
        'interest_rate',
        'repayment_period',
        'first_repayment_date',
        'maturity_date',
        'processing_fee',
        'insurance_fee',
        'other_deductions',
        'net_amount_paid',
        'disbursed_by',
        'approved_by',
        'status',
        'remarks',
    ];

    protected $casts = [
        'approved_amount' => 'decimal:2',
        'disbursed_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'net_amount_paid' => 'decimal:2',
        'disbursement_date' => 'date',
        'first_repayment_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function disbursedBy()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeReversed($query)
    {
        return $query->where('status', 'reversed');
    }
}

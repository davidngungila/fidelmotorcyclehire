<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanInformation extends Model
{
    protected $fillable = [
        'loan_id',
        'user_id',
        'customer_id',
        'loan_type',
        'loan_amount',
        'nature',
        'interest_rate_pm',
        'duration_months',
        'loan_start_date',
        'loan_maturity_date',
        'total_payable',
        'monthly_installment',
        'monthly_principal',
        'principal_paid_to_date',
        'termination_fee',
        'total_paid',
        'outstanding_balance',
        'loan_status',
        'loan_guarantor',
        'number_of_paid_installments',
        'number_of_unpaid_installments',
        'this_month_loan_status',
        'balance_after_payment',
        'loan_agreement_ref_no',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'interest_rate_pm' => 'decimal:2',
        'total_payable' => 'decimal:2',
        'monthly_installment' => 'decimal:2',
        'monthly_principal' => 'decimal:2',
        'principal_paid_to_date' => 'decimal:2',
        'termination_fee' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'balance_after_payment' => 'decimal:2',
        'loan_start_date' => 'date',
        'loan_maturity_date' => 'date',
        'number_of_paid_installments' => 'integer',
        'number_of_unpaid_installments' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByLoanId($query, $loanId)
    {
        return $query->where('loan_id', $loanId);
    }

    public function scopeByCustomerId($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('loan_status', $status);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    protected $fillable = [
        'loan_id',
        'customer_id',
        'payment_amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'principal_amount',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function scopeByLoanId($query, $loanId)
    {
        return $query->where('loan_id', $loanId);
    }

    public function scopeByCustomerId($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}

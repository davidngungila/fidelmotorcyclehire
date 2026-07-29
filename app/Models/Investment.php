<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'investment_product_id',
        'member_number',
        'investment_number',
        'amount',
        'investment_date',
        'maturity_date',
        'interest_rate',
        'expected_return',
        'actual_return',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'expected_return' => 'decimal:2',
        'actual_return' => 'decimal:2',
        'investment_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investmentProduct()
    {
        return $this->belongsTo(InvestmentProduct::class);
    }

    public function scopeByMemberNumber($query, $memberNumber)
    {
        return $query->where('member_number', $memberNumber);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeMatured($query)
    {
        return $query->where('status', 'matured');
    }
}

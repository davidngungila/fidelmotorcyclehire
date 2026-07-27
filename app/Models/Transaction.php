<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'date',
        'membercode',
        'transaction_type',
        'reference_no',
        'amount',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function scopeByMemberCode($query, $memberCode)
    {
        return $query->where('membercode', $memberCode);
    }

    public function scopeByTransactionType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}

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
        'saving_plan_id',
        'product_id',
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

    public function scopeWithRunningBalance($query, $memberCode)
    {
        return $query->byMemberCode($memberCode)
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($transaction, $index) {
                static $runningBalance = 0;
                
                // Calculate running balance
                if (in_array($transaction->transaction_type, ['deposit', 'Flexi-Deposit', 'RDA-Deposit', 'Opening Balance'])) {
                    $runningBalance += (float) $transaction->amount;
                } elseif (in_array($transaction->transaction_type, ['withdrawal', 'Withdrawal'])) {
                    $runningBalance -= (float) $transaction->amount;
                }
                
                $transaction->balance_after = $runningBalance;
                $transaction->running_balance = $runningBalance;
                
                return $transaction;
            })
            ->sortByDesc('date')
            ->values();
    }

    public function savingPlan()
    {
        return $this->belongsTo(SavingPlan::class);
    }

    public function product()
    {
        return $this->belongsTo(SavingsProduct::class, 'product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = [
        'name',
        'financial_period_id',
        'account_id',
        'budgeted_amount',
        'actual_amount',
        'variance',
        'variance_type',
        'created_by',
        'notes',
    ];

    protected $casts = [
        'budgeted_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function financialPeriod()
    {
        return $this->belongsTo(FinancialPeriod::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function calculateVariance()
    {
        $this->variance = $this->actual_amount - $this->budgeted_amount;
        
        if ($this->account->account_type === 'revenue') {
            $this->variance_type = $this->variance >= 0 ? 'favorable' : 'unfavorable';
        } else {
            $this->variance_type = $this->variance <= 0 ? 'favorable' : 'unfavorable';
        }
        
        $this->save();
    }
}

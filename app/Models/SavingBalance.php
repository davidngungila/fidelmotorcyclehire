<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingBalance extends Model
{
    protected $table = 'saving_balances';

    protected $fillable = [
        'customer_id',
        'monthly_saving_target', 'monthly_total_savings_deposits',
        'monthly_goal_achievement', 'overall_saving_goal',
        'total_saved', 'overall_goal_achievement',
        'flexi_opening_balance', 'flexi_deposit', 'flexi_withdrawal', 'flexi_balance',
        'rda_opening_balance', 'rda_deposit', 'rda_withdrawal', 'rda_balance',
        'emergency_opening_balance', 'emergency_deposit', 'emergency_withdrawal', 'emergency_balance',
        'business_opening_balance', 'business_deposit', 'business_withdrawal', 'business_balance',
        'total_balance', 'interest_payable', 'savings_held_for_loan_security',
        'free_savings_emergency', 'free_savings_rda_flexi_business',
        'total_free_saving', 'premature_withdraw_charge', 'metadata'
    ];

    protected $casts = [
        'monthly_saving_target' => 'decimal:2',
        'monthly_total_savings_deposits' => 'decimal:2',
        'monthly_goal_achievement' => 'decimal:2',
        'overall_saving_goal' => 'decimal:2',
        'total_saved' => 'decimal:2',
        'overall_goal_achievement' => 'decimal:2',
        'flexi_opening_balance' => 'decimal:2',
        'flexi_deposit' => 'decimal:2',
        'flexi_withdrawal' => 'decimal:2',
        'flexi_balance' => 'decimal:2',
        'rda_opening_balance' => 'decimal:2',
        'rda_deposit' => 'decimal:2',
        'rda_withdrawal' => 'decimal:2',
        'rda_balance' => 'decimal:2',
        'emergency_opening_balance' => 'decimal:2',
        'emergency_deposit' => 'decimal:2',
        'emergency_withdrawal' => 'decimal:2',
        'emergency_balance' => 'decimal:2',
        'business_opening_balance' => 'decimal:2',
        'business_deposit' => 'decimal:2',
        'business_withdrawal' => 'decimal:2',
        'business_balance' => 'decimal:2',
        'total_balance' => 'decimal:2',
        'interest_payable' => 'decimal:2',
        'savings_held_for_loan_security' => 'decimal:2',
        'free_savings_emergency' => 'decimal:2',
        'free_savings_rda_flexi_business' => 'decimal:2',
        'total_free_saving' => 'decimal:2',
        'premature_withdraw_charge' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id', 'customer_id');
    }

    public function getCombinedOpeningBalanceAttribute()
    {
        return $this->flexi_opening_balance + 
               $this->rda_opening_balance + 
               $this->emergency_opening_balance + 
               $this->business_opening_balance;
    }

    public function getTotalDepositsAttribute()
    {
        return $this->flexi_deposit + 
               $this->rda_deposit + 
               $this->emergency_deposit + 
               $this->business_deposit;
    }

    public function getTotalWithdrawalsAttribute()
    {
        return $this->flexi_withdrawal + 
               $this->rda_withdrawal + 
               $this->emergency_withdrawal + 
               $this->business_withdrawal;
    }

    public function getGoalAchievementRateAttribute()
    {
        if ($this->overall_saving_goal > 0) {
            return round(($this->total_saved / $this->overall_saving_goal) * 100, 2);
        }
        return 0;
    }
}

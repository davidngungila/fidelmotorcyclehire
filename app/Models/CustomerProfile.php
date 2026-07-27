<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProfile extends Model
{
    use SoftDeletes;

    protected $table = 'customer_profiles';

    protected $fillable = [
        'customer_id', 'customer_name', 'email_address',
        'phone_number', 'member_type', 'start_date',
        'end_date', 'account_status', 'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'metadata' => 'array'
    ];

    public function savingBalance(): HasOne
    {
        return $this->hasOne(SavingBalance::class, 'customer_id', 'customer_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'membercode', 'customer_id');
    }

    public function savingPlan(): HasOne
    {
        return $this->hasOne(SavingPlan::class, 'memberid', 'customer_id');
    }

    public function scopeActive($query)
    {
        return $query->where('account_status', 'Active');
    }

    public function scopeByMemberType($query, $type)
    {
        return $query->where('member_type', $type);
    }

    public function getTotalBalanceAttribute()
    {
        return $this->savingBalance ? $this->savingBalance->total_balance : 0;
    }

    public function getAccountBalancesAttribute()
    {
        if (!$this->savingBalance) {
            return ['flexi' => 0, 'rda' => 0, 'emergency' => 0, 'business' => 0];
        }
        
        return [
            'flexi' => $this->savingBalance->flexi_balance,
            'rda' => $this->savingBalance->rda_balance,
            'emergency' => $this->savingBalance->emergency_balance,
            'business' => $this->savingBalance->business_balance,
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->customer_name;
    }

    public function getPhoneAttribute()
    {
        return $this->phone_number;
    }

    public function getEmailAttribute()
    {
        return $this->email_address;
    }
}

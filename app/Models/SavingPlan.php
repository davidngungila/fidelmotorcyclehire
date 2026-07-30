<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingPlan extends Model
{
    protected $fillable = [
        'name',
        'member_number',
        'membership',
        'monthly_goal',
        'goal',
        'target_date',
        'status',
    ];

    protected $casts = [
        'monthly_goal' => 'decimal:2',
        'goal' => 'decimal:2',
        'target_date' => 'date',
    ];

    public function scopeByMemberNumber($query, $memberNumber)
    {
        return $query->where('member_number', $memberNumber);
    }

    public function scopeByMembership($query, $membership)
    {
        return $query->where('membership', $membership);
    }

    public function scopeByMemberId($query, $memberId)
    {
        return $query->where('member_number', $memberId);
    }
}

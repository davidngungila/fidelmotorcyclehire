<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingPlan extends Model
{
    protected $fillable = [
        'name',
        'memberid',
        'membership',
        'monthly_goal',
        'goal',
    ];

    protected $casts = [
        'monthly_goal' => 'decimal:2',
        'goal' => 'decimal:2',
    ];

    public function scopeByMemberId($query, $memberId)
    {
        return $query->where('memberid', $memberId);
    }

    public function scopeByMembership($query, $membership)
    {
        return $query->where('membership', $membership);
    }
}

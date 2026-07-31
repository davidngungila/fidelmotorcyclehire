<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SwfBenefit extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'max_amount',
        'requires_approval',
        'is_active',
    ];

    protected $casts = [
        'max_amount' => 'decimal:2',
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function swfMembers(): BelongsToMany
    {
        return $this->belongsToMany(SwfMember::class, 'swf_member_benefits')
            ->withPivot('amount', 'received_date', 'status')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwfMember extends Model
{
    protected $fillable = [
        'user_id',
        'membership_number',
        'join_date',
        'total_contributions',
        'total_benefits_received',
        'is_active',
    ];

    protected $casts = [
        'join_date' => 'date',
        'total_contributions' => 'decimal:2',
        'total_benefits_received' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contributions(): HasMany
    {
        return $this->hasMany(SwfContribution::class);
    }

    public function benefits(): BelongsToMany
    {
        return $this->belongsToMany(SwfBenefit::class, 'swf_member_benefits')
            ->withPivot('amount', 'received_date', 'status')
            ->withTimestamps();
    }
}

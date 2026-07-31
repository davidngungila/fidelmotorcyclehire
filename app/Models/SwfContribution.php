<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwfContribution extends Model
{
    protected $fillable = [
        'swf_member_id',
        'amount',
        'contribution_date',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'contribution_date' => 'date',
    ];

    public function swfMember(): BelongsTo
    {
        return $this->belongsTo(SwfMember::class);
    }
}

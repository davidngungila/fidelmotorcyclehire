<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Motorcycle extends Model
{
    protected $fillable = [
        'brand',
        'model',
        'engine_number',
        'chassis_number',
        'registration_number',
        'colour',
        'purchase_price',
        'selling_price',
        'status',
        'notes',
        'assigned_to',
        'purchase_date',
        'sale_date',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'purchase_date' => 'date',
        'sale_date' => 'date',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

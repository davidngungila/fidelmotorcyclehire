<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestmentProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'interest_rate',
        'min_investment',
        'max_investment',
        'duration_months',
        'auto_renew',
        'description',
        'status',
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'min_investment' => 'decimal:2',
        'max_investment' => 'decimal:2',
        'auto_renew' => 'boolean',
    ];

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}

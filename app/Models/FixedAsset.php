<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_code',
        'asset_name',
        'description',
        'account_id',
        'purchase_date',
        'depreciation_start_date',
        'purchase_cost',
        'salvage_value',
        'useful_life_years',
        'depreciation_method',
        'accumulated_depreciation',
        'net_book_value',
        'status',
        'disposal_date',
        'disposal_value',
        'disposal_notes',
        'location_id',
        'responsible_person_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'depreciation_start_date' => 'date',
        'disposal_date' => 'date',
        'purchase_cost' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'accumulated_depreciation' => 'decimal:2',
        'net_book_value' => 'decimal:2',
        'disposal_value' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(User::class, 'responsible_person_id');
    }

    public function calculateAnnualDepreciation()
    {
        $depreciableAmount = $this->purchase_cost - $this->salvage_value;
        
        switch ($this->depreciation_method) {
            case 'straight_line':
                return $depreciableAmount / $this->useful_life_years;
            case 'declining_balance':
                $rate = 2 / $this->useful_life_years;
                return $this->net_book_value * $rate;
            case 'units_of_production':
                // This would require additional fields for units
                return $depreciableAmount / $this->useful_life_years;
            default:
                return $depreciableAmount / $this->useful_life_years;
        }
    }

    public function updateDepreciation()
    {
        if ($this->status !== 'active') {
            return;
        }

        $annualDepreciation = $this->calculateAnnualDepreciation();
        $this->accumulated_depreciation += $annualDepreciation;
        $this->net_book_value = $this->purchase_cost - $this->accumulated_depreciation;

        if ($this->net_book_value <= $this->salvage_value) {
            $this->status = 'fully_depreciated';
            $this->net_book_value = $this->salvage_value;
        }

        $this->save();
    }
}

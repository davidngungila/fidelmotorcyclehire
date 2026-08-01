<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'price_per_share',
        'minimum_shares',
        'maximum_shares',
        'dividend_rate',
        'status',
        'issue_date',
        'maturity_date',
    ];

    protected $casts = [
        'price_per_share' => 'decimal:2',
        'dividend_rate' => 'decimal:2',
        'issue_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function sharePurchases()
    {
        return $this->hasMany(SharePurchase::class);
    }

    public function shareCertificates()
    {
        return $this->hasMany(ShareCertificate::class);
    }

    public function shareDividends()
    {
        return $this->hasMany(ShareDividend::class);
    }

    public function shareTransactions()
    {
        return $this->hasMany(ShareTransaction::class);
    }
}

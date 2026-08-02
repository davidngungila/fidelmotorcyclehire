<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareCertificate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'share_product_id',
        'share_purchase_id',
        'certificate_number',
        'number_of_shares',
        'share_value_per_share',
        'issue_date',
        'expiry_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shareProduct()
    {
        return $this->belongsTo(ShareProduct::class);
    }

    public function sharePurchase()
    {
        return $this->belongsTo(SharePurchase::class);
    }

    public function shareTransfers()
    {
        return $this->hasMany(ShareTransfer::class);
    }

    public function shareDividends()
    {
        return $this->hasMany(ShareDividend::class);
    }
}

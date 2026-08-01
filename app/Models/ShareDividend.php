<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareDividend extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'share_product_id',
        'user_id',
        'share_certificate_id',
        'number_of_shares',
        'dividend_per_share',
        'total_dividend',
        'declaration_date',
        'payment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'dividend_per_share' => 'decimal:2',
        'total_dividend' => 'decimal:2',
        'declaration_date' => 'date',
        'payment_date' => 'date',
    ];

    public function shareProduct()
    {
        return $this->belongsTo(ShareProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shareCertificate()
    {
        return $this->belongsTo(ShareCertificate::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'share_product_id',
        'transaction_type',
        'number_of_shares',
        'price_per_share',
        'total_amount',
        'transaction_date',
        'status',
        'description',
        'notes',
    ];

    protected $casts = [
        'price_per_share' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shareProduct()
    {
        return $this->belongsTo(ShareProduct::class);
    }
}

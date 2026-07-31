<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key',
        'account',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

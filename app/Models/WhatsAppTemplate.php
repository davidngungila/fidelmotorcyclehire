<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppTemplate extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_templates';

    protected $fillable = [
        'name',
        'description',
        'parameters',
        'category',
        'language',
        'is_active',
    ];

    protected $casts = [
        'parameters' => 'array',
        'is_active' => 'boolean',
    ];
}

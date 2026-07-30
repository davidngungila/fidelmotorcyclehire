<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'passport_photo',
        'national_id_copy',
        'signature',
        'other_attachments',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

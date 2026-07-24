<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheetsConfig extends Model
{
    protected $table = 'google_sheets_config';

    protected $fillable = [
        'spreadsheet_id',
        'sheet_names',
        'last_sync_at',
        'is_active',
        'service_account_json',
    ];

    protected $casts = [
        'sheet_names' => 'array',
        'is_active' => 'boolean',
        'last_sync_at' => 'datetime',
    ];
}

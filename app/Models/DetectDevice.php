<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetectDevice extends Model
{
    use HasFactory;

    protected $table = 'detect_device';

    protected $fillable = [
        'user_id', 'device_name', 'device_type', 'platform', 'browser', 'ip', 'user_agent', 'fingerprint', 'refresh_token_hash', 'revoked', 'last_active_at', 'revoked_at'
    ];

    protected $casts = [
        'revoked' => 'boolean',
        'last_active_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];
}

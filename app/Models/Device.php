<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    use HasUlids;
    protected $fillable = [
        'user_id',
        'device_id',
        'device_name',
        'web_token',
        'cf_token',
        'security_pin',
        'mode',
        'port',
        'local_ip',
        'active_clients',
        'is_online',
        'last_seen_at'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
        'port' => 'integer',
        'active_clients' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitor extends Model
{
    protected $fillable = [
        'client_id', 'name', 'url', 'method', 'expected_status_code',
        'check_interval_minutes', 'status', 'last_status_code',
        'last_response_time_ms', 'last_checked_at', 'last_success_at',
        'last_failure_at', 'last_error', 'is_active',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'last_success_at' => 'datetime',
        'last_failure_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function checks(): HasMany
    {
        return $this->hasMany(MonitorCheck::class)->latest('checked_at');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoogleAccount extends Model
{
    protected $fillable = [
        'client_id', 'name', 'email', 'status', 'ads_customer_id',
        'analytics_property_id', 'search_console_site_url',
        'enabled_services', 'connected_at', 'last_synced_at',
        'notes', 'is_active',
    ];

    protected $casts = [
        'enabled_services' => 'array',
        'connected_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

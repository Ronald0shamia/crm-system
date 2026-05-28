<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WordpressSite extends Model
{
    protected $fillable = [
        'client_id', 'name', 'url', 'admin_url', 'status',
        'wordpress_version', 'plugins_count', 'outdated_plugins_count',
        'themes_count', 'outdated_themes_count', 'plugins', 'themes',
        'last_checked_at', 'notes', 'is_active',
    ];

    protected $casts = [
        'plugins' => 'array',
        'themes' => 'array',
        'last_checked_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}

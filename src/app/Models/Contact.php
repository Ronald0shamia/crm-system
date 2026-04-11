<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $fillable = [
        'client_id', 'first_name', 'last_name',
        'email', 'phone', 'position',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
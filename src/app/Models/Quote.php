<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quote extends Model
{
    protected $fillable = [
        'user_id', 'client_id', 'quote_number',
        'status', 'valid_until', 'total', 'notes',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'total'       => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function calculateTotal(): void
    {
        $this->total = $this->items->sum('total');
        $this->save();
    }

    protected static function booted(): void
    {
        static::creating(function (Quote $quote) {
            $quote->quote_number = 'QU-' . str_pad(
                (Quote::max('id') ?? 0) + 1, 5, '0', STR_PAD_LEFT
            );
            $quote->user_id = auth()->id();
        });
    }
}
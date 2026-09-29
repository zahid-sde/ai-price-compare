<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_id',
        'product_id',
        'plan_id',
        'country_id',
        'currency',
        'price',
        'billing_period',
        'source_url',
        'verified_at',
        'recorded_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'verified_at' => 'date',
        'recorded_at' => 'datetime',
    ];

    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price == 0 || $this->billing_period === 'free') {
            return 'Free ($0)';
        }

        $symbol = $this->country ? $this->country->currency_symbol : '$';
        $period = match ($this->billing_period) {
            'monthly' => '/mo',
            'yearly' => '/yr',
            'one_time' => ' one-time',
            default => '',
        };

        return $symbol.number_format($this->price, 2).$period;
    }
}

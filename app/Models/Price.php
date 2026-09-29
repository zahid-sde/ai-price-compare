<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'plan_id',
        'country_id',
        'currency',
        'price',
        'billing_period',
        'source_url',
        'verified_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'verified_at' => 'date',
    ];

    protected static function booted(): void
    {
        static::updated(function (Price $price) {
            if ($price->wasChanged(['price', 'currency', 'billing_period', 'country_id'])) {
                PriceHistory::create([
                    'price_id' => $price->id,
                    'product_id' => $price->product_id,
                    'plan_id' => $price->plan_id,
                    'country_id' => $price->country_id,
                    'currency' => $price->currency,
                    'price' => $price->price,
                    'billing_period' => $price->billing_period,
                    'source_url' => $price->source_url,
                    'verified_at' => $price->verified_at,
                    'recorded_at' => now(),
                ]);
            }
        });

        static::created(function (Price $price) {
            PriceHistory::create([
                'price_id' => $price->id,
                'product_id' => $price->product_id,
                'plan_id' => $price->plan_id,
                'country_id' => $price->country_id,
                'currency' => $price->currency,
                'price' => $price->price,
                'billing_period' => $price->billing_period,
                'source_url' => $price->source_url,
                'verified_at' => $price->verified_at,
                'recorded_at' => now(),
            ]);
        });
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

    public function histories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
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

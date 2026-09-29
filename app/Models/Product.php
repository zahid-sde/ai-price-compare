<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'company_name',
        'description',
        'logo_url',
        'official_url',
        'status',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function productFeatures(): HasMany
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'product_features')
            ->withPivot('is_available', 'notes')
            ->withTimestamps();
    }

    public function externalLinks(): HasMany
    {
        return $this->hasMany(ExternalLink::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function sources(): HasMany
    {
        return $this->hasMany(Source::class);
    }

    /**
     * Get the lowest starting price string for a given country code or ID.
     */
    public function startingPriceForCountry(Country $country): array
    {
        $lowestPrice = $this->prices()
            ->where('country_id', $country->id)
            ->orderBy('price', 'asc')
            ->first();

        if (! $lowestPrice) {
            return [
                'formatted' => 'N/A',
                'raw' => null,
                'has_free' => false,
            ];
        }

        $hasFree = $this->plans()->whereHas('prices', function ($q) use ($country) {
            $q->where('country_id', $country->id)->where('price', 0);
        })->exists();

        return [
            'formatted' => $lowestPrice->formatted_price,
            'raw' => $lowestPrice->price,
            'currency' => $lowestPrice->currency,
            'billing_period' => $lowestPrice->billing_period,
            'has_free' => $hasFree,
            'verified_at' => $lowestPrice->verified_at?->format('F j, Y'),
            'source_url' => $lowestPrice->source_url ?: $this->official_url,
        ];
    }
}

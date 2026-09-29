<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'currency_code',
        'currency_symbol',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function externalLinks(): HasMany
    {
        return $this->hasMany(ExternalLink::class);
    }

    public static function defaultCountry(): self
    {
        $country = self::where('code', 'USA')->first() ?? self::first();

        if (! $country) {
            $country = self::updateOrCreate(
                ['code' => 'USA'],
                [
                    'name' => 'United States',
                    'currency_code' => 'USD',
                    'currency_symbol' => '$',
                    'is_active' => true,
                ]
            );
        }

        return $country;
    }
}

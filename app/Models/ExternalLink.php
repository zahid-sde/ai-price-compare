<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExternalLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'country_id',
        'url',
        'type',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class, 'link_id');
    }
}

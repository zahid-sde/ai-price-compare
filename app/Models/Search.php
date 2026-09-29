<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    protected $fillable = [
        'query',
        'parsed_intent',
        'results_count',
        'ip_address',
    ];

    protected $casts = [
        'parsed_intent' => 'array',
    ];
}

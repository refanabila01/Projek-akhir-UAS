<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    // The table associated with the model.
    protected $table = 'news_cache';

    protected $fillable = [
        'country_id',
        'title',
        'content',
        'source',
        'url',
        'published_at',
        'sentiment',
        'sentiment_score',
        'image',
        'category',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the country that this news article is about.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}

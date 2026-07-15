<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskScore extends Model
{
    protected $fillable = [
        'country_id',
        'weather_score',
        'inflation_score',
        'currency_score',
        'news_score',
        'total_score',
        'calculated_at',
    ];

    protected $casts = [
        'calculated_at' => 'datetime',
    ];

    /**
     * Get the country that owns the risk score.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        'name',
        'code',
        'flag',
        'capital',
        'region',
        'population',
        'gdp',
        'inflation',
        'currency_code',
        'currency_name',
        'latitude',
        'longitude',
        'gdp_growth',
        'unemployment_rate',
        'exports',
        'imports',
    ];

    /**
     * Get the risk scores for the country.
     */
    public function riskScores(): HasMany
    {
        return $this->hasMany(RiskScore::class);
    }

    /**
     * Get the latest risk score for the country.
     */
    public function latestRiskScore()
    {
        return $this->hasOne(RiskScore::class)->latestOfMany('calculated_at');
    }

    /**
     * Get the news articles for the country.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }
}

<?php

namespace App\Services;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RiskScoringService
{
    protected $apiService;
    protected $sentimentAnalyzer;

    public function __construct(ExternalApiService $apiService, SentimentAnalyzer $sentimentAnalyzer)
    {
        $this->apiService = $apiService;
        $this->sentimentAnalyzer = $sentimentAnalyzer;
    }

    /**
     * Calculate and save risk score for a country.
     * Force recalculation if $force is true.
     */
    public function calculateCountryRisk(Country $country, bool $force = false): RiskScore
    {
        // 1. Check for cached score within last 1 hour
        if (!$force) {
            $latestScore = RiskScore::where('country_id', $country->id)
                ->where('calculated_at', '>=', now()->subHour())
                ->first();

            if ($latestScore) {
                return $latestScore;
            }
        }

        // 2. Fetch external indicators and compute components
        
        // A. Weather Score (30%)
        $weatherData = $this->apiService->fetchWeather($country->latitude ?? 0, $country->longitude ?? 0);
        $weatherScore = $weatherData['risk_score'] ?? 20;

        // B. Inflation Score (20%)
        // Fetch inflation from DB or update from World Bank API if it's 0
        $inflation = $country->inflation;
        if ($inflation == 0 || $force) {
            $wbInflation = $this->apiService->fetchWorldBankIndicator($country->code, 'FP.CPI.TOTL.ZG');
            if ($wbInflation !== null) {
                $country->update(['inflation' => $wbInflation]);
                $inflation = $wbInflation;
            }
        }

        if ($inflation <= 1.5) {
            $inflationScore = 15;
        } elseif ($inflation <= 3.5) {
            $inflationScore = 35;
        } elseif ($inflation <= 6.0) {
            $inflationScore = 65;
        } else {
            $inflationScore = 90;
        }

        // C. Currency Score (10%)
        $exchangeRates = $this->apiService->fetchExchangeRates();
        $currencyCode = $country->currency_code;
        $currencyScore = 15; // default low risk for stable currencies

        if ($currencyCode !== 'USD' && !empty($exchangeRates)) {
            $rate = $exchangeRates[$currencyCode] ?? 1.0;
            // High nominal value relative to USD can indicate higher emerging market risk
            if ($rate > 1000) {
                $currencyScore = 60; // e.g. IDR
            } elseif ($rate > 50) {
                $currencyScore = 75; // e.g. RUB
            } elseif ($rate > 5) {
                $currencyScore = 35; // e.g. CNY
            }
        }

        // D. News Sentiment Score (40%)
        $newsArticles = $this->apiService->fetchSupplyChainNews($country->name, $country->code);
        $totalNewsScore = 0;
        $newsCount = 0;

        // Clear old cached news for this country
        News::where('country_id', $country->id)->delete();

        foreach ($newsArticles as $article) {
            if (isset($article['sentiment'])) {
                $analysis = [
                    'sentiment' => $article['sentiment'],
                    'score' => $article['sentiment_score'],
                ];
            } else {
                $analysis = $this->sentimentAnalyzer->analyze($article['title'] . ' ' . $article['content']);
            }
            
            // Map sentiment to risk score
            if ($analysis['sentiment'] === 'Negative') {
                $artScore = 80;
            } elseif ($analysis['sentiment'] === 'Positive') {
                $artScore = 15;
            } else {
                $artScore = 40;
            }

            $totalNewsScore += $artScore;
            $newsCount++;

            // Save to news cache
            News::create([
                'country_id' => $country->id,
                'title' => $article['title'],
                'content' => $article['content'],
                'source' => $article['source'],
                'url' => $article['url'],
                'published_at' => $article['published_at'],
                'sentiment' => $analysis['sentiment'],
                'sentiment_score' => $analysis['score'],
            ]);
        }

        $newsScore = $newsCount > 0 ? ($totalNewsScore / $newsCount) : 40;

        // 3. Calculate Weighted Total Score
        // Weather (30%), Inflation (20%), Currency (10%), News (40%)
        $totalScore = ($weatherScore * 0.3) + ($inflationScore * 0.2) + ($currencyScore * 0.1) + ($newsScore * 0.4);
        $totalScore = round($totalScore, 1);

        // 4. Save and return new RiskScore
        return RiskScore::create([
            'country_id' => $country->id,
            'weather_score' => $weatherScore,
            'inflation_score' => $inflationScore,
            'currency_score' => $currencyScore,
            'news_score' => $newsScore,
            'total_score' => $totalScore,
            'calculated_at' => now(),
        ]);
    }
}

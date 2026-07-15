<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Port;
use App\Models\News;
use App\Models\RiskScore;
use App\Services\RiskScoringService;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    protected $scoringService;
    protected $apiService;

    public function __construct(RiskScoringService $scoringService, ExternalApiService $apiService)
    {
        $this->scoringService = $scoringService;
        $this->apiService = $apiService;
    }

    /**
     * GET /api/countries
     * Returns list of all countries with their basic indicators and latest risk score.
     */
    public function indexCountries()
    {
        try {
            // Eager load latestRiskScore to solve N+1 query problem and avoid slow external API hits
            $countries = Country::with('latestRiskScore')->get();
            $result = [];

            foreach ($countries as $c) {
                $latest = $c->latestRiskScore;
                $totalScore = $latest ? $latest->total_score : 40.0;

                $result[] = [
                    'id' => $c->id,
                    'name' => $c->name,
                    'code' => $c->code,
                    'flag' => $c->flag,
                    'capital' => $c->capital,
                    'region' => $c->region,
                    'population' => $c->population,
                    'gdp' => $c->gdp,
                    'inflation' => $c->inflation,
                    'currency_code' => $c->currency_code,
                    'currency_name' => $c->currency_name,
                    'latitude' => $c->latitude,
                    'longitude' => $c->longitude,
                    'risk_score' => $totalScore,
                    'risk_level' => $this->getRiskLevel($totalScore),
                ];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("API Error indexCountries: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data negara: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/risk
     * Returns detailed risk components for all countries.
     */
    public function indexRisk()
    {
        try {
            $countries = Country::all();
            $result = [];

            foreach ($countries as $c) {
                $latestScore = $this->scoringService->calculateCountryRisk($c);
                $result[] = [
                    'country_name' => $c->name,
                    'country_code' => $c->code,
                    'weather_score' => $latestScore->weather_score,
                    'inflation_score' => $latestScore->inflation_score,
                    'currency_score' => $latestScore->currency_score,
                    'news_score' => $latestScore->news_score,
                    'total_score' => $latestScore->total_score,
                    'risk_level' => $this->getRiskLevel($latestScore->total_score),
                    'calculated_at' => $latestScore->calculated_at->toIso8601String(),
                ];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("API Error indexRisk: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data risiko: ' . $e->getMessage()], 500);
        }
    }

    /**
     * GET /api/ports
     * Returns list of ports with congestion status.
     */
    public function indexPorts()
    {
        try {
            $ports = Port::all();
            return response()->json($ports);
        } catch (\Exception $e) {
            Log::error("API Error indexPorts: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data pelabuhan'], 500);
        }
    }

    /**
     * GET /api/news
     * Returns cached news articles with sentiment analysis scores.
     */
    public function indexNews()
    {
        try {
            $news = News::with('country')->orderBy('published_at', 'desc')->take(20)->get();
            return response()->json($news);
        } catch (\Exception $e) {
            Log::error("API Error indexNews: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data berita'], 500);
        }
    }

    /**
     * GET /api/currency
     * Returns exchange rates relative to USD.
     */
    public function indexCurrency()
    {
        try {
            $rates = $this->apiService->fetchExchangeRates();
            return response()->json($rates);
        } catch (\Exception $e) {
            Log::error("API Error indexCurrency: " . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data mata uang'], 500);
        }
    }

    /**
     * Helper to classify risk level based on score.
     */
    private function getRiskLevel(float $score): string
    {
        if ($score >= 70) {
            return 'Tinggi';
        } elseif ($score >= 40) {
            return 'Sedang';
        } else {
            return 'Rendah';
        }
    }
}

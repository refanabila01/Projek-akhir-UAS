<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\News;
use App\Services\ExternalApiService;
use App\Services\RiskScoringService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $apiService;
    protected $scoringService;

    public function __construct(ExternalApiService $apiService, RiskScoringService $scoringService)
    {
        $this->apiService = $apiService;
        $this->scoringService = $scoringService;
    }

    public function index(Request $request)
    {
        // 1. Get all countries for the dropdown selector
        $allCountries = Country::all();

        // 2. Determine selected country (default to Germany 'DE' or first country)
        $countryId = $request->input('country_id');
        if ($countryId) {
            $selectedCountry = Country::with('latestRiskScore')->find($countryId);
        } else {
            $selectedCountry = Country::with('latestRiskScore')->where('code', 'DE')->first() ?: Country::first();
        }

        if (!$selectedCountry) {
            return redirect()->route('dashboard')->with('error', 'Belum ada data negara.');
        }

        // Real-time synchronization check: if force refresh, or data is stale (updated_at older than 1 hour or columns are empty)
        $force = $request->has('refresh') && $request->input('refresh') == 1;
        $isStale = !$selectedCountry->updated_at || $selectedCountry->updated_at->lt(now()->subHour());
        
        if ($force || $isStale || $selectedCountry->gdp_growth == 0) {
            $realTimeData = $this->apiService->fetchRealTimeIndicators($selectedCountry->code);
            if ($realTimeData) {
                if (!empty($realTimeData['details'])) {
                    $selectedCountry->population = $realTimeData['details']['population'] ?: $selectedCountry->population;
                    $selectedCountry->currency_code = $realTimeData['details']['currency_code'] ?: $selectedCountry->currency_code;
                    $selectedCountry->currency_name = $realTimeData['details']['currency_name'] ?: $selectedCountry->currency_name;
                    $selectedCountry->flag = $realTimeData['details']['flag'] ?: $selectedCountry->flag;
                    $selectedCountry->capital = $realTimeData['details']['capital'] ?: $selectedCountry->capital;
                    $selectedCountry->region = $realTimeData['details']['region'] ?: $selectedCountry->region;
                    if ($realTimeData['details']['latitude']) {
                        $selectedCountry->latitude = $realTimeData['details']['latitude'];
                        $selectedCountry->longitude = $realTimeData['details']['longitude'];
                    }
                }
                
                if ($realTimeData['gdp'] !== null) $selectedCountry->gdp = $realTimeData['gdp'];
                if ($realTimeData['gdp_growth'] !== null) $selectedCountry->gdp_growth = $realTimeData['gdp_growth'];
                if ($realTimeData['inflation'] !== null) $selectedCountry->inflation = $realTimeData['inflation'];
                if ($realTimeData['unemployment_rate'] !== null) $selectedCountry->unemployment_rate = $realTimeData['unemployment_rate'];
                if ($realTimeData['exports'] !== null) $selectedCountry->exports = $realTimeData['exports'];
                if ($realTimeData['imports'] !== null) $selectedCountry->imports = $realTimeData['imports'];
                
                $selectedCountry->save();
            }
            
            // Force recalculate the risk score
            $this->scoringService->calculateCountryRisk($selectedCountry, true);
            $selectedCountry->load('latestRiskScore');
        }

        $latestScore = $selectedCountry->latestRiskScore;

        // 3. Fetch weather dynamically for selected country
        $weather = $this->apiService->fetchWeather($selectedCountry->latitude, $selectedCountry->longitude);

        // 4. Fetch news for selected country
        $news = News::where('country_id', $selectedCountry->id)->latest()->take(3)->get();
        if ($news->isEmpty()) {
            // Mock fallback news
            $mockNews = $this->apiService->fetchSupplyChainNews($selectedCountry->name, $selectedCountry->code);
            foreach (array_slice($mockNews, 0, 3) as $article) {
                $news[] = (object)[
                    'title' => $article['title'],
                    'content' => $article['content'],
                    'source' => $article['source'],
                    'published_at' => $article['published_at'] ?? now()->subHours(rand(1, 10)),
                    'sentiment' => 'Neutral',
                ];
            }
        }

        return view('countries.index', compact('selectedCountry', 'allCountries', 'latestScore', 'weather', 'news'));
    }

    public function show(Request $request, $id)
    {
        $selectedCountry = Country::with('latestRiskScore')->find($id);
        if (!$selectedCountry) {
            return redirect()->route('countries')->with('error', 'Negara tidak ditemukan.');
        }

        $allCountries = Country::all();

        // Real-time synchronization check: if force refresh, or data is stale (updated_at older than 1 hour or columns are empty)
        $force = $request->has('refresh') && $request->input('refresh') == 1;
        $isStale = !$selectedCountry->updated_at || $selectedCountry->updated_at->lt(now()->subHour());
        
        if ($force || $isStale || $selectedCountry->gdp_growth == 0) {
            $realTimeData = $this->apiService->fetchRealTimeIndicators($selectedCountry->code);
            if ($realTimeData) {
                if (!empty($realTimeData['details'])) {
                    $selectedCountry->population = $realTimeData['details']['population'] ?: $selectedCountry->population;
                    $selectedCountry->currency_code = $realTimeData['details']['currency_code'] ?: $selectedCountry->currency_code;
                    $selectedCountry->currency_name = $realTimeData['details']['currency_name'] ?: $selectedCountry->currency_name;
                    $selectedCountry->flag = $realTimeData['details']['flag'] ?: $selectedCountry->flag;
                    $selectedCountry->capital = $realTimeData['details']['capital'] ?: $selectedCountry->capital;
                    $selectedCountry->region = $realTimeData['details']['region'] ?: $selectedCountry->region;
                    if ($realTimeData['details']['latitude']) {
                        $selectedCountry->latitude = $realTimeData['details']['latitude'];
                        $selectedCountry->longitude = $realTimeData['details']['longitude'];
                    }
                }
                
                if ($realTimeData['gdp'] !== null) $selectedCountry->gdp = $realTimeData['gdp'];
                if ($realTimeData['gdp_growth'] !== null) $selectedCountry->gdp_growth = $realTimeData['gdp_growth'];
                if ($realTimeData['inflation'] !== null) $selectedCountry->inflation = $realTimeData['inflation'];
                if ($realTimeData['unemployment_rate'] !== null) $selectedCountry->unemployment_rate = $realTimeData['unemployment_rate'];
                if ($realTimeData['exports'] !== null) $selectedCountry->exports = $realTimeData['exports'];
                if ($realTimeData['imports'] !== null) $selectedCountry->imports = $realTimeData['imports'];
                
                $selectedCountry->save();
            }
            
            $this->scoringService->calculateCountryRisk($selectedCountry, true);
            $selectedCountry->load('latestRiskScore');
        }

        $latestScore = $selectedCountry->latestRiskScore;
        $weather = $this->apiService->fetchWeather($selectedCountry->latitude, $selectedCountry->longitude);

        $news = News::where('country_id', $selectedCountry->id)->latest()->take(3)->get();
        if ($news->isEmpty()) {
            $mockNews = $this->apiService->fetchSupplyChainNews($selectedCountry->name, $selectedCountry->code);
            foreach (array_slice($mockNews, 0, 3) as $article) {
                $news[] = (object)[
                    'title' => $article['title'],
                    'content' => $article['content'],
                    'source' => $article['source'],
                    'published_at' => $article['published_at'] ?? now()->subHours(rand(1, 10)),
                    'sentiment' => 'Neutral',
                ];
            }
        }

        return view('countries.show', [
            'country' => $selectedCountry,
            'latestScore' => $latestScore,
            'weather' => $weather,
            'news' => $news
        ]);
    }
}
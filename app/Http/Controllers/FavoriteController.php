<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Country;
use App\Services\RiskScoringService;
use App\Services\ExternalApiService;

class FavoriteController extends Controller
{
    protected $scoringService;
    protected $apiService;

    public function __construct(RiskScoringService $scoringService, ExternalApiService $apiService)
    {
        $this->scoringService = $scoringService;
        $this->apiService = $apiService;
    }

    public function index()
    {
        $userId = auth()->id();

        // Seeding initial favorites if empty for demonstration purposes
        $count = Favorite::where('user_id', $userId)->count();
        if ($count === 0) {
            $initialCountries = Country::whereIn('code', ['ID', 'CN', 'US', 'SG'])->get();
            foreach ($initialCountries as $c) {
                Favorite::create([
                    'user_id' => $userId,
                    'country_id' => $c->id,
                ]);
            }
        }

        $favorites = Favorite::where('user_id', $userId)->with('country')->get();
        $watchlistData = [];

        foreach ($favorites as $fav) {
            $c = $fav->country;
            if ($c) {
                $latestScore = $this->scoringService->calculateCountryRisk($c);
                $weather = $this->apiService->fetchWeather($c->latitude, $c->longitude);

                // Port status: get a port in this country or default to Medium
                $port = \App\Models\Port::where('country_code', $c->code)->first();
                $portStatus = $port ? $port->congestion_status : 'Low';

                $watchlistData[] = [
                    'code' => $c->code,
                    'flag' => $c->flag ?? '🏳️',
                    'name' => $c->name,
                    'risk_score' => $latestScore->total_score,
                    'risk_level' => $latestScore->total_score >= 70 ? 'Tinggi' : ($latestScore->total_score >= 40 ? 'Sedang' : 'Rendah'),
                    'weather_desc' => $weather['description'] ?? 'Cerah',
                    'currency_code' => $c->currency_code ?? 'USD',
                    'port_status' => $portStatus,
                ];
            }
        }

        return view('watchlist.index', compact('watchlistData'));
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    /**
     * Fetch country details from REST Countries API.
     * https://restcountries.com/v3.1/alpha/{code}
     */
    public function fetchCountryDetails(string $countryCode)
    {
        try {
            $response = Http::timeout(5)->get("https://restcountries.com/v3.1/alpha/{$countryCode}");
            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data) && is_array($data)) {
                    $countryInfo = $data[0];
                    $currencies = $countryInfo['currencies'] ?? [];
                    $currencyCode = array_key_first($currencies);
                    $currencyName = $currencies[$currencyCode]['name'] ?? null;

                    return [
                        'name' => $countryInfo['name']['common'] ?? null,
                        'flag' => $countryInfo['flag'] ?? null,
                        'capital' => $countryInfo['capital'][0] ?? null,
                        'region' => $countryInfo['subregion'] ?? $countryInfo['region'] ?? null,
                        'population' => $countryInfo['population'] ?? 0,
                        'currency_code' => $currencyCode,
                        'currency_name' => $currencyName,
                        'latitude' => $countryInfo['latlng'][0] ?? null,
                        'longitude' => $countryInfo['latlng'][1] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("REST Countries API error for {$countryCode}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Fetch economic indicators (GDP, Inflation) from World Bank API.
     * http://api.worldbank.org/v2/country/{code}/indicator/{indicator}?format=json&mrnev=1
     */
    public function fetchWorldBankIndicator(string $countryCode, string $indicator)
    {
        try {
            // mrnev=1 gets the most recent non-empty value
            $response = Http::timeout(5)->get("http://api.worldbank.org/v2/country/{$countryCode}/indicator/{$indicator}", [
                'format' => 'json',
                'mrnev' => 1,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data[1][0]['value'])) {
                    return $data[1][0]['value'];
                }
            }
        } catch (\Exception $e) {
            Log::error("World Bank API error for {$countryCode} (Indicator: {$indicator}): " . $e->getMessage());
        }

        return null;
    }

    /**
     * Fetch weather data from Open-Meteo API (No API key needed).
     * https://api.open-meteo.com/v1/forecast
     */
    public function fetchWeather(float $latitude, float $longitude)
    {
        try {
            $response = Http::timeout(5)->get("https://api.open-meteo.com/v1/forecast", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current' => 'temperature_2m,relative_humidity_2m,rain,showers,wind_speed_10m',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $current = $data['current'] ?? [];
                
                // Determine weather description and risk level
                $temp = $current['temperature_2m'] ?? 25;
                $rain = ($current['rain'] ?? 0) + ($current['showers'] ?? 0);
                $wind = $current['wind_speed_10m'] ?? 0;
                
                $description = 'Cerah';
                $weatherRisk = 0; // scale 0-100

                if ($rain > 10) {
                    $description = 'Hujan Lebat';
                    $weatherRisk = 70;
                } elseif ($rain > 0.5) {
                    $description = 'Hujan Ringan';
                    $weatherRisk = 30;
                } elseif ($wind > 40) {
                    $description = 'Angin Kencang / Badai';
                    $weatherRisk = 85;
                } elseif ($wind > 20) {
                    $description = 'Berangin';
                    $weatherRisk = 25;
                } elseif ($temp > 38 || $temp < 0) {
                    $description = 'Suhu Ekstrim';
                    $weatherRisk = 50;
                }

                return [
                    'temperature' => $temp,
                    'humidity' => $current['relative_humidity_2m'] ?? 50,
                    'rain' => $rain,
                    'wind_speed' => $wind,
                    'description' => $description,
                    'risk_score' => $weatherRisk,
                ];
            }
        } catch (\Exception $e) {
            Log::error("Open-Meteo API error at [{$latitude}, {$longitude}]: " . $e->getMessage());
        }

        // Return a mock default if API fails
        return [
            'temperature' => 25,
            'humidity' => 60,
            'rain' => 0,
            'wind_speed' => 10,
            'description' => 'Cerah',
            'risk_score' => 10,
        ];
    }

    /**
     * Fetch Exchange Rates relative to USD.
     * https://open.er-api.com/v6/latest/USD
     */
    public function fetchExchangeRates()
    {
        try {
            $response = Http::timeout(5)->get("https://open.er-api.com/v6/latest/USD");
            if ($response->successful()) {
                $data = $response->json();
                return $data['rates'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error("ExchangeRate API error: " . $e->getMessage());
        }

        // Return a robust default fallback if offline
        return [
            'USD' => 1.0,
            'IDR' => 17944.0,
            'CNY' => 7.25,
            'EUR' => 0.92,
            'SGD' => 1.34,
            'JPY' => 158.5,
            'GBP' => 0.78,
            'AUD' => 1.49,
            'CAD' => 1.37,
            'ARS' => 920.0,
            'RUB' => 88.5,
            'INR' => 83.5,
            'AED' => 3.67,
            'AFN' => 70.8,
            'ALL' => 92.5,
            'AMD' => 388.0,
            'ANG' => 1.79,
            'AOA' => 840.0,
        ];
    }

    /**
     * Fetch supply chain related news using GNews API.
     * If no API key is set, or request fails, falls back to local simulation.
     */
    public function fetchSupplyChainNews(string $countryName, string $countryCode)
    {
        $apiKey = env('GNEWS_API_KEY');

        if (!empty($apiKey)) {
            try {
                $query = "\"{$countryName}\" AND (supply chain OR logistics OR port OR trade OR tariff OR strike OR economy)";
                $response = Http::timeout(5)->get("https://gnews.io/api/v4/search", [
                    'q' => $query,
                    'lang' => 'en',
                    'apikey' => $apiKey,
                    'max' => 5
                ]);

                if ($response->successful()) {
                    $articles = $response->json()['articles'] ?? [];
                    if (!empty($articles)) {
                        return array_map(function ($article) {
                            return [
                                'title' => $article['title'],
                                'content' => $article['description'] ?? $article['content'] ?? '',
                                'source' => $article['source']['name'] ?? 'GNews',
                                'url' => $article['url'] ?? '',
                                'published_at' => isset($article['publishedAt']) ? date('Y-m-d H:i:s', strtotime($article['publishedAt'])) : now()->toDateTimeString(),
                            ];
                        }, $articles);
                    }
                }
            } catch (\Exception $e) {
                Log::error("GNews API error for {$countryName}: " . $e->getMessage());
            }
        }

        // Local realistic simulation for demo/testing fallback
        return $this->getMockNews($countryName, $countryCode);
    }

    /**
     * Generate realistic simulated supply chain news articles for demo robustness.
     */
    private function getMockNews(string $countryName, string $countryCode): array
    {
        $now = now();
        
        $templates = [
            [
                'title' => "Kepadatan pelabuhan di {$countryName} menyebabkan keterlambatan logistik yang parah",
                'content' => "Peningkatan throughput dan kekurangan tenaga kerja pelabuhan di {$countryName} telah menyebabkan penumpukan kapal yang signifikan. Manajer logistik memperingatkan keterlambatan hingga dua minggu.",
                'source' => "Global Trade Review",
                'url' => "https://example.com/news/1",
                'sentiment' => 'Negative',
                'sentiment_score' => -1.0,
            ],
            [
                'title' => "Fluktuasi mata uang di {$countryName} berdampak pada biaya impor",
                'content' => "Depresiasi mata uang lokal baru-baru ini di {$countryName} terhadap USD meningkatkan biaya input manufaktur, menimbulkan kekhawatiran bagi eksportir utama.",
                'source' => "Supply Chain Digital",
                'url' => "https://example.com/news/2",
                'sentiment' => 'Negative',
                'sentiment_score' => -0.8,
            ],
            [
                'title' => "Cuaca ekstrem mengganggu koridor transportasi utama di {$countryName}",
                'content' => "Curah hujan tinggi dan banjir telah merusak infrastruktur jalan tol kritis di {$countryName}, memutus hubungan zona industri utama dari terminal pengapalan utama.",
                'source' => "Logistics Brief",
                'url' => "https://example.com/news/3",
                'sentiment' => 'Negative',
                'sentiment_score' => -0.9,
            ],
            [
                'title' => "Hubungan perdagangan baru diumumkan antara {$countryName} dan mitra utama",
                'content' => "Kerjasama bilateral baru di {$countryName} diharapkan dapat meningkatkan kelancaran logistik dan mempercepat bea cukai bagi perusahaan global.",
                'source' => "Economy Weekly",
                'url' => "https://example.com/news/4",
                'sentiment' => 'Positive',
                'sentiment_score' => 0.8,
            ]
        ];

        // Shuffle templates and return 2-3 articles
        shuffle($templates);
        $count = rand(2, 3);
        $articles = [];
        
        for ($i = 0; $i < $count; $i++) {
            $article = $templates[$i];
            $article['published_at'] = $now->copy()->subHours(rand(1, 48))->toDateTimeString();
            $articles[] = $article;
        }

        return $articles;
    }

    /**
     * Fetch real-time economic indicators (GDP, growth, inflation, unemployment, exports, imports, details)
     * concurrently using Http::pool.
     */
    public function fetchRealTimeIndicators(string $countryCode)
    {
        $code = strtolower($countryCode);
        try {
            $responses = Http::pool(fn (\Illuminate\Http\Client\Pool $pool) => [
                $pool->as('gdp')->timeout(5)->get("http://api.worldbank.org/v2/country/{$code}/indicator/NY.GDP.MKTP.CD?format=json&mrnev=1"),
                $pool->as('gdp_growth')->timeout(5)->get("http://api.worldbank.org/v2/country/{$code}/indicator/NY.GDP.MKTP.KD.ZG?format=json&mrnev=1"),
                $pool->as('inflation')->timeout(5)->get("http://api.worldbank.org/v2/country/{$code}/indicator/FP.CPI.TOTL.ZG?format=json&mrnev=1"),
                $pool->as('unemployment')->timeout(5)->get("http://api.worldbank.org/v2/country/{$code}/indicator/SL.UEM.TOTL.ZS?format=json&mrnev=1"),
                $pool->as('exports')->timeout(5)->get("http://api.worldbank.org/v2/country/{$code}/indicator/NE.EXP.GNFS.CD?format=json&mrnev=1"),
                $pool->as('imports')->timeout(5)->get("http://api.worldbank.org/v2/country/{$code}/indicator/NE.IMP.GNFS.CD?format=json&mrnev=1"),
                $pool->as('details')->timeout(5)->get("https://restcountries.com/v3.1/alpha/{$code}"),
            ]);

            $extractVal = function ($response) {
                if ($response && $response instanceof \Illuminate\Http\Client\Response && $response->successful()) {
                    $data = $response->json();
                    if (isset($data[1][0]['value'])) {
                        return $data[1][0]['value'];
                    }
                }
                return null;
            };

            $gdp = $extractVal($responses['gdp'] ?? null);
            $gdpGrowth = $extractVal($responses['gdp_growth'] ?? null);
            $inflation = $extractVal($responses['inflation'] ?? null);
            $unemployment = $extractVal($responses['unemployment'] ?? null);
            $exports = $extractVal($responses['exports'] ?? null);
            $imports = $extractVal($responses['imports'] ?? null);

            $details = null;
            if (isset($responses['details']) && $responses['details'] instanceof \Illuminate\Http\Client\Response && $responses['details']->successful()) {
                $data = $responses['details']->json();
                if (!empty($data) && is_array($data)) {
                    $countryInfo = $data[0];
                    $currencies = $countryInfo['currencies'] ?? [];
                    $currencyCode = array_key_first($currencies);
                    $currencyName = $currencies[$currencyCode]['name'] ?? null;

                    $details = [
                        'name' => $countryInfo['name']['common'] ?? null,
                        'flag' => $countryInfo['flag'] ?? null,
                        'capital' => $countryInfo['capital'][0] ?? null,
                        'region' => $countryInfo['subregion'] ?? $countryInfo['region'] ?? null,
                        'population' => $countryInfo['population'] ?? 0,
                        'currency_code' => $currencyCode,
                        'currency_name' => $currencyName,
                        'latitude' => $countryInfo['latlng'][0] ?? null,
                        'longitude' => $countryInfo['latlng'][1] ?? null,
                    ];
                }
            }

            return [
                'gdp' => $gdp,
                'gdp_growth' => $gdpGrowth,
                'inflation' => $inflation,
                'unemployment_rate' => $unemployment,
                'exports' => $exports,
                'imports' => $imports,
                'details' => $details,
            ];
        } catch (\Exception $e) {
            Log::error("Error fetching concurrent real-time indicators for {$countryCode}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch global supply chain news based on category (Logistics, Trade, Shipping, Economy).
     */
    public function fetchGlobalNews(string $category)
    {
        $apiKey = env('GNEWS_API_KEY');
        $category = ucfirst(strtolower($category));
        
        if (!empty($apiKey)) {
            try {
                $query = "\"{$category}\" AND (\"supply chain\" OR \"logistics\" OR \"port\" OR \"shipping\" OR \"trade\" OR \"economy\")";
                $response = Http::timeout(5)->get("https://gnews.io/api/v4/search", [
                    'q' => $query,
                    'lang' => 'en',
                    'apikey' => $apiKey,
                    'max' => 6
                ]);

                if ($response->successful()) {
                    $articles = $response->json()['articles'] ?? [];
                    if (!empty($articles)) {
                        return array_map(function ($article) use ($category) {
                            return [
                                'title' => $article['title'],
                                'content' => $article['description'] ?? $article['content'] ?? '',
                                'source' => $article['source']['name'] ?? 'GNews',
                                'url' => $article['url'] ?? '',
                                'image' => $article['image'] ?? null,
                                'published_at' => isset($article['publishedAt']) ? date('Y-m-d H:i:s', strtotime($article['publishedAt'])) : now()->toDateTimeString(),
                                'category' => $category,
                            ];
                        }, $articles);
                    }
                }
            } catch (\Exception $e) {
                Log::error("GNews global API error for category {$category}: " . $e->getMessage());
            }
        }

        // Mock fallback articles
        $now = now();
        $mockArticles = [];

        if ($category === 'Logistics') {
            $mockArticles = [
                [
                    'title' => 'Warehouse Automation Surges in Response to Global Labor Shortage',
                    'content' => 'Logistics companies are rapidly accelerating the deployment of autonomous mobile robots and smart sorting systems to keep up with the soaring demand and rising labor costs.',
                    'source' => 'Logistics Today',
                    'url' => 'https://www.supplychaindigital.com/',
                    'image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subMinutes(15)->toDateTimeString(),
                    'category' => 'Logistics',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.7,
                ],
                [
                    'title' => 'Severe Freight Bottlenecks Reported Across Major European Transit Hubs',
                    'content' => 'A sudden increase in cargo volume combined with customs processing delays has created a backlog of trucks at border crossings, threatening supply chain schedules.',
                    'source' => 'Freight Waves',
                    'url' => 'https://www.freightwaves.com/',
                    'image' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(2)->toDateTimeString(),
                    'category' => 'Logistics',
                    'sentiment' => 'Negative',
                    'sentiment_score' => -0.8,
                ],
                [
                    'title' => 'Green Logistics Initiatives Gain Momentum as Corporate Carbon Tariffs Loom',
                    'content' => 'Major retail brands are pressuring logistics providers to deploy electric transport fleets and utilize eco-friendly packaging solutions to hit zero-carbon goals.',
                    'source' => 'Supply Chain Info',
                    'url' => 'https://www.supplychainbrain.com/',
                    'image' => 'https://images.unsplash.com/photo-1506012787146-f92b2d7d6d96?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(5)->toDateTimeString(),
                    'category' => 'Logistics',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.6,
                ]
            ];
        } elseif ($category === 'Trade') {
            $mockArticles = [
                [
                    'title' => 'New Bilateral Trade Pact Signed to Eliminate Tariffs on Critical Minerals',
                    'content' => 'The comprehensive agreement aims to streamline supply chains for battery production and electric vehicles, reducing import fees by up to 15 percent.',
                    'source' => 'World Trade Review',
                    'url' => 'https://www.wto.org/',
                    'image' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subMinutes(45)->toDateTimeString(),
                    'category' => 'Trade',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.85,
                ],
                [
                    'title' => 'Rising Trade Tensions Threaten Semiconductor Supply Lines',
                    'content' => 'New export restrictions on manufacturing components have sparked concerns of a renewed global microchip shortage, impacting consumer electronics brands.',
                    'source' => 'Global Commerce Bulletin',
                    'url' => 'https://www.ft.com/',
                    'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(3)->toDateTimeString(),
                    'category' => 'Trade',
                    'sentiment' => 'Negative',
                    'sentiment_score' => -0.9,
                ],
                [
                    'title' => 'Cross-Border E-Commerce Reaches Record High in Q2',
                    'content' => 'Simplified customs regulations and improved payment infrastructure have accelerated international trade volumes, particularly between emerging Asian economies and Europe.',
                    'source' => 'Retail Inside',
                    'url' => 'https://www.bloomberg.com/',
                    'image' => 'https://images.unsplash.com/photo-1557821552-17105176677c?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(7)->toDateTimeString(),
                    'category' => 'Trade',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.75,
                ]
            ];
        } elseif ($category === 'Shipping') {
            $mockArticles = [
                [
                    'title' => 'Panama Canal Transit Restrictions Eased as Water Levels Recover',
                    'content' => 'The Panama Canal Authority announces an increase in daily vessel booking slots, providing significant relief for global shipping companies and shortening trade times.',
                    'source' => 'Maritime Executive',
                    'url' => 'https://maritime-executive.com/',
                    'image' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subMinutes(30)->toDateTimeString(),
                    'category' => 'Shipping',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.8,
                ],
                [
                    'title' => 'Port Congestion Intensifies at Major East Coast Maritime Terminals',
                    'content' => 'Dozens of container ships are waiting at anchor off-shore due to labor strikes and crane maintenance delays, causing severe supply chain ripple effects.',
                    'source' => 'Ocean Freight News',
                    'url' => 'https://www.joc.com/',
                    'image' => 'https://images.unsplash.com/photo-1574044536226-168a8e27f4f0?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(4)->toDateTimeString(),
                    'category' => 'Shipping',
                    'sentiment' => 'Negative',
                    'sentiment_score' => -0.75,
                ],
                [
                    'title' => 'Next-Generation Ammonia Powered Cargo Ship Unveiled',
                    'content' => 'Leading maritime carriers showcase their first zero-emission bulk carrier vessel, charting a green pathway for international shipping lanes.',
                    'source' => 'EcoMarine News',
                    'url' => 'https://www.cleanenergywire.org/',
                    'image' => 'https://images.unsplash.com/photo-1516216628859-9bccecab13ca?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(9)->toDateTimeString(),
                    'category' => 'Shipping',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.9,
                ]
            ];
        } else { // Economy
            $mockArticles = [
                [
                    'title' => 'Inflation Cools Down to Target Levels in Major Developed Nations',
                    'content' => 'Central banks signal possible rate cuts in the coming months as consumer price indexes drop, lowering borrowing costs for logistics expansions.',
                    'source' => 'Financial Monitor',
                    'url' => 'https://www.economist.com/',
                    'image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subMinutes(10)->toDateTimeString(),
                    'category' => 'Economy',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.8,
                ],
                [
                    'title' => 'Rising Raw Material Costs Squeeze Manufacturer Profit Margins',
                    'content' => 'Industrial copper and steel shortages drive prices up, causing manufacturers to raise wholesale rates and sparking fresh supply chain cost concerns.',
                    'source' => 'Economy Journal',
                    'url' => 'https://www.wsj.com/',
                    'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(1)->toDateTimeString(),
                    'category' => 'Economy',
                    'sentiment' => 'Negative',
                    'sentiment_score' => -0.85,
                ],
                [
                    'title' => 'Global Manufacturing Index Shows Surprising Resilience in Q2',
                    'content' => 'Despite geopolitical tensions, a surge in smart manufacturing and high-tech output drives economic growth across Europe and North America.',
                    'source' => 'Industrial Outlook',
                    'url' => 'https://www.bloomberg.com/',
                    'image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=800&auto=format&fit=crop',
                    'published_at' => $now->copy()->subHours(6)->toDateTimeString(),
                    'category' => 'Economy',
                    'sentiment' => 'Positive',
                    'sentiment_score' => 0.7,
                ]
            ];
        }

        return $mockArticles;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Country;

class WeatherController extends Controller
{
    // 195 sovereign countries recognised internationally (UN members + Vatican + Palestine)
    private const SOVEREIGN_CODES = [
        'AF','AL','DZ','AD','AO','AG','AR','AM','AU','AT','AZ',
        'BS','BH','BD','BB','BY','BE','BZ','BJ','BT','BO','BA','BW','BR','BN','BG','BF','BI',
        'CV','KH','CM','CA','CF','TD','CL','CN','CO','KM','CG','CD','CR','CI','HR','CU','CY','CZ',
        'DK','DJ','DM','DO',
        'EC','EG','SV','GQ','ER','EE','SZ','ET',
        'FJ','FI','FR',
        'GA','GM','GE','DE','GH','GR','GD','GT','GN','GW','GY',
        'HT','HN','HU',
        'IS','IN','ID','IR','IQ','IE','IL','IT',
        'JM','JP','JO',
        'KZ','KE','KI','KP','KR','KW','KG',
        'LA','LV','LB','LS','LR','LY','LI','LT','LU',
        'MG','MW','MY','MV','ML','MT','MH','MR','MU','MX','FM','MD','MC','MN','ME','MA','MZ','MM',
        'NA','NR','NP','NL','NZ','NI','NE','NG','NO',
        'OM',
        'PK','PW','PA','PG','PY','PE','PH','PL','PT',
        'QA',
        'RO','RU','RW',
        'KN','LC','VC','WS','SM','ST','SA','SN','RS','SC','SL','SG','SK','SI','SB','SO','ZA','SS',
        'ES','LK','SD','SR','SE','CH','SY',
        'TW','TJ','TZ','TH','TL','TG','TO','TT','TN','TR','TM','TV',
        'UG','UA','AE','GB','US','UY','UZ',
        'VU','VE','VN',
        'YE',
        'ZM','ZW',
        'VA','PS', // Vatican + Palestine
    ];

    public function index()
    {
        // Load only 195 sovereign countries from DB — NO external API calls (avoids timeout)
        $allCountries = Country::with('latestRiskScore')
            ->whereIn('code', self::SOVEREIGN_CODES)
            ->get();

        $weatherData = [];

        foreach ($allCountries as $c) {
            $lat = abs($c->latitude ?? 0);

            // Estimate weather from latitude (fast, no API call)
            if ($lat <= 12) {
                $temp = rand(270, 340) / 10;   // Tropis: 27-34°C
                $humidity = rand(70, 90);
                $rain = rand(0, 4) > 2 ? rand(1, 8) / 10 : 0;
                $desc = $rain > 0 ? 'Hujan Ringan' : 'Cerah Berawan';
            } elseif ($lat <= 25) {
                $temp = rand(240, 330) / 10;   // Sub-tropis: 24-33°C
                $humidity = rand(45, 70);
                $rain = 0;
                $desc = 'Cerah';
            } elseif ($lat <= 40) {
                $temp = rand(150, 260) / 10;   // Mediteran/Hangat: 15-26°C
                $humidity = rand(50, 70);
                $rain = rand(0, 3) > 2 ? rand(1, 5) / 10 : 0;
                $desc = $rain > 0 ? 'Hujan Ringan' : 'Sebagian Berawan';
            } elseif ($lat <= 55) {
                $temp = rand(80, 190) / 10;    // Sedang: 8-19°C
                $humidity = rand(55, 75);
                $rain = rand(0, 3) > 1 ? rand(1, 3) / 10 : 0;
                $desc = $rain > 0 ? 'Berawan Hujan' : 'Berawan';
            } elseif ($lat <= 65) {
                $temp = rand(-20, 100) / 10;   // Dingin: -2 to 10°C
                $humidity = rand(55, 75);
                $rain = rand(0, 3) > 2 ? rand(1, 2) / 10 : 0;
                $desc = $rain > 0 ? 'Hujan Ringan' : 'Berawan';
            } else {
                $temp = rand(-250, -20) / 10;  // Kutub: -25 to -2°C
                $humidity = rand(45, 65);
                $rain = 0;
                $desc = 'Salju';
            }

            $wind = rand(50, 250) / 10;
            $riskScore = 10;

            // Determine icon
            if ($temp <= 0 || $desc === 'Salju') {
                $icon = '❄️'; $iconType = 'salju';
            } elseif ($rain > 5) {
                $icon = '⛈️'; $iconType = 'badai';
            } elseif ($rain > 0.3) {
                $icon = '🌧️'; $iconType = 'hujan';
            } elseif ($wind > 30) {
                $icon = '🌬️'; $iconType = 'berangin';
            } elseif ($temp > 30) {
                $icon = '☀️'; $iconType = 'cerah';
            } else {
                $icon = '⛅'; $iconType = 'berawan';
            }

            $latestScore = $c->latestRiskScore;
            $totalRisk = $latestScore ? $latestScore->total_score : 40;

            $weatherData[] = [
                'code'       => $c->code,
                'flag'       => $c->flag ?? '🏳️',
                'name'       => $c->name,
                'capital'    => $c->capital ?? '',
                'temp'       => round($temp, 1),
                'humidity'   => $humidity,
                'rain'       => round($rain, 1),
                'wind'       => round($wind, 1),
                'desc'       => $desc,
                'icon'       => $icon,
                'icon_type'  => $iconType,
                'risk_score' => $riskScore,
                'total_risk' => $totalRisk,
                'risk_level' => $riskScore >= 70 ? 'Tinggi' : ($riskScore >= 40 ? 'Sedang' : 'Rendah'),
                'latitude'   => $c->latitude,
                'longitude'  => $c->longitude,
                'is_live'    => false, // will be updated client-side via JS
            ];
        }

        // Sort by temp descending
        usort($weatherData, fn($a, $b) => $b['temp'] <=> $a['temp']);

        // Major country codes for client-side live update
        $majorCodes = ['ID','CN','US','SG','JP','RU','DE','AU','NL','AR','BR','IN','GB','FR','SA','ZA','CA','KR','TR','MX'];

        // Build map data from weatherData for major countries (lat/lon only — temp updated client-side)
        $mapData = array_filter($weatherData, fn($d) => in_array($d['code'], $majorCodes));

        return view('weather.index', compact('weatherData', 'mapData', 'majorCodes'));
    }
}
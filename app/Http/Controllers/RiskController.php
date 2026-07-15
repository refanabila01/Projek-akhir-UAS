<?php

namespace App\Http\Controllers;

use App\Models\Country;

class RiskController extends Controller
{
    // 195 sovereign countries only (same list as WeatherController)
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
        'VA','PS',
    ];

    public function index()
    {
        // Load only sovereign countries with their stored risk score — NO external API calls
        $countries = Country::with('latestRiskScore')
            ->whereIn('code', self::SOVEREIGN_CODES)
            ->whereNotNull('latitude')
            ->orderBy('name')
            ->get();

        $riskData = [];

        foreach ($countries as $c) {
            $score = $c->latestRiskScore;

            // Use stored score, or calculate a simple estimate from DB fields
            if ($score) {
                $weatherScore   = round($score->weather_score, 1);
                $inflationScore = round($score->inflation_score, 1);
                $currencyScore  = round($score->currency_score, 1);
                $newsScore      = round($score->news_score, 1);
                $totalScore     = round($score->total_score, 1);
            } else {
                // Estimate from stored inflation data
                $infl = $c->inflation ?? 3;
                $inflationScore = min(100, $infl * 5);
                $weatherScore   = 10;
                $currencyScore  = 15;
                $newsScore      = 20;
                $totalScore     = round(
                    ($weatherScore * 0.30) + ($inflationScore * 0.20) +
                    ($currencyScore * 0.10) + ($newsScore * 0.40), 1
                );
            }

            $riskLevel = $totalScore >= 70 ? 'Tinggi' : ($totalScore >= 40 ? 'Sedang' : 'Rendah');

            $descMap = [
                'Tinggi'  => 'Gangguan logistik kritis, perlu mitigasi segera',
                'Sedang'  => 'Perlu pengawasan ketat & rencana kontigensi',
                'Rendah'  => 'Operasional rantai pasok aman & stabil',
            ];

            $riskData[] = [
                'flag'            => $c->flag ?? '🏳️',
                'name'            => $c->name,
                'code'            => $c->code,
                'capital'         => $c->capital ?? '',
                'region'          => $c->region ?? '',
                'weather_score'   => $weatherScore,
                'inflation_score' => $inflationScore,
                'currency_score'  => $currencyScore,
                'news_score'      => $newsScore,
                'total_score'     => $totalScore,
                'risk_level'      => $riskLevel,
                'description'     => $descMap[$riskLevel],
            ];
        }

        // Sort by total score descending (highest risk first)
        usort($riskData, fn($a, $b) => $b['total_score'] <=> $a['total_score']);

        return view('risk.index', compact('riskData'));
    }
}

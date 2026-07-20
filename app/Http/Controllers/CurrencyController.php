<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CurrencyController extends Controller
{
    public function index()
    {
        // Load all countries from DB with currency data — no external API needed
        $allCountries = Country::select(
            'id', 'name', 'flag', 'code', 'capital', 'region',
            'currency_code', 'currency_name', 'inflation', 'gdp'
        )->whereNotNull('currency_code')->orderBy('name')->get();

        // Simulated live rates vs USD (based on real approximate values)
        // Client-side JS will fetch live data from exchangerate.host after page loads
        $knownRates = [
            'IDR' => 17944,  'CNY' => 7.25,   'EUR' => 0.92,  'SGD' => 1.34,
            'JPY' => 155.0,  'RUB' => 90.0,   'AUD' => 1.52,  'GBP' => 0.78,
            'INR' => 83.5,   'BRL' => 5.05,   'CAD' => 1.36,  'MXN' => 17.2,
            'KRW' => 1340.0, 'SAR' => 3.75,   'AED' => 3.67,  'TRY' => 32.5,
            'ZAR' => 18.8,   'THB' => 35.5,   'MYR' => 4.68,  'PHP' => 56.5,
            'CHF' => 0.89,   'HKD' => 7.82,   'NZD' => 1.63,  'NOK' => 10.6,
            'SEK' => 10.4,   'DKK' => 6.88,   'PLN' => 3.97,  'CZK' => 23.5,
            'HUF' => 360.0,  'RON' => 4.58,   'BGN' => 1.80,  'HRK' => 6.92,
            'UAH' => 38.5,   'EGP' => 48.5,   'NGN' => 1590,  'KES' => 130.0,
            'GHS' => 15.6,   'TZS' => 2640,   'UGX' => 3820,  'ETB' => 57.0,
            'MAD' => 9.95,   'TND' => 3.09,   'DZD' => 134.5, 'LYD' => 4.82,
            'ARS' => 893.0,  'CLP' => 960.0,  'COP' => 3950,  'PEN' => 3.72,
            'VES' => 36.5,   'BOB' => 6.91,   'PYG' => 7350,  'UYU' => 39.5,
            'PKR' => 278.0,  'BDT' => 110.0,  'LKR' => 304.0, 'NPR' => 133.0,
            'MMK' => 2100,   'KHR' => 4070,   'LAK' => 21800, 'VND' => 25400,
            'MNT' => 3440,   'KZT' => 450.0,  'UZS' => 12700, 'GEL' => 2.69,
            'AMD' => 387.0,  'AZN' => 1.70,   'IQD' => 1312,  'IRR' => 42250,
            'JOD' => 0.709,  'KWD' => 0.307,  'OMR' => 0.385, 'QAR' => 3.64,
            'BHD' => 0.376,  'YER' => 250.0,  'SYP' => 2512,  'LBP' => 89600,
            'ILS' => 3.74,   'CRC' => 521.0,  'GTQ' => 7.79,  'HNL' => 24.7,
            'NIO' => 36.6,   'CUP' => 24.0,   'DOP' => 58.5,  'HTG' => 132.0,
            'JMD' => 156.0,  'TTD' => 6.80,   'BSD' => 1.00,  'BBD' => 2.00,
            'XCD' => 2.70,   'AWG' => 1.79,   'SRD' => 36.5,  'GYD' => 209.0,
            'FJD' => 2.22,   'PGK' => 3.93,   'SBD' => 8.52,  'WST' => 2.73,
            'TOP' => 2.36,   'VUV' => 119.0,  'XPF' => 109.0, 'KID' => 1.52,
        ];

        // Build currency data for all countries
        $currencyData = [];
        $seen = [];

        foreach ($allCountries as $c) {
            $code = $c->currency_code;
            if (!$code || in_array($code, $seen)) continue;
            $seen[] = $code;

            $rate = $knownRates[$code] ?? null;

            // Determine risk level based on known volatility
            $highRisk = ['IDR','RUB','TRY','ARS','VES','NGN','ZAR','EGP','IRR','SYP','LBP','HTG','YER','MMK'];
            $lowRisk  = ['EUR','CHF','SGD','HKD','AED','SAR','JOD','KWD','OMR','QAR','BHD','USD','GBP','AUD','NZD'];
            $riskLevel = in_array($code, $highRisk) ? 'Tinggi' : (in_array($code, $lowRisk) ? 'Rendah' : 'Sedang');

            $currencyData[] = [
                'flag'          => $c->flag ?? '🏳️',
                'country'       => $c->name,
                'capital'       => $c->capital ?? '',
                'code'          => $c->code,
                'currency_code' => $code,
                'currency_name' => $c->currency_name ?? $code,
                'rate'          => $rate,
                'risk_level'    => $riskLevel,
                'inflation'     => round($c->inflation ?? 0, 2),
            ];
        }

        // Sort: known rates first, then alphabetical
        usort($currencyData, function($a, $b) {
            if ($a['rate'] !== null && $b['rate'] === null) return -1;
            if ($a['rate'] === null && $b['rate'] !== null) return 1;
            return strcmp($a['country'], $b['country']);
        });

        // Top 4 highlight currencies
        $highlights = [
            'IDR' => $knownRates['IDR'],
            'CNY' => $knownRates['CNY'],
            'EUR' => $knownRates['EUR'],
            'SGD' => $knownRates['SGD'],
        ];

        $total = count($currencyData);

        return view('currency.index', compact('currencyData', 'highlights', 'total'));
    }
}

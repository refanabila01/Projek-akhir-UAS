<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CompareController extends Controller
{
    public function index()
    {
        $countries = Country::all()->map(function ($c) {
            $latestScore = $c->latestRiskScore;
            
            // Get port status
            $port = \App\Models\Port::where('country_code', $c->code)->first();
            $portStatus = $port ? $port->congestion_status : 'Low';

            return [
                'id' => $c->id,
                'flag' => $c->flag ?? '🏳️',
                'name' => $c->name,
                'gdp' => $c->gdp,
                'inflation' => $c->inflation,
                'currency_code' => $c->currency_code,
                'risk_score' => $latestScore ? $latestScore->total_score : 40,
                'risk_level' => $latestScore ? ($latestScore->total_score >= 70 ? 'Tinggi' : ($latestScore->total_score >= 40 ? 'Sedang' : 'Rendah')) : 'Sedang',
                'latitude' => $c->latitude,
                'longitude' => $c->longitude,
                'port_status' => $portStatus,
            ];
        });

        return view('compare.index', compact('countries'));
    }
}

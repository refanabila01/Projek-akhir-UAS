<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'risk_score' => 38,
            'high_risk' => 12,
            'medium_risk' => 25,
            'low_risk' => 63,
            'countries' => 100
        ];

        return view('dashboard.index',compact('data'));
    }

    public function charts()
    {
        return view('charts.index');
    }
}
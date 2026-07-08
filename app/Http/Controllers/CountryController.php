<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    public function index()
    {
        $response = Http::get(
            'https://restcountries.com/v3.1/all?fields=name,capital,region,population,flags,cca2'
        );

        if (!$response->successful()) {
            return view('countries.index', [
                'countries' => []
            ]);
        }

        $countries = collect($response->json())
            ->sortBy(fn ($country) => $country['name']['common'] ?? '')
            ->values();

        return view('countries.index', [
            'countries' => $countries
        ]);
    }
}
@extends('layouts.master')

@section('title', 'Detail Negara - ' . $country->name)

@section('content')
@php
    $score = $latestScore ? $latestScore->total_score : 40;
    $weatherScore = $latestScore ? $latestScore->weather_score : 40;
    $inflationScore = $latestScore ? $latestScore->inflation_score : 30;
    $currencyScore = $latestScore ? $latestScore->currency_score : 20;
    $newsScore = $latestScore ? $latestScore->news_score : 50;

    $riskLevel = $score >= 70 ? 'High Risk' : ($score >= 40 ? 'Medium Risk' : 'Low Risk');
    $badgeClass = $score >= 70 ? 'bg-danger text-white' : ($score >= 40 ? 'bg-warning text-dark' : 'bg-success text-white');
    $textColor = $score >= 70 ? 'text-danger' : ($score >= 40 ? 'text-warning' : 'text-success');

    // Weather params
    $temp = $weather['temperature'] ?? 25;
    $humidity = $weather['humidity'] ?? 60;
    $wind = $weather['wind_speed'] ?? 10;
    $rain = $weather['rain'] ?? 0;
    $desc = $weather['description'] ?? 'Cerah';

    // Format Exports & Imports dynamically
    $formatLargeNumber = function ($value, $fallbackPercent) use ($country) {
        if (!$value || $value == 0) {
            $value = $country->gdp * $fallbackPercent;
        }
        if ($value >= 1e12) {
            return '$' . number_format($value / 1e12, 2) . ' T';
        } elseif ($value >= 1e9) {
            return '$' . number_format($value / 1e9, 2) . ' M';
        } elseif ($value >= 1e6) {
            return '$' . number_format($value / 1e6, 2) . ' Jt';
        } else {
            return '$' . number_format($value, 0);
        }
    };
    $exportsDisplay = $formatLargeNumber($country->exports, 0.15);
    $importsDisplay = $formatLargeNumber($country->imports, 0.12);
@endphp

<div class="container-fluid">
    <!-- Header Bagian Atas -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <img src="https://flagcdn.com/w80/{{ strtolower($country->code) }}.png" alt="Bendera {{ $country->name }}" class="rounded shadow-sm border me-1" style="width: 50px; height: auto;">
            <div>
                <h1 class="fw-bold m-0 d-inline-block">{{ $country->name }}</h1>
                <div class="text-muted mt-1">
                    <span class="fw-semibold">{{ $country->code }}</span> • {{ $country->region }}
                    <button class="btn btn-sm btn-link text-warning p-0 ms-2" id="watchlistBtn" data-id="{{ $country->id }}">
                        <i class="fa-regular fa-star fs-5"></i>
                    </button>
                </div>
            </div>
        </div>
        <div>
            <span class="text-muted small me-3">Diperbarui: {{ now()->format('d M Y, H:i') }} WIB</span>
            <a href="{{ route('countries.show', ['id' => $country->id, 'refresh' => 1]) }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                <i class="fa-solid fa-arrows-rotate"></i> Refresh Data
            </a>
        </div>
    </div>

    <!-- Row 1: 5 Metrik Utama -->
    <div class="row mb-4">
        <!-- GDP Card -->
        <div class="col mb-3">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h6 class="text-muted fw-semibold small">GDP (Nominal)</h6>
                <h3 class="fw-bold mt-2">
                    @if($country->gdp >= 1e12)
                        ${{ number_format($country->gdp / 1e12, 2, ',', '.') }} Trillion
                    @else
                        ${{ number_format($country->gdp / 1e9, 2, ',', '.') }} Billion
                    @endif
                </h3>
                <span class="{{ $country->gdp_growth >= 0 ? 'text-success' : 'text-danger' }} small mt-1 fw-bold">
                    <i class="fa-solid {{ $country->gdp_growth >= 0 ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                    {{ $country->gdp_growth >= 0 ? '+' : '' }}{{ number_format($country->gdp_growth, 2) }}%
                </span>
            </div>
        </div>

        <!-- Inflation Card -->
        <div class="col mb-3">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h6 class="text-muted fw-semibold small">Inflation Rate</h6>
                <h3 class="fw-bold mt-2">{{ number_format($country->inflation, 2, ',', '.') }}%</h3>
                <span class="{{ $country->inflation <= 3.5 ? 'text-success' : 'text-danger' }} small mt-1 fw-bold">
                    {{ $country->inflation <= 3.5 ? 'Stabil' : 'Tinggi' }}
                </span>
            </div>
        </div>

        <!-- Population Card -->
        <div class="col mb-3">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h6 class="text-muted fw-semibold small">Population</h6>
                <h3 class="fw-bold mt-2">
                    @if($country->population >= 1e6)
                        {{ number_format($country->population / 1e6, 2, ',', '.') }} Million
                    @else
                        {{ number_format($country->population) }}
                    @endif
                </h3>
            </div>
        </div>

        <!-- Currency Card -->
        <div class="col mb-3">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h6 class="text-muted fw-semibold small">Currency</h6>
                <h3 class="fw-bold mt-2">{{ $country->currency_code }}</h3>
                <span class="text-muted small">{{ $country->currency_name ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Risk Score Card -->
        <div class="col mb-3">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h6 class="text-muted fw-semibold small">Risk Score <i class="fa-solid fa-circle-info text-muted cursor-pointer" title="Skor Risiko Gabungan"></i></h6>
                <h3 class="fw-bold mt-2 {{ $textColor }}">{{ $score }}</h3>
                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1 fs-7 d-inline-block mt-1">{{ $riskLevel }}</span>
            </div>
        </div>
    </div>

    <!-- Row 2: Weather, Economic Indicators, Risk Breakdown -->
    <div class="row mb-4">
        <!-- Weather Card -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Current Weather</h5>
                <div class="d-flex align-items-center justify-content-between my-3">
                    <div>
                        <h1 class="fw-bold text-dark display-4 mb-1">{{ $temp }}°C</h1>
                        <h6 class="fw-semibold text-secondary"><i class="fa-solid fa-cloud-sun"></i> {{ $desc }}</h6>
                    </div>
                    <div style="font-size: 80px;">
                        @if($rain > 0)
                            🌧️
                        @elseif($temp > 30)
                            ☀️
                        @else
                            ⛅
                        @endif
                    </div>
                </div>
                <div class="row text-center mt-4 border-top pt-3">
                    <div class="col-4 border-end">
                        <small class="text-muted d-block">Humidity</small>
                        <span class="fw-bold">{{ $humidity }}%</span>
                    </div>
                    <div class="col-4 border-end">
                        <small class="text-muted d-block">Wind</small>
                        <span class="fw-bold">{{ $wind }} km/h</span>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Precip</small>
                        <span class="fw-bold">{{ $rain }} mm</span>
                    </div>
                </div>
                <a href="{{ route('weather') }}" class="btn btn-link text-primary small text-center w-100 mt-4 d-block fw-semibold text-decoration-none">View Detail</a>
            </div>
        </div>

        <!-- Economic Indicators Table Card -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Economic Indicators</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Indicator</th>
                                <th>Value</th>
                                <th>Change</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr>
                                 <td>GDP Growth</td>
                                 <td>{{ number_format($country->gdp_growth, 2) }}%</td>
                                 <td class="{{ $country->gdp_growth >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                     {{ $country->gdp_growth >= 0 ? '+' : '' }}{{ number_format($country->gdp_growth, 2) }}%
                                 </td>
                             </tr>
                             <tr>
                                 <td>Inflation</td>
                                 <td>{{ number_format($country->inflation, 2) }}%</td>
                                 <td class="{{ $country->inflation <= 3.5 ? 'text-success' : 'text-danger' }} fw-bold">
                                     {{ $country->inflation <= 3.5 ? 'Stabil' : 'Tinggi' }}
                                 </td>
                             </tr>
                             <tr>
                                 <td>Unemployment</td>
                                 <td>{{ number_format($country->unemployment_rate, 2) }}%</td>
                                 <td class="{{ $country->unemployment_rate <= 5.0 ? 'text-success' : 'text-danger' }} fw-bold">
                                     {{ $country->unemployment_rate <= 5.0 ? 'Rendah' : 'Tinggi' }}
                                 </td>
                             </tr>
                             <tr>
                                 <td>Exports</td>
                                 <td>{{ $exportsDisplay }}</td>
                                 <td class="text-success fw-bold">Active</td>
                             </tr>
                             <tr>
                                 <td>Imports</td>
                                 <td>{{ $importsDisplay }}</td>
                                 <td class="text-success fw-bold">Active</td>
                             </tr>
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('countries') }}" class="btn btn-link text-primary small text-center w-100 mt-3 d-block fw-semibold text-decoration-none">View All</a>
            </div>
        </div>

        <!-- Risk Breakdown Chart Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Risk Breakdown</h5>
                <div class="d-flex align-items-center justify-content-between">
                    <div style="width: 170px; height: 170px; position: relative;">
                        <canvas id="riskBreakdownChart"></canvas>
                        <!-- Center Label of Doughnut Chart -->
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <h3 class="fw-bold m-0 {{ $textColor }}">{{ $score }}</h3>
                            <small class="text-muted fs-8 fw-semibold d-block">Low Risk</small>
                        </div>
                    </div>
                    <div class="small fw-semibold text-muted d-flex flex-column gap-2" style="max-width: 150px;">
                        <div><i class="fa-solid fa-circle" style="color: #10b981;"></i> Weather 30%</div>
                        <div><i class="fa-solid fa-circle" style="color: #3b82f6;"></i> Inflation 20%</div>
                        <div><i class="fa-solid fa-circle" style="color: #f59e0b;"></i> Exchange Rate 10%</div>
                        <div><i class="fa-solid fa-circle" style="color: #ef4444;"></i> News Sentiment 40%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Recent News -->
    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0">Recent News</h5>
            <a href="{{ route('news') }}" class="btn btn-link text-primary small fw-semibold text-decoration-none">View All News</a>
        </div>
        <div class="row">
            @foreach($news as $article)
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="card bg-light border-0 rounded-4 p-4 h-100 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-secondary mb-3 rounded-pill px-2.5 py-1">Sentimen: {{ $article->sentiment }}</span>
                            <h6 class="fw-bold text-dark text-truncate-2 mb-2" style="height: 48px; overflow: hidden;">{{ $article->title }}</h6>
                            <p class="small text-muted text-truncate-3" style="height: 60px; overflow: hidden;">{{ $article->content }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-2">
                            <span class="small text-primary fw-bold"><i class="fa-solid fa-newspaper"></i> {{ $article->source ?? 'GNews' }}</span>
                            <small class="text-muted">{{ is_string($article->published_at) ? $article->published_at : $article->published_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Risk Breakdown Doughnut Chart
    const ctx = document.getElementById('riskBreakdownChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Weather (30%)', 'Inflation (20%)', 'Exchange Rate (10%)', 'News Sentiment (40%)'],
                datasets: [{
                    data: [{{ $weatherScore }}, {{ $inflationScore }}, {{ $currencyScore }}, {{ $newsScore }}],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Watchlist Favorite toggle AJAX handler
    const watchlistBtn = document.getElementById('watchlistBtn');
    if (watchlistBtn) {
        // Cek status awal (kita bisa kueri DB atau mock. Karena user-watchlist disimpan, kita cek AJAX)
        watchlistBtn.addEventListener('click', function() {
            const countryId = this.getAttribute('data-id');
            const icon = this.querySelector('i');
            
            // Simulasikan atau kirim POST request ke /watchlist/toggle
            if (icon.classList.contains('fa-regular')) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                icon.style.color = '#ffc107';
                alert('Negara ditambahkan ke daftar pantau Anda!');
            } else {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                icon.style.color = '';
                alert('Negara dihapus dari daftar pantau Anda!');
            }
        });
    }
});
</script>
@endsection

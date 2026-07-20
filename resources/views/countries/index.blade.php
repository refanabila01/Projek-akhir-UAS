@extends('layouts.master')

@section('title', 'Dashboard Negara - ' . $selectedCountry->name)

@section('content')
@php
    $score = $latestScore ? round($latestScore->total_score, 1) : 40;
    $weatherScore = $latestScore ? $latestScore->weather_score : 40;
    $inflationScore = $latestScore ? $latestScore->inflation_score : 30;
    $currencyScore = $latestScore ? $latestScore->currency_score : 20;
    $newsScore = $latestScore ? $latestScore->news_score : 50;

    $riskLevel = $score >= 70 ? 'Risiko Tinggi' : ($score >= 40 ? 'Risiko Sedang' : 'Risiko Rendah');
    $badgeClass = $score >= 70 ? 'bg-danger text-white' : ($score >= 40 ? 'bg-warning text-dark' : 'bg-success text-white');
    $textColor = $score >= 70 ? 'text-danger' : ($score >= 40 ? 'text-warning' : 'text-success');
    $dotColor = $score >= 70 ? '#dc3545' : ($score >= 40 ? '#f59e0b' : '#198754');

    // Format GDP
    $gdpDisplay = '';
    if ($selectedCountry->gdp >= 1e12) {
        $gdpDisplay = '$' . number_format($selectedCountry->gdp / 1e12, 2) . ' Triliun';
    } elseif ($selectedCountry->gdp >= 1e9) {
        $gdpDisplay = '$' . number_format($selectedCountry->gdp / 1e9, 2) . ' Miliar';
    } else {
        $gdpDisplay = '$' . number_format($selectedCountry->gdp, 0);
    }

    // Format Population
    $popDisplay = '';
    if ($selectedCountry->population >= 1e9) {
        $popDisplay = number_format($selectedCountry->population / 1e9, 2) . ' Miliar';
    } elseif ($selectedCountry->population >= 1e6) {
        $popDisplay = number_format($selectedCountry->population / 1e6, 2) . ' Juta';
    } else {
        $popDisplay = number_format($selectedCountry->population);
    }

    // Weather params
    $temp = $weather['temperature'] ?? 25;
    $humidity = $weather['humidity'] ?? 60;
    $wind = $weather['wind_speed'] ?? 10;
    $rain = $weather['rain'] ?? 0;
    $desc = $weather['description'] ?? 'Cerah';

    // Translate desc to Indonesian
    $descMap = [
        'Clear' => 'Cerah', 'Sunny' => 'Cerah Terang', 'Cloudy' => 'Berawan',
        'Partly Cloudy' => 'Sebagian Berawan', 'Rain' => 'Hujan',
        'Heavy Rain' => 'Hujan Lebat', 'Thunderstorm' => 'Hujan Petir',
        'Drizzle' => 'Gerimis', 'Foggy' => 'Berkabut', 'Windy' => 'Berangin',
        'Cerah' => 'Cerah', 'Berawan' => 'Berawan', 'Hujan' => 'Hujan',
    ];
    $descID = $descMap[$desc] ?? $desc;

    // Translate region
    $regionMap = [
        'Asia' => 'Asia', 'Europe' => 'Eropa', 'Americas' => 'Amerika',
        'Africa' => 'Afrika', 'Oceania' => 'Oseania',
        'Antarctic' => 'Antartika', 'Other' => 'Lainnya',
    ];
    $regionID = $regionMap[$selectedCountry->region] ?? $selectedCountry->region;

    // Format Exports & Imports dynamically
    $formatLargeNumber = function ($value, $fallbackPercent) use ($selectedCountry) {
        if (!$value || $value == 0) {
            $value = $selectedCountry->gdp * $fallbackPercent;
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
    $exportsDisplay = $formatLargeNumber($selectedCountry->exports, 0.15);
    $importsDisplay = $formatLargeNumber($selectedCountry->imports, 0.12);
    $isFavorited = auth()->check() && \App\Models\Favorite::where('user_id', auth()->id())->where('country_id', $selectedCountry->id)->exists();
@endphp

<style>
    .metric-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        height: 100%;
    }
    .metric-card .label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 6px;
    }
    .metric-card .value {
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
        color: #1a1a2e;
        margin-bottom: 4px;
    }
    .metric-card .sub {
        font-size: 12px;
        font-weight: 600;
    }
    .info-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        height: 100%;
    }
    .info-card h5 {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 18px;
    }
    .weather-temp {
        font-size: 52px;
        font-weight: 800;
        color: #1a1a2e;
        line-height: 1;
    }
    .weather-desc {
        font-size: 15px;
        color: #6c757d;
        font-weight: 500;
    }
    .weather-stat-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 500;
        display: block;
        margin-bottom: 2px;
    }
    .weather-stat-val {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
    }
    .eco-table {
        table-layout: fixed;
        width: 100%;
    }
    .eco-table td, .eco-table th {
        padding: 8px 8px;
        font-size: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .eco-table td:first-child, .eco-table th:first-child {
        width: 50%;
        white-space: normal;
        word-break: break-word;
    }
    .eco-table td:nth-child(2), .eco-table th:nth-child(2) {
        width: 25%;
        white-space: nowrap;
    }
    .eco-table td:nth-child(3), .eco-table th:nth-child(3) {
        width: 25%;
        white-space: nowrap;
    }
    .eco-table thead th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #6c757d;
        background: #f8fafc;
    }
    .eco-table tbody tr:last-child td {
        border-bottom: none;
    }
    .eco-table tbody td:first-child {
        font-weight: 500;
        color: #374151;
    }
    .eco-table tbody td:nth-child(2) {
        font-weight: 600;
        color: #1a1a2e;
    }
    .news-card {
        background: #f8fafc;
        border: none;
        border-radius: 12px;
        padding: 18px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .news-card h6 {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1.5;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-card p {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.5;
        margin-bottom: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .sentiment-badge {
        font-size: 10px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 10px;
    }
    .country-selector-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        margin-bottom: 24px;
    }
    .view-link {
        font-size: 13px;
        font-weight: 600;
        color: #4361ee;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 14px;
    }
    .view-link:hover { color: #3a0ca3; text-decoration: underline; }
    .country-header-flag { font-size: 52px; line-height: 1; }
    .country-header-name { font-size: 28px; font-weight: 800; margin: 0; }
    .country-header-meta { font-size: 14px; color: #6c757d; margin-top: 4px; }
</style>

<div class="container-fluid">

    {{-- Selektor Negara --}}
    <div class="country-selector-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="flex-grow-1" style="max-width: 500px;">
                <label class="form-label fw-bold small mb-1">🌍 Pilih Negara yang Ingin Dipantau</label>
                <form action="{{ route('countries') }}" method="GET" id="countrySelectorForm">
                    <select name="country_id" class="form-select form-select-sm rounded-3"
                            onchange="document.getElementById('countrySelectorForm').submit();">
                        @foreach($allCountries as $c)
                            <option value="{{ $c->id }}" {{ $c->id == $selectedCountry->id ? 'selected' : '' }}>
                                {{ $c->flag }} {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="text-end">
                <span class="text-muted" style="font-size:12px;">
                    Diperbarui: {{ now()->format('d M Y, H:i') }} WIB
                </span>
            </div>
        </div>
    </div>

    {{-- Header Negara Terpilih --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="d-flex align-items-center gap-3">
            <img src="https://flagcdn.com/w80/{{ strtolower($selectedCountry->code) }}.png" alt="Bendera {{ $selectedCountry->name }}" class="rounded shadow-sm border" style="width: 50px; height: auto;">
            <div>
                <h1 class="country-header-name">{{ $selectedCountry->name }}</h1>
                <div class="country-header-meta">
                    <span class="fw-semibold text-dark me-1">{{ $selectedCountry->code }}</span>
                    <span class="text-muted">•</span>
                    <span class="ms-1">{{ $regionID }}</span>
                    @if($selectedCountry->capital)
                        <span class="text-muted mx-1">•</span>
                        <span class="ms-1"><i class="fa-solid fa-location-dot text-danger" style="font-size:11px;"></i> {{ $selectedCountry->capital }}</span>
                    @endif
                    <button class="btn btn-sm btn-link text-warning p-0 ms-2 align-middle" id="watchlistBtn"
                            data-id="{{ $selectedCountry->id }}" style="font-size:18px; line-height:1;">
                        <i class="{{ $isFavorited ? 'fa-solid fa-star' : 'fa-regular fa-star' }}" style="{{ $isFavorited ? 'color: #ffc107;' : '' }}"></i>
                    </button>
                </div>
            </div>
        </div>
        <a href="{{ route('countries', ['country_id' => $selectedCountry->id, 'refresh' => 1]) }}" class="btn btn-outline-primary rounded-pill px-4 py-2 d-flex align-items-center gap-2">
            <i class="fa-solid fa-arrows-rotate"></i> Segarkan Data
        </a>
    </div>

    {{-- ============================================================ --}}
    {{-- BARIS 1 : 5 Kartu Metrik Utama --}}
    {{-- ============================================================ --}}
    <div class="row g-3 mb-4">

        {{-- GDP Nominal --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="metric-card">
                <div class="label">PDB (Nominal)</div>
                <div class="value">{{ $gdpDisplay }}</div>
                <div class="sub {{ $selectedCountry->gdp_growth >= 0 ? 'text-success' : 'text-danger' }}">
                    <i class="fa-solid {{ $selectedCountry->gdp_growth >= 0 ? 'fa-caret-up' : 'fa-caret-down' }}"></i>
                    {{ $selectedCountry->gdp_growth >= 0 ? '+' : '' }}{{ number_format($selectedCountry->gdp_growth, 2) }}%
                </div>
            </div>
        </div>

        {{-- Tingkat Inflasi --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="metric-card">
                <div class="label">Tingkat Inflasi</div>
                <div class="value">{{ number_format($selectedCountry->inflation, 2) }}%</div>
                <div class="sub {{ $selectedCountry->inflation <= 3.5 ? 'text-success' : 'text-danger' }}">
                    {{ $selectedCountry->inflation <= 3.5 ? 'Stabil' : 'Tinggi' }}
                </div>
            </div>
        </div>

        {{-- Populasi --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="metric-card">
                <div class="label">Populasi</div>
                <div class="value">{{ $popDisplay }}</div>
                <div class="sub text-muted">Jiwa</div>
            </div>
        </div>

        {{-- Mata Uang --}}
        <div class="col-6 col-md-4 col-xl">
            <div class="metric-card">
                <div class="label">Mata Uang</div>
                <div class="value">{{ $selectedCountry->currency_code }}</div>
                <div class="sub text-muted">{{ Str::limit($selectedCountry->currency_name ?? 'N/A', 20) }}</div>
            </div>
        </div>

        {{-- Skor Risiko --}}
        <div class="col-12 col-md-4 col-xl">
            <div class="metric-card">
                <div class="label">
                    Skor Risiko
                    <i class="fa-solid fa-circle-info text-muted ms-1" style="font-size:10px;"
                       title="Skor Risiko Gabungan Supply Chain"></i>
                </div>
                <div class="value {{ $textColor }}">{{ $score }}</div>
                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1" style="font-size:11px;">
                    {{ $riskLevel }}
                </span>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BARIS 2 : Cuaca | Indikator Ekonomi | Analisis Risiko --}}
    {{-- ============================================================ --}}
    <div class="row g-3 mb-4">

        {{-- Cuaca Saat Ini --}}
        <div class="col-12 col-lg-4">
            <div class="info-card">
                <h5>🌤 Cuaca Saat Ini</h5>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <div class="weather-temp">{{ $temp }}°C</div>
                        <div class="weather-desc mt-2">
                            @if($rain > 0) 🌧️
                            @elseif($temp > 30) ☀️
                            @else ⛅
                            @endif
                            {{ $descID }}
                        </div>
                    </div>
                    <div style="font-size:70px; line-height:1; opacity:0.8;">
                        @if($rain > 0) 🌧️
                        @elseif($temp > 30) ☀️
                        @else ⛅
                        @endif
                    </div>
                </div>

                <div class="row text-center border-top pt-3">
                    <div class="col-4">
                        <span class="weather-stat-label">Kelembapan</span>
                        <span class="weather-stat-val">{{ $humidity }}%</span>
                    </div>
                    <div class="col-4">
                        <span class="weather-stat-label">Kecepatan Angin</span>
                        <span class="weather-stat-val">{{ $wind }} km/j</span>
                    </div>
                    <div class="col-4">
                        <span class="weather-stat-label">Curah Hujan</span>
                        <span class="weather-stat-val">{{ $rain }} mm</span>
                    </div>
                </div>

                <a href="{{ route('weather') }}" class="view-link">Lihat Detail Cuaca →</a>
            </div>
        </div>

        {{-- Indikator Ekonomi --}}
        <div class="col-12 col-lg-4">
            <div class="info-card">
                <h5>📊 Indikator Ekonomi</h5>
                <table class="table eco-table mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Indikator</th>
                            <th>Nilai</th>
                            <th>Perubahan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pertumbuhan PDB</td>
                            <td>{{ number_format($selectedCountry->gdp_growth, 2) }}%</td>
                            <td class="{{ $selectedCountry->gdp_growth >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                {{ $selectedCountry->gdp_growth >= 0 ? '+' : '' }}{{ number_format($selectedCountry->gdp_growth, 2) }}%
                            </td>
                        </tr>
                        <tr>
                            <td>Inflasi</td>
                            <td>{{ number_format($selectedCountry->inflation, 2) }}%</td>
                            <td class="{{ $selectedCountry->inflation <= 3.5 ? 'text-success' : 'text-danger' }} fw-bold">
                                {{ $selectedCountry->inflation <= 3.5 ? 'Stabil' : 'Tinggi' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Pengangguran</td>
                            <td>{{ number_format($selectedCountry->unemployment_rate, 2) }}%</td>
                            <td class="{{ $selectedCountry->unemployment_rate <= 5.0 ? 'text-success' : 'text-danger' }} fw-bold">
                                {{ $selectedCountry->unemployment_rate <= 5.0 ? 'Rendah' : 'Tinggi' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Ekspor</td>
                            <td>{{ $exportsDisplay }}</td>
                            <td class="text-success fw-bold">Aktif</td>
                        </tr>
                        <tr>
                            <td>Impor</td>
                            <td>{{ $importsDisplay }}</td>
                            <td class="text-success fw-bold">Aktif</td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('charts') }}" class="view-link">Lihat Semua →</a>
            </div>
        </div>

        {{-- Analisis Risiko --}}
        <div class="col-12 col-lg-4">
            <div class="info-card">
                <h5>⚠ Analisis Risiko</h5>
                <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">

                    {{-- Donut Chart --}}
                    <div style="position:relative; width:160px; height:160px; flex-shrink:0;">
                        <canvas id="riskBreakdownChart" width="160" height="160"></canvas>
                        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center; pointer-events:none;">
                            <div class="fw-bold {{ $textColor }}" style="font-size:22px; line-height:1;">{{ $score }}</div>
                            <div style="font-size:10px; color:#6c757d; font-weight:600; margin-top:2px;">{{ $riskLevel }}</div>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div style="font-size:12px; font-weight:600; flex-shrink:0;">
                        <div class="mb-2">
                            <i class="fa-solid fa-circle me-1" style="color:#198754; font-size:10px;"></i>
                            Cuaca <span class="text-muted fw-normal">(30%)</span>
                        </div>
                        <div class="mb-2">
                            <i class="fa-solid fa-circle me-1" style="color:#0d6efd; font-size:10px;"></i>
                            Inflasi <span class="text-muted fw-normal">(20%)</span>
                        </div>
                        <div class="mb-2">
                            <i class="fa-solid fa-circle me-1" style="color:#f59e0b; font-size:10px;"></i>
                            Kurs <span class="text-muted fw-normal">(10%)</span>
                        </div>
                        <div>
                            <i class="fa-solid fa-circle me-1" style="color:#dc3545; font-size:10px;"></i>
                            Sentimen Berita <span class="text-muted fw-normal">(40%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BARIS 3 : Berita Terbaru --}}
    {{-- ============================================================ --}}
    <div class="info-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">📰 Berita Terbaru</h5>
            <a href="{{ route('news') }}" class="text-primary fw-semibold" style="font-size:13px; text-decoration:none;">
                Lihat Semua Berita →
            </a>
        </div>
        <div class="row g-3">
            @foreach($news as $article)
                <div class="col-12 col-md-4">
                    <div class="news-card">
                        <div>
                            @php
                                $sentimen = $article->sentiment ?? 'Netral';
                                $sentimenID = ['Positive' => 'Positif', 'Negative' => 'Negatif', 'Neutral' => 'Netral'][$sentimen] ?? $sentimen;
                                $sBadge = $sentimen === 'Positive' ? 'bg-success text-white' :
                                          ($sentimen === 'Negative' ? 'bg-danger text-white' : 'bg-secondary text-white');
                            @endphp
                            <span class="sentiment-badge {{ $sBadge }}">Sentimen: {{ $sentimenID }}</span>
                            <h6>{{ $article->title }}</h6>
                            <p>{{ $article->content }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top"
                             style="font-size:11px;">
                            <span class="text-primary fw-semibold">
                                <i class="fa-solid fa-newspaper me-1"></i>{{ $article->source ?? 'Sumber Berita' }}
                            </span>
                            <span class="text-muted">
                                {{ is_string($article->published_at)
                                    ? $article->published_at
                                    : $article->published_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

            @if(count($news) === 0)
                <div class="col-12">
                    <div class="text-center text-muted py-4">
                        <i class="fa-solid fa-newspaper fa-2x mb-2 d-block"></i>
                        Belum ada berita yang tersedia untuk negara ini.
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Donut Chart Analisis Risiko
    const ctx = document.getElementById('riskBreakdownChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Cuaca (30%)', 'Inflasi (20%)', 'Kurs (10%)', 'Sentimen Berita (40%)'],
                datasets: [{
                    data: [
                        {{ $weatherScore }},
                        {{ $inflationScore }},
                        {{ $currencyScore }},
                        {{ $newsScore }}
                    ],
                    backgroundColor: ['#198754', '#0d6efd', '#f59e0b', '#dc3545'],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ' ' + ctx.label + ': ' + ctx.raw;
                            }
                        }
                    }
                }
            }
        });
    }

    // Tombol Daftar Pantau
    const watchlistBtn = document.getElementById('watchlistBtn');
    if (watchlistBtn) {
        watchlistBtn.addEventListener('click', function() {
            const countryId = this.getAttribute('data-id');
            const icon = this.querySelector('i');
            
            fetch('/watchlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ country_id: countryId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'added') {
                    icon.className = 'fa-solid fa-star';
                    icon.style.color = '#ffc107';
                } else if (data.status === 'removed') {
                    icon.className = 'fa-regular fa-star';
                    icon.style.color = '';
                }
            })
            .catch(err => {
                console.error("Gagal mengubah daftar pantau:", err);
                alert("Gagal mengubah status daftar pantau.");
            });
        });
    }
});
</script>
@endsection
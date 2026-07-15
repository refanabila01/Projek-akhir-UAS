@extends('layouts.master')

@section('title', 'Monitoring Cuaca Global')

@section('content')
@php
    $allTemps  = collect($weatherData)->pluck('temp');
    $avgTemp   = $allTemps->count() > 0 ? round($allTemps->avg(), 1) : 25;
    $rainCount = collect($weatherData)->filter(fn($d) => $d['rain'] > 0.3)->count();
    $coldCount = collect($weatherData)->filter(fn($d) => $d['temp'] <= 5)->count();
    $hotCount  = collect($weatherData)->filter(fn($d) => $d['temp'] >= 30)->count();
    $total     = count($weatherData);
@endphp

<style>
    .stat-card {
        background:#fff; border:none; border-radius:16px;
        padding:20px 22px; box-shadow:0 2px 10px rgba(0,0,0,.07);
    }
    .stat-card .label {
        font-size:11px; font-weight:700; text-transform:uppercase;
        letter-spacing:.5px; color:#6c757d; margin-bottom:6px;
    }
    .stat-card .value { font-size:28px; font-weight:800; line-height:1; }



    .search-wrap { position:relative; }
    .search-wrap .fa-magnifying-glass {
        position:absolute; left:14px; top:50%;
        transform:translateY(-50%); color:#9ca3af; font-size:14px;
    }
    #searchCountry {
        padding-left:38px; border-radius:12px;
        border:2px solid #e5e7eb; font-size:13px; font-weight:500;
        transition:border-color .2s, box-shadow .2s;
    }
    #searchCountry:focus {
        border-color:#4361ee; box-shadow:0 0 0 3px rgba(67,97,238,.15); outline:none;
    }

    .wcard {
        background:#fff; border:none; border-radius:14px;
        padding:14px 16px; box-shadow:0 2px 8px rgba(0,0,0,.06);
        height:100%; transition:transform .2s, box-shadow .2s;
    }
    .wcard:hover { transform:translateY(-4px); box-shadow:0 8px 24px rgba(0,0,0,.12); }
    .wcard .flag { font-size:22px; }
    .wcard .cname {
        font-size:13px; font-weight:700; color:#1a1a2e;
        margin:4px 0 1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .wcard .cap {
        font-size:10px; color:#9ca3af;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .wcard .temp-val { font-size:26px; font-weight:800; color:#1a1a2e; line-height:1; }
    .wcard .desc-val { font-size:10px; color:#6c757d; font-weight:500; }
    .wcard .stat-row {
        font-size:10px; color:#6c757d;
        margin-top:8px; border-top:1px solid #f1f5f9; padding-top:8px;
    }
    .wcard .stat-row b { color:#374151; font-size:11px; }

    .live-dot {
        width:6px; height:6px; background:#22c55e;
        border-radius:50%; display:inline-block;
        animation:pulse 1.5s infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }

    .legend-item { display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:600; }
    #noResult { display:none; }
</style>

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">🌦 Monitoring Cuaca Global</h2>
            <p class="text-muted small mb-0">
                Menampilkan <b>{{ $total }}</b> negara dunia •
                Data diperbarui: {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
        <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Segarkan
        </button>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="label">🌡 Suhu Rata-rata</div>
                <div class="value text-primary">{{ $avgTemp }}°C</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="label">☀️ Negara Panas (≥30°C)</div>
                <div class="value text-danger">{{ $hotCount }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="label">🌧 Negara Berhujan</div>
                <div class="value text-info">{{ $rainCount }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="label">❄️ Negara Sangat Dingin</div>
                <div class="value text-secondary">{{ $coldCount }}</div>
            </div>
        </div>
    </div>



    {{-- Search Bar + Counter --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <div class="search-wrap" style="max-width:420px; width:100%;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchCountry" class="form-control form-control-sm"
                   placeholder="Cari negara, ibukota, atau kondisi cuaca...">
        </div>
        <div class="text-muted small">
            Menampilkan <b id="visibleCount">{{ $total }}</b> dari {{ $total }} negara
        </div>
    </div>

    {{-- Country Cards Grid --}}
    <div class="row g-2" id="weatherGrid">
        @foreach($weatherData as $data)
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 wcard-wrapper"
                 data-name="{{ strtolower($data['name']) }}"
                 data-capital="{{ strtolower($data['capital']) }}"
                 data-desc="{{ strtolower($data['desc']) }}"
                 data-code="{{ strtolower($data['code']) }}"
                 id="card-{{ $data['code'] }}">
                <div class="wcard">
                    <div class="d-flex justify-content-between align-items-start">
                        <img src="https://flagcdn.com/w40/{{ strtolower($data['code']) }}.png" alt="Bendera {{ $data['name'] }}" class="rounded shadow-xs border" style="width: 24px; height: auto;">
                        <span class="weather-icon" style="font-size:20px;">{{ $data['icon'] }}</span>
                    </div>

                    <div class="cname mt-2" title="{{ $data['name'] }}">{{ $data['name'] }}</div>
                    @if($data['capital'])
                        <div class="cap">
                            <i class="fa-solid fa-location-dot" style="font-size:8px;"></i>
                            {{ $data['capital'] }}
                        </div>
                    @endif

                    <div class="d-flex align-items-baseline gap-2 mt-2">
                        <div class="temp-val country-temp">{{ $data['temp'] }}°</div>
                        <div class="desc-val country-desc">{{ $data['desc'] }}</div>
                    </div>

                    <div class="mt-1">
                        @if($data['risk_level'] === 'Tinggi')
                            <span class="badge bg-danger rounded-pill px-2" style="font-size:9px;">Risiko Tinggi</span>
                        @elseif($data['risk_level'] === 'Sedang')
                            <span class="badge bg-warning text-dark rounded-pill px-2" style="font-size:9px;">Risiko Sedang</span>
                        @else
                            <span class="badge bg-success rounded-pill px-2" style="font-size:9px;">Risiko Rendah</span>
                        @endif
                    </div>

                    <div class="stat-row">
                        <div class="d-flex justify-content-between">
                            <div>💧 <b>{{ $data['humidity'] }}%</b></div>
                            <div>💨 <b>{{ $data['wind'] }}</b><small>km/j</small></div>
                            <div>🌧 <b>{{ $data['rain'] }}</b><small>mm</small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- No Result --}}
    <div class="text-center py-5 text-muted" id="noResult">
        <i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block"></i>
        <p>Tidak ada negara yang cocok dengan pencarian Anda.</p>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ===========================================
    // LIVE WEATHER FETCH (client-side, all countries in batches)
    // ===========================================
    const allCardData = @json($weatherData);

    function getWeatherDesc(temp, rain, wind) {
        if (temp <= 0) return { desc: 'Salju', icon: '❄️' };
        if (rain > 5 || wind > 40) return { desc: 'Badai', icon: '⛈️' };
        if (rain > 0.3) return { desc: 'Hujan Ringan', icon: '🌧️' };
        if (wind > 25) return { desc: 'Berangin', icon: '🌬️' };
        if (temp > 30) return { desc: 'Cerah', icon: '☀️' };
        return { desc: 'Berawan', icon: '⛅' };
    }

    const validCountries = allCardData.filter(item => item.latitude !== null && item.longitude !== null);
    
    // Chunk size: 50 countries per request to keep URL length safe
    const chunkSize = 50;
    
    for (let i = 0; i < validCountries.length; i += chunkSize) {
        const chunk = validCountries.slice(i, i + chunkSize);
        const lats = chunk.map(c => c.latitude).join(',');
        const lons = chunk.map(c => c.longitude).join(',');

        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lats}&longitude=${lons}&current=temperature_2m,relative_humidity_2m,rain,wind_speed_10m`)
            .then(r => r.json())
            .then(responseArray => {
                const results = Array.isArray(responseArray) ? responseArray : [responseArray];
                
                results.forEach((data, index) => {
                    const country = chunk[index];
                    if (!country) return;

                    const cur = data.current || {};
                    const temp = Math.round(cur.temperature_2m * 10) / 10;
                    const rain = Math.round((cur.rain || 0) * 10) / 10;
                    const wind = Math.round((cur.wind_speed_10m || 0) * 10) / 10;
                    const { desc, icon } = getWeatherDesc(temp, rain, wind);

                    const card = document.getElementById(`card-${country.code}`);
                    if (card) {
                        const tempEl = card.querySelector('.country-temp');
                        const descEl = card.querySelector('.country-desc');
                        const iconEl = card.querySelector('.weather-icon');
                        if (tempEl) tempEl.textContent = temp + '°';
                        if (descEl) descEl.textContent = desc;
                        if (iconEl) iconEl.textContent = icon;
                    }
                });
            })
            .catch(err => {
                console.error("Gagal memuat data cuaca batch:", err);
            });
    }

    // ===========================================
    // 3. SEARCH FILTER — all 250 countries
    // ===========================================
    const searchInput = document.getElementById('searchCountry');
    const cards = document.querySelectorAll('.wcard-wrapper');
    const visibleCount = document.getElementById('visibleCount');
    const noResult = document.getElementById('noResult');
    const totalCards = {{ $total }};

    searchInput.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        let shown = 0;

        cards.forEach(card => {
            const match = !q
                || (card.dataset.name || '').includes(q)
                || (card.dataset.capital || '').includes(q)
                || (card.dataset.desc || '').includes(q)
                || (card.dataset.code || '').includes(q);

            card.style.display = match ? '' : 'none';
            if (match) shown++;
        });

        visibleCount.textContent = shown;
        noResult.style.display = shown === 0 ? 'block' : 'none';
    });
});
</script>
@endsection
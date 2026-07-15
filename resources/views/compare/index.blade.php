@extends('layouts.master')

@section('title', 'Perbandingan Negara')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">⚖ Perbandingan Parameter Rantai Pasok Negara</h2>
            <p class="text-muted small mb-0">Analisis komparatif risiko, ekonomi, cuaca, dan kelancaran port antar dua negara secara berdampingan</p>
        </div>
        <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Reset Pilihan
        </button>
    </div>

    <!-- Selektor Negara -->
    <div class="card shadow border-0 rounded-4 p-4 mb-4">
        <h5 class="fw-bold mb-3 text-dark">Pilih 2 Negara untuk Dibandingkan</h5>
        <div class="row align-items-center">
            <div class="col-md-5 mb-3 mb-md-0">
                <label class="form-label fw-bold small text-muted">Negara A</label>
                <select id="selectCountryA" class="form-select rounded-3">
                    @foreach($countries as $index => $c)
                        <option value="{{ $c['id'] }}" {{ $loop->first ? 'selected' : '' }}>
                            {{ $c['flag'] }} {{ $c['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2 text-center mb-3 mb-md-0 d-flex flex-column align-items-center justify-content-center">
                <div class="fw-bold text-muted" style="font-size: 18px; letter-spacing: 1px; margin-top: 24px;">VS</div>
            </div>

            <div class="col-md-5">
                <label class="form-label fw-bold small text-muted">Negara B</label>
                <select id="selectCountryB" class="form-select rounded-3">
                    @foreach($countries as $index => $c)
                        <option value="{{ $c['id'] }}" {{ $loop->remaining === 0 ? 'selected' : '' }}>
                            {{ $c['flag'] }} {{ $c['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Tabel Perbandingan Komparatif -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border-0 rounded-4 p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark">📋 Tabel Perbandingan Parameter</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%; font-size: 11px; text-transform: uppercase;" class="text-start ps-4">Parameter</th>
                                <th style="width: 35%; font-size: 14px; font-weight: 700;" id="headerCountryA" class="text-primary">Negara A</th>
                                <th style="width: 35%; font-size: 14px; font-weight: 700;" id="headerCountryB" class="text-success">Negara B</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-start ps-4 fw-semibold text-muted">Nama Negara</td>
                                <td id="nameCountryA" class="fw-bold text-primary">--</td>
                                <td id="nameCountryB" class="fw-bold text-success">--</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 fw-semibold text-muted">PDB (Nominal)</td>
                                <td id="gdpCountryA" class="fw-bold">--</td>
                                <td id="gdpCountryB" class="fw-bold">--</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 fw-semibold text-muted">Skor Risiko Akhir</td>
                                <td><span id="scoreCountryA" class="badge px-4 py-2 rounded-pill">--</span></td>
                                <td><span id="scoreCountryB" class="badge px-4 py-2 rounded-pill">--</span></td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 fw-semibold text-muted">Kondisi Cuaca Saat Ini</td>
                                <td id="weatherCountryA" class="fw-semibold">--</td>
                                <td id="weatherCountryB" class="fw-semibold">--</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 fw-semibold text-muted">Tingkat Inflasi</td>
                                <td id="inflationCountryA" class="fw-bold">--</td>
                                <td id="inflationCountryB" class="fw-bold">--</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 fw-semibold text-muted">Mata Uang (Valuta)</td>
                                <td id="currencyCountryA" class="fw-semibold">--</td>
                                <td id="currencyCountryB" class="fw-semibold">--</td>
                            </tr>
                            <tr>
                                <td class="text-start ps-4 fw-semibold text-muted">Kepadatan Pelabuhan Utama</td>
                                <td><span id="portCountryA" class="badge px-4 py-2 rounded-pill">--</span></td>
                                <td><span id="portCountryB" class="badge px-4 py-2 rounded-pill">--</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kesimpulan Analisis Dinamis -->
    <div class="card shadow border-0 rounded-4 p-4">
        <h5 class="fw-bold mb-3 text-dark">📋 Kesimpulan & Analisis Otomatis</h5>
        <div id="analysisResult" class="mb-0 text-muted lh-lg" style="font-size: 14px;">
            Memproses kesimpulan komparasi kedua negara...
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const countries = @json($countries);
    const selectA = document.getElementById('selectCountryA');
    const selectB = document.getElementById('selectCountryB');

    // Format GDP ke nominal Indonesia (Triliun, Miliar, Juta)
    function formatGDP(gdp) {
        if (!gdp) return '$0';
        if (gdp >= 1e12) {
            return '$' + (gdp / 1e12).toFixed(2) + ' Triliun';
        } else if (gdp >= 1e9) {
            return '$' + (gdp / 1e9).toFixed(2) + ' Miliar';
        } else if (gdp >= 1e6) {
            return '$' + (gdp / 1e6).toFixed(2) + ' Juta';
        } else {
            return '$' + gdp.toLocaleString('id-ID');
        }
    }

    function updateComparison() {
        const idA = selectA.value;
        const idB = selectB.value;

        const dataA = countries.find(c => c.id == idA);
        const dataB = countries.find(c => c.id == idB);

        if (!dataA || !dataB) return;

        // Update Headers
        document.getElementById('headerCountryA').innerText = `${dataA.flag} ${dataA.name}`;
        document.getElementById('headerCountryB').innerText = `${dataB.flag} ${dataB.name}`;

        // Update Names
        document.getElementById('nameCountryA').innerText = `${dataA.flag} ${dataA.name}`;
        document.getElementById('nameCountryB').innerText = `${dataB.flag} ${dataB.name}`;

        // Update GDP
        document.getElementById('gdpCountryA').innerText = formatGDP(dataA.gdp);
        document.getElementById('gdpCountryB').innerText = formatGDP(dataB.gdp);

        // Update Risk Score Badges
        const scoreAEl = document.getElementById('scoreCountryA');
        scoreAEl.innerText = `${dataA.risk_score} (${dataA.risk_level})`;
        scoreAEl.className = `badge px-4 py-2 rounded-pill ${dataA.risk_level === 'Tinggi' ? 'bg-danger' : (dataA.risk_level === 'Sedang' ? 'bg-warning text-dark' : 'bg-success')}`;

        const scoreBEl = document.getElementById('scoreCountryB');
        scoreBEl.innerText = `${dataB.risk_score} (${dataB.risk_level})`;
        scoreBEl.className = `badge px-4 py-2 rounded-pill ${dataB.risk_level === 'Tinggi' ? 'bg-danger' : (dataB.risk_level === 'Sedang' ? 'bg-warning text-dark' : 'bg-success')}`;

        // Update Inflation
        document.getElementById('inflationCountryA').innerText = `${dataA.inflation}%`;
        document.getElementById('inflationCountryB').innerText = `${dataB.inflation}%`;

        // Update Currency
        document.getElementById('currencyCountryA').innerText = dataA.currency_code;
        document.getElementById('currencyCountryB').innerText = dataB.currency_code;

        // Translate Port Status & Badges
        const portLabels = {
            'High': 'Kepadatan Tinggi',
            'Medium': 'Kepadatan Sedang',
            'Low': 'Kepadatan Rendah'
        };

        const portAEl = document.getElementById('portCountryA');
        const statusA = dataA.port_status || 'Low';
        portAEl.innerText = portLabels[statusA] || statusA;
        portAEl.className = `badge px-4 py-2 rounded-pill ${statusA === 'High' ? 'bg-danger' : (statusA === 'Medium' ? 'bg-warning text-dark' : 'bg-success')}`;

        const portBEl = document.getElementById('portCountryB');
        const statusB = dataB.port_status || 'Low';
        portBEl.innerText = portLabels[statusB] || statusB;
        portBEl.className = `badge px-4 py-2 rounded-pill ${statusB === 'High' ? 'bg-danger' : (statusB === 'Medium' ? 'bg-warning text-dark' : 'bg-success')}`;

        // Fetch weather dynamically client-side
        document.getElementById('weatherCountryA').innerText = 'Memuat cuaca...';
        document.getElementById('weatherCountryB').innerText = 'Memuat cuaca...';

        let weatherDescA = 'Cerah';
        let weatherDescB = 'Cerah';

        Promise.all([
            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${dataA.latitude}&longitude=${dataA.longitude}&current=temperature_2m,relative_humidity_2m,rain,wind_speed_10m`)
                .then(res => res.json())
                .then(wData => {
                    const cur = wData.current || {};
                    const temp = cur.temperature_2m || 25;
                    const rain = cur.rain || 0;
                    const wind = cur.wind_speed_10m || 0;
                    
                    if (rain > 5) weatherDescA = 'Hujan Lebat';
                    else if (rain > 0.3) weatherDescA = 'Hujan Ringan';
                    else if (wind > 25) weatherDescA = 'Berangin / Badai';
                    else weatherDescA = 'Cerah Berawan';

                    document.getElementById('weatherCountryA').innerText = `${weatherDescA} (${temp}°C)`;
                }).catch(() => {
                    weatherDescA = 'Cerah Berawan';
                    document.getElementById('weatherCountryA').innerText = 'Cerah Berawan';
                }),

            fetch(`https://api.open-meteo.com/v1/forecast?latitude=${dataB.latitude}&longitude=${dataB.longitude}&current=temperature_2m,relative_humidity_2m,rain,wind_speed_10m`)
                .then(res => res.json())
                .then(wData => {
                    const cur = wData.current || {};
                    const temp = cur.temperature_2m || 25;
                    const rain = cur.rain || 0;
                    const wind = cur.wind_speed_10m || 0;
                    
                    if (rain > 5) weatherDescB = 'Hujan Lebat';
                    else if (rain > 0.3) weatherDescB = 'Hujan Ringan';
                    else if (wind > 25) weatherDescB = 'Berangin / Badai';
                    else weatherDescB = 'Cerah Berawan';

                    document.getElementById('weatherCountryB').innerText = `${weatherDescB} (${temp}°C)`;
                }).catch(() => {
                    weatherDescB = 'Cerah Berawan';
                    document.getElementById('weatherCountryB').innerText = 'Cerah Berawan';
                })
        ]).then(() => {
            // Update Conclusion Text in Bahasa Indonesia
            let conclusion = '';
            if (dataA.risk_score > dataB.risk_score) {
                conclusion = `Negara <b>${dataA.name}</b> memiliki tingkat risiko supply chain yang <b>lebih tinggi</b> (${dataA.risk_score} vs ${dataB.risk_score}) dibandingkan dengan <b>${dataB.name}</b>. `;
            } else if (dataA.risk_score < dataB.risk_score) {
                conclusion = `Negara <b>${dataB.name}</b> memiliki tingkat risiko supply chain yang <b>lebih tinggi</b> (${dataB.risk_score} vs ${dataA.risk_score}) dibandingkan dengan <b>${dataA.name}</b>. `;
            } else {
                conclusion = `Kedua negara memiliki tingkat risiko supply chain yang <b>setara</b> (${dataA.risk_score}). `;
            }

            const highestRisk = dataA.risk_score > dataB.risk_score ? dataA : dataB;
            const highestWeather = dataA.risk_score > dataB.risk_score ? weatherDescA : weatherDescB;
            conclusion += `Penyebab utama tingkat kerawanan pada <b>${highestRisk.name}</b> dipengaruhi oleh faktor cuaca (status saat ini: ${highestWeather}) serta kondisi ekonomi domestik (tingkat inflasi ${highestRisk.inflation}%).`;

            document.getElementById('analysisResult').innerHTML = conclusion;
        });
    }

    selectA.addEventListener('change', updateComparison);
    selectB.addEventListener('change', updateComparison);

    // Initial load
    updateComparison();
});
</script>
@endsection
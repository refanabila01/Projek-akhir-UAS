@extends('layouts.master')

@section('title', 'Dashboard Utama')

@section('content')
<style>
    /* Modern Custom Alerts styling */
    .custom-alert-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 14px;
        background: #f8fafc;
        border-radius: 12px;
        border-left: 4px solid #f59e0b;
        transition: transform 0.2s, background 0.2s;
    }
    .custom-alert-item:hover {
        transform: translateX(4px);
        background: #f1f5f9;
    }
    .custom-alert-item.alert-danger-custom {
        border-left-color: #ef4444;
    }
    .custom-alert-item.alert-success-custom {
        border-left-color: #10b981;
        background: #f0fdf4;
    }
    .custom-alert-icon {
        font-size: 14px;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .custom-alert-content {
        font-size: 11px;
        line-height: 1.4;
    }
    .custom-alert-title {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 2px;
    }
    .custom-alert-desc {
        color: #64748b;
        font-weight: 500;
    }
    
    /* Scrollbar styling for alerts container */
    .custom-scroll-container::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scroll-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scroll-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
<div class="container-fluid">
    <!-- Header Banner -->
    <div class="card border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: white; box-shadow: 0 10px 30px rgba(49, 46, 129, 0.25);">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center g-4">
                <!-- Title Block -->
                <div class="col-lg-7">
                    <span class="badge bg-primary bg-opacity-25 text-info rounded-pill px-3 py-2 mb-3 border border-info border-opacity-25" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                        PLATFORM MONITORING RISIKO GLOBAL
                    </span>
                    <h1 class="fw-extrabold mb-3" style="font-size: 2.2rem; line-height: 1.25; font-family: 'Poppins', sans-serif;">
                        Sistem Intelijen Risiko Rantai Pasok Global
                    </h1>
                    <p class="opacity-80 mb-0 fs-6" style="line-height: 1.6;">
                        Sistem pendukung keputusan logistik berbasis Multi-API yang memantau kondisi makro-ekonomi, iklim global, geopolitik, serta maritim di seluruh negara secara waktu nyata untuk meminimalkan risiko keterlambatan rantai pasok.
                    </p>
                </div>
                <!-- Explanatory Quick Badges (4 Indicators) -->
                <div class="col-lg-5">
                    <div class="row g-2">
                        <!-- Indikator 1: Cuaca -->
                        <div class="col-6">
                            <div class="p-3 rounded-3 h-100 border border-white border-opacity-10" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                                <div class="fs-5 mb-1">🌦</div>
                                <h6 class="fw-bold mb-1" style="font-size: 12px; color: #a5f3fc;">Klimatologi & Cuaca</h6>
                                <p class="mb-0 text-white text-opacity-70" style="font-size: 10px; line-height: 1.4;">
                                    Deteksi suhu ekstrem, curah hujan, angin kencang, & badai dari Open-Meteo.
                                </p>
                            </div>
                        </div>
                        <!-- Indikator 2: Pelabuhan -->
                        <div class="col-6">
                            <div class="p-3 rounded-3 h-100 border border-white border-opacity-10" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                                <div class="fs-5 mb-1">🚢</div>
                                <h6 class="fw-bold mb-1" style="font-size: 12px; color: #fef08a;">Kemacetan Pelabuhan</h6>
                                <p class="mb-0 text-white text-opacity-70" style="font-size: 10px; line-height: 1.4;">
                                    Estimasi waktu tunda & status kepadatan logistik berbasis dataset WPI.
                                </p>
                            </div>
                        </div>
                        <!-- Indikator 3: Finansial -->
                        <div class="col-6">
                            <div class="p-3 rounded-3 h-100 border border-white border-opacity-10" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                                <div class="fs-5 mb-1">💱</div>
                                <h6 class="fw-bold mb-1" style="font-size: 12px; color: #bbf7d0;">Stabilitas Valuta</h6>
                                <p class="mb-0 text-white text-opacity-70" style="font-size: 10px; line-height: 1.4;">
                                    Pemantauan fluktuasi nilai tukar kurs valas & inflasi negara (vs USD).
                                </p>
                            </div>
                        </div>
                        <!-- Indikator 4: Geopolitik -->
                        <div class="col-6">
                            <div class="p-3 rounded-3 h-100 border border-white border-opacity-10" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                                <div class="fs-5 mb-1">📰</div>
                                <h6 class="fw-bold mb-1" style="font-size: 12px; color: #fbcfe8;">Sentimen Geopolitik</h6>
                                <p class="mb-0 text-white text-opacity-70" style="font-size: 10px; line-height: 1.4;">
                                    Skoring sentimen berita dari GNews menggunakan analisis kamus kata PHP.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row mb-4 text-center">
        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="card h-100 p-4 shadow-sm border-0 rounded-4 transition-hover">
                <div class="text-muted mb-2 fw-semibold">📈 Skor Risiko Global (Rata-rata)</div>
                <h1 class="text-primary display-4 fw-bold" id="avgRiskScore">--</h1>
                <span class="badge px-3 py-2 rounded-pill fs-7" id="avgRiskLabel">Loading...</span>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="card h-100 p-4 shadow-sm border-0 rounded-4 transition-hover">
                <div class="text-muted mb-2 fw-semibold">🔴 Negara Risiko Tinggi</div>
                <h1 class="text-danger display-4 fw-bold" id="highRiskCount">--</h1>
                <span class="text-danger fw-semibold" id="highRiskLabel">Memuat data...</span>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="card h-100 p-4 shadow-sm border-0 rounded-4 transition-hover">
                <div class="text-muted mb-2 fw-semibold">🟠 Negara Risiko Sedang</div>
                <h1 class="text-warning display-4 fw-bold" id="mediumRiskCount">--</h1>
                <span class="text-warning fw-semibold" id="mediumRiskLabel">Memuat data...</span>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
            <div class="card h-100 p-4 shadow-sm border-0 rounded-4 transition-hover">
                <div class="text-muted mb-2 fw-semibold">🟢 Negara Risiko Rendah</div>
                <h1 class="text-success display-4 fw-bold" id="lowRiskCount">--</h1>
                <span class="text-success fw-semibold" id="lowRiskLabel">Memuat data...</span>
            </div>
        </div>
    </div>

    <!-- Peta dan Info Panel -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden d-flex flex-column">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-dark">🌍 Peta Risiko & Pelabuhan Dunia</h5>
                    <span class="text-muted small"><i class="fa-solid fa-circle-info"></i> Klik marker untuk detail</span>
                </div>
                <div class="card-body p-0 flex-grow-1" style="min-height: 480px;">
                    <div id="worldMap" style="height: 100%; min-height: 480px;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="d-flex flex-column gap-3 h-100">
                <!-- Extreme Weather Alerts -->
                <div class="card shadow-sm border-0 rounded-4 p-4 flex-fill">
                    <h5 class="fw-bold mb-3"><i class="text-warning fa-solid fa-cloud-bolt"></i> Peringatan Cuaca Ekstrem</h5>
                    <div id="weatherAlerts" class="d-flex flex-column gap-2 overflow-auto custom-scroll-container" style="max-height: 180px;">
                        <div class="text-muted small">Memuat data cuaca real-time...</div>
                    </div>
                </div>

                <!-- Port Delay Warnings -->
                <div class="card shadow-sm border-0 rounded-4 p-4 flex-fill">
                    <h5 class="fw-bold mb-3"><i class="text-danger fa-solid fa-anchor"></i> Peringatan Kepadatan Pelabuhan</h5>
                    <div id="portAlerts" class="d-flex flex-column gap-2 overflow-auto custom-scroll-container" style="max-height: 180px;">
                        <div class="text-muted small">Memuat kondisi pelabuhan...</div>
                    </div>
                </div>

                <!-- Currency Quick Rates -->
                <div class="card shadow-sm border-0 rounded-4 p-4 flex-fill">
                    <h5 class="fw-bold mb-3"><i class="text-success fa-solid fa-coins"></i> Nilai Tukar Mata Uang (vs USD)</h5>
                    <div id="currencyRates" class="d-flex flex-column gap-2">
                        <div class="text-muted small">Memuat kurs mata uang terbaru...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables & Charts -->
    <div class="row mb-4">
        <!-- Pemantauan Risiko & Mata Uang -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">🌍 Pemantauan Risiko Negara</h5>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="countryRiskTable">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>Bendera</th>
                                <th>Negara</th>
                                <th>Skor Risiko</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="4" class="text-center text-muted">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">💱 Nilai Tukar Mata Uang Dunia</h5>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="currencyTable">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>Mata Uang</th>
                                <th>Nilai Nominal</th>
                                <th>Status Volatilitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="3" class="text-center text-muted">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Kepadatan Pelabuhan & Perbandingan -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">🚢 Kepadatan Pelabuhan Dunia</h5>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="portsTable">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>Pelabuhan</th>
                                <th>Kode</th>
                                <th>Status Kepadatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="3" class="text-center text-muted">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">⚖ Perbandingan Parameter Negara</h5>
                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-hover align-middle mb-0" id="comparisonTable">
                        <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                            <tr>
                                <th>Negara</th>
                                <th>PDB (GDP)</th>
                                <th>Tingkat Inflasi</th>
                                <th>Risiko</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="4" class="text-center text-muted">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & News -->
    <div class="row mb-4">
        <div class="col-lg-5 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-4">📈 Distribusi Kategori Risiko Global</h5>
                <div class="d-flex justify-content-center align-items-center" style="position: relative; height: 250px;">
                    <canvas id="riskDoughnutChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 p-4">
                <h5 class="fw-bold mb-3">📰 Intelijen Berita Global (Analisis Sentimen AI)</h5>
                <div id="newsList" class="d-flex flex-column gap-3 overflow-auto" style="max-height: 250px;">
                    <div class="text-muted text-center py-5">Memuat analisis berita global...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .fs-7 {
        font-size: 0.8rem;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Inisialisasi Peta Dunia Leaflet
    const map = L.map('worldMap').setView([20, 0], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Layer Groups untuk Marker
    const countriesLayer = L.layerGroup().addTo(map);
    const portsLayer = L.layerGroup().addTo(map);

    // Data Holders
    let countriesData = [];
    let portsData = [];
    let newsData = [];
    let currencyData = {};

    // 2. Fetch Data from API
    Promise.all([
        fetch('/api/countries').then(res => res.json()),
        fetch('/api/ports').then(res => res.json()),
        fetch('/api/news').then(res => res.json()),
        fetch('/api/currency').then(res => res.json())
    ]).then(([countries, ports, news, currencies]) => {
        countriesData = countries;
        portsData = ports;
        newsData = news;
        currencyData = currencies;

        // Render UI
        renderStatCards();
        renderMap();
        renderAlerts();
        renderTables();
        renderChart();
        renderNews();
    }).catch(error => {
        console.error("Gagal memuat dashboard data:", error);
        alert("Terjadi kesalahan koneksi saat memuat data dashboard.");
    });

    // 3. Render Stat Cards
    function renderStatCards() {
        if (countriesData.length === 0) return;

        let totalScore = 0;
        let highCount = 0;
        let mediumCount = 0;
        let lowCount = 0;

        countriesData.forEach(c => {
            totalScore += c.risk_score;
            if (c.risk_level === 'Tinggi') highCount++;
            else if (c.risk_level === 'Sedang') mediumCount++;
            else lowCount++;
        });

        const avgScore = (totalScore / countriesData.length).toFixed(1);
        document.getElementById('avgRiskScore').innerText = avgScore;
        
        const labelEl = document.getElementById('avgRiskLabel');
        if (avgScore >= 70) {
            labelEl.innerText = 'High Risk';
            labelEl.className = 'badge px-3 py-2 rounded-pill fs-7 bg-danger';
        } else if (avgScore >= 40) {
            labelEl.innerText = 'Medium Risk';
            labelEl.className = 'badge px-3 py-2 rounded-pill fs-7 bg-warning text-dark';
        } else {
            labelEl.innerText = 'Low Risk';
            labelEl.className = 'badge px-3 py-2 rounded-pill fs-7 bg-success';
        }

        document.getElementById('highRiskCount').innerText = highCount;
        document.getElementById('highRiskLabel').innerText = `${((highCount/countriesData.length)*100).toFixed(0)}% dari total negara`;

        document.getElementById('mediumRiskCount').innerText = mediumCount;
        document.getElementById('mediumRiskLabel').innerText = `${((mediumCount/countriesData.length)*100).toFixed(0)}% dari total negara`;

        document.getElementById('lowRiskCount').innerText = lowCount;
        document.getElementById('lowRiskLabel').innerText = `${((lowCount/countriesData.length)*100).toFixed(0)}% dari total negara`;
    }

    // 4. Render Map Markers
    function renderMap() {
        countriesLayer.clearLayers();
        portsLayer.clearLayers();

        // Icon Custom untuk Pelabuhan
        const anchorIcon = L.divIcon({
            html: '<div style="font-size: 20px; background: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 5px rgba(0,0,0,0.3)">⚓</div>',
            className: 'custom-anchor-icon',
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        // Add Country Markers (Circle Marker dengan radius berdasarkan risk score)
        countriesData.forEach(c => {
            if (c.latitude && c.longitude) {
                let color = '#198754'; // Low
                if (c.risk_level === 'Tinggi') color = '#dc3545';
                else if (c.risk_level === 'Sedang') color = '#ffc107';

                const circle = L.circleMarker([c.latitude, c.longitude], {
                    radius: 12 + (c.risk_score / 10),
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.7
                });

                const popupContent = `
                    <div style="font-family: 'Poppins', sans-serif;">
                        <h6 class="fw-bold mb-1"><img src="https://flagcdn.com/w40/${c.code.toLowerCase()}.png" class="rounded border me-1" style="width: 20px; height: auto; vertical-align: middle;"> ${c.name}</h6>
                        <small class="text-muted d-block mb-2">${c.region}</small>
                        <table class="table table-sm table-borderless m-0" style="font-size: 11px;">
                            <tr><td><b>Ibukota:</b></td><td>${c.capital}</td></tr>
                            <tr><td><b>GDP:</b></td><td>$${(c.gdp/1e12).toFixed(2)} T</td></tr>
                            <tr><td><b>Inflasi:</b></td><td>${c.inflation}%</td></tr>
                            <tr><td><b>Skor Risiko:</b></td><td><span class="badge ${c.risk_level === 'Tinggi' ? 'bg-danger' : (c.risk_level === 'Sedang' ? 'bg-warning text-dark' : 'bg-success')}">${c.risk_score} (${c.risk_level})</span></td></tr>
                        </table>
                    </div>
                `;
                circle.bindPopup(popupContent);
                countriesLayer.addLayer(circle);
            }
        });

        // Add Port Markers
        portsData.forEach(p => {
            if (p.latitude && p.longitude) {
                const marker = L.marker([p.latitude, p.longitude], { icon: anchorIcon });
                const popupContent = `
                    <div style="font-family: 'Poppins', sans-serif;">
                        <h6 class="fw-bold mb-1">🚢 ${p.name}</h6>
                        <small class="text-muted d-block mb-2">Kode: ${p.code}</small>
                        <span class="badge ${p.congestion_status === 'High' ? 'bg-danger' : (p.congestion_status === 'Medium' ? 'bg-warning text-dark' : 'bg-success')}">Kepadatan: ${p.congestion_status}</span>
                    </div>
                `;
                marker.bindPopup(popupContent);
                portsLayer.addLayer(marker);
            }
        });
    }

    // 5. Render Panel Alerts (Weather, Ports, Currency)
    function renderAlerts() {
        // Weather alerts
        const weatherEl = document.getElementById('weatherAlerts');
        weatherEl.innerHTML = '';
        
        let weatherWarnings = [];
        countriesData.forEach(c => {
            if (c.risk_score > 60) {
                weatherWarnings.push({
                    country: c.name,
                    desc: `Potensi gangguan logistik akibat cuaca di ${c.name}.`
                });
            }
        });

        if (weatherWarnings.length === 0) {
            weatherEl.innerHTML = `
                <div class="custom-alert-item alert-success-custom">
                    <span class="custom-alert-icon text-success"><i class="fa-solid fa-circle-check"></i></span>
                    <div class="custom-alert-content">
                        <div class="custom-alert-title text-success">Aman</div>
                        <div class="custom-alert-desc">Seluruh jalur logistik cuaca terpantau aman.</div>
                    </div>
                </div>
            `;
        } else {
            weatherWarnings.forEach(w => {
                weatherEl.innerHTML += `
                    <div class="custom-alert-item">
                        <span class="custom-alert-icon text-warning"><i class="fa-solid fa-triangle-exclamation"></i></span>
                        <div class="custom-alert-content">
                            <div class="custom-alert-title">${w.country}</div>
                            <div class="custom-alert-desc">Cuaca ekstrem berisiko menghambat pengiriman.</div>
                        </div>
                    </div>
                `;
            });
        }

        // Port alerts
        const portEl = document.getElementById('portAlerts');
        portEl.innerHTML = '';
        const highCongestionPorts = portsData.filter(p => p.congestion_status === 'High');
        if (highCongestionPorts.length === 0) {
            portEl.innerHTML = `
                <div class="custom-alert-item alert-success-custom">
                    <span class="custom-alert-icon text-success"><i class="fa-solid fa-circle-check"></i></span>
                    <div class="custom-alert-content">
                        <div class="custom-alert-title text-success">Lancar</div>
                        <div class="custom-alert-desc">Kepadatan pelabuhan utama terkendali.</div>
                    </div>
                </div>
            `;
        } else {
            highCongestionPorts.forEach(p => {
                portEl.innerHTML += `
                    <div class="custom-alert-item alert-danger-custom">
                        <span class="custom-alert-icon text-danger"><i class="fa-solid fa-triangle-exclamation"></i></span>
                        <div class="custom-alert-content">
                            <div class="custom-alert-title">${p.name}</div>
                            <div class="custom-alert-desc">Kepadatan tinggi! Delay bertambah (+${p.delay_hours ?? 24} jam).</div>
                        </div>
                    </div>
                `;
            });
        }

        // Currency quick panel
        const currencyEl = document.getElementById('currencyRates');
        currencyEl.innerHTML = '';
        const targetRates = ['IDR', 'CNY', 'EUR', 'SGD', 'JPY'];
        targetRates.forEach(code => {
            if (currencyData[code]) {
                let status = 'Stabil';
                let colorClass = 'bg-success-subtle text-success';
                let icon = '▼';
                if (code === 'IDR' || code === 'RUB') {
                    status = 'Depresiasi';
                    colorClass = 'bg-danger-subtle text-danger';
                    icon = '▲';
                }
                currencyEl.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center p-2 rounded-3" style="background: #f8fafc; font-size: 11px; font-weight: 500;">
                        <span class="text-dark"><b>USD/${code}</b></span>
                        <span class="font-monospace text-secondary">${Number(currencyData[code]).toLocaleString('id-ID')}</span>
                        <span class="badge ${colorClass} px-2 py-1" style="font-size: 9px; font-weight: 700;">
                            ${icon} ${status}
                        </span>
                    </div>
                `;
            }
        });
    }

    // 6. Render Tables (Countries, Currency, Ports, Comparison)
    function renderTables() {
        // Country Risk Table
        const riskTableBody = document.querySelector('#countryRiskTable tbody');
        riskTableBody.innerHTML = '';
        countriesData.forEach(c => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><img src="https://flagcdn.com/w40/${c.code.toLowerCase()}.png" class="rounded border" style="width: 25px; height: auto;"></td>
                <td><b>${c.name}</b></td>
                <td>${c.risk_score}</td>
                <td><span class="badge ${c.risk_level === 'Tinggi' ? 'bg-danger' : (c.risk_level === 'Sedang' ? 'bg-warning text-dark' : 'bg-success')}">${c.risk_level}</span></td>
            `;
            riskTableBody.appendChild(tr);
        });

        // Currency Table
        const currencyTableBody = document.querySelector('#currencyTable tbody');
        currencyTableBody.innerHTML = '';
        const majorCurrencies = ['IDR', 'CNY', 'EUR', 'SGD', 'JPY', 'GBP', 'AUD', 'CAD'];
        majorCurrencies.forEach(code => {
            if (currencyData[code]) {
                const tr = document.createElement('tr');
                let impact = 'Dampak Rendah';
                let badgeClass = 'bg-success';
                if (code === 'IDR' || code === 'RUB' || code === 'INR') {
                    impact = 'Suku Bunga Impor Naik';
                    badgeClass = 'bg-danger';
                } else if (code === 'CNY' || code === 'SGD' || code === 'JPY') {
                    impact = 'Dampak Menengah';
                    badgeClass = 'bg-warning text-dark';
                }
                tr.innerHTML = `
                    <td><b>USD/${code}</b></td>
                    <td>${Number(currencyData[code]).toLocaleString('id-ID')}</td>
                    <td><span class="badge ${badgeClass}">${impact}</span></td>
                `;
                currencyTableBody.appendChild(tr);
            }
        });

        // Ports Table
        const portsTableBody = document.querySelector('#portsTable tbody');
        portsTableBody.innerHTML = '';
        portsData.forEach(p => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><b>${p.name}</b></td>
                <td><code>${p.code || p.country_code}</code></td>
                <td><span class="badge ${p.congestion_status === 'High' ? 'bg-danger' : (p.congestion_status === 'Medium' ? 'bg-warning text-dark' : 'bg-success')}">${p.congestion_status}</span></td>
            `;
            portsTableBody.appendChild(tr);
        });

        // Comparison Table
        const compTableBody = document.querySelector('#comparisonTable tbody');
        compTableBody.innerHTML = '';
        countriesData.slice(0, 6).forEach(c => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><b><img src="https://flagcdn.com/w40/${c.code.toLowerCase()}.png" class="rounded border me-1" style="width: 20px; height: auto; vertical-align: middle;"> ${c.name}</b></td>
                <td>$${(c.gdp/1e12).toFixed(2)} T</td>
                <td>${c.inflation}%</td>
                <td><span class="badge ${c.risk_level === 'Tinggi' ? 'bg-danger' : (c.risk_level === 'Sedang' ? 'bg-warning text-dark' : 'bg-success')}">${c.risk_level}</span></td>
            `;
            compTableBody.appendChild(tr);
        });
    }

    // 7. Render Chart.js
    function renderChart() {
        let high = 0, medium = 0, low = 0;
        countriesData.forEach(c => {
            if (c.risk_level === 'Tinggi') high++;
            else if (c.risk_level === 'Sedang') medium++;
            else low++;
        });

        const ctx = document.getElementById('riskDoughnutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Risiko Tinggi', 'Risiko Sedang', 'Risiko Rendah'],
                datasets: [{
                    data: [high, medium, low],
                    backgroundColor: ['#dc3545', '#ffc107', '#198754'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Poppins'
                            }
                        }
                    }
                }
            }
        });
    }

    // 8. Render News Sentiment List
    function renderNews() {
        const newsEl = document.getElementById('newsList');
        newsEl.innerHTML = '';

        if (newsData.length === 0) {
            newsEl.innerHTML = '<div class="text-muted text-center py-5">Belum ada analisis berita terbaru.</div>';
            return;
        }

        newsData.forEach(item => {
            let sentimentBadge = 'bg-secondary';
            if (item.sentiment === 'Positive') sentimentBadge = 'bg-success';
            else if (item.sentiment === 'Negative') sentimentBadge = 'bg-danger';

            // format date
            const dateStr = item.published_at ? new Date(item.published_at).toLocaleString('id-ID', {
                day: 'numeric',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            }) : 'Baru saja';

            newsEl.innerHTML += `
                <div class="p-3 bg-light rounded-4 border-0 shadow-xs">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge ${sentimentBadge} px-2.5 py-1.5 rounded-pill fs-8">Sentimen: ${item.sentiment}</span>
                        <small class="text-muted">${dateStr}</small>
                    </div>
                    <h6 class="fw-bold mb-1 text-dark">${item.title}</h6>
                    <p class="small text-muted mb-2">${item.content}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-primary fw-semibold"><i class="fa-solid fa-newspaper"></i> ${item.source || 'News Source'}</span>
                        ${item.country ? `<span class="small text-muted fw-semibold">Negara: <img src="https://flagcdn.com/w40/${item.country.code.toLowerCase()}.png" class="rounded border me-1" style="width: 18px; height: auto; vertical-align: middle;"> ${item.country.name}</span>` : ''}
                    </div>
                </div>
            `;
        });
    }
});
</script>
@endsection
@extends('layouts.master')

@section('title', 'Dashboard Kurs Mata Uang')

@section('content')

<style>
    .stat-card {
        background:#fff; border:none; border-radius:16px;
        padding:20px 24px; box-shadow:0 2px 10px rgba(0,0,0,.07);
        height:100%;
    }
    .stat-card .label {
        font-size:11px; font-weight:700; text-transform:uppercase;
        letter-spacing:.5px; color:#6c757d; margin-bottom:6px;
    }
    .stat-card .value { font-size:24px; font-weight:800; line-height:1.1; }

    /* Search */
    .search-wrap { position:relative; }
    .search-wrap .fa-magnifying-glass {
        position:absolute; left:14px; top:50%;
        transform:translateY(-50%); color:#9ca3af; font-size:14px;
    }
    #searchCurrency {
        padding-left:38px; border-radius:12px;
        border:2px solid #e5e7eb; font-size:13px; font-weight:500;
        transition:border-color .2s, box-shadow .2s;
    }
    #searchCurrency:focus {
        border-color:#4361ee; box-shadow:0 0 0 3px rgba(67,97,238,.15); outline:none;
    }

    /* Table */
    .currency-table { font-size:13px; }
    .currency-table thead th {
        font-size:11px; font-weight:700; text-transform:uppercase;
        letter-spacing:.4px; color:#6c757d; background:#f8fafc;
        border-bottom:2px solid #e5e7eb; padding:10px 14px;
        white-space:nowrap;
    }
    .currency-table tbody tr { transition:background .15s; }
    .currency-table tbody tr:hover { background:#f0f4ff; }
    .currency-table tbody td {
        padding:10px 14px; vertical-align:middle;
        border-bottom:1px solid #f1f5f9;
    }
    .currency-table tbody td:first-child { width:220px; }
    .rate-val { font-size:14px; font-weight:700; font-family:'Courier New',monospace; }
    .na-rate { color:#9ca3af; font-style:italic; font-size:12px; }
    .table-card {
        background:#fff; border:none; border-radius:16px;
        box-shadow:0 2px 10px rgba(0,0,0,.07); overflow:hidden;
    }
    .trend-up { color:#dc3545; font-size:11px; }
    .trend-dn { color:#198754; font-size:11px; }
    .trend-nt { color:#6c757d; font-size:11px; }
</style>

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">💱 Dashboard Kurs Mata Uang</h2>
            <p class="text-muted small mb-0">
                Kurs terhadap USD • Menampilkan <b>{{ $total }}</b> mata uang dari seluruh dunia •
                Diperbarui: {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
        <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Segarkan
        </button>
    </div>

    {{-- 4 Highlight Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="label">🇮🇩 USD / IDR</div>
                <div class="value text-danger">{{ number_format($highlights['IDR'], 0, ',', '.') }}</div>
                <div class="mt-1 small text-danger fw-semibold">▲ Volatilitas Tinggi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="label">🇨🇳 USD / CNY</div>
                <div class="value text-warning">{{ number_format($highlights['CNY'], 4, '.', ',') }}</div>
                <div class="mt-1 small text-warning fw-semibold">▲ Stabil-Menengah</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="label">🇪🇺 USD / EUR</div>
                <div class="value text-success">{{ number_format($highlights['EUR'], 4, '.', ',') }}</div>
                <div class="mt-1 small text-success fw-semibold">▼ Stabil-Kuat</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="label">🇸🇬 USD / SGD</div>
                <div class="value text-success">{{ number_format($highlights['SGD'], 4, '.', ',') }}</div>
                <div class="mt-1 small text-success fw-semibold">▼ Sangat Stabil</div>
            </div>
        </div>
    </div>

    {{-- Chart + Ringkasan Risiko --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="stat-card">
                <h5 class="fw-bold mb-3">📈 Pergerakan Nilai Tukar Utama (6 Bulan Terakhir)</h5>
                <div style="height:280px; position:relative;">
                    <canvas id="currencyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="stat-card h-100">
                <h5 class="fw-bold mb-3">⚠ Ringkasan Risiko Kurs</h5>
                <div class="d-flex flex-column gap-3">
                    @php
                        $highCount = collect($currencyData)->where('risk_level','Tinggi')->count();
                        $medCount  = collect($currencyData)->where('risk_level','Sedang')->count();
                        $lowCount  = collect($currencyData)->where('risk_level','Rendah')->count();
                    @endphp
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                        <div>
                            <span class="badge bg-danger me-2">Tinggi</span>
                            <span class="small text-muted">Volatilitas Tinggi</span>
                        </div>
                        <b class="text-danger fs-5">{{ $highCount }}</b>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                        <div>
                            <span class="badge bg-warning text-dark me-2">Sedang</span>
                            <span class="small text-muted">Fluktuasi Normal</span>
                        </div>
                        <b class="text-warning fs-5">{{ $medCount }}</b>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-success me-2">Rendah</span>
                            <span class="small text-muted">Stabil & Terpercaya</span>
                        </div>
                        <b class="text-success fs-5">{{ $lowCount }}</b>
                    </div>

                    <div class="mt-2 pt-2 border-top">
                        <canvas id="riskPieChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Semua Mata Uang --}}
    <div class="table-card">
        <div class="p-3 border-bottom">
            <div class="d-flex align-items-center" style="gap:16px;">
                <h5 class="fw-bold mb-0 flex-shrink-0">🌍 Tabel Kurs Seluruh Mata Uang Dunia</h5>
                <div class="search-wrap flex-grow-1" style="max-width:320px; min-width:200px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchCurrency" class="form-control form-control-sm"
                           placeholder="Cari negara atau kode mata uang...">
                </div>
                <span class="text-muted small flex-shrink-0">
                    <b id="visibleCount">{{ $total }}</b> dari {{ $total }} mata uang
                </span>
            </div>
        </div>

        <div class="table-responsive" style="max-height:600px; overflow-y:auto;">
            <table class="table currency-table mb-0">
                <thead style="position:sticky; top:0; z-index:1;">
                    <tr>
                        <th>Negara / Mata Uang</th>
                        <th>Kode</th>
                        <th>Nama Mata Uang</th>
                        <th>Kurs vs USD</th>
                        <th>Inflasi</th>
                        <th>Risiko</th>
                        <th>Tren</th>
                    </tr>
                </thead>
                <tbody id="currencyTableBody">
                    @foreach($currencyData as $item)
                        <tr class="currency-row"
                            data-country="{{ strtolower($item['country']) }}"
                            data-code="{{ strtolower($item['currency_code']) }}"
                            data-name="{{ strtolower($item['currency_name']) }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://flagcdn.com/w40/{{ strtolower($item['code']) }}.png" alt="Bendera {{ $item['country'] }}" class="rounded shadow-xs border" style="width: 24px; height: auto;">
                                    <div>
                                        <div class="fw-semibold" style="font-size:13px; line-height:1.2;">{{ $item['country'] }}</div>
                                        @if($item['capital'])
                                            <div style="font-size:10px; color:#9ca3af;">
                                                <i class="fa-solid fa-location-dot" style="font-size:8px;"></i>
                                                {{ $item['capital'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-bold" style="font-size:12px;">
                                    {{ $item['currency_code'] }}
                                </span>
                            </td>
                            <td class="text-muted" style="font-size:12px;">{{ $item['currency_name'] }}</td>
                            <td>
                                @if($item['rate'] !== null)
                                    <span class="rate-val">
                                        @if($item['rate'] >= 1000)
                                            {{ number_format($item['rate'], 0, ',', '.') }}
                                        @elseif($item['rate'] >= 10)
                                            {{ number_format($item['rate'], 2, '.', ',') }}
                                        @else
                                            {{ number_format($item['rate'], 4, '.', ',') }}
                                        @endif
                                    </span>
                                @else
                                    <span class="na-rate">Data tidak tersedia</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $item['inflation'] > 10 ? 'text-danger' : ($item['inflation'] > 5 ? 'text-warning' : 'text-success') }} fw-bold">
                                    {{ $item['inflation'] }}%
                                </span>
                            </td>
                            <td>
                                @if($item['risk_level'] === 'Tinggi')
                                    <span class="badge bg-danger rounded-pill px-3">Tinggi</span>
                                @elseif($item['risk_level'] === 'Sedang')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Sedang</span>
                                @else
                                    <span class="badge bg-success rounded-pill px-3">Rendah</span>
                                @endif
                            </td>
                            <td>
                                @if($item['risk_level'] === 'Tinggi')
                                    <span class="trend-up">▲ Melemah</span>
                                @elseif($item['risk_level'] === 'Sedang')
                                    <span class="trend-nt">→ Sideways</span>
                                @else
                                    <span class="trend-dn">▼ Menguat</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- No Result --}}
        <div class="text-center py-5 text-muted" id="noResult" style="display:none;">
            <i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block"></i>
            <p>Tidak ada mata uang yang cocok dengan pencarian Anda.</p>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // =============================================
    // Line Chart — 6-month trend for 3 currencies
    // =============================================
    const ctx = document.getElementById('currencyChart');
    if (ctx) {
        // Fallback static data
        const fallbackLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
        const fallbackIdr = [15.8, 15.9, 16.0, 16.1, 16.2, {{ $highlights['IDR'] / 1000 }}];
        const fallbackCny = [71.0, 72.0, 72.5, 72.5, 72.8, {{ $highlights['CNY'] * 10 }}];
        const fallbackEur = [0.91, 0.92, 0.92, 0.91, 0.92, {{ $highlights['EUR'] }}];

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: fallbackLabels,
                datasets: [{
                    label: 'USD/IDR (÷1000)',
                    data: fallbackIdr,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220,53,69,0.08)',
                    tension: 0.4, fill: true, borderWidth: 2,
                    pointRadius: 3
                }, {
                    label: 'USD/CNY (×10)',
                    data: fallbackCny,
                    borderColor: '#f59e0b',
                    tension: 0.4, fill: false, borderWidth: 2,
                    pointRadius: 3
                }, {
                    label: 'USD/EUR',
                    data: fallbackEur,
                    borderColor: '#198754',
                    tension: 0.4, fill: false, borderWidth: 2,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11 } } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: { grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Dynamic Fetch from Frankfurter API (ECB Data)
        const end = new Date();
        const start = new Date();
        start.setMonth(start.getMonth() - 6);

        const startStr = start.toISOString().split('T')[0];
        const endStr = end.toISOString().split('T')[0];

        fetch(`https://api.frankfurter.app/${startStr}..${endStr}?from=USD&to=EUR,CNY,IDR`)
            .then(res => res.json())
            .then(data => {
                if (data && data.rates) {
                    const dates = Object.keys(data.rates).sort();
                    const step = Math.ceil(dates.length / 12) || 1; // Sample about 12 points

                    const sampledLabels = [];
                    const idrData = [];
                    const cnyData = [];
                    const eurData = [];

                    const formatChartLabel = (dateStr) => {
                        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        const parts = dateStr.split('-');
                        if (parts.length === 3) {
                            const day = parseInt(parts[2]);
                            const monthIndex = parseInt(parts[1]) - 1;
                            return `${day} ${months[monthIndex]}`;
                        }
                        return dateStr;
                    };

                    for (let i = 0; i < dates.length; i += step) {
                        const d = dates[i];
                        sampledLabels.push(formatChartLabel(d));
                        idrData.push((data.rates[d].IDR || 0) / 1000);
                        cnyData.push((data.rates[d].CNY || 0) * 10);
                        eurData.push(data.rates[d].EUR || 0);
                    }

                    // Always add the final date point
                    const lastD = dates[dates.length - 1];
                    if (dates.length > 0 && !sampledLabels.includes(formatChartLabel(lastD))) {
                        sampledLabels.push(formatChartLabel(lastD));
                        idrData.push((data.rates[lastD].IDR || 0) / 1000);
                        cnyData.push((data.rates[lastD].CNY || 0) * 10);
                        eurData.push(data.rates[lastD].EUR || 0);
                    }

                    // Update Chart
                    chart.data.labels = sampledLabels;
                    chart.data.datasets[0].data = idrData;
                    chart.data.datasets[1].data = cnyData;
                    chart.data.datasets[2].data = eurData;
                    chart.update();
                }
            })
            .catch(err => {
                console.warn("Gagal memuat kurs mata uang dinamis, menggunakan fallback data lokal:", err);
            });
    }

    // =============================================
    // Pie Chart — Risk Distribution
    // =============================================
    const pie = document.getElementById('riskPieChart');
    if (pie) {
        new Chart(pie, {
            type: 'doughnut',
            data: {
                labels: ['Risiko Tinggi', 'Risiko Sedang', 'Risiko Rendah'],
                datasets: [{
                    data: [{{ collect($currencyData)->where('risk_level','Tinggi')->count() }}, {{ collect($currencyData)->where('risk_level','Sedang')->count() }}, {{ collect($currencyData)->where('risk_level','Rendah')->count() }}],
                    backgroundColor: ['#dc3545', '#f59e0b', '#198754'],
                    borderWidth: 3, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, cutout: '60%',
                plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, padding: 10 } } }
            }
        });
    }

    // =============================================
    // Search / Filter
    // =============================================
    const searchInput = document.getElementById('searchCurrency');
    const rows = document.querySelectorAll('.currency-row');
    const visibleCount = document.getElementById('visibleCount');
    const noResult = document.getElementById('noResult');

    searchInput.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        let shown = 0;

        rows.forEach(row => {
            const match = !q
                || (row.dataset.country || '').includes(q)
                || (row.dataset.code || '').includes(q)
                || (row.dataset.name || '').includes(q);

            row.style.display = match ? '' : 'none';
            if (match) shown++;
        });

        visibleCount.textContent = shown;
        noResult.style.display = shown === 0 ? 'block' : 'none';
    });
});
</script>
@endsection
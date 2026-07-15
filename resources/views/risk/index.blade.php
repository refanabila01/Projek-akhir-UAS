@extends('layouts.master')

@section('title', 'Skor Risiko Rantai Pasok')

@section('content')
@php
    $total  = count($riskData);
    $high   = collect($riskData)->where('risk_level', 'Tinggi')->count();
    $medium = collect($riskData)->where('risk_level', 'Sedang')->count();
    $low    = collect($riskData)->where('risk_level', 'Rendah')->count();
    $avgScore = $total > 0 ? round(collect($riskData)->avg('total_score'), 1) : 0;
@endphp

<style>
    .stat-card {
        background:#fff; border:none; border-radius:16px;
        padding:20px 24px; box-shadow:0 2px 10px rgba(0,0,0,.07); height:100%;
    }
    .stat-card .label {
        font-size:11px; font-weight:700; text-transform:uppercase;
        letter-spacing:.5px; color:#6c757d; margin-bottom:6px;
    }
    .stat-card .value { font-size:30px; font-weight:800; line-height:1.1; }

    .table-card {
        background:#fff; border:none; border-radius:16px;
        box-shadow:0 2px 10px rgba(0,0,0,.07); overflow:hidden;
    }
    .risk-table { font-size:13px; }
    .risk-table thead th {
        font-size:11px; font-weight:700; text-transform:uppercase;
        letter-spacing:.4px; color:#6c757d; background:#f8fafc;
        border-bottom:2px solid #e5e7eb; padding:10px 14px;
        white-space:nowrap; position:sticky; top:0; z-index:1;
    }
    .risk-table tbody tr { transition:background .15s; }
    .risk-table tbody tr:hover { background:#f0f4ff; }
    .risk-table tbody td { padding:10px 14px; vertical-align:middle; border-bottom:1px solid #f1f5f9; }

    /* Progress bar for scores */
    .score-bar { width:100%; height:6px; background:#f1f5f9; border-radius:4px; overflow:hidden; margin-top:3px; }
    .score-fill { height:100%; border-radius:4px; transition:width .4s; }

    /* Search */
    .search-wrap { position:relative; }
    .search-wrap .fa-magnifying-glass {
        position:absolute; left:14px; top:50%;
        transform:translateY(-50%); color:#9ca3af; font-size:14px;
    }
    #searchRisk {
        padding-left:38px; border-radius:12px;
        border:2px solid #e5e7eb; font-size:13px; font-weight:500;
        transition:border-color .2s, box-shadow .2s;
    }
    #searchRisk:focus {
        border-color:#4361ee; box-shadow:0 0 0 3px rgba(67,97,238,.15); outline:none;
    }

    /* Filter buttons */
    .filter-btn {
        background:#f1f5f9; border:none; border-radius:8px;
        padding:5px 14px; font-size:12px; font-weight:600;
        color:#6c757d; cursor:pointer; transition:all .2s;
    }
    .filter-btn.active { color:#fff; }
    .filter-btn.all.active { background:#4361ee; }
    .filter-btn.high.active { background:#dc3545; }
    .filter-btn.med.active  { background:#f59e0b; }
    .filter-btn.low.active  { background:#198754; }
    .filter-btn:hover:not(.active) { background:#e2e8f0; }
</style>

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">⚠ Skor Risiko Rantai Pasok Global</h2>
            <p class="text-muted small mb-0">
                Analisis risiko untuk <b>{{ $total }}</b> negara •
                Diperbarui: {{ now()->format('d M Y, H:i') }} WIB
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
                <div class="label">🌍 Total Negara</div>
                <div class="value text-primary">{{ $total }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="label">🔴 Risiko Tinggi</div>
                <div class="value text-danger">{{ $high }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="label">🟡 Risiko Sedang</div>
                <div class="value text-warning">{{ $medium }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="label">🟢 Risiko Rendah</div>
                <div class="value text-success">{{ $low }}</div>
            </div>
        </div>
    </div>

    {{-- Chart + Top 10 --}}
    <div class="row g-3 mb-4">
        {{-- Donut Chart --}}
        <div class="col-lg-4">
            <div class="stat-card" style="padding:20px 20px 16px;">
                <h5 class="fw-bold mb-3" style="font-size:14px;">📊 Distribusi Risiko Global</h5>

                {{-- Donut + Center Label --}}
                <div style="position:relative; max-width:200px; margin:0 auto;">
                    <canvas id="riskChart" width="200" height="200"></canvas>
                    <div style="position:absolute; top:50%; left:50%;
                                transform:translate(-50%,-50%); text-align:center; pointer-events:none;">
                        <div class="fw-bold text-primary" style="font-size:24px; line-height:1;">{{ $avgScore }}</div>
                        <div style="font-size:9px; color:#6c757d; font-weight:600;">Rata-rata</div>
                    </div>
                </div>

                {{-- Legend --}}
                <div class="d-flex justify-content-center gap-3 mt-3" style="font-size:12px;">
                    <span class="d-flex align-items-center gap-1">
                        <span style="width:10px;height:10px;background:#dc3545;border-radius:2px;display:inline-block;"></span>
                        Tinggi: <b>{{ $high }}</b>
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span style="width:10px;height:10px;background:#f59e0b;border-radius:2px;display:inline-block;"></span>
                        Sedang: <b>{{ $medium }}</b>
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span style="width:10px;height:10px;background:#198754;border-radius:2px;display:inline-block;"></span>
                        Rendah: <b>{{ $low }}</b>
                    </span>
                </div>
            </div>
        </div>

        {{-- Top 10 --}}
        <div class="col-lg-8">
            <div class="stat-card h-100" style="padding:20px;">
                <h5 class="fw-bold mb-3" style="font-size:14px;">🏆 10 Negara Risiko Tertinggi</h5>
                @foreach(array_slice($riskData, 0, 10) as $i => $d)
                <div class="d-flex align-items-center mb-2" style="gap:10px;">
                    <span style="font-size:11px; color:#9ca3af; min-width:16px; text-align:right;">{{ $i+1 }}</span>
                    <img src="https://flagcdn.com/w40/{{ strtolower($d['code']) }}.png" alt="Bendera {{ $d['name'] }}" class="rounded shadow-xs border" style="width: 20px; height: auto; flex-shrink:0; vertical-align: middle;">
                    <div class="flex-grow-1" style="min-width:0;">
                        <div class="d-flex justify-content-between mb-1">
                            <span style="font-size:12px; font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $d['name'] }}</span>
                            <span style="font-size:12px; font-weight:700; flex-shrink:0; margin-left:8px;
                                color:{{ $d['risk_level']==='Tinggi' ? '#dc3545' : ($d['risk_level']==='Sedang' ? '#f59e0b' : '#198754') }}">
                                {{ $d['total_score'] }}
                            </span>
                        </div>
                        <div class="score-bar">
                            <div class="score-fill" style="width:{{ min(100,$d['total_score']) }}%;
                                background:{{ $d['risk_level']==='Tinggi' ? '#dc3545' : ($d['risk_level']==='Sedang' ? '#f59e0b' : '#4361ee') }}">
                            </div>
                        </div>
                    </div>
                    <span class="badge {{ $d['risk_level']==='Tinggi' ? 'bg-danger' : ($d['risk_level']==='Sedang' ? 'bg-warning text-dark' : 'bg-success') }} rounded-pill flex-shrink-0"
                          style="font-size:9px; min-width:50px; text-align:center;">
                        {{ $d['risk_level'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tabel Semua Negara --}}
    <div class="table-card">

        {{-- Header Kartu Tabel --}}
        <div class="p-3 border-bottom">
            <div class="d-flex align-items-center" style="gap:14px; flex-wrap:nowrap;">
                <h5 class="fw-bold mb-0 flex-shrink-0">📋 Skor Risiko Seluruh Negara</h5>
                <div class="search-wrap flex-grow-1" style="max-width:300px; min-width:160px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchRisk" class="form-control form-control-sm"
                           placeholder="Cari negara...">
                </div>
                <div class="d-flex gap-2 flex-shrink-0">
                    <button class="filter-btn all active" data-filter="semua">Semua</button>
                    <button class="filter-btn high" data-filter="Tinggi">🔴 Tinggi</button>
                    <button class="filter-btn med"  data-filter="Sedang">🟡 Sedang</button>
                    <button class="filter-btn low"  data-filter="Rendah">🟢 Rendah</button>
                </div>
                <span class="text-muted small flex-shrink-0">
                    <b id="visibleCount">{{ $total }}</b> negara
                </span>
            </div>
        </div>

        <div class="table-responsive" style="max-height:600px; overflow-y:auto;">
            <table class="table risk-table mb-0">
                <thead>
                    <tr>
                        <th style="width:30px;">#</th>
                        <th>Negara</th>
                        <th>Cuaca <small class="text-muted">(30%)</small></th>
                        <th>Inflasi <small class="text-muted">(20%)</small></th>
                        <th>Kurs <small class="text-muted">(10%)</small></th>
                        <th>Sentimen Berita <small class="text-muted">(40%)</small></th>
                        <th>Skor Akhir</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="riskTableBody">
                    @foreach($riskData as $i => $data)
                        <tr class="risk-row"
                            data-name="{{ strtolower($data['name']) }}"
                            data-level="{{ $data['risk_level'] }}">
                            <td class="text-muted" style="font-size:12px;">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://flagcdn.com/w40/{{ strtolower($data['code']) }}.png" alt="Bendera {{ $data['name'] }}" class="rounded shadow-xs border" style="width: 24px; height: auto;">
                                    <div>
                                        <div class="fw-semibold" style="font-size:13px; line-height:1.2;">{{ $data['name'] }}</div>
                                        @if($data['capital'])
                                            <div style="font-size:10px; color:#9ca3af;">
                                                <i class="fa-solid fa-location-dot" style="font-size:8px;"></i>
                                                {{ $data['capital'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            @foreach(['weather_score','inflation_score','currency_score','news_score'] as $key)
                            <td>
                                <div class="fw-bold" style="font-size:13px;
                                    color:{{ $data[$key] >= 70 ? '#dc3545' : ($data[$key] >= 40 ? '#f59e0b' : '#374151') }}">
                                    {{ $data[$key] }}
                                </div>
                                <div class="score-bar">
                                    <div class="score-fill" style="width:{{ min(100,$data[$key]) }}%;
                                        background:{{ $data[$key] >= 70 ? '#dc3545' : ($data[$key] >= 40 ? '#f59e0b' : '#4361ee') }}">
                                    </div>
                                </div>
                            </td>
                            @endforeach

                            <td>
                                <span class="fw-bold" style="font-size:15px;
                                    color:{{ $data['risk_level']==='Tinggi' ? '#dc3545' : ($data['risk_level']==='Sedang' ? '#f59e0b' : '#198754') }}">
                                    {{ $data['total_score'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $data['risk_level']==='Tinggi' ? 'bg-danger' : ($data['risk_level']==='Sedang' ? 'bg-warning text-dark' : 'bg-success') }} rounded-pill px-3" style="font-size:10px;">
                                    {{ $data['risk_level'] }}
                                </span>
                            </td>
                            <td style="font-size:11px; color:#6c757d; min-width:200px; white-space:normal;">{{ $data['description'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-center py-5 text-muted" id="noResult" style="display:none;">
            <i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block"></i>
            <p>Tidak ada negara yang cocok dengan pencarian Anda.</p>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Doughnut Chart
    const ctx = document.getElementById('riskChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Risiko Tinggi', 'Risiko Sedang', 'Risiko Rendah'],
                datasets: [{
                    data: [{{ $high }}, {{ $medium }}, {{ $low }}],
                    backgroundColor: ['#dc3545', '#f59e0b', '#198754'],
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
                            label: c => ` ${c.label}: ${c.raw} negara`
                        }
                    }
                }
            }
        });
    }

    // Filter Buttons
    const filterBtns = document.querySelectorAll('.filter-btn');
    const rows = document.querySelectorAll('.risk-row');
    const visibleCount = document.getElementById('visibleCount');
    const noResult = document.getElementById('noResult');
    const searchInput = document.getElementById('searchRisk');

    let activeFilter = 'semua';
    let searchQuery = '';

    function applyFilters() {
        let shown = 0;
        rows.forEach(row => {
            const name = row.dataset.name || '';
            const level = row.dataset.level || '';
            const matchFilter = activeFilter === 'semua' || level === activeFilter;
            const matchSearch = !searchQuery || name.includes(searchQuery);
            const match = matchFilter && matchSearch;
            row.style.display = match ? '' : 'none';
            if (match) shown++;
        });
        visibleCount.textContent = shown;
        noResult.style.display = shown === 0 ? 'block' : 'none';
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeFilter = this.dataset.filter;
            applyFilters();
        });
    });

    searchInput.addEventListener('input', function () {
        searchQuery = this.value.trim().toLowerCase();
        applyFilters();
    });
});
</script>
@endsection
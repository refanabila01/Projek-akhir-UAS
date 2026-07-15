@extends('layouts.master')

@section('title', 'Daftar Pantau')

@section('content')
@php
    $total = count($watchlistData);
    $high = collect($watchlistData)->where('risk_level', 'Tinggi')->count();
    $medLow = collect($watchlistData)->whereIn('risk_level', ['Sedang', 'Rendah'])->count();
    
    // Translation Maps
    $weatherMap = [
        'Clear' => 'Cerah', 'Sunny' => 'Cerah Terang', 'Cloudy' => 'Berawan',
        'Partly Cloudy' => 'Sebagian Berawan', 'Rain' => 'Hujan',
        'Heavy Rain' => 'Hujan Lebat', 'Thunderstorm' => 'Hujan Petir',
        'Drizzle' => 'Gerimis', 'Foggy' => 'Berkabut', 'Windy' => 'Berangin',
        'Cerah' => 'Cerah', 'Berawan' => 'Berawan', 'Hujan' => 'Hujan',
        'Hujan Ringan' => 'Hujan Ringan', 'Hujan Lebat' => 'Hujan Lebat',
        'Angin Kencang / Badai' => 'Angin Kencang / Badai',
        'Berangin' => 'Berangin', 'Suhu Ekstrim' => 'Suhu Ekstrem'
    ];

    $portLabels = [
        'High' => 'Kepadatan Tinggi',
        'Medium' => 'Kepadatan Sedang',
        'Low' => 'Kepadatan Rendah'
    ];
@endphp

<style>
    .watchlist-page { font-family: 'Poppins', sans-serif; }

    /* ---- Stat Cards ---- */
    .stat-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        height: 100%;
        text-align: center;
    }
    .stat-card .label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 6px;
    }
    .stat-card .value {
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
    }

    /* ---- Table Card ---- */
    .table-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .watchlist-table { font-size: 13px; }
    .watchlist-table thead th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #6c757d;
        background: #f8fafc;
        border-bottom: 2px solid #e5e7eb;
        padding: 12px 16px;
    }
    .watchlist-table tbody tr { transition: background 0.15s; }
    .watchlist-table tbody tr:hover { background: #f0f4ff; }
    .watchlist-table tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="container-fluid watchlist-page">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">⭐ Pemantauan Favorit</h2>
            <p class="text-muted small mb-0">Daftar pantau khusus negara yang Anda prioritas-kan untuk pengawasan logistik rantai pasok</p>
        </div>
        <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Segarkan Data
        </button>
    </div>

    <!-- Statistik Watchlist -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">Total Negara Dipantau</div>
                <div class="value text-primary">{{ $total }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">🔴 Kategori Risiko Tinggi</div>
                <div class="value text-danger">{{ $high }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="label">🟢 Kategori Risiko Sedang & Rendah</div>
                <div class="value text-success">{{ $medLow }}</div>
            </div>
        </div>
    </div>

    <!-- Tabel Watchlist -->
    <div class="table-card">
        <div class="p-3 border-bottom">
            <h5 class="fw-bold mb-0 text-dark">📋 Daftar Negara Prioritas</h5>
        </div>
        
        <div class="table-responsive">
            <table class="table watchlist-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Negara</th>
                        <th>Skor & Tingkat Risiko</th>
                        <th>Kondisi Cuaca Saat Ini</th>
                        <th>Mata Uang</th>
                        <th>Kepadatan Pelabuhan</th>
                        <th class="text-center">Status Pantauan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($watchlistData as $data)
                        @php
                            $weatherDesc = $weatherMap[$data['weather_desc']] ?? $data['weather_desc'];
                            $portDesc = $portLabels[$data['port_status']] ?? $data['port_status'];
                            
                            $statusClass = 'bg-success';
                            if ($data['port_status'] === 'High') {
                                $statusClass = 'bg-danger';
                            } elseif ($data['port_status'] === 'Medium') {
                                $statusClass = 'bg-warning text-dark';
                            }
                        @endphp
                        <tr>
                            <td>
                                <img src="https://flagcdn.com/w40/{{ strtolower($data['code']) }}.png" alt="Bendera {{ $data['name'] }}" class="rounded shadow-xs border me-2" style="width: 24px; height: auto; vertical-align: middle;">
                                <b class="text-dark">{{ $data['name'] }}</b>
                            </td>
                            <td>
                                <span class="badge {{ $data['risk_level'] === 'Tinggi' ? 'bg-danger' : ($data['risk_level'] === 'Sedang' ? 'bg-warning text-dark' : 'bg-success') }} px-3 py-1.5 rounded-pill">
                                    {{ round($data['risk_score'], 1) }} — {{ $data['risk_level'] }}
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 16px;" class="me-1">
                                    @if(str_contains($weatherDesc, 'Hujan')) 🌧️
                                    @elseif(str_contains($weatherDesc, 'Salju')) ❄️
                                    @elseif(str_contains($weatherDesc, 'Cerah')) ☀️
                                    @else ⛅
                                    @endif
                                </span>
                                {{ $weatherDesc }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-bold" style="font-size: 11px;">
                                    {{ $data['currency_code'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }} px-3 py-1.5 rounded-pill">
                                    {{ $portDesc }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-warning fw-bold small">
                                    <i class="fa-solid fa-star me-1"></i> Dipantau
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-star fa-3x mb-3 d-block text-secondary"></i>
                                Belum ada negara dalam daftar pantauan Anda. Silakan tambahkan dari halaman negara.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
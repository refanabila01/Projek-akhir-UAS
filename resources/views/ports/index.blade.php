@extends('layouts.master')

@section('title', 'Dashboard Pelabuhan')

@section('content')
@php
    $total = count($ports);
    $high = collect($ports)->where('congestion_status', 'High')->count();
    $medium = collect($ports)->where('congestion_status', 'Medium')->count();
    $low = collect($ports)->where('congestion_status', 'Low')->count();
@endphp

<style>
    .port-page { font-family: 'Poppins', sans-serif; }
    
    /* Custom scrollbar for port sidebar list */
    #portListContainer::-webkit-scrollbar {
        width: 6px;
    }
    #portListContainer::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    #portListContainer::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    #portListContainer::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="container-fluid port-page">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">🚢 Dashboard Kepadatan Pelabuhan Dunia</h2>
            <p class="text-muted small mb-0">Status penundaan maritim dan kepadatan real-time di seluruh pelabuhan utama global</p>
        </div>
    </div>

    <!-- Statistik Pelabuhan (matching screenshot stats block) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Pelabuhan -->
        <div class="col-12 col-md-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 h-100" style="background: #ffffff;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #eff6ff; color: #3b82f6; width: 56px; height: 56px;">
                        <i class="fa-solid fa-anchor fs-4"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark" style="font-size: 26px;">{{ $total }}</h2>
                        <span class="text-muted fw-semibold" style="font-size: 12px;">Total Pelabuhan</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 2: Kemacetan Rendah -->
        <div class="col-12 col-md-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 h-100" style="background: #ffffff;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #ecfdf5; color: #10b981; width: 56px; height: 56px;">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark" style="font-size: 26px;">{{ $low }}</h2>
                        <span class="text-muted fw-semibold" style="font-size: 12px;">Kemacetan Rendah</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 3: Kemacetan Sedang -->
        <div class="col-12 col-md-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 h-100" style="background: #ffffff;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fffbeb; color: #f59e0b; width: 56px; height: 56px;">
                        <i class="fa-solid fa-circle-exclamation fs-4"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark" style="font-size: 26px;">{{ $medium }}</h2>
                        <span class="text-muted fw-semibold" style="font-size: 12px;">Kemacetan Sedang</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 4: Kemacetan Tinggi -->
        <div class="col-12 col-md-3">
            <div class="card border-0 rounded-4 shadow-sm p-3 h-100" style="background: #ffffff;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background: #fdf2f2; color: #ef4444; width: 56px; height: 56px;">
                        <i class="fa-solid fa-circle-xmark fs-4"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0 text-dark" style="font-size: 26px;">{{ $high }}</h2>
                        <span class="text-muted fw-semibold" style="font-size: 12px;">Kemacetan Tinggi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card (matching screenshot dropdown and sync buttons) -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4" style="background: #ffffff; border-left: 4px solid #3b82f6 !important;">
        <form action="{{ route('ports') }}" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
            <!-- Search Input -->
            <div class="flex-grow-1" style="min-width: 250px;">
                <label class="form-label fw-bold small mb-1 text-muted">Cari Pelabuhan</label>
                <div class="position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="left: 14px; top: 50%; transform: translateY(-50%);"></i>
                    <input type="text" id="searchPort" name="search" class="form-control rounded-3" style="padding-left: 38px; height: 42px; border: 2px solid #e2e8f0; font-size: 14px;" placeholder="Nama pelabuhan, negara, wilayah..." value="{{ request('search') }}">
                </div>
            </div>
            
            <!-- Country Filter -->
            <div style="min-width: 200px;">
                <label class="form-label fw-bold small mb-1 text-muted">Filter Negara</label>
                <select id="filterCountry" name="country" class="form-select rounded-3 text-dark" style="height: 42px; border: 2px solid #e2e8f0; font-size: 14px;">
                    <option value="">Semua Negara</option>
                    @foreach(collect($countries)->sortBy('name') as $code => $country)
                        <option value="{{ $code }}" {{ request('country') == $code ? 'selected' : '' }}>
                            {{ $country['flag'] }} {{ $country['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="d-flex gap-2 align-self-end">
                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm d-flex align-items-center gap-2" style="background: #3b82f6; border: none; height: 42px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                </button>
                <a href="{{ route('ports', ['sync' => 1]) }}" class="btn btn-indigo rounded-3 px-4 fw-semibold shadow-sm text-white d-flex align-items-center gap-2" style="background: #4f46e5; border: none; height: 42px;">
                    <i class="fa-solid fa-arrows-rotate"></i> Sync Pelabuhan Global
                </a>
            </div>
        </form>
    </div>

    <!-- Map & Sidebar Split Section -->
    <div class="row g-4 mb-4">
        <!-- Left Side: Map -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="m-0 fw-bold text-dark">🌍 Peta Kepadatan Pelabuhan Global</h5>
                    <span class="text-muted small"><i class="fa-solid fa-circle-info"></i> Klik titik untuk detail</span>
                </div>
                <div class="card-body p-0" style="min-height: 580px;">
                    <div id="portMap" style="height: 580px; width: 100%;"></div>
                </div>
            </div>
        </div>

        <!-- Right Side: Sidebar List (matching sidebar design) -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm p-3 d-flex flex-column h-100" style="background: #ffffff; min-height: 640px;">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">
                        <i class="fa-solid fa-list-check text-primary me-2"></i>Daftar Pelabuhan
                    </h5>
                    <span class="badge bg-primary rounded-pill px-2 py-1" style="font-size: 11px;">
                        {{ $total }}
                    </span>
                </div>

                <!-- Scrollable Container -->
                <div class="overflow-auto pr-2 flex-grow-1" style="max-height: 540px; min-height: 400px;" id="portListContainer">
                    @foreach($ports as $port)
                        @php
                            $countryInfo = $countries[$port->country_code] ?? null;
                            $countryName = $countryInfo['name'] ?? $port->country_code;
                            $flag = $countryInfo['flag'] ?? '🏳️';

                            $statusClass = 'bg-success';
                            $statusLabel = 'Rendah';
                            if ($port->congestion_status === 'High') {
                                $statusClass = 'bg-danger';
                                $statusLabel = 'Tinggi';
                            } elseif ($port->congestion_status === 'Medium') {
                                $statusClass = 'bg-warning text-dark';
                                $statusLabel = 'Sedang';
                            }
                        @endphp
                        <div class="card border-0 rounded-3 shadow-sm mb-3 p-3 port-list-item position-relative" 
                             style="background: #f8fafc; border-left: 4px solid {{ $port->congestion_status === 'High' ? '#ef4444' : ($port->congestion_status === 'Medium' ? '#f59e0b' : '#10b981') }} !important; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                             onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.08)';"
                             onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)';"
                             onclick="focusPort({{ $port->latitude }}, {{ $port->longitude }}, '{{ $port->wpi_number }}')"
                             data-name="{{ strtolower($port->name) }}"
                             data-code="{{ strtolower($port->code) }}"
                             data-country-code="{{ strtolower($port->country_code) }}"
                             data-region="{{ strtolower($port->region) }}"
                             data-status="{{ $port->congestion_status }}">
                            
                            <!-- Congestion Badge -->
                            <span class="badge {{ $statusClass }} position-absolute" style="top: 14px; right: 14px; font-size: 10px; font-weight: 700; padding: 4px 8px;">
                                {{ $statusLabel }}
                            </span>

                            <!-- Title -->
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 13px; max-width: 78%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $port->name }}
                            </h6>

                            <!-- Subtitle / Meta -->
                            <div class="text-muted mb-2" style="font-size: 11px;">
                                {{ $port->country_code }} - {{ $port->region }} - WPI: {{ $port->wpi_number }}
                            </div>

                            <!-- Details Footer Row -->
                            <div class="d-flex align-items-center gap-3 pt-2 border-top text-muted" style="font-size: 11px;">
                                <span class="d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-clock text-warning"></i> Tunda: {{ $port->delay_hours }}j
                                </span>
                                <span class="d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-location-dot text-primary"></i> {{ number_format($port->latitude, 4) }}, {{ number_format($port->longitude, 4) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- No Results Message inside List -->
                <div class="text-center py-5 text-muted" id="noResult" style="display: none;">
                    <i class="fa-solid fa-magnifying-glass fa-2x mb-2 d-block"></i>
                    <p style="font-size: 13px;">Tidak ada pelabuhan yang cocok.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ---- Inisiasi Leaflet Map ----
    const map = L.map('portMap', {
        center: [15, 0],
        zoom: 2,
        zoomControl: true,
        scrollWheelZoom: true
    });

    // Gaya Peta Berwarna Elegan (Voyager)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors © CARTO',
        maxZoom: 19
    }).addTo(map);

    const ports = @json($ports);
    const countries = @json($countries);
    const markers = {};

    ports.forEach(function(item) {
        if (item.latitude && item.longitude) {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);

            const countryInfo = countries[item.country_code] || {};
            const countryName = countryInfo.name || item.country_code;
            const flag = countryInfo.flag || '';

            let statusLabel = 'Kepadatan Rendah';
            let statusBadgeClass = 'bg-success';
            let markerColor = '#10b981'; // Green
            if (item.congestion_status === 'High') {
                statusLabel = 'Kepadatan Tinggi';
                statusBadgeClass = 'bg-danger';
                markerColor = '#ef4444'; // Red
            } else if (item.congestion_status === 'Medium') {
                statusLabel = 'Kepadatan Sedang';
                statusBadgeClass = 'bg-warning text-dark';
                markerColor = '#f59e0b'; // Orange
            }

            const popupHtml = `
                <div style="font-family: 'Poppins', sans-serif; min-width: 180px;">
                    <h6 class="fw-bold mb-1" style="font-size: 13px;">🚢 ${item.name}</h6>
                    <div style="font-size: 11px; color:#6b7280;" class="mb-1"><img src="https://flagcdn.com/w40/${item.country_code.toLowerCase()}.png" class="rounded border me-1" style="width: 18px; height: auto; vertical-align: middle;"> ${countryName} (${item.region})</div>
                    <small class="d-block text-muted mb-1">Kode: ${item.code} • WPI: ${item.wpi_number}</small>
                    <small class="d-block text-warning fw-semibold mb-2"><i class="fa-solid fa-clock"></i> Keterlambatan: ${item.delay_hours} jam</small>
                    <span class="badge ${statusBadgeClass} px-2 py-1" style="font-size: 10px;">
                        Status: ${statusLabel}
                    </span>
                </div>
            `;

            // Draw circle marker for super high performance rendering of 3616 dots
            const marker = L.circleMarker([lat, lng], {
                radius: 6,
                fillColor: markerColor,
                color: '#ffffff',
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map).bindPopup(popupHtml, { maxWidth: 220 });

            markers[item.wpi_number] = marker;
        }
    });

    // Global function to center and focus port from list click
    window.focusPort = function(lat, lng, wpiNumber) {
        map.setView([lat, lng], 8);
        if (markers[wpiNumber]) {
            markers[wpiNumber].openPopup();
        }
    };

    // ---- Live Filter & Pencarian Live di Sidebar ----
    const searchInput = document.getElementById('searchPort');
    const filterCountry = document.getElementById('filterCountry');
    const portItems = document.querySelectorAll('.port-list-item');
    const noResult = document.getElementById('noResult');

    function applyFilters() {
        const query = searchInput.value.trim().toLowerCase();
        const country = filterCountry.value.trim().toLowerCase();
        let shown = 0;
        let firstMatch = null;

        // Apply filters to list items
        portItems.forEach(item => {
            const name = item.dataset.name || '';
            const code = item.dataset.code || '';
            const cCode = item.dataset.countryCode || '';
            const region = item.dataset.region || '';
            
            const matchSearch = !query || name.includes(query) || code.includes(query) || region.includes(query);
            const matchCountry = !country || cCode === country;

            if (matchSearch && matchCountry) {
                item.style.setProperty('display', '', 'important');
                shown++;
            } else {
                item.style.setProperty('display', 'none', 'important');
            }
        });

        // Filter Leaflet markers and track the first match
        ports.forEach(item => {
            if (!item.latitude || !item.longitude) return;

            const name = item.name.toLowerCase();
            const code = item.code.toLowerCase();
            const cCode = item.country_code.toLowerCase();
            const region = (item.region || '').toLowerCase();
            
            const matchSearch = !query || name.includes(query) || code.includes(query) || region.includes(query);
            const matchCountry = !country || cCode === country;

            if (matchSearch && matchCountry) {
                if (markers[item.wpi_number] && !map.hasLayer(markers[item.wpi_number])) {
                    markers[item.wpi_number].addTo(map);
                }
                if (!firstMatch) {
                    firstMatch = item;
                }
            } else {
                if (markers[item.wpi_number] && map.hasLayer(markers[item.wpi_number])) {
                    map.removeLayer(markers[item.wpi_number]);
                }
            }
        });

        // Focus map and open popup for the first matching port
        if (firstMatch && (query.length >= 3 || country !== '')) {
            const lat = parseFloat(firstMatch.latitude);
            const lng = parseFloat(firstMatch.longitude);
            map.setView([lat, lng], 6);
            if (markers[firstMatch.wpi_number]) {
                markers[firstMatch.wpi_number].openPopup();
            }
        }

        noResult.style.display = shown === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', applyFilters);
    filterCountry.addEventListener('change', applyFilters);
});
</script>
@endsection
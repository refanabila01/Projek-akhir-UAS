@extends('layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .admin-page { font-family: 'Poppins', sans-serif; }

    /* ---- 5 Stat Cards in Row ---- */
    .stat-card-row {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-mini-card {
        background: #fff;
        border: 1px solid transparent;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        flex: 1;
        min-width: 180px;
        transition: all 0.25s ease;
    }
    .stat-mini-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        border-color: #4361ee !important;
    }
    .stat-mini-card .label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        margin-bottom: 6px;
    }
    .stat-mini-card .value {
        font-size: 24px;
        font-weight: 800;
        line-height: 1.1;
        color: #1a1a2e;
    }
    .stat-mini-card .sub {
        font-size: 10px;
        font-weight: 600;
        color: #10b981;
        margin-top: 4px;
    }

    /* ---- Section Layouts ---- */
    .dashboard-section {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 20px 22px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        height: 100%;
    }
    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 16px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
    }

    /* ---- Tables ---- */
    .admin-table { font-size: 12px; }
    .admin-table thead th {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6c757d;
        background: #f8fafc;
        border-bottom: 2px solid #e5e7eb;
        padding: 8px 10px;
    }
    .admin-table tbody td {
        padding: 10px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    /* ---- Recent Articles ---- */
    .article-item {
        border-bottom: 1px solid #f1f5f9;
        padding: 10px 0;
    }
    .article-item:last-child { border-bottom: none; }
    .article-title {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .article-time { font-size: 10px; color: #9ca3af; margin-top: 2px; }

    /* ---- Explanation Panel ---- */
    .explain-card {
        background: #f8fafc;
        border-left: 4px solid #4361ee;
        border-radius: 12px;
        padding: 20px;
        margin-top: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
</style>

<div class="container-fluid admin-page">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">👤 Admin Dashboard</h2>
            <p class="text-muted small mb-0">Pemantauan data pengguna, jumlah integrasi, serta lalu lintas pemanggilan API eksternal</p>
        </div>
        <button class="btn btn-outline-primary rounded-pill px-4" onclick="window.location.reload();">
            <i class="fa-solid fa-arrows-rotate me-1"></i> Segarkan Dasbor
        </button>
    </div>

    <!-- 5 Stat Cards Row -->
    <div class="stat-card-row">
        
        <!-- Total Users -->
        <a href="{{ route('admin.users') }}" class="stat-mini-card text-decoration-none">
            <div class="label">Total Pengguna</div>
            <div class="value">{{ $userCount }}</div>
            <div class="sub">▲ +12 bulan ini</div>
        </a>

        <!-- Total Countries -->
        <div class="stat-mini-card">
            <div class="label">Total Negara</div>
            <div class="value">{{ $countryCount }}</div>
            <div class="sub text-muted">Negara Berdaulat</div>
        </div>

        <!-- Total Ports -->
        <a href="{{ route('admin.ports') }}" class="stat-mini-card text-decoration-none">
            <div class="label">Total Pelabuhan</div>
            <div class="value">{{ number_format($portCount) }}</div>
            <div class="sub text-muted">Pelabuhan Utama</div>
        </a>

        <!-- Total Articles -->
        <a href="{{ route('admin.articles') }}" class="stat-mini-card text-decoration-none">
            <div class="label">Total Artikel</div>
            <div class="value">{{ $newsCount }}</div>
            <div class="sub text-muted">Berita Rantai Pasok</div>
        </a>

        <!-- API Calls Today -->
        <div class="stat-mini-card">
            <div class="label">Panggilan API Hari Ini</div>
            <div class="value">12.458</div>
            <div class="sub">▲ Kuota Aman</div>
        </div>

    </div>

    <!-- Modul Utama (3 Kolom) -->
    <div class="row g-3">
        
        <!-- Widget 1: User Management -->
        <div class="col-lg-5">
            <div class="dashboard-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark" style="font-size: 14px;">Manajemen Pengguna</span>
                    <a href="{{ route('admin.users') }}" class="text-primary small fw-semibold" style="text-decoration:none;">Kelola Semua →</a>
                </div>
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usersList as $item)
                                <tr>
                                    <td>{{ $item['id'] }}</td>
                                    <td><b>{{ $item['name'] }}</b></td>
                                    <td class="text-muted">{{ $item['email'] }}</td>
                                    <td>{{ $item['role'] }}</td>
                                    <td>
                                        <span class="badge {{ $item['status'] === 'Aktif' ? 'bg-success' : 'bg-danger' }}" style="font-size: 9px;">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.users') }}" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0" style="font-size: 10px;">
                                            <i class="fa-solid fa-gear"></i> Kelola
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Widget 2: Recent Articles -->
        <div class="col-lg-3">
            <div class="dashboard-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark" style="font-size: 14px;">Artikel Terbaru</span>
                    <a href="{{ route('admin.articles') }}" class="text-primary small fw-semibold" style="text-decoration:none;">Lihat Semua</a>
                </div>
                
                <div class="d-flex flex-column gap-1">
                    @forelse($recentArticles as $art)
                        <div class="article-item">
                            <div class="article-title" title="{{ $art->title }}">{{ $art->title }}</div>
                            <div class="article-time">
                                📅 {{ $art->published_at ? $art->published_at->diffForHumans() : 'Baru saja' }}
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-5 small">Belum ada artikel.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Widget 3: API Usage -->
        <div class="col-lg-4">
            <div class="dashboard-section d-flex flex-column align-items-center">
                <span class="fw-bold text-dark align-self-start mb-2" style="font-size: 14px;">Penggunaan API</span>
                
                {{-- Doughnut Chart --}}
                <div style="position:relative; width:150px; height:150px; margin:10px auto;">
                    <canvas id="apiUsageChart" width="150" height="150"></canvas>
                    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center; pointer-events:none;">
                        <div class="fw-bold text-primary" style="font-size:16px; line-height:1.1;">12.458</div>
                        <div style="font-size:8px; color:#6c757d; font-weight:600;">Total Panggilan</div>
                    </div>
                </div>

                {{-- Legend & Percentages --}}
                <div class="w-100 mt-2" style="font-size: 11px; font-weight: 600;">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span>🔵 API Cuaca (Open-Meteo)</span>
                        <span>35%</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span>🟢 API Kurs (Exchange Rate)</span>
                        <span>25%</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span>🟡 API Berita (GNews)</span>
                        <span>20%</span>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span>🟠 API Bank Dunia (World Bank)</span>
                        <span>12%</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span>⚫ Lain-lain / Cache</span>
                        <span>8%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Penjelasan Modul (Widget Penjelasan Baru) -->
    <div class="explain-card">
        <h5 class="fw-bold text-dark mb-2">💡 Penjelasan Komponen Dasbor Admin</h5>
        <div class="row g-3" style="font-size: 12.5px; color: #4b5563;">
            <div class="col-md-4">
                <h6 class="fw-bold text-dark mb-1">1. Pengelolaan Pengguna (User Management)</h6>
                <p class="mb-0">Menampilkan tabel otorisasi akun pengguna. Administrator memiliki akses penuh untuk mengubah peran (*Role*) seperti Analis, Editor, atau Viewer, serta menonaktifkan pengguna yang melanggar ketentuan operasional.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-dark mb-1">2. Artikel Logistik (Recent Articles)</h6>
                <p class="mb-0">Memuat analisis berita rantai pasok global terbaru yang terintegrasi dengan deteksi sentimen otomatis. Bagian ini membantu analis logistik melacak gejolak geopolitik, pemogokan pelabuhan, atau tarif dagang baru.</p>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold text-dark mb-1">3. Statistik API (API Calls & Usage)</h6>
                <p class="mb-0">Menunjukkan total alokasi pemanggilan web service. Integrasi chart donat memetakan distribusi trafik harian demi memastikan ketersediaan kuota data cuaca maritim, nilai tukar mata uang, dan indikator PDB Bank Dunia tetap terjaga.</p>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ---- API Usage Doughnut Chart ----
    const ctx = document.getElementById('apiUsageChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['API Cuaca', 'API Kurs', 'API Berita', 'API Bank Dunia', 'Lainnya'],
                datasets: [{
                    data: [35, 25, 20, 12, 8],
                    backgroundColor: ['#2563eb', '#16a34a', '#eab308', '#ea580c', '#6b7280'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endsection

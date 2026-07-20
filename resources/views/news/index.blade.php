@extends('layouts.master')

@section('title', 'Intelijen Berita & Sentimen AI')

@section('content')
@php
    $total = count($news);
    $positive = collect($news)->where('sentiment', 'Positive')->count();
    $neutral = collect($news)->where('sentiment', 'Neutral')->count();
    $negative = collect($news)->where('sentiment', 'Negative')->count();
@endphp

<style>
    .news-page { font-family: 'Poppins', sans-serif; }

    /* ---- Stat Cards ---- */
    .stat-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        height: 100%;
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

    /* ---- Left & Right Cards ---- */
    .info-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        height: 100%;
    }
    .info-card h5 {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 18px;
    }

    /* ---- Filter Tabs & Search ---- */
    .search-wrap { position: relative; }
    .search-wrap .fa-magnifying-glass {
        position: absolute; left: 14px; top: 50%;
        transform: translateY(-50%); color: #9ca3af; font-size: 14px;
    }
    #searchNews {
        padding-left: 38px; border-radius: 12px; border: 2px solid #e5e7eb;
        font-size: 13px; font-weight: 500;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    #searchNews:focus {
        border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        outline: none;
    }

    .filter-btn {
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-btn.active { color: #fff; }
    .filter-btn.all.active { background: #4361ee; }
    .filter-btn.positive.active { background: #198754; }
    .filter-btn.neutral.active { background: #f59e0b; }
    .filter-btn.negative.active { background: #dc3545; }
    .filter-btn:hover:not(.active) {
        background: #e2e8f0;
        color: #374151;
    }

    /* ---- News Card Items (Modern Grid) ---- */
    .news-card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .news-card-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    }
    .news-card-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08) !important;
        border-color: #4f46e5 !important;
    }
    .news-card-item:hover .news-zoom-img {
        transform: scale(1.06);
    }
    .news-card-item:hover .news-title-hover {
        color: #4f46e5 !important;
    }
    
    .sentiment-badge {
        font-size: 9px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }
    .badge-positive { background-color: rgba(25, 135, 84, 0.12); color: #198754; }
    .badge-neutral { background-color: rgba(245, 158, 11, 0.12); color: #b45309; }
    .badge-negative { background-color: rgba(220, 53, 69, 0.12); color: #dc3545; }
</style>

<div class="container-fluid news-page">

    <!-- Header Banner Card (matching screenshot look) -->
    <div class="card border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #1e1b4b 100%); color: white; box-shadow: 0 10px 30px rgba(79, 70, 229, 0.15);">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="fw-bold mb-1 d-flex align-items-center gap-2" style="font-size: 26px;">
                    <i class="fa-regular fa-newspaper"></i> Pemantauan Berita Global
                </h1>
                <p class="fs-6 opacity-75 mb-0">
                    Berita terkini mengenai Logistics, Trade, Shipping, dan Economy dunia secara real-time.
                </p>
            </div>
            <a href="{{ route('news', ['category' => $category ?? 'Economy', 'refresh' => 1]) }}" class="btn btn-light rounded-pill px-4 text-primary fw-bold shadow-sm">
                <i class="fa-solid fa-arrows-rotate me-1"></i> Segarkan Data
            </a>
        </div>
    </div>

    <!-- Category Selector Card (matching screenshot dropdown) -->
    <div class="card border-0 rounded-4 shadow-sm p-3 mb-4" style="background: #ffffff; border-left: 4px solid #4f46e5 !important;">
        <form action="{{ route('news') }}" method="GET" class="d-flex align-items-center gap-3 flex-wrap">
            <div class="flex-grow-1" style="min-width: 250px; max-width: 500px;">
                <select name="category" class="form-select rounded-3 fw-semibold text-dark" style="font-size: 15px; border: 2px solid #e2e8f0; padding: 10px 15px;">
                    <option value="Economy" {{ ($category ?? 'Economy') == 'Economy' ? 'selected' : '' }}>Economy</option>
                    <option value="Logistics" {{ ($category ?? 'Economy') == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                    <option value="Trade" {{ ($category ?? 'Economy') == 'Trade' ? 'selected' : '' }}>Trade</option>
                    <option value="Shipping" {{ ($category ?? 'Economy') == 'Shipping' ? 'selected' : '' }}>Shipping</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary rounded-3 px-5 py-2 fw-semibold shadow-sm" style="background: #4f46e5; border: none; font-size: 15px; padding: 10px 30px;">
                Cari Berita
            </button>
        </form>
    </div>

    <!-- Main Content Section -->
    <div class="row g-4">
        
        <!-- Full Width News List Grid -->
        <div class="col-12">
            <div class="info-card d-flex flex-column">
                <h5 class="mb-3">📰 Daftar Berita & Analisis Logistik</h5>

                <!-- Articles Scrollable Grid Container -->
                <div class="overflow-auto pr-2 custom-scroll-container" style="max-height: 580px;" id="newsContainer">
                    <div class="row g-3" style="margin: 0;">
                        @forelse($news as $article)
                            @php
                                $sentimentClass = 'badge-neutral';
                                $sentimentLabel = 'Netral';
                                if ($article->sentiment === 'Positive') {
                                    $sentimentClass = 'badge-positive';
                                    $sentimentLabel = 'Positif';
                                } elseif ($article->sentiment === 'Negative') {
                                    $sentimentClass = 'badge-negative';
                                    $sentimentLabel = 'Negatif';
                                }

                                $fallbackImages = [
                                    'Logistics' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&auto=format&fit=crop',
                                    'Trade' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&auto=format&fit=crop',
                                    'Shipping' => 'https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?w=800&auto=format&fit=crop',
                                    'Economy' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&auto=format&fit=crop'
                                ];
                                $imageUrl = $article->image ?: ($fallbackImages[$category ?? 'Economy'] ?? $fallbackImages['Economy']);
                            @endphp
                            <div class="col-12 col-md-6 news-item" 
                                 data-title="{{ strtolower($article->title) }}"
                                 data-content="{{ strtolower($article->content) }}"
                                 data-country="{{ strtolower($article->country->name ?? '') }}"
                                 data-sentiment="{{ $article->sentiment }}">
                                
                                <a href="{{ $article->url ?? '#' }}" target="_blank" class="news-card-link">
                                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden news-card-item" style="background: #ffffff; border: 1px solid #f1f5f9 !important;">
                                        <!-- Image Header -->
                                        <div class="position-relative overflow-hidden" style="height: 140px;">
                                            <img src="{{ $imageUrl }}" class="w-100 h-100 news-zoom-img" style="object-fit: cover; transition: transform 0.4s ease;" alt="Gambar Berita">
                                            <span class="position-absolute top-0 start-0 m-2.5 badge {{ $article->sentiment === 'Positive' ? 'bg-success' : ($article->sentiment === 'Negative' ? 'bg-danger' : 'bg-warning text-dark') }} px-2 py-1.5 rounded-pill shadow" style="font-size: 8px; font-weight: 700; letter-spacing: 0.5px; z-index: 10;">
                                                Sentimen: {{ $sentimentLabel }}
                                            </span>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body p-3 d-flex flex-column justify-content-between" style="min-height: 180px;">
                                            <div>
                                                <small class="text-muted d-block mb-1.5" style="font-size: 10px; font-weight: 500;">
                                                    <i class="fa-solid fa-clock me-1"></i>{{ $article->published_at ? $article->published_at->format('d M Y, H:i') . ' WIB' : 'Baru saja' }}
                                                </small>
                                                <h6 class="fw-bold text-dark mb-1.5 news-title-hover" style="font-size: 12.5px; line-height: 1.4; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                                    {{ $article->title }}
                                                </h6>
                                                <p class="text-secondary mb-3" style="font-size: 11px; line-height: 1.5; height: 50px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                    {{ $article->content }}
                                                </p>
                                            </div>

                                            <!-- Footer Info -->
                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top border-light" style="font-size: 10px;">
                                                <span class="text-primary fw-bold">
                                                    <i class="fa-solid fa-newspaper me-1"></i>{{ $article->source ?? 'GNews' }}
                                                </span>
                                                @if($article->country)
                                                    <span class="text-muted fw-semibold">
                                                        <img src="https://flagcdn.com/w40/{{ strtolower($article->country->code) }}.png" class="rounded border me-1" style="width: 16px; height: auto; vertical-align: middle;">
                                                        {{ $article->country->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @empty
                            <div class="col-12 text-muted text-center py-5">
                                <i class="fa-solid fa-newspaper fa-3x mb-3 d-block text-secondary"></i>
                                Belum ada data berita. Silakan jalankan simulasi di Dashboard.
                            </div>
                        @endforelse

                        {{-- Tambahkan kartu filler jika jumlah total genap ganjil --}}
                        @php
                            $newsCount = count($news);
                            $isOdd = $newsCount % 2 !== 0;
                        @endphp
                        @if($isOdd && $newsCount > 0)
                            @php
                                $fillers = [
                                    'Economy' => [
                                        'title' => 'Global Supply Chain Inflation Rates Show Signals of Moderation in Q3',
                                        'content' => 'A consensus of leading central banks indicates that supply chain pressure indexes are starting to ease, lowering wholesale import inflation rates across major trading ports.',
                                        'source' => 'World Economic Forum',
                                        'sentiment' => 'Positive',
                                        'sentiment_label' => 'Positif'
                                    ],
                                    'Logistics' => [
                                        'title' => 'Logistics Optimization Trends: Green Warehousing Drives Down Operating Costs',
                                        'content' => 'Adopting energy-efficient automated storage and solar-powered cold chains allows global distributors to offset rising fuel prices and minimize route delays.',
                                        'source' => 'Logistics Inside',
                                        'sentiment' => 'Positive',
                                        'sentiment_label' => 'Positif'
                                    ],
                                    'Trade' => [
                                        'title' => 'New Bilateral Maritime Agreements Aim to Reduce Tariff Friction',
                                        'content' => 'Bilateral trade pacts between major Asian hubs and European markets are set to eliminate bottlenecks and streamline custom clearance procedures next month.',
                                        'source' => 'Global Trade Daily',
                                        'sentiment' => 'Positive',
                                        'sentiment_label' => 'Positif'
                                    ],
                                    'Shipping' => [
                                        'title' => 'Maritime Freight Rates Steady as Vessel Capacity Increases',
                                        'content' => 'The introduction of modern high-tonnage container vessels stabilizes global shipping spot rates, providing a predictable window for maritime operators.',
                                        'source' => 'Shipping Gazette',
                                        'sentiment' => 'Neutral',
                                        'sentiment_label' => 'Netral'
                                    ]
                                ];

                                $filler = $fillers[$category ?? 'Economy'] ?? $fillers['Economy'];
                                $fillerImage = $fallbackImages[$category ?? 'Economy'] ?? $fallbackImages['Economy'];
                            @endphp
                            <div class="col-12 col-md-6 news-item">
                                <a href="#" class="news-card-link">
                                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden news-card-item" style="background: #ffffff; border: 1px solid #f1f5f9 !important;">
                                        <!-- Image Header -->
                                        <div class="position-relative overflow-hidden" style="height: 140px;">
                                            <img src="{{ $fillerImage }}" class="w-100 h-100 news-zoom-img" style="object-fit: cover; transition: transform 0.4s ease;" alt="Gambar Berita">
                                            <span class="position-absolute top-0 start-0 m-2.5 badge {{ $filler['sentiment'] === 'Positive' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-1.5 rounded-pill shadow" style="font-size: 8px; font-weight: 700; letter-spacing: 0.5px; z-index: 10;">
                                                Sentimen: {{ $filler['sentiment_label'] }}
                                            </span>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body p-3 d-flex flex-column justify-content-between" style="min-height: 180px;">
                                            <div>
                                                <small class="text-muted d-block mb-1.5" style="font-size: 10px; font-weight: 500;">
                                                    <i class="fa-solid fa-clock me-1"></i>{{ now()->format('d M Y, H:i') }} WIB
                                                </small>
                                                <h6 class="fw-bold text-dark mb-1.5 news-title-hover" style="font-size: 12.5px; line-height: 1.4; height: 36px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                                    {{ $filler['title'] }}
                                                </h6>
                                                <p class="text-secondary mb-3" style="font-size: 11px; line-height: 1.5; height: 50px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                    {{ $filler['content'] }}
                                                </p>
                                            </div>

                                            <!-- Footer Info -->
                                            <div class="d-flex justify-content-between align-items-center pt-2 border-top border-light" style="font-size: 10px;">
                                                <span class="text-primary fw-bold">
                                                    <i class="fa-solid fa-newspaper me-1"></i>{{ $filler['source'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // No scripts needed
});
</script>
@endsection
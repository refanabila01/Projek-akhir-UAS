<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>

        body{
    background: #f8fafc;
    font-family:'Poppins',sans-serif;
}

        .sidebar{
    width:280px;
    height:100vh;
    overflow-y:auto;
    position:fixed;
    left:0;
    top:0;
    padding:25px;
    background: linear-gradient(
        180deg,
        #1e293b 0%,
        #0f172a 100%
    );
    color:#cbd5e1;
    box-shadow: 0 4px 20px rgba(15,23,42,0.15);
}

        .logo{
            font-size:30px;
            font-weight:bold;
            color:white;
            margin-bottom:40px;
        }

      .menu-item{
    display:flex;
    align-items:center;
    gap:12px;
    width:100%;
    padding:10px 15px;
    margin-bottom:8px;
    border-radius:12px;
    color:#94a3b8;
    text-decoration:none;
    font-size:14px;
    background: transparent;
    transition:all .25s ease;
}

/* Custom Scrollbar for Sidebar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}
.sidebar::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
}
.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.25);
}

.menu-item:hover{
    background:rgba(255,255,255,0.06);
    transform:translateX(4px);
    color:white;
}

.active-menu{
    background: #3b82f6 !important;
    color: white !important;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
}
        .main-content{
            margin-left:290px;
            padding:30px;
        }

       .topbar{
    background: #1e293b;
    color:white;
    border-radius:16px;
    padding:16px 24px;
    box-shadow: 0 4px 12px rgba(15,23,42,0.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

        .profile{
            width:50px;
            height:50px;
            border-radius:50%;
            background:#7b61ff;
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:20px;
        }

    </style>
<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<body>

<div class="sidebar d-flex flex-column justify-content-between">
    <div>
        <!-- Header / Logo -->
        <div class="sidebar-header mb-4 pb-3 border-bottom border-white border-opacity-10">
            <h3 class="fw-bold text-white m-0 d-flex align-items-center gap-2">
                <span>🌍</span> GSC RISK
            </h3>
        </div>

        <!-- Menu Navigasi -->
        <div class="nav flex-column gap-1">
            <a href="/dashboard" class="menu-item {{ request()->is('dashboard') ? 'active-menu' : '' }}">
                📊 Dashboard
            </a>

            <a href="/countries" class="menu-item {{ request()->is('countries*') ? 'active-menu' : '' }}">
                🌍 Dashboard Negara
            </a>

            <a href="/risk-score" class="menu-item {{ request()->is('risk-score') ? 'active-menu' : '' }}">
                ⚠ Risk Score
            </a>

            <a href="/weather-monitoring" class="menu-item {{ request()->is('weather-monitoring') ? 'active-menu' : '' }}">
                🌦 Monitoring Cuaca
            </a>

            <a href="/currency-dashboard" class="menu-item {{ request()->is('currency-dashboard') ? 'active-menu' : '' }}">
                💱 Dashboard Kurs
            </a>

            <a href="/news-intelligence" class="menu-item {{ request()->is('news-intelligence') ? 'active-menu' : '' }}">
                📰 News Intelligence
            </a>

            <a href="/port-dashboard" class="menu-item {{ request()->is('port-dashboard') ? 'active-menu' : '' }}">
                🚢 Dashboard Pelabuhan
            </a>

            <a href="/chart-dashboard" class="menu-item {{ request()->is('chart-dashboard') ? 'active-menu' : '' }}">
                📈 Dashboard Grafik
            </a>

            <a href="/compare-countries" class="menu-item {{ request()->is('compare-countries') ? 'active-menu' : '' }}">
                ⚖ Perbandingan Negara
            </a>

            <a href="/watchlist" class="menu-item {{ request()->is('watchlist') ? 'active-menu' : '' }}">
                ⭐ Favorite Monitoring
            </a>

            <!-- Panel Admin -->
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="/admin/dashboard" class="menu-item {{ request()->is('admin/dashboard') || request()->is('admin*') ? 'active-menu' : '' }}">
                    🛡️ Admin Dashboard
                </a>
                @if(request()->is('admin*'))
                    <div class="ps-3 d-flex flex-column gap-1">
                        <a href="/admin/users" class="menu-item {{ request()->is('admin/users') ? 'active-menu' : '' }}" style="font-size: 13px; margin-bottom: 4px;">
                            👥 Kelola User
                        </a>
                        <a href="/admin/ports" class="menu-item {{ request()->is('admin/ports') ? 'active-menu' : '' }}" style="font-size: 13px; margin-bottom: 4px;">
                            🚢 Kelola Pelabuhan
                        </a>
                        <a href="/admin/articles" class="menu-item {{ request()->is('admin/articles') ? 'active-menu' : '' }}" style="font-size: 13px; margin-bottom: 4px;">
                            📝 Kelola Artikel
                        </a>
                    </div>
                @endif
            @endif

        </div>
    </div>

    <!-- Tombol Logout -->
    <div class="mt-4">
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="menu-item w-100 text-start border-0 bg-transparent text-danger fw-bold d-flex align-items-center gap-3" style="cursor: pointer;">
                <span>🚪</span> Logout
            </button>
        </form>
    </div>
</div>


<div class="main-content">

    <div class="topbar">
        <h5 class="m-0 fw-bold text-white flex-shrink-0" style="font-size: 16px;">
            Global Supply Chain Risk Intelligence
        </h5>

        <div class="d-flex align-items-center flex-wrap gap-4 text-white ms-auto" style="font-size: 13px;">
            <!-- Real-time Clock -->
            <div id="realTimeClock" class="fw-bold opacity-90" style="font-family: 'Courier New', monospace; min-width: 100px;">
                --:--:-- --
            </div>

            <!-- Online Indicator -->
            <div class="d-flex align-items-center gap-1.5 opacity-90">
                <span class="d-inline-block rounded-circle" style="width: 8px; height: 8px; background: #2ecc71; box-shadow: 0 0 8px #2ecc71;"></span>
                <span>Online</span>
            </div>

            <!-- User Info -->
            <div class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-user fs-5 text-info"></i>
                <span class="fw-bold">{{ strtolower(auth()->user() ? auth()->user()->name : 'nasywa alifta') }}</span>
            </div>

            <!-- Logout Button -->
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn border-0 py-1 px-2.5 rounded text-white d-flex align-items-center gap-1" 
                        style="background: #c0392b; font-size: 11px; font-weight: 700; transition: background 0.2s;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <script>
        function startRealTimeClock() {
            const clockEl = document.getElementById('realTimeClock');
            if (!clockEl) return;
            
            function update() {
                const now = new Date();
                let hours = now.getHours();
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                const hoursStr = String(hours).padStart(2, '0');
                clockEl.textContent = `${hoursStr}:${minutes}:${seconds} ${ampm}`;
            }
            update();
            setInterval(update, 1000);
        }
        document.addEventListener('DOMContentLoaded', startRealTimeClock);
    </script>

        @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>


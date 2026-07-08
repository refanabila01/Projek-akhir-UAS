<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <style>

        body{
    background:
    linear-gradient(
        135deg,
        #f5f7ff,
        #eef2ff
    );

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
        #6C63FF 0%,
        #8B7FFF 100%
    );

    color:white;

    box-shadow:
        0 20px 40px rgba(108,99,255,.25);
}

        .logo{
            font-size:30px;
            font-weight:bold;
            color:white;
            margin-bottom:40px;
        }

       .menu-item{
    display:block;
    padding:14px 18px;
    margin-bottom:12px;
    border-radius:16px;
    color:white;
    text-decoration:none;
    transition:.3s;
}

.menu-item:hover{
    background:rgba(255,255,255,.15);
    transform:translateX(8px);
}

.active-menu{
    background:white;
    color:#6C63FF;
    font-weight:600;
}

        .main-content{
            margin-left:290px;
            padding:30px;
        }

       .topbar{
    background:
    linear-gradient(
        135deg,
        #6C63FF,
        #8B7FFF
    );

    color:white;

    border-radius:25px;

    padding:25px 35px;

    box-shadow:
    0 15px 35px rgba(108,99,255,.3);

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

<div class="sidebar">

    <div class="logo">
        🌍 GSC RISK
    </div>

   <a href="/dashboard" class="menu-item">
    🌍 Global Country Dashboard
</a>

<a href="/risk-score" class="menu-item">
    ⚠ Risk Scoring Engine
</a>

<a href="/weather-monitoring" class="menu-item">
    🌦 Global Weather Monitoring
</a>

<a href="/currency-dashboard" class="menu-item">
    💱 Currency Impact Dashboard
</a>

<a href="/news-intelligence" class="menu-item">
    📰 News Intelligence
</a>

<a href="/port-dashboard" class="menu-item">
    🚢 Port Location Dashboard
</a>

<a href="/chart-dashboard" class="menu-item">
    📈 Data Visualization Dashboard
</a>

<a href="/compare-countries" class="menu-item">
    ⚖ Country Comparison Engine
</a>

<a href="/watchlist" class="menu-item">
    ⭐ Favorite Monitoring List
</a>

<a href="/admin-dashboard" class="menu-item">
    👤 Admin Dashboard
</a>

</div> <!-- TUTUP SIDEBAR -->



<div class="main-content">

    <div class="topbar">

        <h4>
            Global Supply Chain Risk Intelligence
        </h4>

        <div class="d-flex align-items-center gap-4">

            <i class="fa-solid fa-bell fs-4"></i>

            <div class="profile">
                A
            </div>

        </div>

    </div>

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html> 


@extends('layouts.master')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">
<div class="card soft-card p-5 rounded-4 mb-4 border-0">

    <h1 class="fw-bold">
        🌍 Global Supply Chain Risk Intelligence
    </h1>

    <p class="text-muted">
        Monitor global risk, weather disruption,
        currency movement, port congestion and
        international events in one dashboard.
    </p>

</div>
<h1 class="fw-bold mb-4">
    Global Supply Chain Risk Dashboard
</h1>

<div class="row">

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4">
            <h6>Global Risk Score</h6>
            <h1 class="text-primary">38</h1>
            <span class="text-warning">Medium Risk</span>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4">
            <h6>High Risk Countries</h6>
            <h1 class="text-danger">12</h1>
            <small class="text-success">+20%</small>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4">
            <h6>Medium Risk Countries</h6>
            <h1 class="text-warning">25</h1>
            <small class="text-danger">-5%</small>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4">
            <h6>Low Risk Countries</h6>
            <h1 class="text-success">63</h1>
            <small class="text-success">+10%</small>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-8">

        <div class="card p-4 shadow border-0 rounded-4 mb-4">
            <h4 class="mb-3">🌍 World Risk Map</h4>

            <div id="worldMap"
                 style="height:400px;border-radius:20px;">
            </div>
        </div>

    </div>

    <div class="col-lg-4">

        <div class="card p-3 shadow border-0 rounded-4 mb-3">
            <h5>⚠ Extreme Weather</h5>
            <p>Cyclone detected in Southeast Asia.</p>
        </div>

        <div class="card p-3 shadow border-0 rounded-4 mb-3">
            <h5>🚢 Port Alert</h5>
            <p>Singapore port congestion increased.</p>
        </div>

        <div class="card p-3 shadow border-0 rounded-4">
            <h5>💰 Currency</h5>
            <p>USD/IDR : 16.250</p>
            <p>EUR/IDR : 18.950</p>
            <p>JPY/IDR : 112</p>
        </div>

    </div>

</div>

<div class="row">

    <div class="col-lg-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4 h-100">
            <h4>💱 Currency Impact Dashboard</h4>

            <table class="table mt-3">
                <tr><td>USD/IDR</td><td>16.250</td></tr>
                <tr><td>EUR/IDR</td><td>18.950</td></tr>
                <tr><td>JPY/IDR</td><td>112</td></tr>
                <tr><td>SGD/IDR</td><td>12.150</td></tr>
            </table>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4 h-100">
            <h4>🌍 Top Risk Countries</h4>

            <table class="table mt-3">
                <tr>
                    <th>Country</th>
                    <th>Risk</th>
                </tr>

                <tr>
                    <td>China</td>
                    <td><span class="badge bg-danger">High</span></td>
                </tr>

                <tr>
                    <td>Russia</td>
                    <td><span class="badge bg-danger">High</span></td>
                </tr>

                <tr>
                    <td>Indonesia</td>
                    <td><span class="badge bg-warning">Medium</span></td>
                </tr>

                <tr>
                    <td>Singapore</td>
                    <td><span class="badge bg-success">Low</span></td>
                </tr>
            </table>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-lg-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4 h-100">
            <h4>🚢 Port Congestion Monitoring</h4>

            <ul class="mt-3">
                <li>Singapore Port : Medium</li>
                <li>Shanghai Port : High</li>
                <li>Rotterdam Port : Low</li>
                <li>Los Angeles Port : Medium</li>
            </ul>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card p-4 shadow border-0 rounded-4 h-100">
            <h4>⚖ Country Comparison</h4>

            <table class="table mt-3">
                <tr>
                    <th>Country</th>
                    <th>GDP</th>
                    <th>Risk</th>
                </tr>

                <tr>
                    <td>Indonesia</td>
                    <td>$1.37T</td>
                    <td>Medium</td>
                </tr>

                <tr>
                    <td>China</td>
                    <td>$17.8T</td>
                    <td>High</td>
                </tr>

                <tr>
                    <td>USA</td>
                    <td>$26T</td>
                    <td>Low</td>
                </tr>
            </table>
        </div>
    </div>

</div>

<div class="card p-4 shadow border-0 rounded-4 mb-4">
    <h4>📰 News Intelligence</h4>

    <ul class="mt-3">
        <li>Shipping delays in Red Sea</li>
        <li>Heavy rain affects Asia logistics</li>
        <li>USD strengthens against Asian currencies</li>
    </ul>
</div>

</div>
<script>
document.addEventListener("DOMContentLoaded", function(){

    var map = L.map('worldMap').setView([20,0],2);

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution:'© OpenStreetMap'
        }
    ).addTo(map);

    L.marker([-6.2,106.8])
        .addTo(map)
        .bindPopup('<b>Indonesia</b><br>Medium Risk');

    L.marker([35.8,104.1])
        .addTo(map)
        .bindPopup('<b>China</b><br>High Risk');

    L.marker([37,-95])
        .addTo(map)
        .bindPopup('<b>USA</b><br>Low Risk');

    L.marker([1.3521,103.8198])
        .addTo(map)
        .bindPopup('<b>Singapore Port</b><br>Port Congestion');
});
</script>
<div class="card p-4 card border-0 rounded-4 mt-4">
    <h4>📈 Global Risk Trend</h4>

    <canvas id="riskChart"></canvas>
</div>
</script>

@endsection
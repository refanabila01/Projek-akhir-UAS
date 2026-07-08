@extends('layouts.master')

@section('title','Data Visualization Dashboard')

@section('content')

<div class="container-fluid">

    <h1 class="fw-bold mb-4">
        📈 Data Visualization Dashboard
    </h1>

    <div class="row">

        <div class="col-md-6 mb-4">
            <div class="card p-4 shadow border-0 rounded-4">
                <h5>Global Risk Trend</h5>
                <canvas id="riskChart"></canvas>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card p-4 shadow border-0 rounded-4">
                <h5>Risk Distribution</h5>
                <canvas id="pieChart"></canvas>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(document.getElementById('riskChart'),{
    type:'line',
    data:{
        labels:['Jan','Feb','Mar','Apr','May','Jun'],
        datasets:[{
            label:'Risk Score',
            data:[22,28,35,31,38,40]
        }]
    }
});

new Chart(document.getElementById('pieChart'),{
    type:'pie',
    data:{
        labels:['High','Medium','Low'],
        datasets:[{
            data:[12,25,63]
        }]
    }
});

</script>

@endsection
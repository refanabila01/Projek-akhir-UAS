@extends('layouts.master')

@section('title','Weather Monitoring')

@section('content')

<h1 class="fw-bold mb-4">
    🌦 Global Weather Monitoring
</h1>

<div class="card p-4 shadow border-0 rounded-4">

<table class="table">

<tr>
    <th>Country</th>
    <th>Temperature</th>
    <th>Condition</th>
    <th>Risk</th>
</tr>

<tr>
    <td>Indonesia</td>
    <td>28°C</td>
    <td>Cloudy</td>
    <td class="text-warning">Medium</td>
</tr>

<tr>
    <td>China</td>
    <td>22°C</td>
    <td>Rain</td>
    <td class="text-danger">High</td>
</tr>

<tr>
    <td>Singapore</td>
    <td>31°C</td>
    <td>Sunny</td>
    <td class="text-success">Low</td>
</tr>

</table>

</div>

@endsection
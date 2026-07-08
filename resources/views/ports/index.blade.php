@extends('layouts.master')

@section('title','Port Dashboard')

@section('content')

<h1 class="fw-bold mb-4">
    🚢 Port Dashboard
</h1>

<div class="card p-4 shadow border-0 rounded-4">

<table class="table">

<tr>
    <th>Port</th>
    <th>Country</th>
    <th>Status</th>
</tr>

<tr>
    <td>Singapore Port</td>
    <td>Singapore</td>
    <td class="text-warning">Medium</td>
</tr>

<tr>
    <td>Shanghai Port</td>
    <td>China</td>
    <td class="text-danger">High</td>
</tr>

<tr>
    <td>Rotterdam Port</td>
    <td>Netherlands</td>
    <td class="text-success">Low</td>
</tr>

</table>

</div>

@endsection
@extends('layouts.master')

@section('title','Currency Dashboard')

@section('content')

<h1 class="fw-bold mb-4">
    💱 Currency Impact Dashboard
</h1>

<div class="card p-4 shadow border-0 rounded-4">

<table class="table">

<tr>
    <th>Currency</th>
    <th>Value</th>
    <th>Change</th>
</tr>

<tr>
    <td>USD/IDR</td>
    <td>16.250</td>
    <td class="text-success">+0.4%</td>
</tr>

<tr>
    <td>EUR/IDR</td>
    <td>18.950</td>
    <td class="text-danger">-0.2%</td>
</tr>

<tr>
    <td>JPY/IDR</td>
    <td>112</td>
    <td class="text-success">+0.1%</td>
</tr>

</table>

</div>

@endsection
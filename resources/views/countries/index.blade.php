@extends('layouts.master')

@section('title','Countries')

@section('content')

<h1 class="mb-4">🌍 Countries Dashboard</h1>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Country</th>
            <th>Capital</th>
            <th>Region</th>
            <th>Population</th>
        </tr>
    </thead>

    <tbody>
        @foreach($countries as $country)
        <tr>
            <td>{{ $country['name']['common'] }}</td>
            <td>{{ $country['capital'][0] ?? '-' }}</td>
            <td>{{ $country['region'] }}</td>
            <td>{{ number_format($country['population']) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
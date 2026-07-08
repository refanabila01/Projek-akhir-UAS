@extends('layouts.master')

@section('title','Compare Countries')

@section('content')

<div class="card-soft p-5">

    <h1>⚖ Perbandingan Negara</h1>

    <table class="table mt-4">
        <tr>
            <th>Country</th>
            <th>Risk</th>
            <th>GDP</th>
        </tr>

        <tr>
            <td>Indonesia</td>
            <td>35</td>
            <td>1.37T</td>
        </tr>

        <tr>
            <td>Singapore</td>
            <td>20</td>
            <td>0.52T</td>
        </tr>
    </table>

</div>

@endsection
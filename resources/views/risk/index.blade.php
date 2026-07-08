@extends('layouts.master')

@section('title','Risk Score')

@section('content')

<div class="container-fluid">

    <h1 class="fw-bold mb-4">
        ⚠ Risk Scoring Engine
    </h1>

    <div class="card shadow border-0 rounded-4 p-4">

        <table class="table table-hover">

            <thead>
                <tr>
                    <th>Country</th>
                    <th>Political Risk</th>
                    <th>Economic Risk</th>
                    <th>Weather Risk</th>
                    <th>Port Risk</th>
                    <th>Total Score</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Indonesia</td>
                    <td>20</td>
                    <td>15</td>
                    <td>25</td>
                    <td>10</td>
                    <td>70</td>
                    <td>
                        <span class="badge bg-warning">
                            Medium
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>China</td>
                    <td>35</td>
                    <td>30</td>
                    <td>20</td>
                    <td>25</td>
                    <td>110</td>
                    <td>
                        <span class="badge bg-danger">
                            High
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>Singapore</td>
                    <td>10</td>
                    <td>10</td>
                    <td>15</td>
                    <td>10</td>
                    <td>45</td>
                    <td>
                        <span class="badge bg-success">
                            Low
                        </span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection
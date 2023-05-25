@extends('layouts.dashboard')
@section('styles')
    @parent
    <style>
        .yes {
            color: green !important;
        }
        .no {
            color: red !important;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Daily accesses</h2>
    </div>

    <canvas class="my-4 w-100" id="myChart" width="900" height="300"></canvas>

    <h2>Last access</h2>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Authorized</th>
                <th scope="col">Access at</th>
            </tr>
            </thead>
            <tbody>
            @forelse($accessAttempts as $access)
                <tr>
                    <td>{{ $access->authorized_face->name ?? "Unknown"}}</td>
                    <td class={{ $access->authorized ? "yes" : "no" }}>{{ $access->authorized ? 'Yes' : 'No' }}</td>
                    <td>{{ $access->attempted_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No access attempts</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{ $accessAttempts->links() }}
    </div>
@endsection


@section('scripts')
    @parent
    <script>
        /* globals Chart:false, feather:false */

        (() => {
            'use strict'

            feather.replace({ 'aria-hidden': 'true' })

            // Graphs
            const ctx = document.getElementById('myChart')
            // eslint-disable-next-line no-unused-vars
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach($accessesPerDay as $date => $count)
                            '{{ $date }}',
                        @endforeach
                    ],
                    datasets: [{
                        data: [
                            @foreach($accessesPerDay as $date => $count)
                                '{{ $count }}',
                            @endforeach
                        ],
                        lineTension: 0,
                        backgroundColor: 'transparent',
                        borderColor: '#007bff',
                        borderWidth: 4,
                        pointBackgroundColor: '#007bff'
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: false
                            }
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
            })
        })();
    </script>
@endsection

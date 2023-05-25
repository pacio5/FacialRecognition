@extends('layouts.dashboard')

@section('content')
    <style>
        .yes {
            color: green !important;
        }
        .no {
            color: red !important;
        }
    </style>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Accesses List</h2>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Authorized</th>
            <th scope="">Access At</th>
        </tr>
        </thead>
        <tbody>
        @forelse($accessAttempts as $access)
            <tr>
                <td>  {{$access->authorized_face->name ?? "Unknown"  }}  </td>
                <td class="{{ $access->authorized ? 'yes' : 'no' }}">{{ $access->authorized ? 'Yes' : 'No' }}</td>
                <td>{{ $access->attempted_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No Authorized Faces</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection

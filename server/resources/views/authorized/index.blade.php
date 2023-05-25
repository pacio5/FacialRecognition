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
        <h2>User's list</h2>
    </div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Authorized</th>
            <th scope="">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($authorizedFaces as $auth)
            <tr>
                <td><img style="max-width:50px;" src="{{ URL::asset('/profile/'.$auth->img_path) }}" alt="{{$auth->name}}" /> {{ $auth->name }}</td>
                <td class={{ $auth->is_authorized ? "yes" : "no" }}>{{ $auth->is_authorized ? 'Yes' : 'No' }}</td>
                <td>
                    <a type="button" href="{{route('authorized.show', $auth->id)}}" class="btn btn-secondary btn-sm">View logs</a>
                    @if(!$auth->is_authorized)
                    <form action="{{ route('authorized.authorize', $auth->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm">Authorize</button>
                    </form>
                    @else
                    <form action="{{ route('authorized.revokeAuthorization', $auth->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-warning btn-sm">Revoke</button>
                    </form>
                    @endif
                    <form action="{{ route('authorized.delete', $auth->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No Authorized Faces</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection

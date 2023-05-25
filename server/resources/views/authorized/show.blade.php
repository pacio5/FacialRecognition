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
        <h2>{{$authorizedFace->name}}'s access</h2>
    </div>
    <div class="row">
    <div class="col-sm-4">
        <img class="img-fluid img-thumbnail" src="{{ URL::asset('/profile/'.$authorizedFace->img_path) }}" alt="{{$authorizedFace->name}}" />
    </div>
    <div class="col-sm-8">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Authorized</th>
                <th scope="col">Access At</th>
            </tr>
            </thead>
            <tbody>
            @forelse($authorizedFace->access_attempts as $access)
                <tr>
                    <td class={{ $access->authorized ? "yes" : "no" }}>{{ $access->authorized ? 'Yes' : 'No' }}</td>
                    <td>{{ $access->attempted_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No Accesses Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    </div>
    <div class="row p-3">
        <div class="col-sm-4">
            @if(!$authorizedFace->is_authorized)
                <form action="{{ route('authorized.authorize', $authorizedFace->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success btn">Authorize</button>
                </form>
            @else
                <form action="{{ route('authorized.revokeAuthorization', $authorizedFace->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning btn">Revoke</button>
                </form>
            @endif
            <form action="{{ route('authorized.delete', $authorizedFace->id) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn">Delete</button>
            </form>
        </div>
    </div>
@endsection

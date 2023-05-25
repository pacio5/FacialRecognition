@extends('layouts.dashboard')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2>Add Face</h2>
        <p class="small">Please note, when adding a new face, images with the format: jpg, jpeg, png are required.
        </p>
    </div>

    <div class="col-12">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{route('authorized.store')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="firstName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="firstName" name="name" placeholder="" value="" required>
                    <div class="invalid-feedback">
                        Valid name is required.
                    </div>
                </div>

                <div class="col-sm-3">
                    <label for="firstName" class="form-label">Authorization</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="authorized" id="flexSwitchCheckChecked">
                        <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch for authorize</label>
                    </div>
                    <div class="invalid-feedback">
                        Value is required.
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Face Image</label>
                        <input class="form-control" type="file" id="formFile" name="face_file">
                    </div>
                    <div class="invalid-feedback">
                        Valid format (jpg, jpeg, png) is required.
                    </div>
                </div>

            </div>

            <hr class="my-4">

            <button class="w-100 btn btn-primary btn-lg" type="submit">Save</button>
        </form>
    </div>
@endsection

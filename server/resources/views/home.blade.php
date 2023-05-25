@extends('layouts.app')

@section('content')
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <h1 class="display-6 fw-normal">Face Recognition</h1>
            <p class="lead fw-normal">Gestión de autorizaciones de acceso al FabLab del Centro Universitario de Mérida.</p>
            <span class="btn btn-danger disabled" href="#">Versión beta</span>
        </div>
        <div class="product-device shadow-sm d-none d-md-block"></div>
        <div class="product-device product-device-2 shadow-sm d-none d-md-block"></div>
    </div>

    <div class="d-md-flex flex-md-equal w-100 my-md-3 ps-md-3">
        <div class="text-bg-dark me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            <div class="my-3 py-3">
                <h2 class="display-5">Reconocimiento facial 2D</h2>
            </div>
            <div class="bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
                <img src="{{ asset('images/demo.jpeg') }}" alt="Demo" class="img-fluid img-thumbnail"/>
            </div>
        </div>
        <div class="bg-light me-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            <div class="my-3 p-3">
                <h2 class="display-5">Codificación del rostro</h2>
            </div>
            <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
                <img src="{{ asset('images/encoding.jpeg') }}" alt="Facial encoding" class="img-fluid img-thumbnail"/>
            </div>
        </div>
    </div>
@endsection

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dashboard para la gestión de autorizaciones de acceso al FabLab del Centro Universitario de Mérida.">
    <meta name="author" content="Elia Pacioni">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel App') }}</title>

    <style>
        /*
         * Custom translucent site header
         */

        .site-header {
            background-color: rgba(0, 0, 0, .85);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            backdrop-filter: saturate(180%) blur(20px);
        }
        .site-header a {
            color: #8e8e8e;
            transition: color .15s ease-in-out;
        }
        .site-header a:hover {
            color: #fff;
            text-decoration: none;
        }

        /*
         * Dummy devices (replace them with your own or something else entirely!)
         */

        .product-device {
            position: absolute;
            right: 10%;
            bottom: -30%;
            width: 300px;
            height: 540px;
            background-color: #333;
            border-radius: 21px;
            transform: rotate(30deg);
        }

        .product-device::before {
            position: absolute;
            top: 10%;
            right: 10px;
            bottom: 10%;
            left: 10px;
            content: "";
            background-color: rgba(255, 255, 255, .1);
            border-radius: 5px;
        }

        .product-device-2 {
            top: -25%;
            right: auto;
            bottom: 0;
            left: 5%;
            background-color: #e5e5e5;
        }


        /*
         * Extra utilities
         */

        .flex-equal > * {
            flex: 1;
        }
        @media (min-width: 768px) {
            .flex-md-equal > * {
                flex: 1;
            }
        }

    </style>
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    @vite('resources/sass/app.scss')

</head>
<body>

<header class="site-header sticky-top py-1">
    <div class="container">
        <div class="row">
            <nav class="col-sm-6 offset-3 d-flex flex-column flex-md-row justify-content-between">
                <a class="py-2" href="#" aria-label="Product">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="d-block mx-auto" role="img" viewBox="0 0 24 24"><title>Product</title><circle cx="12" cy="12" r="10"/><path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"/></svg>
                </a>
                <a class="py-2 d-none d-md-inline-block" href="#">Home</a>
                <a class="py-2 d-none d-md-inline-block" href="#">Progetto</a>
                @guest
                    @if (Route::has('login'))
                        <a class="py-2 d-none d-md-inline-block" href="{{ route('login') }}">{{ __('Login') }}</a>
                    @endif

                    @if (Route::has('register'))
                        <a class="py-2 d-none d-md-inline-block" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                @else
                    <a class="py-2 d-none d-md-inline-block" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>

                    <a class="py-2 d-none d-md-inline-block" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </nav>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="container-fluid py-5">
    <div class="row">
        <div class="col-3">
            <!-- Logo Centro Universitario de Mérida -->
            <img src="{{url('images/cum.png')}}" alt="Centro Universitario de Mérida" class="img-fluid">
        </div>
        <div class="col-6">
            <p>
                Elia Pacioni
            </p>
            <p>
                Asignatura: Mundo Inteligente - Práctica final </br>
                Centro Universitario de Mérida </br>
                Universidad de Extremadura
            </p>
        </div>
        <div class="col-3">
            <!-- Logo Universidad de Extremadura -->
            <img src="{{url('images/logo.png')}}" alt="Centro Universitario de Mérida" class="img-fluid float-sm-end me-sm-3">
        </div>
    </div>
</footer>


@vite('resources/js/app.js')
</body>
</html>

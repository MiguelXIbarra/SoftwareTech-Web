<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Software Tech | Home</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #030712;
            color: #fff;
            overflow-x: hidden;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            scroll-behavior: smooth;
        }

        .navbar {
            background: rgba(3, 7, 18, 0.4) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            transition: all 0.5s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            padding: 20px 0;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.6) !important;
            font-weight: 700 !important;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            margin: 0 12px;
        }

        .navbar-nav .nav-link:hover {
            color: #06b6d4 !important;
            transform: translateY(-2px);
        }

        .btn-portal {
            background: rgba(6, 182, 212, 0.1) !important;
            border: 1px solid rgba(6, 182, 212, 0.4) !important;
            color: #06b6d4 !important;
            padding: 10px 22px !important;
            border-radius: 8px;
            font-weight: 700 !important;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            display: inline-block;
            white-space: nowrap;
        }

        .btn-portal:hover {
            background: rgba(6, 182, 212, 0.2) !important;
            border-color: rgba(6, 182, 212, 0.8) !important;
            color: #00d4ff !important;
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
            transform: translateY(-2px);
        }

        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #030712;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #8a2be2, #00d4ff);
            border-radius: 5px;
            border: 2px solid #030712;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #a044ff, #33e0ff);
        }
    </style>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('images/SoftwareTechnologies_Logotipo01.png') }}" class="logo-circular me-3"
                        alt="Logo" style="height: 48px; width: 48px; border-radius: 50%;">
                    <span class="text-white fw-bold" style="letter-spacing: 2px;">SOFTWARE TECH</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="#servicios">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tecnologia">Tecnología</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#proceso">Proceso</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contacto">Contacto</a>
                        </li>
                        <li class="nav-item ms-3">
                            <a href="/login" class="btn-portal">Portal Clientes</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Software Tech | Sistema</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #030712 !important;
            color: #fff;
            overflow-x: hidden;
            font-family: 'Nunito', sans-serif;
            margin: 0;
            scroll-behavior: smooth;
        }

        #app {
            background-color: #030712 !important;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
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

        .btn-logout {
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #ef4444;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);
        }

        .admin-viewport {
            background: #030712 !important;
            min-height: calc(100vh - 75px);
            color: #ffffff;
            position: relative;
            padding: 80px 20px;
        }

        .admin-viewport::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 30%, rgba(6, 182, 212, 0.05) 0%, transparent 60%);
            z-index: 1;
            pointer-events: none;
        }

        .kanban-card {
            background: rgba(255, 255, 255, 0.02) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .prio-border-medio {
            border: 1px solid rgba(6, 182, 212, 0.15) !important;
        }

        .filter-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.6);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .filter-btn:hover, .filter-btn.active {
            color: #fff;
            background: rgba(6, 182, 212, 0.15);
            border-color: #06b6d4;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.02) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #fff !important;
            font-family: monospace;
            border-radius: 8px;
            padding: 10px;
            box-shadow: none !important;
        }

        /* Corregir el fondo de las opciones del select cuando se despliegan */
        .form-select option {
            background: #030712 !important;
            color: #fff !important;
        }

        /* Mantener consistencia en el texto del placeholder deshabilitado */
        .form-select option[value=""] {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.3) !important;
            outline: none;
            background: rgba(255, 255, 255, 0.02) !important;
        }

        /* Corregir el icono del calendario nativo en inputs de fecha */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.4;
            cursor: pointer;
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
    </style>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div id="app">
        @if(\Illuminate\Support\Str::contains(request()->url(), 'console'))
            <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark">
                <div class="container d-flex justify-content-between align-items-center">
                    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                        <img src="{{ asset('images/Software-Technologies_Isotipo-SinFondo.png') }}" class="logo-circular me-3" alt="Logo" style="height: 50px; width: 65px; border-radius: 50%;">
                        <span class="text-white fw-bold" style="letter-spacing: 2px;">SOFTWARE TECH</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNavAdmin">
                        <ul class="navbar-nav ms-auto align-items-center mb-0">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            </td>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.proyectos.index') ? 'active' : '' }}" href="{{ route('admin.proyectos.index') }}">Proyectos</a>
                            </td>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.clientes.index') ? 'active' : '' }}" href="{{ route('admin.clientes.index') }}">Clientes</a>
                            </td>
                            <li class="nav-item ms-lg-4 mt-2 mt-lg-0">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-logout">Salir</button>
                                </form>
                            </td>
                        </ul>
                    </div>
                </div>
            </nav>
        @else
            <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark">
                <div class="container d-flex justify-content-between align-items-center">
                    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                        <img src="{{ asset('images/Software-Technologies_Isotipo-SinFondo.png') }}" class="logo-circular me-3" alt="Logo" style="height: 50px; width: 65px; border-radius: 50%;">
                        <span class="text-white fw-bold" style="letter-spacing: 2px;">SOFTWARE TECH</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavPublic">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNavPublic">
                        <ul class="navbar-nav ms-auto align-items-center mb-0">
                            <li class="nav-item">
                                <a class="nav-link" href="#servicios">Servicios</a>
                            </td>
                            <li class="nav-item">
                                <a class="nav-link" href="#tecnologia">Tecnología</a>
                            </td>
                            <li class="nav-item">
                                <a class="nav-link" href="#proceso">Proceso</a>
                            </td>
                            <li class="nav-item">
                                <a class="nav-link" href="#contacto">Contacto</a>
                            </td>
                            <li class="nav-item ms-3">
                                <a href="/login" class="btn-portal">Portal Clientes</a>
                            </td>
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <main style="flex: 1; background-color: #030712 !important;">
            @yield('content')
        </main>
    </div>
</body>

</html>

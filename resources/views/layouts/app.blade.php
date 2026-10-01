<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags Principales --}}
    <title>@yield('title', 'Software Tech | Arquitecturas Web, Ciberseguridad & Ecosistemas Digitales')</title>
    <meta name="description" content="@yield('meta_description', 'Desarrollo de software a medida, arquitecturas web escalables, auditoría de ciberseguridad y automatización digital corporativa.')">
    <meta name="keywords" content="desarrollo de software, arquitecturas web, ciberseguridad SAST, aplicaciones móviles, automatización, cloud, consultoría tecnológica, Software Tech">
    <meta name="author" content="Software Technologies">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#030712">

    {{-- Favicon Corporativo (Ultra-visible vector SVG & PNG Fallback) --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=4">
    <link rel="alternate icon" type="image/png" href="{{ asset('images/Software-Technologies_Isotipo-SinFondo.png') }}?v=4">
    <link rel="apple-touch-icon" href="{{ asset('images/Software-Technologies_Isotipo-SinFondo.png') }}?v=4">

    {{-- Open Graph / Tarjetas para WhatsApp, Facebook, LinkedIn, Slack --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Software Tech | Innovación en Ingeniería de Software')">
    <meta property="og:description" content="@yield('og_description', 'Construimos infraestructuras web robustas, ciberseguridad SAST y soluciones digitales a la medida de tu corporación.')">
    <meta property="og:image" content="{{ asset('images/Software-Technologies_Isologo.png') }}">
    <meta property="og:site_name" content="Software Tech">
    <meta property="og:locale" content="es_ES">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Software Tech | Innovación en Ingeniería de Software')">
    <meta name="twitter:description" content="@yield('og_description', 'Construimos infraestructuras web robustas, ciberseguridad SAST y soluciones digitales a la medida.')">
    <meta name="twitter:image" content="{{ asset('images/Software-Technologies_Isologo.png') }}">

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
            padding: 120px 20px 60px 20px;
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

        .form-select option {
            background: #030712 !important;
            color: #fff !important;
        }

        .form-select option[value=""] {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.3) !important;
            outline: none;
            background: rgba(255, 255, 255, 0.02) !important;
        }

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

        /* =========================================================
           GLOBAL CYBER ALERT SYSTEM (SOFTWARE TECH BRANDING)
        ========================================================= */
        .cyber-alert-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(3, 7, 18, 0.75);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            z-index: 99999999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            box-sizing: border-box;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .cyber-alert-backdrop.show {
            opacity: 1;
            visibility: visible;
        }

        .cyber-alert-card {
            background: rgba(11, 15, 25, 0.98);
            border: 1px solid rgba(6, 182, 212, 0.4);
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9), 0 0 35px rgba(6, 182, 212, 0.25);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            transform: scale(0.92) translateY(-20px);
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .cyber-alert-backdrop.show .cyber-alert-card {
            transform: scale(1) translateY(0);
        }

        .cyber-alert-progress-track {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }

        .cyber-alert-progress-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #06b6d4, #8a2be2);
            transition: width 0.05s linear;
        }

        .cyber-alert-body {
            padding: 26px 26px 16px 26px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            position: relative;
        }

        .cyber-alert-icon-wrap {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            background: rgba(6, 182, 212, 0.12);
            color: #00d4ff;
            border: 1px solid rgba(6, 182, 212, 0.35);
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.25);
        }

        .cyber-alert-text-wrap {
            flex: 1;
            min-width: 0;
        }

        .cyber-alert-badge {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #06b6d4;
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.25);
            padding: 2px 8px;
            border-radius: 6px;
        }

        .cyber-alert-timer-text {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.45);
            font-family: monospace;
            font-weight: 600;
        }

        .cyber-alert-title {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 800;
            margin: 6px 0 4px 0;
            letter-spacing: -0.3px;
            line-height: 1.3;
        }

        .cyber-alert-message {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.88rem;
            line-height: 1.5;
            margin: 0;
            word-break: break-word;
            white-space: pre-line;
        }

        .cyber-alert-close-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .cyber-alert-close-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            color: #ffffff;
        }

        .cyber-alert-footer {
            padding: 12px 26px 22px 26px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
        }

        .cyber-alert-confirm-btn {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 10px 26px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 4px 18px rgba(6, 182, 212, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.3px;
        }

        .cyber-alert-confirm-btn:hover {
            box-shadow: 0 0 25px rgba(6, 182, 212, 0.65);
            transform: translateY(-1px);
        }

        /* Color Variations */
        .cyber-alert-card.type-success {
            border-color: rgba(16, 185, 129, 0.45);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9), 0 0 35px rgba(16, 185, 129, 0.25);
        }
        .cyber-alert-card.type-success .cyber-alert-progress-bar {
            background: linear-gradient(90deg, #10b981, #06b6d4);
        }
        .cyber-alert-card.type-success .cyber-alert-icon-wrap {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.4);
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);
        }
        .cyber-alert-card.type-success .cyber-alert-badge {
            color: #34d399;
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
        }
        .cyber-alert-card.type-success .cyber-alert-confirm-btn {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
            box-shadow: 0 4px 18px rgba(16, 185, 129, 0.35);
        }

        .cyber-alert-card.type-error {
            border-color: rgba(239, 68, 68, 0.45);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9), 0 0 35px rgba(239, 68, 68, 0.25);
        }
        .cyber-alert-card.type-error .cyber-alert-progress-bar {
            background: linear-gradient(90deg, #ef4444, #f43f5e);
        }
        .cyber-alert-card.type-error .cyber-alert-icon-wrap {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.4);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.3);
        }
        .cyber-alert-card.type-error .cyber-alert-badge {
            color: #f87171;
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
        }
        .cyber-alert-card.type-error .cyber-alert-confirm-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 18px rgba(239, 68, 68, 0.35);
        }

        .cyber-alert-card.type-info {
            border-color: rgba(167, 139, 250, 0.45);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.9), 0 0 35px rgba(123, 104, 238, 0.25);
        }
        .cyber-alert-card.type-info .cyber-alert-progress-bar {
            background: linear-gradient(90deg, #8b5cf6, #c084fc);
        }
        .cyber-alert-card.type-info .cyber-alert-icon-wrap {
            background: rgba(139, 92, 246, 0.12);
            color: #c4b5fd;
            border-color: rgba(167, 139, 250, 0.4);
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.3);
        }
        .cyber-alert-card.type-info .cyber-alert-badge {
            color: #c4b5fd;
            background: rgba(139, 92, 246, 0.1);
            border-color: rgba(167, 139, 250, 0.3);
        }
        .cyber-alert-card.type-info .cyber-alert-confirm-btn {
            background: linear-gradient(135deg, #7b68ee 0%, #a855f7 100%);
            box-shadow: 0 4px 18px rgba(123, 104, 238, 0.35);
        }
    </style>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div id="app">
        @if(\Illuminate\Support\Str::contains(request()->url(), 'console') || request()->is('console*'))
            <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark">
                <div class="container d-flex justify-content-between align-items-center">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('images/Software-Technologies_Isotipo-SinFondo.png') }}" class="logo-circular me-3" alt="Logo" style="height: 50px; width: 65px; border-radius: 50%;">
                        <span class="text-white fw-bold" style="letter-spacing: 2px;">SOFTWARE TECH</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNavAdmin">
                        <ul class="navbar-nav ms-auto align-items-center mb-0">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-chart-line me-1 opacity-75"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.proyectos.index') ? 'active' : '' }}" href="{{ route('admin.proyectos.index') }}">
                                    <i class="fas fa-cubes me-1 opacity-75"></i> Proyectos
                                </a>
                            </li>

                            @if(auth()->check() && (auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.clientes.index') ? 'active' : '' }}" href="{{ route('admin.clientes.index') }}">
                                        <i class="fas fa-users me-1 opacity-75"></i> Clientes
                                    </a>
                                </li>
                            @endif

                            @if(auth()->check() && auth()->user()->role === 'superadmin')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.equipo*') ? 'active' : '' }}" href="{{ route('admin.equipo') }}">
                                        <i class="fas fa-user-shield me-1 opacity-75"></i> Equipo
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.configuracion*') ? 'active' : '' }}" href="{{ route('admin.configuracion') }}">
                                    <i class="fas fa-sliders-h me-1 opacity-75"></i> Configuración
                                </a>
                            </li>

                            <li class="nav-item ms-lg-4 mt-2 mt-lg-0">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-logout">
                                        <i class="fas fa-power-off me-1"></i> Salir
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        @elseif(\Illuminate\Support\Str::contains(request()->url(), 'portal') || request()->is('portal*') || (auth()->check() && auth()->user()->role === 'cliente'))
            <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark">
                <div class="container d-flex justify-content-between align-items-center">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('portal.dashboard') }}">
                        <img src="{{ asset('images/Software-Technologies_Isotipo-SinFondo.png') }}" class="logo-circular me-3" alt="Logo" style="height: 50px; width: 65px; border-radius: 50%;">
                        <span class="text-white fw-bold" style="letter-spacing: 2px;">SOFTWARE TECH</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavPortal">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNavPortal">
                        <ul class="navbar-nav ms-auto align-items-center mb-0">
                            <li class="nav-item">
                                <a class="nav-link {{ (request()->routeIs('portal.dashboard') || request()->routeIs('portal.proyecto')) ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
                                    <i class="fas fa-cubes me-1 opacity-75"></i> Proyectos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('portal.configuracion*') ? 'active' : '' }}" href="{{ route('portal.configuracion') }}">
                                    <i class="fas fa-sliders-h me-1 opacity-75"></i> Configuración
                                </a>
                            </li>
                            <li class="nav-item ms-lg-4 mt-2 mt-lg-0">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-logout">
                                        <i class="fas fa-power-off me-1"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
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
                                <a class="nav-link" href="#servicios">
                                    <i class="fas fa-layer-group me-1 opacity-75"></i> Servicios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tecnologia">
                                    <i class="fas fa-microchip me-1 opacity-75"></i> Tecnología
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#proceso">
                                    <i class="fas fa-tasks me-1 opacity-75"></i> Proceso
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#contacto">
                                    <i class="fas fa-envelope me-1 opacity-75"></i> Contacto
                                </a>
                            </li>
                            <li class="nav-item ms-3">
                                <a href="/login" class="btn-portal">
                                    <i class="fas fa-user-circle me-1"></i> Portal Clientes
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <main style="flex: 1; background-color: #030712 !important;">
            @yield('content')
        </main>
    </div>

    <!-- GLOBAL CYBER ALERT MODAL -->
    <div id="cyberAlertModal" class="cyber-alert-backdrop" onclick="handleClickBackdropCyberAlert(event)">
        <div class="cyber-alert-card" onclick="event.stopPropagation()">
            <!-- Progress bar (Auto-dismiss countdown) -->
            <div class="cyber-alert-progress-track">
                <div id="cyberAlertProgressBar" class="cyber-alert-progress-bar"></div>
            </div>
            
            <div class="cyber-alert-body">
                <!-- Icon Wrap -->
                <div id="cyberAlertIconContainer" class="cyber-alert-icon-wrap">
                    <i id="cyberAlertIcon" class="fas fa-check-circle"></i>
                </div>
                
                <!-- Text Area -->
                <div class="cyber-alert-text-wrap">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span id="cyberAlertBadge" class="cyber-alert-badge">NOTIFICACIÓN</span>
                        <span id="cyberAlertTimerLabel" class="cyber-alert-timer-text">4s</span>
                    </div>
                    <h4 id="cyberAlertTitle" class="cyber-alert-title">¡Operación Exitosa!</h4>
                    <p id="cyberAlertMessage" class="cyber-alert-message">Notificación del sistema.</p>
                </div>
                
                <!-- Close Button -->
                <button type="button" class="cyber-alert-close-btn" onclick="closeCyberAlert()" title="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Footer Action Button -->
            <div class="cyber-alert-footer">
                <button type="button" id="cyberAlertConfirmBtn" class="cyber-alert-confirm-btn" onclick="confirmCyberAlert()">
                    <span>Entendido</span>
                    <i class="fas fa-check ms-1" style="font-size: 0.75rem;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- CYBER ALERT JAVASCRIPT ENGINE -->
    <script>
        let cyberAlertProgressInterval = null;
        let cyberAlertCallback = null;
        let cyberAlertRemainingMs = 0;
        let cyberAlertTotalMs = 4500;
        let cyberAlertIsPaused = false;

        window.showCyberAlert = function(options) {
            let msg = '';
            let title = '';
            let type = 'info';
            let duration = 4500;
            let onConfirm = null;

            if (typeof options === 'string') {
                msg = options;
                const lower = msg.toLowerCase();
                if (lower.includes('exitos') || lower.includes('éxito') || lower.includes('copiado') || lower.includes('correctamente') || lower.includes('guardad')) {
                    type = 'success';
                    title = '¡Operación Exitosa!';
                } else if (lower.includes('error') || lower.includes('falló') || lower.includes('fallo') || lower.includes('no se pudo') || lower.includes('problema') || lower.includes('failed')) {
                    type = 'error';
                    title = 'Aviso del Sistema';
                } else if (lower.includes('atención') || lower.includes('advertencia') || lower.includes('aviso')) {
                    type = 'warning';
                    title = 'Atención';
                } else {
                    type = 'info';
                    title = 'Notificación';
                }
            } else if (typeof options === 'object' && options !== null) {
                msg = options.message || options.msg || '';
                title = options.title || '';
                type = options.type || 'info';
                duration = options.duration !== undefined ? options.duration : 4500;
                onConfirm = options.onConfirm || null;

                if (!title) {
                    title = type === 'success' ? '¡Operación Exitosa!' : (type === 'error' ? 'Aviso del Sistema' : 'Notificación');
                }
            }

            cyberAlertCallback = onConfirm;
            cyberAlertTotalMs = duration;
            cyberAlertRemainingMs = duration;
            cyberAlertIsPaused = false;

            const modal = document.getElementById('cyberAlertModal');
            if (!modal) return;

            const card = modal.querySelector('.cyber-alert-card');
            const icon = document.getElementById('cyberAlertIcon');
            const badge = document.getElementById('cyberAlertBadge');
            const titleEl = document.getElementById('cyberAlertTitle');
            const msgEl = document.getElementById('cyberAlertMessage');
            const timerLabel = document.getElementById('cyberAlertTimerLabel');
            const progressBar = document.getElementById('cyberAlertProgressBar');

            // Set type class
            card.className = 'cyber-alert-card type-' + type;

            // Set icon and badge
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
                badge.innerText = 'ÉXITO';
            } else if (type === 'error') {
                icon.className = 'fas fa-exclamation-triangle';
                badge.innerText = 'ATENCIÓN';
            } else if (type === 'warning') {
                icon.className = 'fas fa-exclamation-circle';
                badge.innerText = 'AVISO';
            } else {
                icon.className = 'fas fa-info-circle';
                badge.innerText = 'NOTIFICACIÓN';
            }

            titleEl.innerText = title;
            msgEl.innerText = msg;
            progressBar.style.width = '100%';
            timerLabel.innerText = duration > 0 ? Math.ceil(duration / 1000) + 's' : '';

            // Show modal
            modal.style.display = 'flex';
            void modal.offsetWidth;
            modal.classList.add('show');

            // Clear previous interval
            clearInterval(cyberAlertProgressInterval);

            if (duration > 0) {
                const stepMs = 50;
                cyberAlertProgressInterval = setInterval(() => {
                    if (cyberAlertIsPaused) return;

                    cyberAlertRemainingMs -= stepMs;
                    const pct = Math.max(0, (cyberAlertRemainingMs / cyberAlertTotalMs) * 100);
                    progressBar.style.width = pct + '%';
                    timerLabel.innerText = Math.max(1, Math.ceil(cyberAlertRemainingMs / 1000)) + 's';

                    if (cyberAlertRemainingMs <= 0) {
                        clearInterval(cyberAlertProgressInterval);
                        closeCyberAlert();
                    }
                }, stepMs);
            } else {
                progressBar.style.width = '0%';
                timerLabel.innerText = '';
            }
        };

        window.closeCyberAlert = function() {
            clearInterval(cyberAlertProgressInterval);
            const modal = document.getElementById('cyberAlertModal');
            if (!modal) return;

            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 250);
        };

        window.confirmCyberAlert = function() {
            if (typeof cyberAlertCallback === 'function') {
                cyberAlertCallback();
            }
            closeCyberAlert();
        };

        window.handleClickBackdropCyberAlert = function(e) {
            if (e.target.id === 'cyberAlertModal') {
                closeCyberAlert();
            }
        };

        // Pause countdown on hover
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.cyber-alert-card');
            if (card) {
                card.addEventListener('mouseenter', function() { cyberAlertIsPaused = true; });
                card.addEventListener('mouseleave', function() { cyberAlertIsPaused = false; });
            }
        });

        // Global Alert Helper Objects
        window.cyberAlert = {
            success: function(msg, title, duration, onConfirm) {
                window.showCyberAlert({ message: msg, title: title || '¡Operación Exitosa!', type: 'success', duration: duration || 4500, onConfirm });
            },
            error: function(msg, title, duration, onConfirm) {
                window.showCyberAlert({ message: msg, title: title || 'Aviso del Sistema', type: 'error', duration: duration || 6000, onConfirm });
            },
            info: function(msg, title, duration, onConfirm) {
                window.showCyberAlert({ message: msg, title: title || 'Notificación', type: 'info', duration: duration || 4500, onConfirm });
            },
            warning: function(msg, title, duration, onConfirm) {
                window.showCyberAlert({ message: msg, title: title || 'Atención', type: 'warning', duration: duration || 5000, onConfirm });
            }
        };

        // Automatically replace window.alert with Cyber Alert across the application
        window.alert = function(message) {
            window.showCyberAlert(message);
        };
    </script>
</body>

</html>

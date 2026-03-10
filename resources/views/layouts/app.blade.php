<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Software Tech | Innovation Lab</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* ESTILO "CASA MODERNA" - DARK MINIMALISM */
        body {
            background-color: #000;
            /* */
            color: #fff;
            overflow-x: hidden;
            font-family: 'Nunito', sans-serif;
            margin: 0;
        }

        /* NAVBAR: CRISTAL AHUMADO */
        .navbar {
            background: rgba(0, 0, 0, 0.2) !important;
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

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.9) !important;
            padding: 12px 0;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.6) !important;
            font-weight: 700 !important;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            font-size: 0.8rem;
        }

        /* ÍCONO DE PERFIL: MINIMALISTA Y ELEGANTE */
        .profile-wrapper {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .profile-wrapper i {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            /* Brillo sutil */
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.2));
        }

        .profile-wrapper:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* DROPDOWN: SMOKED GLASS MODERNO (ANCHO Y OSCURO) */
        .dropdown-menu {
            min-width: 320px !important;
            /* Más ancho y espacioso */
            background: rgba(5, 5, 5, 0.85) !important;
            /* Negro ahumado transparente */
            backdrop-filter: blur(45px) saturate(180%);
            -webkit-backdrop-filter: blur(45px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 24px !important;
            padding: 25px !important;
            margin-top: 25px !important;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.7);
            right: 0 !important;
            left: auto !important;
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.5) !important;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 14px 22px;
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .dropdown-item i {
            font-size: 1.1rem;
            margin-right: 15px;
            opacity: 0.4;
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.05) !important;
            color: #fff !important;
            transform: translateX(10px);
        }

        .dropdown-item:hover i {
            opacity: 1;
            color: #fff;
        }

        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            margin: 20px 0;
        }

        /* CARRUSEL FULL SCREEN */
        .carousel,
        .carousel-inner,
        .carousel-item {
            height: 100vh !important;
            width: 100% !important;
        }

        .carousel-item img {
            height: 100vh !important;
            width: 100% !important;
            object-fit: cover;
        }

        #dna-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.2;
        }

        /* FOOTER CLONADO (ESTILO EXACTO DE LA APP) */
        .main-footer {
            background: #000 !important;
            border-top: 1px solid transparent !important;
            border-image: linear-gradient(to right, transparent, #8a2be2, #00d4ff, #8a2be2, transparent) 1 !important;

            width: 100% !important;
            padding: 20px 30px !important;
            margin-top: 0 !important;
            text-align: center !important;
            color: rgba(200, 200, 200, 0.8) !important;
            font-family: 'Nunito', sans-serif !important;
            font-size: 0.85rem !important;
            letter-spacing: 1px;
        }

        .main-footer strong,
        .main-footer a {
            color: #f8f9fa !important;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 800 !important;
            text-decoration: none !important;
        }

        /* Color específico para el enlace como en la app */
        .main-footer a {
            color: #00d4ff !important;
        }
    </style>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div id="dna-canvas"></div>
    <div id="app">
        @if(!request()->is('*pass') && !request()->is('*reset*'))
        <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/home') }}">
                    <img src="{{ asset('images/SoftwareTechnologies_Logotipo01.png') }}" class="logo-circular me-3"
                        alt="Logo" style="height: 48px; width: 48px; border-radius: 50%;">
                    <span class="text-white fw-bold" style="letter-spacing: 2px;">SOFTWARE <span
                            style="opacity: 0.3;">TECH</span></span>
                </a>

                <div class="collapse navbar-collapse" id="navC">
                    <ul class="navbar-nav ms-auto align-items-center">
                        {{-- MÓDULOS OPERATIVOS: Solo se ven si está logueado --}}
                        @auth
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('users.index') }}">Usuarios</a>
                        </li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('projects.index') }}">Proyectos</a>
                        </li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('milestones.index') }}">Hitos</a>
                        </li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('payments.index') }}">Pagos</a>
                        </li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('messages.index') }}">Mensajes</a>
                        </li>
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('lab_posts.index') }}">Blog</a>
                        </li>
                        @endauth

                        {{-- BOTÓN DE PERFIL: Siempre visible con la misma estructura --}}
                        <li class="nav-item dropdown ms-4">
                            <a id="navbarDropdown" class="nav-link d-flex align-items-center" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <div class="profile-wrapper">
                                    <i class="fas fa-shapes"></i>
                                </div>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end">
                                @auth
                                {{-- VISTA PARA USUARIOS REGISTRADOS --}}
                                <div class="px-3 pb-4">
                                    <p class="mb-0 fw-bold"
                                        style="color: #fff; font-size: 1.1rem; letter-spacing: 0.5px;">
                                        {{ Auth::user()->name }}
                                    </p>
                                    <p class="mb-0 small"
                                        style="color: rgba(255,255,255,0.3); text-transform: uppercase; font-weight: 700; font-size: 0.7rem;">
                                        Account Manager
                                    </p>
                                </div>
                                <a class="dropdown-item" href="{{ url('/admin/profile') }}">
                                    <i class="fas fa-layer-group"></i> Perfil Personal
                                </a>
                                <a class="dropdown-item" href="{{ url('/admin/settings') }}">
                                    <i class="fas fa-adjust"></i> Configuración
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-power-off"></i> Cerrar Sesión
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf
                                </form>
                                @else
                                {{-- VISTA PARA INVITADOS (SIN CUENTA) --}}
                                <div class="px-3 pb-4">
                                    <p class="mb-0 fw-bold"
                                        style="color: #fff; font-size: 1.1rem; letter-spacing: 0.5px;">
                                        Bienvenido
                                    </p>
                                    <p class="mb-0 small"
                                        style="color: rgba(255,255,255,0.3); text-transform: uppercase; font-weight: 700; font-size: 0.7rem;">
                                        Innovation Lab Visitor
                                    </p>
                                </div>
                                <a class="dropdown-item" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                                </a>
                                <a class="dropdown-item" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus"></i> Registrarse
                                </a>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endif

        <main>
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</body>

</html>
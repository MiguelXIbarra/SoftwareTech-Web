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
        :root {
            --st-purple: #8a2be2;
            --st-blue: #007bff;
            --st-neon-gradient: linear-gradient(135deg, #8a2be2 0%, #007bff 100%);
        }

        body {
            background-color: #000;
            color: #fff;
            overflow-x: hidden;
            font-family: 'Nunito', sans-serif;
            margin: 0;
        }

        /* NAVBAR FIJO, TRANSPARENTE Y OPACO (GLASSMORPHISM) */
        .navbar {
            background: rgba(0, 4, 10, 0.4) !important;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            transition: all 0.4s ease;
            border-bottom: 1px solid rgba(138, 43, 226, 0.15) !important;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999;
            padding: 15px 0;
        }

        .navbar.scrolled {
            background: rgba(0, 4, 10, 0.8) !important;
            padding: 10px 0;
            border-bottom: 1px solid rgba(138, 43, 226, 0.4) !important;
        }

        /* LOGO CIRCULAR LIMPIO SIN NEÓN */
        .logo-circular {
            height: 45px;
            width: 45px;
            object-fit: contain;
            border-radius: 50%;
            border: none !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.75) !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .text-neon-gradient {
            background: var(--st-neon-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .text-white-50,
        .text-muted {
            /* Cambio: Subimos la opacidad para que no se pierdan en el fondo negro */
            color: rgba(255, 255, 255, 0.9) !important;
        }

        #dna-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="dna-canvas"></div>
    <div id="app">
        <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/home') }}">
                    <img src="{{ asset('images/SoftwareTechnologies_Logotipo01.png') }}" class="logo-circular me-2"
                        alt="Logo">
                    <span class="text-white fw-bold">SOFTWARE <span class="text-neon-gradient">TECH</span></span>
                </a>
                <div class="collapse navbar-collapse" id="navC">
                    <ul class="navbar-nav me-auto">
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
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('lab_posts.index') }}">Blog</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
        <main>@yield('content')</main>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("dna-canvas", {
            "particles": {
                "number": { "value": 80 },
                "color": { "value": ["#8a2be2", "#007bff"] },
                "line_linked": { "enable": true, "distance": 150, "color": "#8a2be2", "opacity": 0.3 }
            }
        });
        window.onscroll = function() {
            var nav = document.getElementById('mainNavbar');
            if (window.pageYOffset > 50) { nav.classList.add("scrolled"); } 
            else { nav.classList.remove("scrolled"); }
        };
    </script>
</body>

</html>
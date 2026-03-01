@extends('layouts.app')

@section('content')
<style>
    /* 1. NAVBAR FIJO Y ESTILO CRISTAL OSCURO */
    .navbar {
        position: fixed !important;
        top: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
        background: rgba(0, 4, 10, 0.5) !important;
        backdrop-filter: blur(15px) !important;
        -webkit-backdrop-filter: blur(15px) !important;
        border-bottom: 1px solid rgba(138, 43, 226, 0.2) !important;
        transition: all 0.4s ease;
    }

    /* 2. ANIMACIONES DE REVELACIÓN (REVEAL) */
    .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition: all 1s cubic-bezier(0.15, 0.45, 0.25, 1);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* 3. CONTENEDOR PRINCIPAL Y CARRUSEL */
    .main-terminal {
        background-color: #00040a;
        margin-top: -75px;
        overflow-x: hidden;
    }

    .hero-carousel .carousel-item {
        height: 100vh;
        position: relative;
    }

    .hero-carousel img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        filter: brightness(0.35);
    }

    .hero-vignette {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 60%;
        background: linear-gradient(to bottom, transparent, #00040a);
        z-index: 2;
    }

    /* TEXTOS DEL CARRUSEL */
    .carousel-caption h1 {
        font-size: 5.5rem;
        font-weight: 800;
        color: #ffffff !important;
        text-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        text-transform: uppercase;
        letter-spacing: -2px;
    }

    .hero-carousel .carousel-caption p {
        font-size: 1.2rem;
        font-weight: 800;
        color: #ffffff !important;
        opacity: 1 !important;
        text-shadow: none !important;
        letter-spacing: 1px;
    }

    /* 4. BOTONES LATERALES ULTRA-MINIMALISTAS (DISEÑO MEJORADO) */
    .full-screen-control {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 15% !important;
        /* Área táctil optimizada */
        background: transparent !important;
        border: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        opacity: 0;
        /* Totalmente invisible para no cortar el fondo */
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        /* Transición fluida */
    }

    /* Estilo de la flecha: Delgada, elegante y sin bordes bruscos */
    .full-screen-control .carousel-control-prev-icon,
    .full-screen-control .carousel-control-next-icon {
        width: clamp(3rem, 7vw, 5rem) !important;
        height: clamp(3rem, 7vw, 5rem) !important;
        background-size: 100% 100%;
        /* Filtro para color blanco puro con suavizado */
        filter: invert(1) brightness(2) opacity(0.3);
        transition: filter 0.4s ease, transform 0.4s ease;
    }

    /* EFECTO AL PASAR EL MOUSE: Sin cortes de color sólidos */
    @media (hover: hover) {
        .full-screen-control:hover {
            opacity: 1;
            /* Blur muy suave para dar profundidad sin marcar bordes */
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }

        /* Degradados extremadamente suaves para evitar el efecto de "corte" */
        .carousel-control-prev:hover {
            background: linear-gradient(to right, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 100%) !important;
        }

        .carousel-control-next:hover {
            background: linear-gradient(to left, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0) 100%) !important;
        }

        /* Brillo elegante al activarse */
        .full-screen-control:hover .carousel-control-prev-icon,
        .full-screen-control:hover .carousel-control-next-icon {
            filter: invert(1) brightness(2) opacity(0.9);
            transform: scale(1.1);
        }
    }

    /* Ajuste para que no se vea el "recuadro" de enfoque de Bootstrap */
    .full-screen-control:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    /* 5. OTROS ELEMENTOS (ADN Y PANELES) */
    .dna-gradient {
        background: linear-gradient(135deg, #007bff, #8a2be2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    .icon-feat {
        font-size: 3.2rem;
        margin-bottom: 25px;
        display: inline-block;
        background: linear-gradient(135deg, #007bff, #8a2be2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.015);
        backdrop-filter: blur(25px);
        border: 1px solid rgba(138, 43, 226, 0.2);
        border-radius: 35px;
        padding: 45px;
        width: 420px;
        flex-shrink: 0;
        transition: 0.5s ease;
    }

    .glass-panel:hover {
        border-color: #007bff;
        transform: translateY(-10px);
        box-shadow: 0 0 30px rgba(138, 43, 226, 0.15);
    }

    .operational-ribbon {
        overflow-x: auto;
        padding: 40px 0 100px 0;
        scrollbar-width: none;
    }

    .ribbon-track {
        display: flex;
        gap: 30px;
        padding: 0 10%;
        width: max-content;
    }
</style>

<div class="main-terminal">
    <div id="mainHero" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4500">
        <div class="carousel-inner hero-carousel">
            @php
            $slides = [
            ['img' => 'photo-1451187580459-43490279c0fa', 't' => 'FUTURE TECH'],
            ['img' => 'photo-1550751827-4bd374c3f58b', 't' => 'IA CORE'],
            ['img' => 'photo-1518770660439-4636190af475', 't' => 'HARDWARE DNA'],
            ['img' => 'photo-1555066931-4365d14bab8c', 't' => 'CYBER SECURITY'],
            ['img' => 'photo-1485827404703-89b55fcc595e', 't' => 'INNOVATION HUB']
            ];
            @endphp

            @foreach($slides as $key => $s)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <img src="https://images.unsplash.com/{{$s['img']}}?w=1920" class="d-block w-100"
                    alt="Terminal Background">
                <div class="hero-vignette"></div>
                <div class="carousel-caption text-start" style="left: 10%; bottom: 25%;">
                    <h1 class="reveal active text-white display-2 fw-800">{{$s['t']}}</h1>
                    <p class="h4">Ingeniería de software de alto rendimiento v4.5</p>
                </div>
            </div>
            @endforeach
        </div>

        <button class="carousel-control-prev full-screen-control" type="button" data-bs-target="#mainHero"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next full-screen-control" type="button" data-bs-target="#mainHero"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="container py-5 mt-5 reveal">
        <div class="glass-panel w-100" style="border-left: 5px solid #8a2be2; width: auto; flex-shrink: 1;">
            <h2 class="text-white fw-bold mb-3">SISTEMA INICIADO: <span class="dna-gradient">{{ Auth::user()->name
                    }}</span></h2>
            <p class="text-white opacity-75 lead mb-0">Acceso total concedido al núcleo operativo de Software
                Technologies. Todos los módulos de innovación están listos para ejecución estratégica.</p>
        </div>
    </div>

    <div class="container py-5 reveal">
        <h2 class="text-white fw-bold mb-5 ps-3 border-start border-primary" style="border-width: 6px !important;">
            NUESTROS <span class="dna-gradient">SERVICIOS</span></h2>
        <div class="row g-4">
            @foreach(['Desarrollo Web' => 'laptop-code', 'Inteligencia Artificial' => 'brain', 'App Móvil' =>
            'mobile-alt', 'Videojuegos' => 'gamepad'] as $serv => $icon)
            <div class="col-6 col-md-3">
                <div class="glass-panel w-100 p-4 text-center" style="width: auto;">
                    <i class="fas fa-{{$icon}} icon-feat"></i>
                    <h5 class="text-white fw-bold">{{$serv}}</h5>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="container-fluid px-0 mt-5">
        <h2 class="text-white fw-bold mb-4 ms-md-5 ps-4 reveal">ADN <span class="dna-gradient">ESTRATÉGICO</span></h2>

        <div class="operational-ribbon">
            <div class="ribbon-track">
                <div class="glass-panel">
                    <i class="fas fa-rocket icon-feat"></i>
                    <h3 class="text-white fw-bold">Nuestra <span class="dna-gradient">Misión</span></h3>
                    <p class="text-white opacity-75">Impulsar la evolución digital mediante arquitecturas de software
                        robustas, escalables y seguras que transforman industrias.</p>
                </div>

                <div class="glass-panel">
                    <i class="fas fa-eye icon-feat"></i>
                    <h3 class="text-white fw-bold">Nuestra <span class="dna-gradient">Visión</span></h3>
                    <p class="text-white opacity-75">Convertirnos en el estándar global de innovación tecnológica para
                        el 2030, liderando la próxima frontera digital.</p>
                </div>

                <div class="glass-panel">
                    <i class="fas fa-shield-alt icon-feat"></i>
                    <h3 class="text-white fw-bold">INTEGRIDAD</h3>
                    <p class="text-white opacity-75">Ética digital inquebrantable y transparencia total en cada línea de
                        código desplegada.</p>
                </div>

                <div class="glass-panel">
                    <i class="fas fa-bolt icon-feat"></i>
                    <h3 class="text-white fw-bold">AGILIDAD</h3>
                    <p class="text-white opacity-75">Respuesta inmediata ante entornos disruptivos y adaptación
                        constante a los cambios del mercado global.</p>
                </div>

                <div class="glass-panel">
                    <i class="fas fa-users icon-feat"></i>
                    <h3 class="text-white fw-bold">SINERGIA</h3>
                    <p class="text-white opacity-75">Colaboración de alto nivel entre nuestra ingeniería avanzada y las
                        metas estratégicas de nuestros clientes.</p>
                </div>
            </div>
            <p class="text-center text-white-50 mt-4 small"><i class="fas fa-arrows-alt-h me-2"></i> Desliza
                lateralmente para explorar el ADN</p>
        </div>
    </div>

    <footer class="py-5 text-center border-top border-dark">
        <p class="text-muted small">© 2026 Software Technologies Lab | Terminal Operativa v4.5</p>
    </footer>
</div>

<script>
    // SISTEMA DE ANIMACIÓN (REVEAL) REFORZADO
    function reveal() {
        var reveals = document.querySelectorAll(".reveal");
        for (var i = 0; i < reveals.length; i++) {
            var windowHeight = window.innerHeight;
            var elementTop = reveals[i].getBoundingClientRect().top;
            var elementVisible = 120;
            if (elementTop < windowHeight - elementVisible) {
                reveals[i].classList.add("active");
            }
        }
    }

    // Disparadores de animación
    window.addEventListener("scroll", reveal);
    window.addEventListener("load", reveal);
    
    // Forzamos un disparo inicial para elementos ya visibles
    setTimeout(reveal, 300);
</script>
@endsection
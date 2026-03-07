{{-- resources/views/vendor/adminlte/page.blade.php --}}
@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
@stack('css')
@yield('css')
<style>
    /* 1. ESTRUCTURA BASE Y MOTOR STICKY */
    html,
    body {
        height: 100% !important;
        margin: 0;
        overflow-y: auto !important;
        background: #000 !important;
    }

    /* SCROLLBAR NEÓN PERSONALIZADO */
    ::-webkit-scrollbar {
        width: 8px;
        background-color: #000;
    }

    ::-webkit-scrollbar-track {
        background: #00040a;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #8a2be2, #00d4ff);
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(138, 43, 226, 0.5);
    }

    .wrapper {
        min-height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        background: radial-gradient(circle at top, #000814 0%, #000 100%) !important;
    }

    /* CONTENEDOR DE CONTENIDO (PULMÓN DEL STICKY FOOTER) */
    .content-wrapper {
        flex: 1 0 auto !important;
        display: flex !important;
        flex-direction: column !important;
        background: transparent !important;
        margin-left: 250px !important;
        width: calc(100% - 250px) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding-bottom: 20px !important;
    }

    .content {
        flex: 1 0 auto !important;
    }

    /* 2. INTERFAZ CRISTAL (CARDS & TABLES) */
    .card,
    .card-header,
    .card-footer,
    .post,
    .mailbox-read-info {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
    }

    /* 3. NAVBAR Y PERFIL DINÁMICO */
    body .main-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding-right: 20px !important;
    }

    body .main-header .nav-item.dropdown.user-menu .nav-link {
        display: flex !important;
        align-items: center !important;
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 50px !important;
        padding: 5px 5px 5px 20px !important;
        color: #ffffff !important;
        text-transform: uppercase;
        font-weight: 800;
        font-size: 0.7rem;
        letter-spacing: 2px;
    }

    body .main-header .nav-item.dropdown.user-menu .nav-link::after {
        content: "\f61f";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        background: #000 !important;
        color: #fff !important;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin-left: 15px;
        border: 2px solid transparent !important;
        background-image: linear-gradient(#000, #000), linear-gradient(to right, #8a2be2, #00d4ff) !important;
        background-origin: border-box !important;
        background-clip: content-box, border-box !important;
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.3);
    }

    body .dropdown-menu-lg {
        min-width: 320px !important;
        background: rgba(5, 5, 5, 0.85) !important;
        backdrop-filter: blur(45px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 24px !important;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.7) !important;
    }

    /* 4. SIDEBAR Y ESTADO ACTIVO GRADIENTE */
    .main-sidebar {
        background: rgba(5, 8, 15, 0.92) !important;
        backdrop-filter: blur(40px) saturate(180%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
        font-family: 'Nunito', sans-serif !important;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .sidebar-form,
    .nav-sidebar .form-inline {
        margin: 15px 25px !important;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
        border: 1px solid rgba(138, 43, 226, 0.3) !important;
        height: 32px !important;
    }

    /* PESTAÑA SELECCIONADA CON GRADIENTE NEÓN */
    .nav-sidebar .nav-item .nav-link.active {
        border-left: 4px solid transparent !important;
        border-image: linear-gradient(to bottom, #8a2be2, #00d4ff) 1 !important;
        background: linear-gradient(to right, rgba(138, 43, 226, 0.15), rgba(0, 212, 255, 0.05), transparent) !important;
        color: #fff !important;
        font-weight: 700 !important;
        box-shadow: inset 10px 0 20px -10px rgba(138, 43, 226, 0.3);
    }

    .nav-sidebar .nav-item .nav-link.active i {
        color: #00d4ff !important;
        filter: drop-shadow(0 0 8px rgba(0, 212, 255, 0.8)) !important;
        opacity: 1 !important;
    }

    /* 5. TABLAS (LIMPIEZA Y HOVER) */
    .table td,
    .table th {
        border-top: 1px solid rgba(255, 255, 255, 0.03) !important;
        color: #fff !important;
        vertical-align: middle !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }

    /* 6. FOOTER STICKY (TEXTO BLANCO + MARCA CIAN) */
    .main-footer {
        flex-shrink: 0 !important;
        background: #000 !important;
        border-top: 1px solid transparent !important;
        border-image: linear-gradient(to right, transparent, #8a2be2, #00d4ff, #8a2be2, transparent) 1 !important;
        margin-left: 250px !important;
        width: calc(100% - 250px) !important;
        height: 40px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #ffffff !important;
        font-size: 0.8rem !important;
        line-height: 1 !important;
        margin-top: 25px !important;
        transition: all 0.3s ease;
    }

    .main-footer a {
        color: #00d4ff !important;
        font-weight: 800 !important;
        text-shadow: 0 0 10px rgba(0, 212, 255, 0.4);
        text-decoration: none !important;
        margin: 0 5px;
    }

    /* 7. AJUSTES DE COLAPSO Y DESPLIEGUE */
    .sidebar-collapse .main-sidebar {
        width: 4.6rem !important;
    }

    .sidebar-open .main-sidebar,
    .sidebar-is-opening .main-sidebar,
    .sidebar-mini.sidebar-collapse .main-sidebar:hover {
        width: 250px !important;
    }

    .sidebar-collapse .content-wrapper,
    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    .sidebar-collapse .sidebar-form {
        display: none !important;
    }

    /* 8. BOTONES ACCIÓN */
    body .content-wrapper .btn-secondary,
    body .content-wrapper .btn-default {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #ffffff !important;
        border: 2px solid #ffffff !important;
        font-weight: 800 !important;
        border-radius: 12px !important;
    }
</style>
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body')
<div class="wrapper">
    @include('adminlte::partials.navbar.navbar')
    @include('adminlte::partials.sidebar.left-sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">@yield('content_header')</div>
        </div>
        <div class="content">
            <div class="container-fluid">@yield('content')</div>
        </div>
    </div>

    <footer class="main-footer">
        <strong>&copy; {{ date('Y') }} <a href="/home" style="color: #00d4ff;">SOFTWARE TECH</a> | INNOVATION LAB |
            V4.5</strong>
    </footer>
</div>
@stop

@section('adminlte_js')
@stack('js') @yield('js')
<script>
    (function() {
            const body = document.body;
            const sidebar = document.querySelector('.main-sidebar');
            const STORAGE_KEY = 'adminlte_sidebar_compact';

            // 1. FUNCIÓN QUE APLICA EL ESTADO DE BANDEJA COMPACTA
            const updateSidebar = (isCompact) => {
                if (isCompact) {
                    body.classList.add('sidebar-collapse');
                    body.classList.remove('sidebar-open');
                } else {
                    body.classList.remove('sidebar-collapse');
                }
            };

            // 2. BLOQUEO DE TEMA (NEGRO)
            const lockTheme = () => {
                if (sidebar) {
                    sidebar.style.setProperty('background-color', '#00040a', 'important');
                    sidebar.classList.add('dark-mode');
                }
                body.classList.add('dark-mode');
            };

            // 3. PERSISTENCIA: Aplicar estado guardado de inmediato al cargar el script
            const savedState = localStorage.getItem(STORAGE_KEY) === 'true';
            updateSidebar(savedState);
            lockTheme();

            // Escuchar cambios visuales del usuario
            document.addEventListener('click', (e) => {
                // Al hacer clic en el botón de hamburguesa tradicional
                if (e.target.closest('[data-widget="pushmenu"]')) {
                    setTimeout(() => {
                        const isNowCollapsed = body.classList.contains('sidebar-collapse');
                        localStorage.setItem(STORAGE_KEY, isNowCollapsed);
                    }, 300);
                }
            });

            // Sincronizar con el switch de la vista (Bandeja de datos compacta)
            document.addEventListener('change', (e) => {
                // Si el usuario mueve el switch de "Bandeja de datos compacta"
                if (e.target.closest('.custom-control-input')) {
                    const active = e.target.checked;
                    localStorage.setItem(STORAGE_KEY, active);
                    updateSidebar(active);
                }
            });

            // 4. INTERVALO DE SEGURIDAD (Refuerzo para cambios de vista)
            let checks = 0;
            const interval = setInterval(() => {
                const currentState = localStorage.getItem(STORAGE_KEY) === 'true';
                updateSidebar(currentState);
                lockTheme();
                if (++checks > 20) clearInterval(interval);
            }, 50);

            window.onload = applyState;
        })();
</script>
@stop
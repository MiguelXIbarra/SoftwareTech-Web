{{-- resources/views/vendor/adminlte/page.blade.php --}}
@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
@stack('css')
@yield('css')
<style>
    /* 1. ESTRUCTURA Y ELIMINACIÓN DE SCROLL */
    html,
    body {
        height: 100% !important;
        margin: 0;
        overflow: hidden !important;
    }

    .wrapper {
        height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        background: radial-gradient(circle at top, #000814 0%, #000 100%) !important;
    }

    .content-wrapper {
        flex: 1 !important;
        overflow-y: auto !important;
        background: transparent !important;
        margin-left: 250px !important;
        width: calc(100% - 250px) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* 2. LIMPIEZA DE INTERFAZ */
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

    /* 3. NAVBAR Y PERFIL (ESTILO HOME - MINIMALISMO TOTAL + SALIDA EN ROJO) */

    /* Limpieza de la barra superior */
    body .main-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding-right: 20px !important;
    }

    /* EL ACTIVADOR (NOMBRE IZQ + CÍRCULO DER CON GRADIENTE) */
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
        transition: 0.4s ease;
    }

    body .main-header .nav-item.dropdown.user-menu .nav-link::after {
        content: "\f61f";
        /* fa-shapes (Diamante) */
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
        font-size: 1rem;
        border: 2px solid transparent !important;
        background-image: linear-gradient(#000, #000),
            linear-gradient(to right, #8a2be2, #00d4ff) !important;
        background-origin: border-box !important;
        background-clip: content-box, border-box !important;
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.3);
    }

    /* PANEL DROPDOWN (TU CÓDIGO DEL HOME: NEGRO AHUMADO + BLUR 45px) */
    body .dropdown-menu-lg {
        min-width: 320px !important;
        background: rgba(5, 5, 5, 0.85) !important;
        backdrop-filter: blur(45px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(45px) saturate(180%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 24px !important;
        padding: 25px !important;
        margin-top: 25px !important;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.7) !important;
        right: 0 !important;
        left: auto !important;
    }

    /* OCULTAR CABECERA Y CUERPO PREDETERMINADO DE ADMINLTE */
    body .user-header,
    body .user-body,
    body .user-footer {
        display: none !important;
    }

    /* ESTILO UNIFICADO DE ÍTEMS (ESTRUCTURA DEL HOME) */
    body .dropdown-item {
        color: rgba(255, 255, 255, 0.5) !important;
        font-size: 0.95rem !important;
        font-weight: 600 !important;
        padding: 14px 22px !important;
        border-radius: 14px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        margin-bottom: 8px !important;
        display: flex !important;
        align-items: center !important;
        background: transparent !important;
        border: none !important;
        width: 100% !important;
        text-align: left !important;
    }

    /* ICONOS (Opacidad 40% que sube al 100% en Hover) */
    body .dropdown-item i {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 1.1rem !important;
        margin-right: 15px !important;
        opacity: 0.4 !important;
        color: #fff !important;
        transition: 0.3s ease;
    }

    /* HOVER GENERAL (Salto 10px + Brillo Blanco) */
    body .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
        transform: translateX(10px) !important;
        text-decoration: none !important;
    }

    body .dropdown-item:hover i {
        opacity: 1 !important;
    }

    /* CONFIGURACIÓN ESPECÍFICA PARA "FINALIZAR SESIÓN" (EN ROJO) */
    body .dropdown-item[href*="logout"] {
        color: rgba(255, 50, 50, 0.6) !important;
        /* Rojo tenue inicial */
        border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
        margin-top: 15px !important;
        padding-top: 20px !important;
    }

    /* Icono de encendido en Rojo para Logout */
    body .dropdown-item[href*="logout"] i {
        color: #ff3232 !important;
        /* Rojo vibrante */
        opacity: 0.5 !important;
    }

    /* Hover específico para Logout */
    body .dropdown-item[href*="logout"]:hover {
        background: rgba(255, 50, 50, 0.08) !important;
        /* Brillo rojizo */
        color: #ff4d4d !important;
        /* Rojo brillante al tocar */
    }

    body .dropdown-item[href*="logout"]:hover i {
        opacity: 1 !important;
        text-shadow: 0 0 10px rgba(255, 50, 50, 0.5);
    }

    /* 4. SIDEBAR (NUNITO - CENTRADO Y COLORES INNOVATION LAB) */

    .main-sidebar {
        background: rgba(5, 8, 15, 0.92) !important;
        backdrop-filter: blur(40px) saturate(180%) !important;
        -webkit-backdrop-filter: blur(40px) saturate(180%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
        font-family: 'Nunito', sans-serif !important;
        height: 100vh !important;
    }

    /* ELIMINACIÓN DE DOBLE BUSCADOR */
    .sidebar-search-results,
    .nav-sidebar>.nav-item:first-child .form-inline+.form-inline {
        display: none !important;
    }

    /* BUSCADOR: CÁPSULA MINI, CENTRADA Y CON LUZ */
    .sidebar-form,
    .nav-sidebar .form-inline {
        /* Aumentamos el margen lateral a 25px para forzar que sea pequeño y esté centrado */
        margin: 15px 25px !important;
        display: flex !important;
        flex-wrap: nowrap !important;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
        /* Borde con toque morado sutil */
        border: 1px solid rgba(138, 43, 226, 0.3) !important;
        padding: 0 !important;
        overflow: hidden !important;
        height: 32px !important;
        transition: all 0.3s ease;
    }

    /* Efecto al enfocar: Iluminación Cian */
    .sidebar-form:focus-within {
        border-color: rgba(0, 212, 255, 0.5) !important;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
    }

    .form-control-sidebar {
        background: transparent !important;
        border: none !important;
        color: #fff !important;
        font-family: 'Nunito', sans-serif !important;
        height: 100% !important;
        padding: 0 5px 0 15px !important;
        font-size: 0.75rem !important;
        flex: 1 !important;
        outline: none !important;
    }

    /* BOTÓN LUPA: Color Cian para dar vida */
    .btn-sidebar {
        background: transparent !important;
        border: none !important;
        width: 35px !important;
        height: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
    }

    .btn-sidebar i {
        font-size: 0.8rem !important;
        color: #00d4ff !important;
        /* Cian Eléctrico */
        opacity: 0.8;
        position: static !important;
        transform: none !important;
    }

    /* LINKS DEL MENÚ: COMPACTOS Y VIVIDOS */
    .nav-sidebar .nav-item {
        padding: 0 10px !important;
        margin-bottom: 2px !important;
    }

    .nav-sidebar .nav-link {
        border-radius: 10px !important;
        color: rgba(255, 255, 255, 0.45) !important;
        font-family: 'Nunito', sans-serif !important;
        font-size: 0.8rem !important;
        padding: 8px 12px !important;
        transition: 0.3s ease;
    }

    /* ESTADO ACTIVO: Línea de degradado Morado-Cian */
    .nav-sidebar .nav-item .nav-link.active {
        border-left: 3px solid transparent !important;
        /* Degradado que combina con el footer */
        border-image: linear-gradient(to bottom, #8a2be2, #00d4ff) 1 !important;
        background: linear-gradient(to right, rgba(138, 43, 226, 0.1), transparent) !important;
        color: #fff !important;
    }

    .nav-sidebar .nav-link:hover i,
    .nav-sidebar .nav-link.active i {
        color: #00d4ff !important;
        opacity: 1 !important;
        filter: drop-shadow(0 0 5px rgba(0, 212, 255, 0.5));
    }

    /* 5. TABLAS Y BOTONES ACCIÓN */
    .table td .btn {
        background: rgba(255, 255, 255, 0.03) !important;
        color: rgba(255, 255, 255, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        transition: 0.3s ease !important;
    }

    .table td .btn:hover {
        transform: translateY(-4px) scale(1.1) !important;
        color: #ffffff !important;
        border-color: #ffffff !important;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
    }

    /* AJUSTE DE TABLAS: RESPONSIVIDAD INVISIBLE */
    .table-responsive {
        border: none !important;
        overflow-x: auto !important;
        scrollbar-width: none !important;
        /* Firefox */
        -ms-overflow-style: none !important;
        /* IE/Edge */
    }

    /* Ocultar barra de scroll en Chrome/Safari pero permitir scroll */
    .table-responsive::-webkit-scrollbar {
        display: none !important;
    }

    table.table {
        width: 100% !important;
        margin-bottom: 0 !important;
        background: transparent !important;
        border-collapse: separate !important;
        border-spacing: 0 8px !important;
        /* Espaciado entre filas para airear el diseño */
    }

    /* Evitar que las celdas se deformen bruscamente */
    .table td,
    .table th {
        white-space: nowrap !important;
        /* Mantiene el texto en una línea */
        padding: 15px 20px !important;
        vertical-align: middle !important;
        border: none !important;
    }

    /* Evitamos el color de fondo fuerte al pasar el cursor */
    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
        /* Brillo apenas perceptible */
        color: #fff !important;
        transition: background-color 0.5s ease;
    }

    /* Quitamos cualquier borde o resalte tosco */
    table.dataTable,
    .table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        background: transparent !important;
    }

    .table td,
    .table th {
        border-top: 1px solid rgba(255, 255, 255, 0.03) !important;
        /* Divisor muy tenue */
        padding: 15px 20px !important;
        background: transparent !important;
        transition: all 0.3s ease;
    }

    /* Responsividad sin barras de scroll molestas */
    .table-responsive {
        border: none !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    .table-responsive::-webkit-scrollbar {
        display: none !important;
    }

    /* 6. BOTONES REGRESAR */
    body .content-wrapper .btn-secondary,
    body .content-wrapper .btn-default {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #ffffff !important;
        border: 2px solid #ffffff !important;
        font-weight: 800 !important;
        border-radius: 12px !important;
    }

    /* 7. FOOTER DE LA APP - NOMBRE EN CIAN */
    .main-footer {
        background: #000 !important;
        border-top: 1px solid transparent !important;
        border-image: linear-gradient(to right, transparent, #8a2be2, #00d4ff, #8a2be2, transparent) 1 !important;

        /* Mantenemos el margin-left para el sidebar de la app */
        margin-left: 250px !important;
        width: calc(100% - 250px) !important;
        padding: 20px 30px !important;

        text-align: center !important;
        color: rgba(200, 200, 200, 0.8) !important;
        font-family: 'Nunito', sans-serif !important;
        font-size: 0.85rem !important;
    }

    .main-footer strong {
        color: #f8f9fa !important;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* EL CAMBIO CLAVE: Software Tech en Cian */
    .main-footer a {
        color: #00d4ff !important;
        /* Color Cian Eléctrico */
        font-weight: 800 !important;
        text-shadow: 0 0 10px rgba(0, 212, 255, 0.4);
        /* Resplandor sutil */
        text-decoration: none !important;
    }

    /* Ajuste responsivo */
    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    /* Sincronización con el colapso del sidebar */
    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    /* Sincronización con el colapso del sidebar */
    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    /* AJUSTE COLLAPSE */
    .sidebar-collapse .content-wrapper,
    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
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
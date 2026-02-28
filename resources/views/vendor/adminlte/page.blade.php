{{-- resources/views/vendor/adminlte/page.blade.php --}}
@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
@stack('css')
@yield('css')
<style>
    /* Fondo Gris Oxford con efecto cristal */
    .main-sidebar {
        background-color: #12141d !important;
        /* Un poco más claro que el negro puro */
        backdrop-filter: blur(10px);
        border-right: 1px solid rgba(138, 43, 226, 0.2) !important;
        transition: all 0.3s ease;
    }

    /* Encabezados de sección (ADMINISTRACIÓN, PROYECTOS...) */
    .nav-header {
        color: #8a2be2 !important;
        /* Morado Software Tech */
        font-size: 0.65rem !important;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        padding: 1.5rem 1rem 0.5rem 1.5rem !important;
        opacity: 0.8;
    }

    /* Ítem del menú (Texto e Icono) */
    .nav-sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
        /* Blanco más brillante */
        font-weight: 500;
        margin-bottom: 5px;
        border-radius: 10px !important;
        margin-left: 10px;
        margin-right: 10px;
    }

    /* Iconos: Morado neón suave */
    .nav-sidebar .nav-link i {
        color: #a366ff !important;
        text-shadow: 0 0 8px rgba(163, 102, 255, 0.3);
    }

    /* Ítem ACTIVO: Efecto de iluminación */
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
        background: rgba(138, 43, 226, 0.15) !important;
        color: #ffffff !important;
        border: 1px solid rgba(138, 43, 226, 0.4) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active i {
        color: #ffffff !important;
        text-shadow: 0 0 10px #8a2be2;
    }

    /* HOVER: Al pasar el mouse */
    .nav-sidebar .nav-link:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #ffffff !important;
        transform: translateX(5px);
    }

    /* Buscador lateral */
    .sidebar-search-block .form-control-sidebar {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(138, 43, 226, 0.2) !important;
        color: #fff !important;
        border-radius: 8px;
    }

    /* 1. ELIMINACIÓN TOTAL DE BORDES BLANCOS */
    body,
    .content-wrapper,
    .main-sidebar,
    .main-footer,
    .card,
    .modal-content,
    .card-header,
    .card-footer,
    .main-header,
    .table td,
    .table th {
        background-color: #00040a !important;
        color: #ffffff !important;
        border: none !important;
        /* Adiós a los bordes blancos */
    }

    /* 2. TARJETAS CON EFECTO NEÓN SUTIL */
    .card {
        background: rgba(255, 255, 255, 0.01) !important;
        backdrop-filter: blur(30px);
        border: 1px solid rgba(138, 43, 226, 0.2) !important;
        /* Borde morado muy fino */
        border-radius: 20px !important;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.8) !important;
    }

    /* 3. BOTONES RESPONSIVOS AL CURSOR (HOVER) */
    .btn-primary,
    .btn-submit,
    .btn-success,
    .btn-info {
        background: linear-gradient(135deg, #8a2be2 0%, #007bff 100%) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.4) !important;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }

    /* Efecto al pasar el mouse (Responsive Cursor) */
    .btn-primary:hover,
    .btn-submit:hover,
    .btn-success:hover,
    .btn-info:hover {
        transform: translateY(-3px) scale(1.05) !important;
        box-shadow: 0 0 30px rgba(0, 123, 255, 0.7) !important;
        filter: brightness(1.2);
        cursor: pointer;
    }

    /* Botones de acción en tablas (Ver, Editar, Borrar) */
    .btn-group .btn {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        margin: 0 2px;
    }

    .btn-group .btn:hover {
        border-color: #8a2be2 !important;
        background: rgba(138, 43, 226, 0.1) !important;
    }

    /* 4. TABLAS LIMPIAS */
    .table thead th {
        background: rgba(138, 43, 226, 0.1) !important;
        color: #8a2be2 !important;
        border-bottom: 2px solid #8a2be2 !important;
    }

    .table tbody tr {
        transition: 0.3s;
    }

    .table tbody tr:hover {
        background: rgba(138, 43, 226, 0.05) !important;
    }

    /* 5. FORMULARIOS OSCUROS */
    .form-control {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(138, 43, 226, 0.3) !important;
        color: #ffffff !important;
    }

    .form-control:focus {
        border-color: #007bff !important;
        box-shadow: 0 0 15px rgba(0, 123, 255, 0.3) !important;
    }

    /* Barra de desplazamiento neón */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #00040a;
    }

    ::-webkit-scrollbar-thumb {
        background: #8a2be2;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #007bff;
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
</div>
@stop

@section('adminlte_js')
@stack('js')
@yield('js')
<script>
    (function() {
            function lockTheme() {
                const body = document.body;
                body.classList.remove('high-contrast');
                body.classList.add('dark-mode'); 
                const compact = localStorage.getItem('st_user_compact') === 'true';
                if (compact) body.classList.add('sidebar-collapse');
            }
            document.addEventListener('DOMContentLoaded', lockTheme);
            window.onload = lockTheme;
        })();
</script>
@stop
{{-- resources/views/vendor/adminlte/page.blade.php --}}
@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
@stack('css')
@yield('css')
<style>
    /* 1. ESTRUCTURA GLOBAL: VISIÓN SOFTWARE TECH */
    body,
    .content-wrapper,
    .main-sidebar,
    .main-footer,
    .main-header,
    .modal-content,
    .card-header,
    .card-footer,
    .table td,
    .table th {
        background-color: #00040a !important;
        /* Negro profundo coherente con el Home */
        color: #ffffff !important;
        border: none !important;
    }

    /* 2. TARJETAS CON EFECTO CRISTAL (GLASSMORPHISM) */
    .card {
        background: rgba(255, 255, 255, 0.01) !important;
        /* Más transparente para evitar "cortes" */
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(138, 43, 226, 0.2) !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.8) !important;
        margin-bottom: 2rem;
    }

    /* 3. TABLAS PROFESIONALES: ALTO CONTRASTE */
    .table thead th {
        color: #ffffff !important;
        /* Blanco brillante para legibilidad total */
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 2px solid #8a2be2 !important;
        background-color: rgba(255, 255, 255, 0.03) !important;
        padding: 15px !important;
    }

    .table tbody td {
        color: rgba(255, 255, 255, 0.85) !important;
        border-bottom: 1px solid rgba(138, 43, 226, 0.08) !important;
        padding: 12px 15px !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(138, 43, 226, 0.04) !important;
        border-left: 4px solid #8a2be2;
        /* Indicador neón lateral */
        transition: all 0.3s ease;
    }

    /* 4. BOTONES PRINCIPALES RESPONSIVOS */
    .btn-primary,
    .btn-success,
    .btn-info,
    .btn-submit {
        background: linear-gradient(135deg, #8a2be2 0%, #007bff 100%) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(138, 43, 226, 0.3) !important;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
    }

    .btn-primary:hover,
    .btn-success:hover,
    .btn-info:hover {
        transform: translateY(-3px) scale(1.03) !important;
        box-shadow: 0 8px 25px rgba(0, 123, 255, 0.5) !important;
        filter: brightness(1.1);
    }

    /* 7. BOTONES DE ACCIÓN (TABLAS) - CORRECCIÓN DE BORDES BLANCOS */
    .btn-group .btn,
    .table .btn {
        background: rgba(255, 255, 255, 0.05) !important;
        /* Fondo de cristal tenue */
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        /* Borde casi invisible */
        color: #ffffff !important;
        border-radius: 8px !important;
        margin: 0 3px;
        transition: all 0.3s ease !important;
    }

    .btn-group .btn:hover {
        background: rgba(138, 43, 226, 0.2) !important;
        /* Brillo morado al pasar el mouse */
        border-color: #8a2be2 !important;
        transform: scale(1.1);
    }

    /* Colores responsivos para iconos específicos */
    .btn-group .btn i.fa-eye {
        color: #00d4ff !important;
    }

    .btn-group .btn i.fa-edit {
        color: #ffcc00 !important;
    }

    .btn-group .btn i.fa-trash {
        color: #ff4444 !important;
    }

    /* 5. FORMULARIOS E INPUTS MODERNOS */
    .form-control {
        background: rgba(0, 0, 0, 0.4) !important;
        border: 1px solid rgba(138, 43, 226, 0.3) !important;
        color: #ffffff !important;
        border-radius: 10px !important;
    }

    .form-control:focus {
        border-color: #8a2be2 !important;
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.3) !important;
    }

    /* 6. SIDEBAR Y SCROLLBAR */
    .main-sidebar {
        border-right: 1px solid rgba(138, 43, 226, 0.15) !important;
    }

    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
        background: rgba(138, 43, 226, 0.12) !important;
        border-left: 3px solid #8a2be2 !important;
    }

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

    /* 8. BOTÓN CANCELAR: FUERZA BRUTA BLANCO NÍTIDO */
    body.dark-mode .content-wrapper .btn-secondary,
    body.dark-mode .content-wrapper .btn-default,
    body.dark-mode .modal-content .btn-secondary,
    .btn-secondary:not(:disabled):not(.disabled) {
        /* Fondo: Cristal blanco constante */
        background-color: rgba(255, 255, 255, 0.18) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;

        /* Borde: Blanco semitransparente */
        border: 1px solid rgba(255, 255, 255, 0.5) !important;

        /* TEXTO: Blanco puro absoluto (Aquí es donde fallaba antes) */
        color: #ffffff !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 1.5px;

        /* Forzamos visibilidad total para eliminar el "gris" */
        opacity: 1 !important;
        filter: none !important;
        /* Elimina filtros de brillo automáticos de AdminLTE */
        border-radius: 10px !important;
        padding: 8px 25px !important;

        /* Limpieza de sombras que ensucian el texto */
        box-shadow: none !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    /* EFECTO RESPONSIVO: Brillo Neon Blanco al Cursor */
    .btn-secondary:hover,
    .btn-secondary:not(:disabled):not(.disabled):hover {
        background-color: rgba(255, 255, 255, 0.3) !important;
        border-color: #ffffff !important;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 0 25px rgba(255, 255, 255, 0.3) !important;
        color: #ffffff !important;
    }

    .st-force-white .btn-secondary { color: #fff !important; opacity: 1 !important; }
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


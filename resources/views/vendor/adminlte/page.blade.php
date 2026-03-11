@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
@stack('css')
@yield('css')
<style>
    /* 1. ESTRUCTURA Y SCROLLBAR GRADIENTE */
    html,
    body {
        height: 100% !important;
        margin: 0;
        background: #000 !important;
        scrollbar-width: thin;
        scrollbar-color: #00d4ff #000;
    }

    ::-webkit-scrollbar {
        width: 10px;
        background: transparent;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, rgba(138, 43, 226, 0), rgba(0, 212, 255, 0));
        border-radius: 10px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    body:hover::-webkit-scrollbar-thumb,
    html:hover::-webkit-scrollbar-thumb,
    .wrapper:hover::-webkit-scrollbar-thumb,
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #8a2be2, #00d4ff);
        border: 2px solid #05080f;
    }

    .wrapper {
        min-height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        background: radial-gradient(circle at top right, #001f3f, #000) !important;
    }

    .content-wrapper {
        flex: 1 0 auto !important;
        background: transparent !important;
        transition: margin-left 0.3s ease-in-out !important;
    }

    /* 2. LIMPIEZA DE INTERFAZ */
    .card,
    .card-header,
    .card-footer {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
    }

    .main-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    /* 3. NAVBAR Y PERFIL (ESTILO HOME - MINIMALISMO TOTAL + SALIDA EN ROJO) */
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
        transition: 0.4s ease;
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
        font-size: 1rem;
        border: 2px solid transparent !important;
        background-image: linear-gradient(#000, #000),
            linear-gradient(to right, #8a2be2, #00d4ff) !important;
        background-origin: border-box !important;
        background-clip: content-box, border-box !important;
        box-shadow: 0 0 15px rgba(138, 43, 226, 0.3);
    }

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

    body .user-header,
    body .user-body,
    body .user-footer {
        display: none !important;
    }

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

    body .dropdown-item i {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 1.1rem !important;
        margin-right: 15px !important;
        opacity: 0.4 !important;
        color: #fff !important;
        transition: 0.3s ease;
    }

    body .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
        transform: translateX(10px) !important;
        text-decoration: none !important;
    }

    body .dropdown-item:hover i {
        opacity: 1 !important;
    }

    body .dropdown-item[href*="logout"] {
        color: rgba(255, 50, 50, 0.6) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.06) !important;
        margin-top: 15px !important;
        padding-top: 20px !important;
    }

    body .dropdown-item[href*="logout"] i {
        color: #ff3232 !important;
        opacity: 0.5 !important;
    }

    body .dropdown-item[href*="logout"]:hover {
        background: rgba(255, 50, 50, 0.08) !important;
        color: #ff4d4d !important;
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

    .sidebar-search-results,
    .nav-sidebar>.nav-item:first-child .form-inline+.form-inline {
        display: none !important;
    }

    .sidebar-form,
    .nav-sidebar .form-inline {
        margin: 15px 25px !important;
        display: flex !important;
        flex-wrap: nowrap !important;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
        border: 1px solid rgba(138, 43, 226, 0.3) !important;
        padding: 0 !important;
        overflow: hidden !important;
        height: 32px !important;
        transition: all 0.3s ease;
    }

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
        opacity: 0.8;
        position: static !important;
        transform: none !important;
    }

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

    .nav-sidebar .nav-item .nav-link.active {
        border-left: 3px solid transparent !important;
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

    .table-responsive {
        border: none !important;
        overflow-x: auto !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    .table-responsive::-webkit-scrollbar {
        display: none !important;
    }

    table.table {
        width: 100% !important;
        margin-bottom: 0 !important;
        background: transparent !important;
        border-collapse: separate !important;
        border-spacing: 0 8px !important;
    }

    .table td,
    .table th {
        white-space: nowrap !important;
        padding: 15px 20px !important;
        vertical-align: middle !important;
        border: none !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
        color: #fff !important;
        transition: background-color 0.5s ease;
    }

    table.dataTable,
    .table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        background: transparent !important;
    }

    .table td,
    .table th {
        border-top: 1px solid rgba(255, 255, 255, 0.03) !important;
        padding: 15px 20px !important;
        background: transparent !important;
        transition: all 0.3s ease;
    }

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
        transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out !important;
        text-align: center !important;
        padding: 15px !important;
    }

    .main-footer strong {
        color: #f8f9fa !important;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .main-footer {
        flex-shrink: 0 !important;
        background: rgba(5, 8, 15, 0.9) !important;
        backdrop-filter: blur(10px);
        border-top: 1px solid transparent !important;
        border-image: linear-gradient(to right, transparent, #8a2be2, #00d4ff, #8a2be2, transparent) 1 !important;

        height: 35px !important;
        padding: 0 15px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        color: #ffffff !important;
        font-size: 0.75rem !important;
        transition: margin-left 0.3s ease-in-out !important;
    }

    .main-footer a {
        color: #00d4ff !important;
        text-decoration: none;
        font-weight: bold;
    }

    .main-sidebar {
        background: rgba(5, 8, 15, 0.85) !important;
        backdrop-filter: blur(20px) !important;
        border-right: 1px solid rgba(138, 43, 226, 0.2) !important;
    }

    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    .sidebar-collapse .content-wrapper,
    .sidebar-collapse .main-footer {
        margin-left: 4.6rem !important;
        width: calc(100% - 4.6rem) !important;
    }

    /* 8. ESTILOS PARA CAMPOS DE AUTOllenado */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-text-fill-color: #ffffff !important;
        -webkit-box-shadow: 0 0 0 1000px rgba(10, 15, 25, 1) inset !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    input:focus {
        outline: none !important;
    }

    /* 9. CONTENEDOR PARA EL VER/OCULTAR CONTRASEÑA Y ESTILO DE INPUTS */
    .password-container {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .form-control {
        background: rgba(15, 15, 15, 0.7) !important;
        border: 1px solid rgba(0, 212, 255, 0.15) !important;
        border-radius: 10px !important;
        color: #fff !important;
        padding: 12px 45px 12px 15px !important;
        height: auto !important;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(20, 20, 20, 0.9) !important;
        border-color: rgba(0, 212, 255, 0.4) !important;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.05) !important;
        outline: none;
    }

    .toggle-password {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;

        color: rgba(200, 200, 200, 0.4) !important;
        opacity: 1 !important;
        z-index: 10;

        font-size: 0.85rem;
        filter: none !important;
        transition: 0.3s ease;
    }

    .toggle-password:hover {
        color: rgba(255, 255, 255, 0.9) !important;
        text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function() {
        const body = document.body;
        const STORAGE_KEY = 'adminlte_sidebar_compact';

        // 1. INICIALIZACIÓN DE DISEÑO (Sidebar y Modo Oscuro)
        const initApp = () => {
            if (localStorage.getItem(STORAGE_KEY) === 'true') body.classList.add('sidebar-collapse');
            body.classList.add('dark-mode');
        };
        initApp();

        // 2. DETECTOR DE ÉXITO (POST-RECARGA)
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('status') || session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'OPERACIÓN EXITOSA',
                    text: '{{ session("status") ?? session("success") }}',
                    background: '#05080f', color: '#fff', confirmButtonColor: '#00d4ff'
                });
            @endif
        });

        // 3. VALIDACIÓN AJAX "AL INSTANTE" (EL GURDAESPALDAS)
        window.verificarCredenciales = async function(event) {
            event.preventDefault();
            
            const form = document.getElementById('form-password-update');
            const currentPass = document.querySelector('input[name="current_password"]');
            const newPass = document.querySelector('input[name="password"]');
            const confirmPass = document.querySelector('input[name="password_confirmation"]');

            // 3.1. VALIDACIÓN DE CAMPOS VACÍOS (Al instante)
            if (!currentPass.value || !newPass.value || !confirmPass.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'CAMPOS INCOMPLETOS',
                    text: 'Por favor, completa los tres campos de contraseña para continuar.',
                    background: '#05080f',
                    color: '#fff',
                    confirmButtonColor: '#00d4ff'
                });
                return;
            }

            // 3.2. VALIDACIÓN DE COINCIDENCIA (Al instante)
            if (newPass.value !== confirmPass.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'ERROR DE COINCIDENCIA',
                    text: 'La nueva contraseña y la confirmación no son iguales.',
                    background: '#05080f',
                    color: '#fff',
                    confirmButtonColor: '#00d4ff'
                });
                return; 
            }

            // 3.3. VALIDACIÓN DE LONGITUD (Al instante)
            if (newPass.value.length < 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'CONTRASEÑA DÉBIL',
                    text: 'La nueva contraseña debe tener al menos 8 caracteres.',
                    background: '#05080f',
                    color: '#fff',
                    confirmButtonColor: '#00d4ff'
                });
                return;
            }

            // 3.4. SOLO SI PASA TODO LO ANTERIOR, SE MUESTRA LA VERIFICACIÓN DE SEGURIDAD
            Swal.fire({
                title: 'VERIFICANDO SEGURIDAD...',
                html: 'Comprobando identidad con el servidor',
                allowOutsideClick: false,
                background: '#05080f',
                color: '#fff',
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch("{{ route('password.verify.ajax') }}", {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ current_password: currentPass.value })
                });

                const data = await response.json();

                if (data.valid) {
                    form.submit();
                } else {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'ACCESO DENEGADO', 
                        text: 'Tu contraseña actual es incorrecta.', 
                        background: '#05080f', 
                        color: '#fff', 
                        confirmButtonColor: '#00d4ff'
                    });
                }
            } catch (e) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'ERROR', 
                    text: 'Error de comunicación con el servidor.', 
                    background: '#05080f', 
                    color: '#fff' 
                });
            }
        };

        // 4. FUNCIÓN DE ELIMINACIÓN PARA TABLAS
        window.confirmDelete = function(formId) {
            Swal.fire({
                title: '¿ELIMINAR REGISTRO?',
                text: "ESTA ACCIÓN NO SE PUEDE DESHACER",
                icon: 'warning',
                showCancelButton: true,
                background: '#05080f', 
                color: '#fff',
                backdrop: `rgba(0,0,0,0.85)`,
                confirmButtonColor: '#ff3232',
                cancelButtonColor: 'rgba(255, 255, 255, 0.1)',
                confirmButtonText: 'ELIMINAR PERMANENTEMENTE',
                cancelButtonText: 'CANCELAR',
                customClass: {
                    popup: 'border border-danger shadow-lg',
                    title: 'font-weight-bold letter-spacing-2',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'PROCESANDO...',
                        background: '#05080f',
                        color: '#fff',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById(formId).submit();
                }
            });
        };

        // 5. LÓGICA PARA VER/OCULTAR CONTRASEÑA (EL OJITO)
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('toggle-password')) {
                const icon = e.target;
                const input = icon.parentElement.querySelector('input');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        });

    })();
</script>
@stop
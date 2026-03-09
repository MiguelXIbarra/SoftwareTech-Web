@extends('adminlte::page')

@section('title', 'Configuración | Software Tech')

@section('content_header')
<h1 class="m-0 dna-gradient">CONFIGURACIÓN DEL SISTEMA</h1>
@stop

@section('css')
<style>
    .content-wrapper {
        background: transparent !important;
    }

    .card {
        background: rgba(255, 255, 255, 0.015) !important;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(138, 43, 226, 0.2) !important;
        border-radius: 25px !important;
        color: #ffffff !important;
    }

    .dna-gradient {
        background: linear-gradient(135deg, #00d4ff, #8a2be2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }

    .btn-primary {
        background: linear-gradient(135deg, #8a2be2, #00d4ff) !important;
        border: none !important;
        font-weight: 700 !important;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.3) !important;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 25px rgba(0, 123, 255, 0.6) !important;
    }

    ..form-control {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(138, 43, 226, 0.3) !important;
        color: #ffffff !important;
    }

    .form-control:-webkit-autofill {
        -webkit-text-fill-color: #ffffff !important;
        -webkit-box-shadow: 0 0 0 1000px #0a0f19 inset !important;
    }

    label {
        color: rgba(255, 255, 255, 0.7) !important;
        font-size: 0.8rem;
    }
</style>
@stop

@section('content')
<div class="row pt-4">
    <div class="col-md-6">
        {{-- 1. AUTENTICACIÓN REFORZADA (2FA) --}}
        <div class="card shadow-lg mb-4">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-white">AUTENTICACIÓN REFORZADA</h3>
            </div>
            <div class="card-body text-center p-5">
                @if(!Auth::user()->two_factor_confirmed_at)
                <i class="fas fa-user-shield fa-4x mb-4 dna-gradient"></i>
                <p class="text-white opacity-75 mb-4">Protege tu cuenta vinculando Google Authenticator.</p>
                <form method="POST" action="{{ route('custom.2fa.toggle') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block py-3">ACTIVAR PROTOCOLO 2FA</button>
                </form>
                @else
                <div class="badge badge-success px-4 py-2 mb-4 shadow-sm"
                    style="background: rgba(40, 167, 69, 0.2); color: #28a745; border: 1px solid #28a745;">
                    PROTECCIÓN ACTIVA
                </div>
                <form method="POST" action="{{ route('custom.2fa.toggle') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-block btn-sm mt-2 text-white border-danger">
                        DESACTIVAR 2FA
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- 2. ACTUALIZAR CREDENCIALES CON VALIDACIÓN AJAX --}}
        <div class="card shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-white">ACTUALIZAR CREDENCIALES</h3>
            </div>
            <form id="form-password-update" action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label>Contraseña Actual</label>
                        <div class="password-container">
                            <input type="password" name="current_password" class="form-control"
                                placeholder="Escribe tu contraseña actual" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <div class="password-container">
                            <input type="password" name="password" class="form-control"
                                placeholder="Mínimo 8 caracteres" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirmar Nueva Contraseña</label>
                        <div class="password-container">
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Repite la contraseña" required>
                            <i class="fas fa-eye toggle-password"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 text-right">
                    <button type="submit" onclick="verificarCredenciales(event)" class="btn btn-primary px-5 py-2">
                        GUARDAR CAMBIOS
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- 3. PREFERENCIAS DE INTERFAZ --}}
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-white">PREFERENCIAS DE INTERFAZ</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tema de Visualización Activo</label>
                    <div class="p-3 rounded border border-primary text-center"
                        style="background: rgba(138, 43, 226, 0.1);">
                        <span class="dna-gradient fw-bold">SOFTWARE TECH (DARK NEON)</span>
                    </div>
                </div>
                <div class="custom-control custom-switch mt-4">
                    <input type="checkbox" class="custom-control-input" id="st-compact">
                    <label class="custom-control-label text-white" for="st-compact">BANDEJA DE DATOS COMPACTA</label>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. MODAL QR (Solo aparece si se solicitó activar 2FA) --}}
    @if(session('show_qr_modal'))
    <div class="modal fade show" id="qrModal" style="display: block; background: rgba(0,0,0,0.95);"
        data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border border-primary" style="background: #00040a; border-radius: 30px;">
                <div class="modal-body text-center p-5">
                    <h4 class="text-white fw-bold mb-4">ESCANEAR CÓDIGO QR</h4>
                    @php
                    $user = Auth::user();
                    $secret = decrypt($user->two_factor_secret);
                    $qrData = "otpauth://totp/SoftwareTech:{$user->email}?secret={$secret}&issuer=SoftwareTech";
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
                    @endphp
                    <div class="p-3 bg-white d-inline-block rounded mb-4">
                        <img src="{{ $qrUrl }}" style="width: 180px; height: 180px;" alt="Código QR de Seguridad">
                    </div>
                    <form method="POST" action="{{ route('custom.2fa.confirm') }}">
                        @csrf
                        <input type="text" name="code" class="form-control form-control-lg text-center mb-4"
                            placeholder="CÓDIGO DE 6 DÍGITOS" maxlength="6" required autofocus>
                        <button type="submit" class="btn btn-primary btn-block py-3 fw-bold">VINCULAR
                            DISPOSITIVO</button>
                    </form>
                    <form method="POST" action="{{ route('custom.2fa.cancel') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-link text-white-50 btn-sm">CANCELAR OPERACIÓN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@stop

@push('js')
<script>
    (function() {
        const STORAGE_KEY = 'adminlte_sidebar_compact';
        const compactSwitch = document.getElementById('st-compact');

        // 1. SINCRONIZACIÓN DEL SWITCH (BANDEJA COMPACTA)
        if (compactSwitch) {
            compactSwitch.checked = localStorage.getItem(STORAGE_KEY) === 'true';
            compactSwitch.addEventListener('change', () => {
                const isActive = compactSwitch.checked;
                localStorage.setItem(STORAGE_KEY, isActive);
                
                if (isActive) {
                    document.body.classList.add('sidebar-collapse');
                } else {
                    document.body.classList.remove('sidebar-collapse');
                }
            });
        }

        // 2. VALIDACIÓN DE CONTRASEÑA POR AJAX
        window.verificarCredenciales = async function(event) {
            event.preventDefault();
            
            const form = document.getElementById('form-password-update');
            const currentPassInput = document.querySelector('input[name="current_password"]');

            if (!currentPassInput || currentPassInput.value === "") {
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'ATENCIÓN', 
                    text: 'Escribe tu contraseña actual para continuar.', 
                    background: '#05080f', 
                    color: '#fff' 
                });
                return;
            }

            Swal.fire({
                title: 'CONSULTANDO SEGURIDAD',
                html: 'Validando identidad...',
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
                    body: JSON.stringify({ current_password: currentPassInput.value })
                });

                const data = await response.json();

                if (!data.valid) {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'DATOS INCORRECTOS', 
                        text: 'La contraseña actual no coincide con nuestros registros.', 
                        background: '#05080f', 
                        color: '#fff',
                        confirmButtonColor: '#00d4ff'
                    });
                    currentPassInput.focus();
                } else {
                    form.submit();
                }

            } catch (e) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'ERROR DE CONEXIÓN', 
                    text: 'No se pudo verificar la seguridad. Intenta más tarde.', 
                    background: '#05080f', 
                    color: '#fff' 
                });
            }
        };
    })();
</script>
@endpush
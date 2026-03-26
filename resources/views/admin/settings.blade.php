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

    .form-control {
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

    .swal2-container {
        background: rgba(0, 0, 0, 0.75) !important;
        backdrop-filter: blur(10px);
    }

    .swal2-popup {
        background: linear-gradient(135deg, rgba(15, 18, 36, 0.96), rgba(30, 35, 55, 0.92)) !important;
        border: 1px solid rgba(138, 43, 226, 0.55) !important;
        box-shadow: 0 0 60px rgba(0, 212, 255, 0.2) !important;
        border-radius: 20px !important;
        color: #fff !important;
    }

    .swal2-title,
    .swal2-html-container {
        text-align: center;
    }

    .swal2-input {
        background: rgba(0, 0, 0, 0.45) !important;
        border: 1px solid rgba(138, 43, 226, 0.55) !important;
        color: #fff !important;
        text-align: center !important;
        text-align-last: center !important;
        text-indent: 0.4rem !important;
        box-sizing: border-box !important;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-variant-numeric: tabular-nums;
        font-feature-settings: "tnum" 1;
        font-size: 1.8rem !important;
        letter-spacing: 11px !important;
        line-height: 60px !important;
        height: 60px !important;
        padding: 0 1.2rem !important;
        width: fit-content !important;
        max-width: 18rem !important;
        margin: 0 auto !important;
        border-radius: 12px !important;
    }

    .swal2-actions {
        justify-content: center;
    }

    .center-modal-tech {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
@stop

@section('content')
<div class="row pt-4">
    <div class="col-md-6">
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
                {{-- Badge con colores de la empresa corregido --}}
                <div class="badge px-4 py-2 mb-4 shadow-sm"
                    style="background: rgba(0, 212, 255, 0.1); color: #00d4ff; border: 1px solid rgba(0, 212, 255, 0.3); letter-spacing: 1px;">
                    SISTEMA BLINDADO
                </div>

                <button type="button" onclick="confirmarDesactivacion2FA()" class="btn btn-block btn-sm mt-2 text-white"
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(138, 43, 226, 0.4); border-radius: 10px;">
                    DESACTIVAR PROTOCOLO
                </button>
                @endif
            </div>
        </div>

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

    @if(session('show_qr_modal'))
    <div class="modal fade show" id="qrModal" style="display: block; background: rgba(0,0,0,0.95);"
        data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border border-primary" style="background: #00040a; border-radius: 30px;">
                <div class="modal-body text-center p-5">
                    <h4 class="text-white fw-bold mb-4">ESCANEAR CÓDIGO QR</h4>
                    @php
                    $user = Auth::user();
                    $secret = $user->two_factor_secret;
                    $qrData = "otpauth://totp/SoftwareTech:{$user->email}?secret={$secret}&issuer=SoftwareTech";
                    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
                    @endphp
                    <div class="p-3 bg-white d-inline-block rounded mb-4">
                        <img src="{{ $qrUrl }}" style="width: 180px; height: 180px;" alt="Código QR de Seguridad">
                    </div>
                    <form method="POST" action="{{ route('custom.2fa.confirm') }}">
                        @csrf
                        <div class="form-group text-center">
                            <input type="text" name="code" class="form-control input-2fa-terminal mb-4"
                                placeholder="000000" maxlength="6" required autofocus autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-gradient-tech-blue btn-block py-3 fw-bold">
                            VINCULAR DISPOSITIVO
                        </button>
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

    window.confirmarDesactivacion2FA = async function() {
        const { value: code } = await Swal.fire({
            title: 'VERIFICACIÓN DE SEGURIDAD',
            text: 'Ingresa el código de tu terminal para desactivar la protección.',
            input: 'text',
            inputPlaceholder: '000000',
            showCancelButton: true,
            confirmButtonText: 'CONFIRMAR',
            cancelButtonText: 'CANCELAR',
            background: 'transparent',
            color: '#fff',
            customClass: {
                container: 'center-modal-tech',
                popup: 'swal2-popup-tech',
                input: 'input-2fa-terminal',
                confirmButton: 'btn-gradient-tech-cian px-5 py-2 mx-2',
                cancelButton: 'btn btn-secondary px-5 py-2 mx-2'
            },
            inputAttributes: {
                maxlength: 6,
                autofocus: true,
                autocomplete: 'off'
            },
            inputValidator: (value) => {
                if (!value || value.length !== 6) return 'Se requiere código de 6 dígitos';
            }
        });

    if (code) {
        Swal.fire({
            title: 'AUTENTICANDO...',
            background: '#05080f',
            color: '#fff',
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const response = await fetch("{{ route('custom.2fa.deactivate') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ code: code })
            });

            const data = await response.json();
            if (data.success) {
                Swal.fire({ 
                    icon: 'success', 
                    title: 'PROTOCOLO FINALIZADO', 
                    background: '#05080f', 
                    color: '#fff', 
                    confirmButtonColor: '#00d4ff' 
                }).then(() => location.reload());
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            Swal.fire({ 
                icon: 'error', 
                title: 'ACCESO DENEGADO', 
                text: error.message, 
                background: '#05080f', 
                color: '#fff' 
            });
        }
    }
};
        window.verificarCredenciales = async function(event) {
            event.preventDefault();
            const form = document.getElementById('form-password-update');
            const currentPassInput = document.querySelector('input[name="current_password"]');
            if (!currentPassInput || currentPassInput.value === "") {
                Swal.fire({ icon: 'warning', title: 'ATENCIÓN', text: 'Escribe tu contraseña actual.', background: '#05080f', color: '#fff' });
                return;
            }
            Swal.fire({ title: 'VALIDANDO...', allowOutsideClick: false, background: '#05080f', color: '#fff', didOpen: () => { Swal.showLoading(); } });
            try {
                const response = await fetch("{{ route('password.verify.ajax') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ current_password: currentPassInput.value })
                });
                const data = await response.json();
                if (!data.valid) {
                    Swal.fire({ icon: 'error', title: 'ERROR', text: 'Contraseña incorrecta.', background: '#05080f', color: '#fff' });
                } else {
                    form.submit();
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'ERROR', background: '#05080f', color: '#fff' });
            }
        };
    })();
</script>
@endpush
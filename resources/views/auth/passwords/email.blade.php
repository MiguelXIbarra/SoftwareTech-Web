@extends('layouts.auth')

@push('styles')
<style>
    body,
    html {
        height: 100vh;
        margin: 0;
        background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2000&auto=format&fit=crop') !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-size: cover !important;
        background-attachment: fixed !important;
        overflow: hidden !important;
    }

    /* Ocultar elementos del layout principal si es necesario */
    #dna-canvas {
        display: none !important;
    }

    .auth-overlay-fix {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        z-index: 1;
    }

    .auth-content {
        position: relative;
        z-index: 2;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .glass-terminal-v2 {
        background: rgba(10, 10, 10, 0.85) !important;
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 50px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8);
        margin-top: -20px;
    }

    .form-control-tech {
        background: rgba(20, 20, 20, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 12px;
        color: #fff !important;
        padding: 12px 15px !important;
    }

    .form-control-tech:focus {
        border-color: #00d4ff !important;
        outline: none;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
    }
</style>
@endpush

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content">
    <div class="glass-terminal-v2 text-center">

        {{-- ESTADO 1: ÉXITO (CORREO ENVIADO) --}}
        @if (session('status'))
        <div class="py-4">
            <i class="fas fa-paper-plane fa-4x mb-4"
                style="color: #00d4ff; filter: drop-shadow(0 0 15px rgba(0, 212, 255, 0.4));"></i>
            <h2 class="text-white fw-bold mb-3" style="letter-spacing: 2px;">¡CORREO ENVIADO!</h2>
            <p class="text-white-50 mb-5" style="font-size: 1rem;">Se ha transmitido un enlace de acceso a tu terminal
                de correo.</p>

            <button type="button" onclick="window.location.href='{{ route('login') }}'" class="btn w-100 text-white"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                VOLVER AL LOGIN
            </button>
        </div>

        {{-- ESTADO 2: ERROR (CORREO INCORRECTO) --}}
        @elseif ($errors->has('email'))
        <div class="py-4">
            <i class="fas fa-exclamation-triangle fa-4x mb-4"
                style="color: #ff4b5c; filter: drop-shadow(0 0 15px rgba(255, 75, 92, 0.4));"></i>
            <h2 class="text-white fw-bold mb-3" style="letter-spacing: 2px;">ACCESO DENEGADO</h2>
            <p class="text-white-50 mb-5">El identificador ingresado no coincide con nuestros registros de seguridad.
            </p>

            <button type="button" onclick="window.location.href='{{ route('password.request') }}'"
                class="btn w-100 text-white"
                style="background: linear-gradient(135deg, #ff4b5c, #8a2be2); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(255, 75, 92, 0.2);">
                REINTENTAR PROTOCOLO
            </button>
        </div>

        {{-- ESTADO 3: FORMULARIO INICIAL --}}
        @else
        <div class="mb-2">
            <i class="fas fa-user-shield fa-4x mb-4"
                style="color: #00d4ff; filter: drop-shadow(0 0 10px rgba(0, 212, 255, 0.3));"></i>
            <h2 class="text-white fw-bold" style="letter-spacing: 2px;">SEGURIDAD</h2>

            @if(Auth::check())
            {{-- Usuario Autenticado --}}
            <p class="text-white-50 mb-4">Hola, <strong>{{ Auth::user()->name }}</strong>.<br>¿Deseas generar un enlace
                de recuperación?</p>
            <form id="form-auto-reset" action="{{ route('password.auto_send') }}" method="POST">
                @csrf
                <button type="button"
                    onclick="confirmarAccion('form-auto-reset', 'Se enviará un enlace de seguridad a tu terminal registrado.')"
                    class="btn w-100 text-white mb-4"
                    style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                    SOLICITAR ENLACE
                </button>
            </form>
            @else
            {{-- Usuario Invitado --}}
            <p class="text-white-50 mb-4">Ingresa tu email para validar tu identidad en el Lab.</p>
            <form id="form-recovery" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4 text-start">
                    <label class="small fw-bold mb-2 opacity-75 text-white">CORREO ELECTRÓNICO</label>
                    <input id="email_input" type="email" name="email" class="form-control-tech w-100" required autofocus
                        placeholder="usuario@softwaretech.com">
                </div>
                <button type="button" onclick="validarYEnviarInvitado()" class="btn w-100 text-white mb-4"
                    style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                    VERIFICAR Y ENVIAR
                </button>
            </form>
            @endif

            <a href="{{ route('login') }}" class="text-white-50 small text-decoration-none fw-bold"
                style="letter-spacing: 1px;">VOLVER AL INICIO</a>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function procesarEnvio(formId) {
        Swal.fire({
            title: 'PROCESANDO...',
            html: 'Sincronizando con el servidor de seguridad',
            allowOutsideClick: false,
            background: '#0a0a0a',
            color: '#fff',
            confirmButtonColor: '#00d4ff',
            didOpen: () => { Swal.showLoading(); }
        });
        document.getElementById(formId).submit();
    }

    function confirmarAccion(formId, mensaje) {
        Swal.fire({
            title: '¿CONFIRMAR ENVÍO?',
            text: mensaje,
            icon: 'question',
            background: '#0a0a0a', 
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#00d4ff',
            cancelButtonColor: '#ff4b5c',
            confirmButtonText: 'SÍ, ENVIAR',
            cancelButtonText: 'CANCELAR'
        }).then((result) => {
            if (result.isConfirmed) procesarEnvio(formId);
        });
    }

    function validarYEnviarInvitado() {
        const email = document.getElementById('email_input').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if(!email) {
            Swal.fire({
                icon: 'warning',
                title: 'DATO REQUERIDO',
                text: 'Por favor, ingresa un correo electrónico.',
                background: '#0a0a0a',
                color: '#fff',
                confirmButtonColor: '#00d4ff'
            });
            return;
        }

        if(!emailRegex.test(email)) {
             Swal.fire({
                icon: 'error',
                title: 'FORMATO INVÁLIDO',
                text: 'El formato del correo no es correcto.',
                background: '#0a0a0a',
                color: '#fff',
                confirmButtonColor: '#00d4ff'
            });
            return;
        }

        confirmarAccion('form-recovery', `Se transmitirá un enlace a: ${email}`);
    }
</script>
@endsection
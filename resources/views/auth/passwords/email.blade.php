@extends('layouts.app')

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
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 50px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8);
    }

    .form-control-tech {
        background: rgba(20, 20, 20, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 12px;
        color: #fff !important;
        padding: 12px;
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
            <p class="text-white-50 mb-5" style="font-size: 1.1rem;">Se envió un email a tu correo para cambiar tu
                contraseña.</p>

            <button type="button" onclick="window.location.href='{{ route('login') }}'" class="btn w-100 text-white"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none;">
                CONTINUAR
            </button>
        </div>

        {{-- ESTADO 2: ERROR (CORREO INCORRECTO) --}}
        @elseif ($errors->has('email'))
        <div class="py-4">
            <i class="fas fa-exclamation-triangle fa-4x mb-4"
                style="color: #ff4b5c; filter: drop-shadow(0 0 15px rgba(255, 75, 92, 0.4));"></i>
            <h2 class="text-white fw-bold mb-3" style="letter-spacing: 2px;">ACCESO DENEGADO</h2>
            <p class="text-white-50 mb-5">El correo electrónico ingresado no coincide con nuestros registros de
                seguridad.</p>

            <button type="button" onclick="window.location.href='{{ route('password.request') }}'"
                class="btn w-100 text-white"
                style="background: linear-gradient(135deg, #ff4b5c, #8a2be2); border-radius: 12px; padding: 15px; font-weight: 800; border: none;">
                REINTENTAR
            </button>
        </div>

        {{-- ESTADO 3: FORMULARIO INICIAL (O SEGURIDAD SI ESTÁ LOGUEADO) --}}
        @else
        <div class="mb-4">
            <i class="fas fa-user-shield fa-4x mb-3" style="color: #00d4ff;"></i>
            <h2 class="text-white fw-bold" style="letter-spacing: 2px;">SEGURIDAD</h2>

            @if(Auth::check())
            {{-- Usuario Autenticado --}}
            <p class="text-white-50">Hola, <strong>{{ Auth::user()->name }}</strong>.<br>¿Deseas restablecer tu
                contraseña operativa?</p>
            <form id="form-auto-reset" action="{{ route('password.auto_send') }}" method="POST">
                @csrf
                <button type="button"
                    onclick="confirmarAccion('form-auto-reset', 'Se enviará el enlace a tu correo registrado.')"
                    class="btn w-100 text-white mb-3"
                    style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none;">
                    GENERAR ENLACE
                </button>
            </form>
            @else
            {{-- Usuario Invitado --}}
            <p class="text-white-50">Ingresa tu correo para validar tu identidad.</p>
            <form id="form-recovery" method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4 text-start">
                    <label class="small fw-bold mb-2 opacity-75 text-white">CORREO ELECTRÓNICO</label>
                    <input id="email_input" type="email" name="email" class="form-control-tech w-100" required
                        autofocus>
                </div>
                <button type="button" onclick="validarYEnviarInvitado()" class="btn w-100 text-white mb-3"
                    style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none;">
                    VERIFICAR Y ENVIAR
                </button>
            </form>
            @endif

            <a href="{{ route('login') }}" class="text-white-50 small text-decoration-none fw-bold">Volver al Inicio</a>
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Función genérica para mostrar carga y enviar
    function procesarEnvio(formId) {
        Swal.fire({
            title: 'PROCESANDO...',
            html: 'Conectando con el servidor de seguridad',
            allowOutsideClick: false,
            background: '#05080f',
            color: '#fff',
            didOpen: () => { Swal.showLoading(); }
        });
        document.getElementById(formId).submit();
    }

    // Confirmación para Logueados
    function confirmarAccion(formId, mensaje) {
        Swal.fire({
            title: '¿CONTINUAR?',
            text: mensaje,
            icon: 'question',
            background: '#05080f', color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#00d4ff',
            confirmButtonText: 'SÍ, ENVIAR'
        }).then((result) => {
            if (result.isConfirmed) procesarEnvio(formId);
        });
    }

    // Validación para Invitados
    function validarYEnviarInvitado() {
        const email = document.getElementById('email_input').value;
        if(!email) return;
        confirmarAccion('form-recovery', `Se enviará un enlace de recuperación a: ${email}`);
    }
</script>
@endsection
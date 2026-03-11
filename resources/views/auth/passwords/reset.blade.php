@extends('layouts.auth')

@push('styles')
<style>
    /* Mantenemos todo tu CSS original intacto */
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
        padding: 45px 40px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8);
        margin-top: -20px;
    }

    .user-pill {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        padding: 6px 16px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .form-control-tech {
        background: rgba(20, 20, 20, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 12px;
        color: #fff !important;
        padding: 12px 45px 12px 15px !important;
    }

    .form-control-tech:focus {
        border-color: #00d4ff !important;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
        outline: none;
    }

    .password-container {
        position: relative;
        width: 100%;
    }

    .toggle-password {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: rgba(255, 255, 255, 0.4) !important;
        z-index: 10;
    }

    /* Bloqueo de iconos nativos para que no se encimen con los tuyos */
    input::-ms-reveal,
    input::-ms-clear {
        display: none !important;
    }

    input::-webkit-contacts-auto-fill-button,
    input::-webkit-credentials-auto-fill-button {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content">
    <div class="glass-terminal-v2 text-center">

        <div class="mb-6">
            <div class="user-pill" style="margin-bottom: 18px;">
                <i class="fas fa-user-circle me-2" style="color: #00d4ff;"></i>
                <span class="text-white small fw-bold">{{ $email ?? old('email') }}</span>
            </div>
            <h2 class="text-white fw-bold" style="letter-spacing: 2px; font-size: 1.6rem; margin-bottom: 15px;">
                ACTUALIZAR IDENTIDAD</h2>
            <p class="small opacity-50 text-white" style="letter-spacing: 2px; margin-bottom: 40px;">PROTOCOLO DE
                SEGURIDAD
            </p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <div class="mb-4 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">NUEVA CONTRASEÑA</label>
                <div class="password-container">
                    <input type="password" name="password"
                        class="form-control-tech w-100 @error('password') is-invalid @enderror" required autofocus
                        placeholder="Mínimo 8 caracteres">
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                @error('password')
                <span class="text-danger small mt-2 d-block" style="font-size: 0.75rem;">* {{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">CONFIRMAR IDENTIDAD</label>
                <div class="password-container">
                    <input type="password" name="password_confirmation" class="form-control-tech w-100" required
                        placeholder="Repite la contraseña" autocomplete="new-password">
                    <i class="fas fa-eye toggle-password"></i>
                </div>
            </div>

            <button type="submit" class="btn w-100 text-white mb-3"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                ACTUALIZAR Y ENTRAR
            </button>

            <a href="{{ route('login') }}" class="text-white-50 small text-decoration-none fw-bold">Cancelar proceso</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. SCRIPT PARA LOS OJOS (CAMBIAR MODO)
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // 2. Errores con SweetAlert
    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'PROTOCOL ERROR',
            text: 'Las credenciales no cumplen con los requisitos de seguridad.',
            background: '#0a0a0a',
            color: '#fff',
            confirmButtonColor: '#00d4ff'
        });
    @endif
</script>
@endsection
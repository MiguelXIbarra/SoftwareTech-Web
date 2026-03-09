@extends('layouts.app')

@push('styles')
<style>
    body,
    html {
        height: 100vh;
        margin: 0;
        background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2000&auto=format&fit=crop') !important;
        background-position: center !important;
        background-size: cover !important;
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
    }

    .form-control-tech {
        background: rgba(20, 20, 20, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 12px;
        color: #fff !important;
        padding: 12px 45px 12px 15px !important;
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
        color: rgba(200, 200, 200, 0.4) !important;
        font-size: 0.85rem;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content">
    <div class="glass-terminal-v2 text-center">
        <div class="mb-5">
            <i class="fas fa-user-shield fa-3x" style="color: #00d4ff;"></i>
            <h2 class="text-white fw-bold mt-3" style="letter-spacing: 2px;">ACTUALIZAR IDENTIDAD</h2>
            <p class="small opacity-50 text-white">Establece una nueva clave de acceso segura</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

            <div class="mb-4 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">NUEVA CONTRASEÑA</label>
                <div class="password-container">
                    <input type="password" name="password" class="form-control-tech w-100" required autofocus>
                    <i class="fas fa-eye toggle-password"></i>
                </div>
            </div>

            <div class="mb-5 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">CONFIRMAR NUEVA CLAVE</label>
                <div class="password-container">
                    <input type="password" name="password_confirmation" class="form-control-tech w-100" required>
                    <i class="fas fa-eye toggle-password"></i>
                </div>
            </div>

            <button type="submit" class="btn w-100 text-white mb-3"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none;">
                ACTUALIZAR Y ENTRAR
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

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

        @if (session('status'))
            Swal.fire({
                title: 'IDENTIDAD ACTUALIZADA',
                text: 'Tu contraseña ha sido cambiada con éxito. Ya puedes acceder al sistema.',
                icon: 'success',
                background: '#05080f',
                color: '#fff',
                confirmButtonColor: '#00d4ff',
                confirmButtonText: 'CONTINUAR AL LOGIN',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'ERROR DE VALIDACIÓN',
                text: '{{ $errors->first() }}',
                icon: 'error',
                background: '#05080f',
                color: '#fff',
                confirmButtonColor: '#8a2be2'
            });
        @endif
    });
</script>
@endsection
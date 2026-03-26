@extends('layouts.auth')

@section('css')
<style>
    .swal2-popup-tech {
        background: rgba(10, 10, 10, 0.95) !important;
        border: 1px solid rgba(0, 212, 255, 0.3) !important;
        border-radius: 20px !important;
        backdrop-filter: blur(15px) !important;
        box-shadow: 0 0 30px rgba(0, 212, 255, 0.15) !important;
    }

    .swal2-title-tech {
        color: #fff !important;
        font-weight: 700 !important;
        letter-spacing: 2px !important;
        text-transform: uppercase;
        font-size: 1.5rem !important;
    }
</style>
@endsection

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content">
    <div class="glass-terminal-v2">
        <div class="text-center mb-5">
            <h2 class="text-white fw-bold" style="letter-spacing: 2px;">ACCESO AL LAB</h2>
            <p class="small opacity-50 text-white" style="letter-spacing: 2px;">IDENTIDAD VERIFICADA</p>
        </div>

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <div class="mb-4 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">USUARIO / EMAIL</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="form-control-tech w-100 @error('email') is-invalid @enderror" required autofocus>
                @error('email')
                <span class="text-danger small mt-2 d-block" style="font-size: 0.75rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">CONTRASEÑA</label>
                <div class="password-container">
                    <input type="password" name="password" id="password-input"
                        class="form-control-tech w-100 @error('password') is-invalid @enderror"
                        placeholder="Mínimo 8 caracteres" required autocomplete="current-password">
                    <i class="fas fa-eye toggle-password" style="cursor: pointer;"></i>
                </div>
                @error('password')
                <span class="text-danger small mt-2 d-block" style="font-size: 0.75rem;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn w-100 text-white mb-4"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                ESTABLECER CONEXIÓN
            </button>

            <div class="d-flex justify-content-between">
                <a href="{{ route('register') }}" class="text-white-50 small text-decoration-none fw-bold">Crear
                    Cuenta</a>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-white-50 small text-decoration-none fw-bold">
                    ¿Olvidaste tu contraseña?
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<form id="2fa-form" method="POST" action="{{ route('login.2fa') }}" style="display: none;">
    @csrf
    <input type="hidden" name="email" id="2fa-email">
    <input type="hidden" name="password" id="2fa-password">
    <input type="hidden" name="code" id="2fa-code">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('#login-form');

        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const email = this.querySelector('input[name="email"]').value;
            const password = document.querySelector('#password-input').value;

            Swal.fire({
                title: 'VERIFICANDO...',
                background: '#0a0a0a',
                color: '#fff',
                showConfirmButton: false,
                allowOutsideClick: false,
                customClass: { popup: 'swal2-popup-tech', title: 'swal2-title-tech', input: 'input-2fa-terminal', confirmButton: 'btn-gradient-tech', cancelButton: 'btn-cancel-tech'},
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch("{{ route('login.check.2fa') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email, password })
                });

                if (!response.ok) { this.submit(); return; }

                const data = await response.json();
                if (data.requires2FA) {
                    pedirCodigoTerminal(email, password);
                } else {
                    this.submit();
                }
            } catch (error) {
                this.submit();
            }
        });

        async function pedirCodigoTerminal(email, password) {
            const { value: code } = await Swal.fire({
                title: 'SEGURIDAD 2FA',
                html: '<p class="small text-white-50" style="letter-spacing: 1px;">INGRESE EL CÓDIGO DE SU TERMINAL</p>',
                input: 'text',
                inputPlaceholder: '000000',
                background: '#0a0a0a',
                color: '#fff',
                confirmButtonText: 'AUTENTICAR',
                showCancelButton: true,
                cancelButtonText: 'CANCELAR',
                width: '26em',
                customClass: {
                    popup: 'swal2-popup-tech',
                    title: 'swal2-title-tech',
                    input: 'input-2fa-terminal',
                    confirmButton: 'btn-gradient-tech',
                    cancelButton: 'btn-cancel-tech'
                },
                inputAttributes: { maxlength: 6, autofocus: true, autocomplete: 'off' },
                inputValidator: (value) => {
                    if (!value || value.length !== 6) return 'Código de 6 dígitos requerido';
                }
            });

            if (code) {
                document.getElementById('2fa-email').value = email;
                document.getElementById('2fa-password').value = password;
                document.getElementById('2fa-code').value = code;
                document.getElementById('2fa-form').submit();
            }
        }
    });
</script>

@if (session('status'))
<script>
    Swal.fire({
        title: 'SISTEMA ACTUALIZADO',
        text: "{{ session('status') }}",
        icon: 'success',
        background: '#05080f',
        color: '#fff',
        confirmButtonColor: '#00d4ff'
    });
</script>
@endif
@endsection
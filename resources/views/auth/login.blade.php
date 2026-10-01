@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    body {
        background-color: #030712 !important;
    }

    .login-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        color: #ffffff;
        position: relative;
        padding: 40px 20px;
    }

    .login-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.1) 0%, transparent 60%),
                    radial-gradient(circle at 20% 20%, rgba(138, 43, 226, 0.07) 0%, transparent 40%);
        z-index: 1;
        pointer-events: none;
    }

    .login-wrapper {
        max-width: 440px;
        width: 100%;
        position: relative;
        z-index: 5;
    }

    .login-container {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(255, 255, 255, 0.15) !important;
        border-left-color: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6) !important;
        border-radius: 24px;
        padding: 45px;
        width: 100%;
    }

    .login-header h2 {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 6px;
        color: #ffffff;
    }

    .login-header span {
        font-family: monospace;
        font-size: 0.75rem;
        color: #06b6d4;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 35px;
        text-transform: uppercase;
    }

    .form-glass {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 12px !important;
        color: #ffffff !important;
        padding: 14px 18px !important;
        transition: all 0.3s ease !important;
        font-size: 0.95rem !important;
    }

    .form-glass::placeholder {
        color: rgba(255, 255, 255, 0.3) !important;
    }

    .form-glass:focus {
        background: rgba(255, 255, 255, 0.06) !important;
        border-color: #06b6d4 !important;
        color: #ffffff !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.25) !important;
    }

    .btn-login-submit {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #ffffff !important;
        padding: 15px;
        border-radius: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 0.85rem;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
    }

    .btn-login-submit:hover {
        border-color: rgba(138, 43, 226, 0.4);
        background: rgba(255, 255, 255, 0.05);
        box-shadow: 0 0 25px rgba(138, 43, 226, 0.2);
        transform: translateY(-2px);
    }

    .btn-login-submit i {
        color: #06b6d4;
    }

    .custom-error-panel {
        background: rgba(239, 68, 68, 0.06) !important;
        border: 1px solid rgba(239, 68, 68, 0.2) !important;
        padding: 12px 16px;
        border-radius: 12px;
        color: #fca5a5 !important;
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 25px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    /* ESTILO PARA EL TEXTO INFORMATIVO */
    .login-info-text {
        margin-top: 24px;
        padding: 0 10px;
        font-size: 0.82rem;
        line-height: 1.5;
        color: rgba(255, 255, 255, 0.45);
        text-align: center;
    }
    .password-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input-wrapper .form-glass {
        padding-right: 40px !important;
    }

    .btn-toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        color: rgba(255, 255, 255, 0.35);
        padding: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease, color 0.2s ease, transform 0.2s ease;
        border-radius: 6px;
        z-index: 10;
        -webkit-tap-highlight-color: transparent;
    }

    .password-input-wrapper:hover .btn-toggle-password,
    .password-input-wrapper:focus-within .btn-toggle-password,
    .password-input-wrapper.has-value .btn-toggle-password {
        opacity: 0.45;
        pointer-events: auto;
    }

    .btn-toggle-password:hover {
        opacity: 1 !important;
        color: #06b6d4 !important;
    }

    .btn-toggle-password:focus,
    .btn-toggle-password:active {
        outline: none !important;
        box-shadow: none !important;
    }

    .btn-toggle-password:focus:not(:hover) {
        color: rgba(255, 255, 255, 0.45) !important;
        opacity: 0.5 !important;
    }
</style>

<div class="login-viewport">
    <div class="login-wrapper">
        <div class="login-container">
            <div class="login-header text-center">
                <h2>Acceso al Sistema</h2>
                <span>Autenticación de Terminal</span>
            </div>

            @if ($errors->any())
                <div class="custom-error-panel">
                    <i class="fas fa-exclamation-circle" style="color: #ef4444; margin-top: 3px;"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="email" name="email" class="form-control form-glass" placeholder="Correo Corporativo" value="{{ old('email') }}" required autocomplete="email" autofocus>
                </div>

                <div class="mb-4">
                    <div class="password-input-wrapper" id="wrap_loginPassword">
                        <input type="password" id="loginPassword" name="password" class="form-control form-glass" placeholder="Clave de Acceso" required autocomplete="current-password" oninput="checkInputVal(this)">
                        <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('loginPassword', this)" title="Mostrar / Ocultar contraseña" aria-label="Mostrar contraseña" tabindex="-1">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login-submit w-100 border-0">
                    <span>Iniciar Sesión</span>
                    <i class="fas fa-sign-in-alt"></i>
                </button>
            </form>
        </div>

        <!-- TEXTO INFORMATIVO BAJO EL CUADRO -->
        <p class="login-info-text">
            Recuerda que para tener acceso al sistema, se debió de haber aprobado previamente la solicitud para tu proyecto empresarial. Si no lo has hecho, te invitamos a ponerte en contacto con nosotros.
        </p>
    </div>
</div>

<script>
function checkInputVal(input) {
    const wrap = input.closest('.password-input-wrapper');
    if (wrap) {
        if (input.value.trim().length > 0) {
            wrap.classList.add('has-value');
        } else {
            wrap.classList.remove('has-value');
        }
    }
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        if (icon) {
            icon.className = 'far fa-eye-slash';
        }
        btn.setAttribute('aria-label', 'Ocultar contraseña');
    } else {
        input.type = 'password';
        if (icon) {
            icon.className = 'far fa-eye';
        }
        btn.setAttribute('aria-label', 'Mostrar contraseña');
    }

    btn.blur();
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.password-input-wrapper input').forEach(inp => checkInputVal(inp));
});
</script>
@endsection

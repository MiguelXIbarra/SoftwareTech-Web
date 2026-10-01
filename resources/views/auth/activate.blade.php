@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    .auth-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        color: #ffffff;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .auth-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.06) 0%, transparent 60%);
        z-index: 1;
        pointer-events: none;
    }

    .card-auth-neon {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
        border-radius: 20px;
        padding: 40px;
        width: 100%;
        max-width: 450px;
        position: relative;
        z-index: 5;
    }

    .form-glass {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-radius: 12px !important;
        color: #ffffff !important;
        padding: 14px 18px !important;
        transition: all 0.3s ease !important;
    }

    .form-glass:focus {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2) !important;
        background: rgba(255, 255, 255, 0.05) !important;
    }

    .btn-portal {
        background: #06b6d4 !important;
        color: #ffffff !important;
        font-weight: 700;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
        transition: all 0.3s ease;
    }

    .btn-portal:hover {
        background: #0891b2 !important;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.6);
        transform: translateY(-2px);
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

    /* Mostrar botón si el wrapper está en hover, o si el input tiene foco o texto */
    .password-input-wrapper:hover .btn-toggle-password,
    .password-input-wrapper:focus-within .btn-toggle-password,
    .password-input-wrapper.has-value .btn-toggle-password {
        opacity: 0.45;
        pointer-events: auto;
    }

    /* Solo al pasar el cursor (hover) se activa el color cian */
    .btn-toggle-password:hover {
        opacity: 1 !important;
        color: #06b6d4 !important;
    }

    /* Prevenir que se quede cian o con borde al hacer clic (focus) */
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

<div class="auth-viewport">
    <div class="card-auth-neon text-center">
        <h3 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">Activar Terminal</h3>
        <p class="text-white-50 small mb-4">Hola <b class="text-white">{{ $user->name }}</b>, genera tu contraseña de acceso seguro para la plataforma.</p>

        @if ($errors->any())
            <div class="mb-4 text-start p-3" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); border-left: 4px solid #ef4444; border-radius: 12px; backdrop-filter: blur(10px);">
                <div class="d-flex align-items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-danger mt-1" style="font-size: 0.9rem; flex-shrink: 0;"></i>
                    <div style="color: #fecaca; font-size: 0.82rem; line-height: 1.45;">
                        @foreach ($errors->all() as $error)
                            <div class="fw-semibold">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 text-start p-3" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); border-left: 4px solid #ef4444; border-radius: 12px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 0.95rem;"></i>
                    <span style="color: #fecaca; font-size: 0.82rem; font-weight: 600;">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <form action="{{ route('portal.activate.submit', $token) }}" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label text-white-50 small fw-bold">Nueva Contraseña <span class="text-info" style="font-size: 0.72rem;">(mínimo 8 caracteres)</span></label>
                <div class="password-input-wrapper" id="wrap_password">
                    <input type="password" id="password" name="password" class="form-control form-glass" minlength="8" required placeholder="••••••••" oninput="checkInputVal(this)">
                    <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password', this)" title="Mostrar / Ocultar contraseña" aria-label="Mostrar contraseña" tabindex="-1">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label text-white-50 small fw-bold">Confirmar Contraseña</label>
                <div class="password-input-wrapper" id="wrap_password_confirmation">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-glass" minlength="8" required placeholder="••••••••" oninput="checkInputVal(this)">
                    <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)" title="Mostrar / Ocultar contraseña" aria-label="Mostrar contraseña" tabindex="-1">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-portal w-100 border-0 py-2.5">Establecer y Acceder</button>
        </form>
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

    // Desenfocar el botón inmediatamente para que no retenga estado de focus
    btn.blur();
}

// Inicializar en caso de que el navegador autocomplete valores
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.password-input-wrapper input').forEach(inp => checkInputVal(inp));
});
</script>
@endsection

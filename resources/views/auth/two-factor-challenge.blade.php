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
        background: radial-gradient(circle at 50% 40%, rgba(16, 185, 129, 0.12) 0%, transparent 60%),
                    radial-gradient(circle at 80% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 50%);
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
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top-color: rgba(16, 185, 129, 0.3) !important;
        backdrop-filter: blur(28px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(28px) saturate(160%) !important;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.7), 0 0 30px rgba(16, 185, 129, 0.08) !important;
        border-radius: 24px;
        padding: 40px 36px;
        width: 100%;
    }

    .twofactor-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #34d399;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 16px auto;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
    }

    .login-header h2 {
        font-size: 1.55rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 4px;
        color: #ffffff;
        text-align: center;
    }

    .login-header span {
        font-family: monospace;
        font-size: 0.72rem;
        color: #34d399;
        letter-spacing: 2px;
        display: block;
        margin-bottom: 20px;
        text-transform: uppercase;
        text-align: center;
    }

    .input-2fa-code {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 2px solid rgba(16, 185, 129, 0.3) !important;
        border-radius: 14px !important;
        color: #ffffff !important;
        font-family: monospace !important;
        font-size: 1.75rem !important;
        font-weight: 800 !important;
        letter-spacing: 10px !important;
        text-align: center !important;
        padding: 14px 10px !important;
        transition: all 0.3s ease !important;
        width: 100%;
        outline: none;
    }

    .input-2fa-code:focus {
        background: rgba(16, 185, 129, 0.04) !important;
        border-color: #34d399 !important;
        box-shadow: 0 0 25px rgba(16, 185, 129, 0.25) !important;
    }

    .btn-submit-2fa {
        background: linear-gradient(135deg, #10b981, #06b6d4) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 0.88rem !important;
        letter-spacing: 0.8px !important;
        padding: 13px !important;
        border-radius: 12px !important;
        text-transform: uppercase !important;
        transition: all 0.3s ease !important;
        width: 100%;
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.35);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-submit-2fa:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 28px rgba(16, 185, 129, 0.5);
        color: #ffffff;
    }

    .btn-cancel-link {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.45);
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: color 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-cancel-link:hover {
        color: #ffffff;
    }

    .authenticator-pills {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .auth-pill {
        font-size: 0.68rem;
        color: rgba(255, 255, 255, 0.5);
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>

<div class="login-viewport">
    <div class="login-wrapper">
        <div class="login-container text-center">

            <div class="twofactor-icon-wrap">
                <i class="fas fa-shield-alt"></i>
            </div>

            <div class="login-header">
                <h2>Verificación 2FA</h2>
                <span>SEGURIDAD DE DOBLE FACTOR</span>
            </div>

            <p style="font-size: 0.82rem; color: rgba(255,255,255,0.65); line-height: 1.5; margin-bottom: 22px;">
                Introduce el código de 6 dígitos generado por tu aplicación autenticadora (Google o Microsoft Authenticator).
            </p>

            @if($errors->has('code'))
                <div class="alert mb-3 p-2 text-start d-flex align-items-center gap-2" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35); color: #f87171; border-radius: 10px; font-size: 0.8rem;">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    <div>{{ $errors->first('code') }}</div>
                </div>
            @endif

            <form action="{{ route('two-factor.verify') }}" method="POST" id="formTwoFactorVerify">
                @csrf

                <div class="mb-3">
                    <input type="text" 
                           id="code" 
                           name="code" 
                           class="input-2fa-code" 
                           placeholder="••••••" 
                           maxlength="6" 
                           inputmode="numeric" 
                           pattern="[0-9]*" 
                           autocomplete="one-time-code" 
                           autofocus 
                           required>
                </div>

                <div class="mb-3">
                    <button type="submit" id="btnSubmit2FA" class="btn-submit-2fa">
                        <i class="fas fa-unlock-alt"></i> Verificar y Entrar
                    </button>
                </div>
            </form>

            <form action="{{ route('two-factor.cancel') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn-cancel-link">
                    <i class="fas fa-arrow-left"></i> Volver a Iniciar Sesión
                </button>
            </form>

            <div class="authenticator-pills">
                <span class="auth-pill"><i class="fab fa-google text-danger"></i> Google Authenticator</span>
                <span class="auth-pill"><i class="fab fa-microsoft text-info"></i> Microsoft Authenticator</span>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const codeInput = document.getElementById('code');
    const form = document.getElementById('formTwoFactorVerify');
    const submitBtn = document.getElementById('btnSubmit2FA');
    let hasSubmitted = false;

    if (form) {
        form.addEventListener('submit', (e) => {
            if (hasSubmitted) {
                e.preventDefault();
                return false;
            }
            hasSubmitted = true;
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
            }
        });
    }

    if (codeInput) {
        codeInput.focus();

        codeInput.addEventListener('input', (e) => {
            const val = e.target.value.replace(/\D/g, '');
            e.target.value = val;
            if (val.length === 6 && !hasSubmitted) {
                if (form.requestSubmit) {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            }
        });
    }
});
</script>
@endsection

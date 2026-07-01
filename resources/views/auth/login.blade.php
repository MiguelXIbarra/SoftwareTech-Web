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
        max-width: 440px;
        width: 100%;
        position: relative;
        z-index: 5;
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
</style>

<div class="login-viewport">
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
                <input type="password" name="password" class="form-control form-glass" placeholder="Clave de Acceso" required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-login-submit w-100 border-0">
                <span>Iniciar Sesión</span>
                <i class="fas fa-sign-in-alt"></i>
            </button>
        </form>
    </div>
</div>
@endsection

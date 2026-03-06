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

    /* DESACTIVAR BOLITAS FLOTANTES */
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
        /* CENTRADO VERTICAL */
        justify-content: center;
        /* CENTRADO HORIZONTAL */
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
        /* AJUSTE FINO PARA QUE NO SE VEA ABAJO */
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
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
        outline: none;
    }
</style>
@endpush

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content">
    <div class="glass-terminal-v2">
        <div class="text-center mb-5">
            <h2 class="text-white fw-bold" style="letter-spacing: 2px;">ACCESO AL LAB</h2>
            <p class="small opacity-50 text-white" style="letter-spacing: 2px;">IDENTIDAD VERIFICADA</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">USUARIO / EMAIL</label>
                <input type="email" name="email" class="form-control-tech w-100" required autofocus>
            </div>
            <div class="mb-5 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">CONTRASEÑA</label>
                <input type="password" name="password" class="form-control-tech w-100" required>
            </div>
            <button type="submit" class="btn w-100 text-white mb-4"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none;">
                ENTRAR AL SISTEMA
            </button>
            <div class="d-flex justify-content-between">
                <a href="{{ route('register') }}" class="text-white-50 small text-decoration-none fw-bold">Crear
                    Cuenta</a>
                <a href="{{ route('password.request') }}"
                    class="text-white-50 small text-decoration-none fw-bold">¿Olvidaste tu clave?</a>
            </div>
        </form>
    </div>
</div>
@endsection
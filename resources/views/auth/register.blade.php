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
        padding: 40px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8);
        margin-top: -20px;
        /* AJUSTE FINO */
    }

    .form-control-tech {
        background: rgba(20, 20, 20, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 12px;
        color: #fff !important;
        padding: 10px;
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
        <div class="text-center mb-4">
            <h2 class="text-white fw-bold" style="letter-spacing: 2px;">NUEVA IDENTIDAD</h2>
            <p class="small opacity-50 text-white" style="letter-spacing: 2px;">IDENTIDAD OPERATIVA</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3 text-start">
                <label class="small fw-bold mb-1 opacity-75 text-white">NOMBRE COMPLETO</label>
                <input type="text" name="name" class="form-control-tech w-100" required>
            </div>
            <div class="mb-3 text-start">
                <label class="small fw-bold mb-1 opacity-75 text-white">CORREO ELECTRÓNICO</label>
                <input type="email" name="email" class="form-control-tech w-100" required>
            </div>
            <div class="row mb-4">
                <div class="col-md-6 text-start">
                    <label class="small fw-bold mb-1 opacity-75 text-white">CONTRASEÑA</label>
                    <div class="password-container">
                        <input type="password" name="password" class="form-control-tech w-100" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
                <div class="col-md-6 text-start">
                    <label class="small fw-bold mb-1 opacity-75 text-white">CONFIRMAR</label>
                    <div class="password-container">
                        <input type="password" name="password_confirmation" class="form-control-tech w-100" required>
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn w-100 text-white mb-3"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 12px; font-weight: 800; border: none;">
                REGISTRARSE</button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-white-50 small text-decoration-none fw-bold">Inicia
                    Sesión</a>
            </div>
        </form>
    </div>
</div>
@endsection
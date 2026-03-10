@extends('layouts.auth')

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
        padding: 50px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8);
        margin-top: -20px;
    }

    .brand-accent {
        color: #00d4ff;
        text-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    }
</style>
@endpush

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content">
    <div class="glass-terminal-v2 text-center">

        <div class="mb-4">
            <i class="fas fa-envelope-open-text fa-4x mb-4 brand-accent"></i>
            <h2 class="text-white fw-bold" style="letter-spacing: 2px;">VERIFICAR TERMINAL</h2>
            <p class="small opacity-50 text-white" style="letter-spacing: 2px;">IDENTIDAD PENDIENTE</p>
        </div>

        <div class="mb-4">
            @if (session('resent'))
            <div class="alert alert-success border-0 mb-4"
                style="background: rgba(0, 212, 255, 0.1); color: #00d4ff; border-radius: 12px;">
                {{ __('Un nuevo enlace de verificación ha sido enviado a tu dirección de correo.') }}
            </div>
            @endif

            <p class="text-white-50">
                Antes de acceder al Lab, por favor verifica tu cuenta mediante el enlace que enviamos a tu correo
                electrónico.
            </p>
            <p class="text-white-50 small mt-2">
                ¿No recibiste el protocolo de verificación?
            </p>
        </div>

        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn w-100 text-white mb-4"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                SOLICITAR NUEVO ENLACE
            </button>
        </form>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-white-50 small text-decoration-none fw-bold">Volver al Inicio</a>
        </div>
    </div>
</div>
@endsection
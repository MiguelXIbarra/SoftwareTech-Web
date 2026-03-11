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

    /* Ocultar elementos del layout principal si es necesario */
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
        max-width: 480px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.8);
        margin-top: -20px;
    }

    .form-control-tech {
        background: rgba(20, 20, 20, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        border-radius: 12px;
        color: #fff !important;
        padding: 12px 15px !important;
    }

    .form-control-tech:focus {
        border-color: #00d4ff !important;
        outline: none;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
    }
</style>
@endpush

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content">
    <div class="glass-terminal-v2 text-center">
        <div class="py-4">
            <i class="fas fa-check-circle fa-4x mb-4"
                style="color: #00d4ff; filter: drop-shadow(0 0 15px rgba(0, 212, 255, 0.4));"></i>
            <h2 class="text-white fw-bold mb-3" style="letter-spacing: 2px;">¡CONTRASEÑA ACTUALIZADA!</h2>
            <p class="text-white-50 mb-5" style="font-size: 1rem;">Se han reestablecido las credenciales de acceso a tu
                terminal de forma exitosa. El protocolo de seguridad ha finalizado.</p>

            <button type="button" onclick="window.location.href='{{ route('login') }}'" class="btn w-100 text-white"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                VOLVER AL LOGIN
            </button>
        </div>
    </div>
</div>
@endsection
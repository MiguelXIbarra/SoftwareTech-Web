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
</style>

<div class="auth-viewport">
    <div class="card-auth-neon text-center">
        <h3 class="fw-bold text-white mb-2" style="letter-spacing: -0.5px;">Activar Terminal</h3>
        <p class="text-white-50 small mb-4">Hola <b class="text-white">{{ $user->name }}</b>, genera tu contraseña de acceso seguro para la plataforma.</p>

        @if ($errors->any())
            <div class="alert alert-danger bg-danger rgba-10 text-danger border-0 small text-start mb-3" style="border-radius: 10px;">
                @foreach ($errors->all() as $error)
                    <div>● {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('portal.activate.submit', $token) }}" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label text-white-50 small fw-bold">Nueva Contraseña</label>
                <input type="password" name="password" class="form-control form-glass" required>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label text-white-50 small fw-bold">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control form-glass" required>
            </div>
            <button type="submit" class="btn-portal w-100 border-0 py-2.5">Establecer y Acceder</button>
        </form>
    </div>
</div>
@endsection

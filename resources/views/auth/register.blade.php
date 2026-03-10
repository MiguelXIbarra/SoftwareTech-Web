@extends('layouts.auth')

@push('styles')
<style>
    /* 1. MATAR ICONOS NATIVOS DEL NAVEGADOR (Edge, Chrome, Safari) */
    input::-ms-reveal,
    input::-ms-clear {
        display: none !important;
    }

    input::-webkit-contacts-auto-fill-button,
    input::-webkit-credentials-auto-fill-button {
        display: none !important;
        visibility: hidden !important;
        pointer-events: none !important;
        position: absolute !important;
        right: 0 !important;
    }

    /* Ajuste para que el texto no se encime con el ojo */
    .form-control-tech {
        padding-right: 45px !important;
    }
</style>
@endpush

@section('content')
<div class="auth-overlay-fix"></div>
<div class="auth-content w-100">
    <div class="glass-terminal-v2 mx-auto">
        <div class="text-center mb-4">
            <h2 class="text-white fw-bold" style="letter-spacing: 2px;">NUEVA IDENTIDAD</h2>
            <p class="small opacity-50 text-white" style="letter-spacing: 2px;">IDENTIDAD OPERATIVA</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3 text-start">
                <label class="small fw-bold mb-1 opacity-75 text-white">NOMBRE COMPLETO</label>
                <input type="text" name="name" class="form-control-tech w-100 @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required autofocus>
                @error('name')
                <span class="text-danger small d-block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3 text-start">
                <label class="small fw-bold mb-1 opacity-75 text-white">CORREO ELECTRÓNICO</label>
                <input type="email" name="email" class="form-control-tech w-100 @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                <span class="text-danger small d-block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="row mb-4">
                <div class="col-md-6 text-start">
                    <label class="small fw-bold mb-1 opacity-75 text-white">CONTRASEÑA</label>
                    <div class="password-container">
                        <input type="password" name="password"
                            class="form-control-tech w-100 @error('password') is-invalid @enderror" required
                            autocomplete="new-password">
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="col-md-6 text-start">
                    <label class="small fw-bold mb-1 opacity-75 text-white">CONFIRMAR</label>
                    <div class="password-container">
                        <input type="password" name="password_confirmation" class="form-control-tech w-100" required
                            autocomplete="new-password">
                        <i class="fas fa-eye toggle-password"></i>
                    </div>
                </div>

                @error('password')
                <div class="col-12 mt-2">
                    <span class="text-danger small d-block">{{ $message }}</span>
                </div>
                @enderror
            </div>

            <button type="submit" class="btn w-100 text-white mb-3"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 12px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                REGISTRARSE
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-white-50 small text-decoration-none fw-bold">Ya tengo
                    cuenta</a>
            </div>
        </form>
    </div>
</div>
@endsection
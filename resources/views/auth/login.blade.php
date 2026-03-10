@extends('layouts.auth')

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
                <input type="email" name="email" value="{{ old('email') }}"
                    class="form-control-tech w-100 @error('email') is-invalid @enderror" required autofocus>
                @error('email')
                <span class="text-danger small mt-2 d-block" style="font-size: 0.75rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5 text-start">
                <label class="small fw-bold mb-2 opacity-75 text-white">CONTRASEÑA</label>
                <div class="password-container">
                    <input type="password" name="password"
                        class="form-control-tech w-100 @error('password') is-invalid @enderror"
                        placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
                    <i class="fas fa-eye toggle-password"></i>
                </div>
                @error('password')
                <span class="text-danger small mt-2 d-block" style="font-size: 0.75rem;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn w-100 text-white mb-4"
                style="background: linear-gradient(135deg, #8a2be2, #00d4ff); border-radius: 12px; padding: 15px; font-weight: 800; border: none; box-shadow: 0 10px 20px rgba(0, 212, 255, 0.2);">
                INICIAR SESIÓN
            </button>

            <div class="d-flex justify-content-between">
                <a href="{{ route('register') }}" class="text-white-50 small text-decoration-none fw-bold">Crear
                    Cuenta</a>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-white-50 small text-decoration-none fw-bold">
                    ¿Olvidaste tu contraseña?
                </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    html, body {
        overflow-y: hidden !important;
    }

    .admin-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        height: 100vh;
        max-height: 100vh;
        color: #ffffff;
        position: relative;
        padding: 70px 24px 20px 24px !important;
        overflow: hidden !important;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 50% 20%, rgba(6, 182, 212, 0.06) 0%, transparent 55%),
                    radial-gradient(circle at 80% 60%, rgba(138, 43, 226, 0.04) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .portal-container {
        position: relative;
        z-index: 5;
        max-width: 1600px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        height: 100%;
        max-height: calc(100vh - 90px);
        overflow: hidden;
    }

    /* HEADER */
    .config-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 8px;
        flex-shrink: 0;
    }

    .header-tag-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.25);
        padding: 4px 12px;
        border-radius: 20px;
        font-family: monospace;
        font-size: 0.68rem;
        font-weight: 700;
        color: #22d3ee;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 4px;
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.15);
    }

    .header-tag-pill .pulse-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #06b6d4;
        box-shadow: 0 0 8px #06b6d4;
        animation: pulseCyber 2s infinite ease-in-out;
    }

    @keyframes pulseCyber {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.8); }
    }

    .header-main-title {
        font-size: 1.85rem;
        font-weight: 800 !important;
        letter-spacing: -0.8px;
        background: linear-gradient(135deg, #ffffff 40%, rgba(255, 255, 255, 0.7) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        line-height: 1.1;
    }

    .btn-back-projects {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.85);
        padding: 8px 18px;
        border-radius: 12px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        backdrop-filter: blur(12px);
        transition: all 0.25s ease;
    }

    .btn-back-projects:hover {
        background: rgba(6, 182, 212, 0.1);
        border-color: rgba(6, 182, 212, 0.35);
        color: #ffffff;
        transform: translateX(-3px);
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
    }

    /* CONTROLS BAR */
    .gallery-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 10px;
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 14px;
        padding: 6px 14px;
        backdrop-filter: blur(16px);
    }

    .gallery-indicators-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-pills-nav {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sec-pill-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.7rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.55);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
    }

    .sec-pill-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .sec-pill-btn.active {
        background: rgba(6, 182, 212, 0.15);
        border-color: #06b6d4;
        color: #22d3ee;
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.25);
    }

    .keyboard-hint {
        font-family: monospace;
        font-size: 0.68rem;
        color: rgba(255, 255, 255, 0.4);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.02);
        padding: 3px 10px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .key-badge {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        padding: 1px 5px;
        font-size: 0.65rem;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.4);
    }

    .gallery-scroll-btn {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.85);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
        font-size: 0.75rem;
    }

    .gallery-scroll-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.25), rgba(138, 43, 226, 0.25));
        border-color: #06b6d4;
        color: #ffffff;
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.35);
        transform: translateY(-1px);
    }

    .gallery-scroll-btn:disabled {
        opacity: 0.25;
        cursor: not-allowed;
    }

    /* HORIZONTAL TRACK - SCROLLBAR HIDDEN */
    .gallery-track {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        padding: 4px 6px 8px 4px;
        cursor: grab;
        user-select: none;
        scrollbar-width: none !important; /* Firefox */
        -ms-overflow-style: none !important; /* IE & Edge */
        flex: 1;
        min-height: 0;
        height: 100%;
    }

    .gallery-track::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    .gallery-track.is-dragging {
        cursor: grabbing;
        scroll-behavior: auto !important;
        scroll-snap-type: none !important;
    }

    /* CARDS - EXACTAMENTE 3 CARTAS VISIBLES EN PANTALLA */
    .config-card {
        flex: 0 0 calc((100% - 40px) / 3);
        width: calc((100% - 40px) / 3);
        min-width: 320px;
        height: calc(100vh - 175px);
        max-height: 470px;
        min-height: 420px;
        scroll-snap-align: start !important;
        scroll-snap-stop: always !important;
        background: rgba(15, 23, 42, 0.55) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(28px) !important;
        -webkit-backdrop-filter: blur(28px) !important;
        border-radius: 22px;
        padding: 20px 24px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.65), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        box-sizing: border-box;
    }

    .config-card:hover {
        border-color: rgba(6, 182, 212, 0.4) !important;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.75), 0 0 25px rgba(6, 182, 212, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
    }

    @media (max-width: 1024px) {
        .config-card {
            flex: 0 0 calc((100% - 20px) / 2);
            width: calc((100% - 20px) / 2);
        }
    }

    @media (max-width: 640px) {
        .config-card {
            flex: 0 0 100%;
            width: 100%;
            height: auto;
            min-height: 440px;
            padding: 18px;
        }
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .section-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.25);
        color: #06b6d4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .form-label-cyber {
        font-family: monospace;
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.55);
        letter-spacing: 1.2px;
        text-transform: uppercase;
        margin-bottom: 4px;
        display: block;
    }

    .input-cyber {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        border-radius: 9px;
        padding: 8px 12px;
        font-size: 0.85rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .input-cyber:focus {
        background: rgba(6, 182, 212, 0.03) !important;
        border-color: rgba(6, 182, 212, 0.6) !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2) !important;
        outline: none;
    }

    .btn-cyber-primary {
        background: linear-gradient(135deg, #06b6d4, #0284c7);
        border: none;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.76rem;
        letter-spacing: 0.6px;
        padding: 11px 16px;
        border-radius: 10px;
        text-transform: uppercase;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        width: 100%;
        cursor: pointer;
        flex-shrink: 0;
        margin-top: auto; /* Anclado limpiamente al fondo de la tarjeta */
    }

    .btn-cyber-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(6, 182, 212, 0.45);
        color: #ffffff;
    }

    .info-badge {
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: #22d3ee;
        padding: 3px 8px;
        border-radius: 5px;
        font-size: 0.7rem;
        font-family: monospace;
    }

    /* PASSWORD EYE TOGGLE - VISIBLE SOLO AL PASAR EL CURSOR (HOVER) */
    .password-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input-wrapper .input-cyber {
        padding-right: 42px !important;
    }

    .btn-toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        color: rgba(255, 255, 255, 0.5);
        padding: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease, color 0.2s ease;
        border-radius: 6px;
        z-index: 10;
        -webkit-tap-highlight-color: transparent;
    }

    .password-input-wrapper:hover .btn-toggle-password,
    .password-input-wrapper:focus-within .btn-toggle-password {
        opacity: 1;
        visibility: visible;
        color: rgba(255, 255, 255, 0.75);
    }

    .btn-toggle-password:hover {
        color: #06b6d4 !important;
    }

    /* CYBER SWITCH */
    .cyber-switch-wrap {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.04);
        border-radius: 12px;
        margin-bottom: 10px;
    }

    .cyber-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin-bottom: 0;
    }

    .cyber-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .cyber-slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(255, 255, 255, 0.1);
        transition: .3s cubic-bezier(0.16, 1, 0.3, 1);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .cyber-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s cubic-bezier(0.16, 1, 0.3, 1);
        border-radius: 50%;
    }

    input:checked + .cyber-slider {
        background: #06b6d4;
        border-color: #22d3ee;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.4);
    }

    input:checked + .cyber-slider:before {
        transform: translateX(20px);
    }

    /* 2FA 2-COLUMN LAYOUT */
    .two-factor-grid {
        display: grid;
        grid-template-columns: 104px 1fr;
        gap: 12px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 14px;
        padding: 10px 12px;
        align-items: center;
        margin-bottom: 6px;
    }

    .qr-container {
        background: #ffffff;
        border-radius: 9px;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .qr-container img {
        width: 96px;
        height: 96px;
        display: block;
        border-radius: 6px;
    }

    .secret-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        min-width: 0;
    }

    .secret-key-display {
        font-family: monospace;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: #34d399;
        background: rgba(16, 185, 129, 0.08);
        border: 1px dashed rgba(16, 185, 129, 0.3);
        border-radius: 8px;
        padding: 5px 8px;
        word-break: break-all;
    }

    .btn-copy-secret {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.85);
        border-radius: 7px;
        padding: 4px 10px;
        font-size: 0.68rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        align-self: flex-start;
    }

    .btn-copy-secret:hover {
        background: rgba(16, 185, 129, 0.15);
        border-color: #10b981;
        color: #ffffff;
    }

    .compat-badge {
        font-size: 0.65rem;
        color: rgba(255, 255, 255, 0.45);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .compat-badge span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.62rem;
        color: rgba(255, 255, 255, 0.7);
    }
</style>

<div class="admin-viewport">
    <div class="container-fluid portal-container px-lg-4">

        <!-- Top Header -->
        <div class="config-header-row">
            <div>
                <div class="header-tag-pill">
                    <span class="pulse-dot"></span>
                    <i class="fas fa-shield-alt"></i> SEGURIDAD & IDENTIDAD CORPORATIVA
                </div>
                <h1 class="header-main-title">
                    Configuración de Cuenta
                </h1>
            </div>
            <div>
                <a href="{{ route('portal.dashboard') }}" class="btn-back-projects">
                    <i class="fas fa-arrow-left"></i> Volver a Proyectos
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert mb-3 p-3 d-flex align-items-center gap-3" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; border-radius: 12px; max-width: 900px;">
                <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
                <div style="font-size: 0.9rem;">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert mb-3 p-3" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; border-radius: 12px; max-width: 900px;">
                <div class="d-flex align-items-center gap-2 mb-2 font-mono" style="font-size: 0.8rem; font-weight: 700;">
                    <i class="fas fa-exclamation-triangle"></i> SE DETECTARON ERRORES:
                </div>
                <ul class="mb-0 ps-3" style="font-size: 0.85rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- GALLERY NAVIGATION BAR -->
        <div class="gallery-header-bar">
            <div class="gallery-indicators-wrap">
                <div class="section-pills-nav">
                    <button type="button" class="sec-pill-btn active" id="pill-0" onclick="goToCard(0)" title="Ir a Perfil Corporativo">
                        <i class="fas fa-building text-info"></i> <span>Perfil</span>
                    </button>
                    <button type="button" class="sec-pill-btn" id="pill-1" onclick="goToCard(1)" title="Ir a Contraseña">
                        <i class="fas fa-key" style="color: #c084fc;"></i> <span>Contraseña</span>
                    </button>
                    <button type="button" class="sec-pill-btn" id="pill-2" onclick="goToCard(2)" title="Ir a Verificación 2FA">
                        <i class="fas fa-shield-alt text-success"></i> <span>2FA</span>
                    </button>
                    <button type="button" class="sec-pill-btn" id="pill-3" onclick="goToCard(3)" title="Ir a Notificaciones">
                        <i class="fas fa-bell text-warning"></i> <span>Notificaciones</span>
                    </button>
                </div>
                <span class="keyboard-hint d-none d-lg-inline-flex">
                    <i class="fas fa-mouse text-info me-1"></i> Rueda o teclas <span class="key-badge">←</span> <span class="key-badge">→</span>
                </span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="gallery-scroll-btn" id="btnScrollPrev" onclick="scrollGallery('prev')" title="Sección anterior (←)" aria-label="Anterior">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="gallery-scroll-btn" id="btnScrollNext" onclick="scrollGallery('next')" title="Siguiente sección (→)" aria-label="Siguiente">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- HORIZONTAL CAROUSEL GALLERY TRACK -->
        <div class="gallery-track" id="galleryTrack" tabindex="0">

            <!-- 1. PERFIL CORPORATIVO -->
            <div class="config-card" id="card-1">
                <div>
                    <div class="section-title">
                        <div class="section-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <span>Perfil Corporativo</span>
                    </div>

                    <form action="{{ route('portal.configuracion.perfil') }}" method="POST" id="formPerfil">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label-cyber">Nombre / Empresa</label>
                            <input type="text" name="name" class="input-cyber" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-cyber">Correo Electrónico</label>
                            <input type="email" name="email" class="input-cyber" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="p-3 rounded-3 mb-3" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04);">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="form-label-cyber mb-0">Rol del Sistema</span>
                                <span class="info-badge">CLIENTE VERIFICADO</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="form-label-cyber mb-0">Estado de Cuenta</span>
                                <span class="text-success font-mono" style="font-size: 0.72rem;"><i class="fas fa-shield-alt me-1"></i> Activa & Protegida</span>
                            </div>
                        </div>
                    </form>
                </div>

                <button type="submit" form="formPerfil" class="btn-cyber-primary">
                    <i class="fas fa-save"></i> Guardar Cambios de Perfil
                </button>
            </div>

            <!-- 2. CONTRASEÑA DE ACCESO -->
            <div class="config-card" id="card-2">
                <div>
                    <div class="section-title">
                        <div class="section-icon" style="background: rgba(138, 43, 226, 0.1); border-color: rgba(138, 43, 226, 0.25); color: #c084fc;">
                            <i class="fas fa-key"></i>
                        </div>
                        <span>Contraseña de Acceso</span>
                    </div>

                    <form action="{{ route('portal.configuracion.password') }}" method="POST" id="formPassword">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label-cyber">Contraseña Actual</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="current_password" name="current_password" class="input-cyber" placeholder="••••••••••••" required>
                                <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('current_password', this)" title="Mostrar contraseña" tabindex="-1">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-cyber">Nueva Contraseña</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password" name="password" class="input-cyber" placeholder="Mínimo 8 caracteres" required>
                                <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password', this)" title="Mostrar contraseña" tabindex="-1">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-cyber">Confirmar Nueva Contraseña</label>
                            <div class="password-input-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation" class="input-cyber" placeholder="Repite la contraseña" required>
                                <button type="button" class="btn-toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)" title="Mostrar contraseña" tabindex="-1">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <button type="submit" form="formPassword" class="btn-cyber-primary" style="background: linear-gradient(135deg, #8a2be2, #06b6d4);">
                    <i class="fas fa-shield-alt"></i> Actualizar Contraseña
                </button>
            </div>

            <!-- 3. VERIFICACIÓN 2FA -->
            <div class="config-card" id="card-3">
                <div>
                    <div class="section-title">
                        <div class="section-icon" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.25); color: #34d399;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Verificación 2FA</span>
                    </div>

                    <form action="{{ route('portal.configuracion.twofactor') }}" method="POST" id="form2FA">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="two_factor_secret" value="{{ $totpSecret }}">

                        <p style="font-size: 0.76rem; color: rgba(255,255,255,0.6); line-height: 1.4; margin-bottom: 8px;">
                            Refuerza el acceso con códigos TOTP compatibles con Google y Microsoft Authenticator.
                        </p>

                        <div class="cyber-switch-wrap mb-2" style="padding: 7px 12px;">
                            <div>
                                <div class="fw-bold" style="font-size: 0.78rem; color: #fff;">Autenticación 2FA</div>
                                <div class="text-muted" style="font-size: 0.65rem;">Solicitar código de seguridad</div>
                            </div>
                            <label class="cyber-switch">
                                <input type="checkbox" name="two_factor_enabled" value="1" {{ !empty($isTwoFactorActive) ? 'checked' : '' }}>
                                <span class="cyber-slider"></span>
                            </label>
                        </div>

                        <!-- 2 COLUMNAS: QR + LLAVE DE CONFIGURACIÓN -->
                        <div class="two-factor-grid">
                            <!-- Col 1: Código QR -->
                            <div class="qr-container" title="Escanear con Google Authenticator o Microsoft Authenticator">
                                <img src="{{ $qrCodeUrl ?? '' }}" alt="Código QR 2FA" width="96" height="96" loading="lazy">
                            </div>

                            <!-- Col 2: Llave de Sincronización Manual -->
                            <div class="secret-container">
                                <span class="form-label-cyber mb-0" style="font-size: 0.62rem;">Llave de Configuración</span>
                                <div class="secret-key-display">{{ chunk_split($totpSecret ?? 'ST2FASEC9921ABCD', 4, ' ') }}</div>
                                <div class="d-flex align-items-center justify-content-between gap-1 mt-1">
                                    <button type="button" class="btn-copy-secret" onclick="copySecret('{{ $totpSecret ?? 'ST2FASEC9921ABCD' }}', this)">
                                        <i class="far fa-copy"></i> Copiar Llave
                                    </button>
                                </div>
                                <div class="compat-badge mt-1">
                                    <span><i class="fab fa-google text-danger"></i> Google</span>
                                    <span><i class="fab fa-microsoft text-info"></i> Microsoft</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <button type="submit" form="form2FA" class="btn-cyber-primary" style="background: linear-gradient(135deg, #10b981, #06b6d4);">
                    <i class="fas fa-lock"></i> Guardar Ajustes 2FA
                </button>
            </div>

            <!-- 4. NOTIFICACIONES -->
            <div class="config-card" id="card-4">
                <div>
                    <div class="section-title">
                        <div class="section-icon" style="background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.25); color: #fbbf24;">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span>Notificaciones</span>
                    </div>

                    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 mb-2" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.2);">
                        <i class="fas fa-envelope text-warning" style="font-size: 0.82rem;"></i>
                        <div style="font-size: 0.72rem; color: rgba(255, 255, 255, 0.8); line-height: 1.3;">
                            Las alertas se enviarán a: <strong class="text-warning font-mono">{{ $user->email }}</strong>
                        </div>
                    </div>

                    <form action="{{ route('portal.configuracion.notificaciones') }}" method="POST" id="formNotificaciones">
                        @csrf
                        @method('PUT')

                        <div class="cyber-switch-wrap mb-2" style="padding: 7px 12px;">
                            <div>
                                <div class="fw-bold" style="font-size: 0.78rem; color: #fff;">Hitos y Entregables</div>
                                <div class="text-muted" style="font-size: 0.65rem;">Avisos por correo cuando un hito es completado o aprobado</div>
                            </div>
                            <label class="cyber-switch">
                                <input type="checkbox" name="notif_milestones" value="1" {{ $user->getNotificationPreference('notif_milestones', true) ? 'checked' : '' }}>
                                <span class="cyber-slider"></span>
                            </label>
                        </div>

                        <div class="cyber-switch-wrap mb-2" style="padding: 7px 12px;">
                            <div>
                                <div class="fw-bold" style="font-size: 0.78rem; color: #fff;">Despliegues y Código</div>
                                <div class="text-muted" style="font-size: 0.65rem;">Avisos por correo de nuevas versiones en producción</div>
                            </div>
                            <label class="cyber-switch">
                                <input type="checkbox" name="notif_deployments" value="1" {{ $user->getNotificationPreference('notif_deployments', true) ? 'checked' : '' }}>
                                <span class="cyber-slider"></span>
                            </label>
                        </div>

                        <div class="cyber-switch-wrap mb-2" style="padding: 7px 12px;">
                            <div>
                                <div class="fw-bold" style="font-size: 0.78rem; color: #fff;">Alertas de Seguridad</div>
                                <div class="text-muted" style="font-size: 0.65rem;">Avisos por correo de inicios de sesión y accesos</div>
                            </div>
                            <label class="cyber-switch">
                                <input type="checkbox" name="notif_security" value="1" {{ $user->getNotificationPreference('notif_security', true) ? 'checked' : '' }}>
                                <span class="cyber-slider"></span>
                            </label>
                        </div>

                        <div class="cyber-switch-wrap mb-2" style="padding: 7px 12px;">
                            <div>
                                <div class="fw-bold" style="font-size: 0.78rem; color: #fff;">Resumen Semanal</div>
                                <div class="text-muted" style="font-size: 0.65rem;">Reporte consolidado de avances y métricas por correo</div>
                            </div>
                            <label class="cyber-switch">
                                <input type="checkbox" name="notif_weekly_digest" value="1" {{ $user->getNotificationPreference('notif_weekly_digest', false) ? 'checked' : '' }}>
                                <span class="cyber-slider"></span>
                            </label>
                        </div>
                    </form>
                </div>

                <button type="submit" form="formNotificaciones" class="btn-cyber-primary" style="background: linear-gradient(135deg, #f59e0b, #ec4899);">
                    <i class="fas fa-bell"></i> Guardar Notificaciones
                </button>
            </div>

        </div>

    </div>
</div>

<script>
// 1. Password Visibility Toggle
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
    btn.blur();
}

// 2. Copy 2FA Secret Key to Clipboard
function copySecret(text, btn) {
    if (!text) return;
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text).then(() => showCopiedFeedback(btn));
    } else {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showCopiedFeedback(btn);
        } catch (err) {
            console.error('Error al copiar', err);
        }
        document.body.removeChild(textarea);
    }
}

function showCopiedFeedback(btn) {
    if (!btn) return;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check text-success"></i> ¡Copiado!';
    btn.style.borderColor = '#10b981';
    btn.style.color = '#34d399';
    setTimeout(() => {
        btn.innerHTML = originalHtml;
        btn.style.borderColor = '';
        btn.style.color = '';
    }, 2000);
}

// 3. Horizontal Gallery Carousel Logic
const track = document.getElementById('galleryTrack');
const btnPrev = document.getElementById('btnScrollPrev');
const btnNext = document.getElementById('btnScrollNext');
const indexIndicator = document.getElementById('currentCardIndex');

function getCards() {
    return Array.from(document.querySelectorAll('.config-card'));
}

function getCardStep() {
    const card = document.querySelector('.config-card');
    if (!card) return 500;
    return card.offsetWidth + 20; // card width + gap
}

function updateGalleryIndicators() {
    if (!track) return;
    const cards = getCards();
    if (!cards || cards.length === 0) return;

    const scrollLeft = track.scrollLeft;
    const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);

    let currentIdx = 0;
    let minDiff = Infinity;
    cards.forEach((card, idx) => {
        const cardPos = card.offsetLeft - track.offsetLeft;
        const diff = Math.abs(scrollLeft - cardPos);
        if (diff < minDiff) {
            minDiff = diff;
            currentIdx = idx;
        }
    });

    if (maxScroll > 0 && scrollLeft >= maxScroll - 15) {
        currentIdx = cards.length - 1;
    } else if (scrollLeft <= 15) {
        currentIdx = 0;
    }

    if (indexIndicator) {
        indexIndicator.textContent = currentIdx + 1;
    }

    // Update active tab buttons
    for (let i = 0; i < 4; i++) {
        const pill = document.getElementById(`pill-${i}`);
        if (pill) {
            if (i === currentIdx) {
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
            }
        }
    }

    if (btnPrev) {
        btnPrev.disabled = scrollLeft <= 8;
    }
    if (btnNext) {
        btnNext.disabled = maxScroll <= 0 || scrollLeft >= maxScroll - 8;
    }
}

function scrollGallery(direction) {
    if (!track) return;
    const step = getCardStep();
    const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);
    if (maxScroll <= 0) return;

    let targetLeft;
    if (direction === 'next') {
        if (track.scrollLeft >= maxScroll - 6) return;
        targetLeft = Math.min(track.scrollLeft + step, maxScroll);
    } else {
        if (track.scrollLeft <= 6) return;
        targetLeft = Math.max(track.scrollLeft - step, 0);
    }

    track.scrollTo({ left: targetLeft, behavior: 'smooth' });
    setTimeout(updateGalleryIndicators, 200);
}

function goToCard(index) {
    if (!track) return;
    const cards = getCards();
    if (!cards || cards.length === 0) return;

    const targetCard = cards[Math.min(Math.max(index, 0), cards.length - 1)];
    if (targetCard) {
        const maxScroll = Math.max(0, track.scrollWidth - track.clientWidth);
        let targetLeft = targetCard.offsetLeft - track.offsetLeft;
        targetLeft = Math.min(Math.max(targetLeft, 0), maxScroll);
        track.scrollTo({ left: targetLeft, behavior: 'smooth' });
        setTimeout(updateGalleryIndicators, 200);
    }
}

// 3. Global Mouse Wheel to Horizontal Scroll (1 giro = 1 avance exacto)
if (track) {
    let lastWheelTime = 0;
    window.addEventListener('wheel', (e) => {
        if (!track) return;
        if (['TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName)) {
            return;
        }
        const delta = Math.abs(e.deltaY) >= Math.abs(e.deltaX) ? e.deltaY : e.deltaX;
        if (Math.abs(delta) > 6) {
            e.preventDefault();
            const now = Date.now();
            if (now - lastWheelTime < 260) {
                return;
            }
            lastWheelTime = now;
            
            if (delta > 0) {
                scrollGallery('next');
            } else {
                scrollGallery('prev');
            }
        }
    }, { passive: false });

    track.addEventListener('scroll', updateGalleryIndicators, { passive: true });

    // 4. Keyboard Navigation (ArrowLeft / ArrowRight)
    window.addEventListener('keydown', (e) => {
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement?.tagName)) {
            return;
        }
        if (e.key === 'ArrowRight') {
            scrollGallery('next');
        } else if (e.key === 'ArrowLeft') {
            scrollGallery('prev');
        }
    });

    // 5. Click on Card to Bring It Smoothly into Full View
    const cards = getCards();
    cards.forEach((card, idx) => {
        card.addEventListener('click', (e) => {
            if (!e.target.closest('input, button, select, label, a, textarea')) {
                goToCard(idx);
            }
        });
    });

    // 6. Mouse Drag to Scroll
    let isDown = false;
    let startX;
    let scrollLeftPos;

    track.addEventListener('mousedown', (e) => {
        if (['INPUT', 'BUTTON', 'LABEL', 'SELECT', 'A', 'I'].includes(e.target.tagName) || e.target.closest('button, input, select, label, a')) {
            return;
        }
        isDown = true;
        track.classList.add('is-dragging');
        startX = e.pageX - track.offsetLeft;
        scrollLeftPos = track.scrollLeft;
    });

    window.addEventListener('mouseup', () => {
        if (isDown) {
            isDown = false;
            if (track) track.classList.remove('is-dragging');
            updateGalleryIndicators();
        }
    });

    track.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - track.offsetLeft;
        const walk = (x - startX) * 1.5;
        track.scrollLeft = scrollLeftPos - walk;
    });

    updateGalleryIndicators();
}
</script>
@endsection

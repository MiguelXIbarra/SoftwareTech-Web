@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    .admin-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        color: #ffffff;
        position: relative;
        padding: 120px 20px 60px 20px;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 50% 30%, rgba(6, 182, 212, 0.06) 0%, transparent 60%),
                    radial-gradient(circle at 80% 70%, rgba(138, 43, 226, 0.04) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .portal-container {
        position: relative;
        z-index: 5;
        max-width: 960px;
        margin: 0 auto;
    }

    .btn-back-portal {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.6);
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back-portal:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.15);
    }

    .project-header-card {
        background: rgba(255, 255, 255, 0.01) !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        backdrop-filter: blur(24px) !important;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        position: relative;
        overflow: hidden;
    }

    .project-header-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, #06b6d4, transparent);
    }

    .tech-badge {
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: #22d3ee;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        font-family: monospace;
    }

    .progress-container {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 20px;
        height: 6px;
        overflow: hidden;
    }

    .progress-bar-cyan {
        background: linear-gradient(90deg, #06b6d4, #22d3ee);
        height: 100%;
        border-radius: 20px;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.5);
    }

    .meta-label {
        font-family: monospace;
        font-size: 0.65rem;
        color: rgba(255, 255, 255, 0.35);
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 4px;
        display: block;
    }

    .meta-value {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    .section-title-label {
        font-size: 0.8rem;
        font-family: monospace;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #06b6d4;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    /* Tarjetas de Assets / Entregables */
    .asset-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 14px;
        padding: 16px 20px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .asset-card:hover {
        background: rgba(6, 182, 212, 0.03);
        border-color: rgba(6, 182, 212, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    .asset-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #22d3ee;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .btn-asset-download {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        color: #22d3ee;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-family: monospace;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-asset-download:hover {
        background: rgba(6, 182, 212, 0.2);
        color: #fff;
        border-color: #06b6d4;
    }

    /* Tarjetas de Hitos / Milestones */
    .milestone-payment-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .milestone-payment-card:hover {
        border-color: rgba(138, 43, 226, 0.3);
        background: rgba(138, 43, 226, 0.02);
    }

    .badge-paid {
        background: rgba(16, 185, 129, 0.1) !important;
        border: 1px solid rgba(16, 185, 129, 0.4) !important;
        color: #34d399 !important;
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.1) !important;
        border: 1px solid rgba(245, 158, 11, 0.4) !important;
        color: #fbbf24 !important;
    }

    .btn-pay-now {
        background: rgba(6, 182, 212, 0.15);
        border: 1px solid #06b6d4;
        color: #00d4ff;
        font-size: 0.72rem;
        padding: 4px 12px;
        border-radius: 6px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-pay-now:hover {
        background: #06b6d4;
        color: #ffffff;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.5);
    }

    .pay-tab-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.6);
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .pay-tab-btn.active {
        background: rgba(6, 182, 212, 0.15);
        border-color: #06b6d4;
        color: #00d4ff;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.2);
    }

    /* Estilos de Campos de Entrada de Pago */
    .form-glass {
        background: rgba(15, 23, 42, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        font-size: 0.95rem !important;
        transition: all 0.2s ease !important;
    }

    .form-glass:focus {
        background: #0b1329 !important;
        border-color: #06b6d4 !important;
        color: #ffffff !important;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.5) !important;
        outline: none !important;
    }

    .form-glass::placeholder {
        color: #cbd5e1 !important;
        opacity: 0.9 !important;
        font-weight: 500 !important;
    }

    .modal-label-bright {
        color: #f8fafc !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        letter-spacing: 0.3px !important;
    }

    /* Slider Animado de Hitos Financieros y Entregables (1 Tarjeta por Vista, 100% Width) */
    .milestones-slider-container {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding: 4px 0;
    }

    .milestones-viewport-wrapper {
        width: 100%;
        overflow: hidden;
        border-radius: 14px;
    }

    .milestones-track-neon {
        display: flex;
        width: 100%;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .milestone-slide-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        padding: 20px 24px;
        flex: 0 0 100%;
        width: 100%;
        min-width: 100%;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    .milestone-slide-card:hover {
        border-color: rgba(6, 182, 212, 0.4);
        background: rgba(6, 182, 212, 0.025);
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.1);
    }

    .btn-slider-nav {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #94a3b8;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-slider-nav:hover {
        background: rgba(6, 182, 212, 0.15);
        border-color: #06b6d4;
        color: #00d4ff;
    }

    /* Timeline de Fases Técnicas */
    .milestone-timeline {
        position: relative;
        padding-left: 30px;
    }

    .milestone-timeline::before {
        content: '';
        position: absolute;
        left: 7px; top: 5px; bottom: 5px;
        width: 2px;
        background: rgba(255, 255, 255, 0.04);
    }

    .milestone-item {
        position: relative;
        padding-bottom: 30px;
    }

    .milestone-item:last-child {
        padding-bottom: 0;
    }

    .milestone-dot {
        position: absolute;
        left: -30px; top: 4px;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: #1f2937;
        border: 3px solid #030712;
        z-index: 5;
        box-shadow: 0 0 10px rgba(0,0,0,0.8);
    }

    .milestone-card {
        background: rgba(255, 255, 255, 0.01);
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 14px;
        padding: 20px 24px;
    }

    .milestone-title {
        color: #ffffff !important;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .milestone-desc {
        color: #9ca3af !important;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 0;
    }

    .badge-transform {
        font-size: 0.68rem;
        padding: 5px 12px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .badge-status-cian {
        background: rgba(6, 182, 212, 0.1) !important;
        border: 1px solid #06b6d4 !important;
        color: #22d3ee !important;
        box-shadow: 0 0 10px rgba(6, 182, 212, 0.2);
    }

    .badge-status-etapa-activa {
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.15);
    }

    .badge-status-gris-oscuro {
        background: rgba(55, 65, 81, 0.2) !important;
        border: 1px solid rgba(75, 85, 99, 0.4) !important;
        color: rgba(255, 255, 255, 0.3) !important;
    }

    @keyframes pulseGreyDot {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.6);
            transform: scale(1);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(255, 255, 255, 0);
            transform: scale(1.08);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            transform: scale(1);
        }
    }

    .dot-pulsing-grey {
        animation: pulseGreyDot 1.8s infinite ease-in-out !important;
        background: #9ca3af !important;
        border: 3px solid #030712 !important;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5) !important;
    }

    .dot-completed-cyan {
        background: #06b6d4 !important;
        border: 3px solid #030712 !important;
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.5) !important;
    }

    .dot-pending-dark {
        background: #1f2937 !important;
        border: 3px solid #030712 !important;
        box-shadow: none !important;
    }
</style>

<div class="admin-viewport">
    <div class="container portal-container">

        <div class="mb-4">
            <a href="{{ route('portal.dashboard') }}" class="btn-back-portal">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert d-flex align-items-center gap-3 mb-4" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; border-radius: 14px;">
                <i class="fas fa-check-circle fs-5"></i>
                <div class="font-mono" style="font-size: 0.88rem;">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="alert d-flex align-items-center gap-3 mb-4" style="background: rgba(6, 182, 212, 0.12); border: 1px solid rgba(6, 182, 212, 0.4); color: #22d3ee; border-radius: 14px;">
                <i class="fas fa-info-circle fs-5"></i>
                <div class="font-mono" style="font-size: 0.88rem;">{{ session('info') }}</div>
            </div>
        @endif

        {{-- Cabecera del Proyecto --}}
        <div class="project-header-card mb-5">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <span style="font-family: monospace; font-size: 0.7rem; color: #06b6d4; letter-spacing: 1px;">
                        {{ $proyecto->servicio }} Core
                    </span>
                    <h2 class="fw-bold text-white mt-1 mb-0" style="font-size: 2rem; font-weight: 800 !important; letter-spacing: -0.5px;">
                        {{ $proyecto->nombre }}
                    </h2>
                </div>
                <div class="tech-badge">
                    <i class="fas fa-circle-notch fa-spin me-2" style="font-size: 0.65rem;"></i> {{ $proyecto->estado }}
                </div>
            </div>

            @if($proyecto->descripcion)
                <p class="text-secondary mb-4" style="font-size: 0.92rem; line-height: 1.6;">
                    {{ $proyecto->descripcion }}
                </p>
            @endif

            <div class="mt-4 mb-2">
                <div class="progress-container">
                    <div class="progress-bar-cyan" style="width: {{ $proyecto->progreso }}%;"></div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span style="font-family: monospace; font-size: 0.7rem; color: rgba(255,255,255,0.3);">Progreso Realizado</span>
                <span class="font-mono text-info fw-bold" style="font-size: 0.75rem;">{{ $proyecto->progreso }}%</span>
            </div>

            <div class="row g-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.04);">
                <div class="col-6 col-md-4">
                    <span class="meta-label">Próxima Entrega</span>
                    <span class="meta-value font-mono text-info">{{ $proyecto->siguiente_entrega ?? 'Por definir' }}</span>
                </div>
                <div class="col-6 col-md-4">
                    <span class="meta-label">Desarrollador Líder</span>
                    <span class="meta-value">{{ $proyecto->developer->name ?? 'Asignando...' }}</span>
                </div>
                <div class="col-12 col-md-4">
                    <span class="meta-label">Prioridad de Despliegue</span>
                    <span class="meta-value text-uppercase font-mono" style="font-size: 0.8rem; color: {{ $proyecto->priority === 'critico' ? '#f87171' : '#22d3ee' }}">
                        {{ $proyecto->priority ?? 'Medio' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- SECCIÓN A: Bóveda de Entregables y Activos Digitales --}}
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="section-title-label mb-0">
                    <i class="fas fa-folder-open"></i> Bóveda de Entregables y Recursos
                </div>
                @if($proyecto->assets && $proyecto->assets->count() > 1)
                    <div class="d-flex align-items-center gap-2">
                        <span class="font-mono text-white-50" style="font-size: 0.75rem;" id="assetCounter">1 / {{ $proyecto->assets->count() }}</span>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn-slider-nav" onclick="moveAssetSlider(-1)" title="Anterior">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="btn-slider-nav" onclick="moveAssetSlider(1)" title="Siguiente">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            @if($proyecto->assets && $proyecto->assets->count() > 0)
                <div id="assetsSliderContainer" class="milestones-slider-container">
                    <div class="milestones-viewport-wrapper">
                        <div id="assetsTrack" class="milestones-track-neon">
                            @foreach($proyecto->assets as $asset)
                                @php
                                    $extension = pathinfo($asset->path, PATHINFO_EXTENSION);
                                    $icono = 'fa-file-alt';
                                    if (in_array(strtolower($extension), ['pdf'])) $icono = 'fa-file-pdf text-danger';
                                    elseif (in_array(strtolower($extension), ['zip', 'rar', 'tar', 'gz'])) $icono = 'fa-file-archive text-warning';
                                    elseif (in_array(strtolower($extension), ['apk'])) $icono = 'fa-android text-success';
                                    elseif (in_array(strtolower($asset->tipo), ['imagen', 'image'])) $icono = 'fa-file-image text-info';
                                    elseif (in_array(strtolower($asset->tipo), ['video'])) $icono = 'fa-file-video text-purple';
                                @endphp
                                <div class="milestone-slide-card p-3">
                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                                            <div class="asset-icon-box" style="flex-shrink: 0;">
                                                <i class="fas {{ $icono }}"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <h6 class="text-white mb-0 text-truncate font-mono" style="font-size: 0.88rem;">
                                                    {{ $asset->nombre }}
                                                </h6>
                                                <span style="font-size: 0.7rem; color: rgba(255,255,255,0.4); text-transform: uppercase;">
                                                    {{ $asset->tipo ?? 'Entregable' }} • {{ $asset->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div style="flex-shrink: 0;">
                                            <a href="{{ route('assets.download', $asset->id) }}" 
                                               class="btn-asset-download" 
                                               download>
                                                <i class="fas fa-arrow-down"></i> Descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="p-4 rounded-3 text-center" style="background: rgba(255,255,255,0.015); border: 1px dashed rgba(255,255,255,0.08);">
                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: rgba(6,182,212,0.4);"></i>
                    <p class="text-white mb-1" style="font-size: 0.88rem; font-weight: 600;">Sin entregables adjuntos por el momento</p>
                    <p style="color: rgba(255,255,255,0.4); font-size: 0.75rem; margin-bottom: 0;">
                        Los manuales, especificaciones, accesos a staging y entregables del código aparecerán listados aquí a medida que avancen las entregas.
                    </p>
                </div>
            @endif
        </div>

        {{-- SECCIÓN B: Hitos y Liquidación Financiera con Slider Dinámico --}}
        @if($proyecto->milestones && $proyecto->milestones->count() > 0)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="section-title-label mb-0">
                        <i class="fas fa-file-invoice-dollar"></i> Hitos Financieros y Liquidación
                    </div>
                    @if($proyecto->milestones->count() > 1)
                        <div class="d-flex align-items-center gap-2">
                            <span class="font-mono text-white-50" style="font-size: 0.75rem;" id="milestoneCounter">1 / {{ $proyecto->milestones->count() }}</span>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn-slider-nav" onclick="moveMilestoneSlider(-1)" title="Anterior">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn-slider-nav" onclick="moveMilestoneSlider(1)" title="Siguiente">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div id="milestonesSliderContainer" class="milestones-slider-container">
                    <div class="milestones-viewport-wrapper">
                        <div id="milestonesTrack" class="milestones-track-neon">
                            @foreach($proyecto->milestones as $milestone)
                                <div class="milestone-slide-card">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <h6 class="text-white fw-bold mb-0" style="font-size: 0.98rem; letter-spacing: -0.2px;">
                                            {{ $milestone->name ?? $milestone->title }}
                                        </h6>
                                        <span class="badge font-mono badge-transform {{ $milestone->is_paid ? 'badge-paid' : 'badge-pending' }}">
                                            {{ $milestone->is_paid ? 'Liquidado' : 'Pendiente' }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.04);">
                                        <div>
                                            <span class="font-mono text-info fw-bold" style="font-size: 1.05rem;">
                                                ${{ number_format($milestone->cost, 2) }} USD
                                            </span>
                                            @if($milestone->due_date)
                                                <span class="font-mono ms-2 d-none d-sm-inline" style="font-size: 0.74rem; color: rgba(255,255,255,0.45);">
                                                    • Límite: {{ $milestone->due_date->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($milestone->is_paid)
                                            <span class="font-mono text-success" style="font-size: 0.78rem; font-weight: 600;">
                                                <i class="fas fa-check-circle me-1"></i> Acreditado
                                            </span>
                                        @else
                                            <button type="button" class="btn btn-sm btn-pay-now" onclick="abrirModalPago({{ $milestone->id }}, '{{ addslashes($milestone->name ?? $milestone->title) }}', '{{ number_format($milestone->cost, 2, '.', '') }}', 'PROY-{{ $proyecto->id }}-H{{ $milestone->id }}')">
                                                <i class="fas fa-wallet me-1"></i> Pagar Hito
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- SECCIÓN C: Pipeline Técnico de Desarrollo --}}
        <div class="section-title-label">
            <i class="fas fa-code-branch"></i> Línea de Fases Técnicas
        </div>

        @php
            $estado = strtolower($proyecto->estado);

            // Fase 1: Inicialización
            $fase1Completada = in_array($estado, ['en desarrollo', 'en pruebas', 'finalizado', 'completado']);
            $fase1Activa = ($estado === 'prospecto');

            // Fase 2: En Desarrollo
            $fase2Completada = in_array($estado, ['en pruebas', 'finalizado', 'completado']);
            $fase2Activa = ($estado === 'en desarrollo');

            // Fase 3: En Pruebas
            $fase3Completada = in_array($estado, ['finalizado', 'completado']);
            $fase3Activa = ($estado === 'en pruebas');

            // Fase 4: Despliegue Final
            $fase4Completada = in_array($estado, ['finalizado', 'completado']);
            $fase4Activa = false;
        @endphp

        <div class="milestone-timeline">
            {{-- Fase 01 --}}
            <div class="milestone-item">
                <div class="milestone-dot {{ $fase1Completada ? 'dot-completed-cyan' : ($fase1Activa ? 'dot-pulsing-grey' : 'dot-pending-dark') }}"></div>
                <div class="milestone-card" style="{{ $fase1Activa ? 'border-color: rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.02);' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title" style="{{ !$fase1Completada && !$fase1Activa ? 'color: rgba(255,255,255,0.4) !important;' : '' }}">
                            Fase 01 - Inicialización Estructura Base
                        </h5>
                        <span class="badge font-mono badge-transform {{ $fase1Completada ? 'badge-status-cian' : ($fase1Activa ? 'badge-status-etapa-activa' : 'badge-status-gris-oscuro') }}">
                            @if($fase1Completada)
                                <i class="fas fa-check me-1"></i> Completado
                            @elseif($fase1Activa)
                                <i class="fas fa-play me-1"></i> En Curso
                            @else
                                Pendiente
                            @endif
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase1Completada && !$fase1Activa ? 'color: rgba(255,255,255,0.25) !important;' : '' }}">
                        Levantamiento de requerimientos conceptuales, aprovisionamiento del repositorio de código Git y migración del esquema relacional base.
                    </p>
                </div>
            </div>

            {{-- Fase 02 --}}
            <div class="milestone-item">
                <div class="milestone-dot {{ $fase2Completada ? 'dot-completed-cyan' : ($fase2Activa ? 'dot-pulsing-grey' : 'dot-pending-dark') }}"></div>
                <div class="milestone-card" style="{{ $fase2Activa ? 'border-color: rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.02);' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title" style="{{ !$fase2Completada && !$fase2Activa ? 'color: rgba(255,255,255,0.4) !important;' : '' }}">
                            Fase 02 - Programación del Núcleo & API Controladores
                        </h5>
                        <span class="badge font-mono badge-transform {{ $fase2Completada ? 'badge-status-cian' : ($fase2Activa ? 'badge-status-etapa-activa' : 'badge-status-gris-oscuro') }}">
                            @if($fase2Completada)
                                <i class="fas fa-check me-1"></i> Completado
                            @elseif($fase2Activa)
                                <i class="fas fa-hammer me-1"></i> En Desarrollo
                            @else
                                Pendiente
                            @endif
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase2Completada && !$fase2Activa ? 'color: rgba(255,255,255,0.25) !important;' : '' }}">
                        Desarrollo activo de controladores de backend, endpoints lógicos del servicio interno y vinculación inicial de interfaces dinámicas.
                    </p>
                </div>
            </div>

            {{-- Fase 03 --}}
            <div class="milestone-item">
                <div class="milestone-dot {{ $fase3Completada ? 'dot-completed-cyan' : ($fase3Activa ? 'dot-pulsing-grey' : 'dot-pending-dark') }}"></div>
                <div class="milestone-card" style="{{ $fase3Activa ? 'border-color: rgba(255, 255, 255, 0.2); background: rgba(255, 255, 255, 0.02);' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title" style="{{ !$fase3Completada && !$fase3Activa ? 'color: rgba(255,255,255,0.4) !important;' : '' }}">
                            Fase 03 - Entorno de Pruebas Sandbox & Control de Calidad
                        </h5>
                        <span class="badge font-mono badge-transform {{ $fase3Completada ? 'badge-status-cian' : ($fase3Activa ? 'badge-status-etapa-activa' : 'badge-status-gris-oscuro') }}">
                            @if($fase3Completada)
                                <i class="fas fa-check me-1"></i> Completado
                            @elseif($fase3Activa)
                                <i class="fas fa-vial me-1"></i> En Pruebas
                            @else
                                Pendiente
                            @endif
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase3Completada && !$fase3Activa ? 'color: rgba(255,255,255,0.25) !important;' : '' }}">
                        Despliegue provisional en servidor staging, auditoría de seguridad perimetral, depuración de logs de peticiones y optimización de UX/UI.
                    </p>
                </div>
            </div>

            {{-- Fase 04 --}}
            <div class="milestone-item">
                <div class="milestone-dot {{ $fase4Completada ? 'dot-completed-cyan' : 'dot-pending-dark' }}"></div>
                <div class="milestone-card" style="{{ $fase4Completada ? 'border-color: rgba(6, 182, 212, 0.25);' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title" style="{{ !$fase4Completada ? 'color: rgba(255,255,255,0.4) !important;' : '' }}">
                            Fase 04 - Despliegue Final a Producción Estable
                        </h5>
                        <span class="badge font-mono badge-transform {{ $fase4Completada ? 'badge-status-cian' : 'badge-status-gris-oscuro' }}">
                            @if($fase4Completada)
                                <i class="fas fa-rocket me-1"></i> Liberado
                            @else
                                Pendiente
                            @endif
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase4Completada ? 'color: rgba(255,255,255,0.25) !important;' : '' }}">
                        Lanzamiento oficial en el host de producción, monitoreo de contingencia inicial y entrega del entorno operativo completamente empaquetado.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal de Pasarela de Pago Híbrido -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content" style="background: #070c18 !important; border: 1px solid rgba(6, 182, 212, 0.3) !important; box-shadow: 0 0 50px rgba(6, 182, 212, 0.2), 0 25px 60px rgba(0, 0, 0, 0.95) !important; border-radius: 20px !important; color: #ffffff !important; overflow: hidden;">
            
            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-start" style="background: rgba(6, 182, 212, 0.03);">
                <div>
                    <span class="font-mono text-info fw-semibold" style="font-size: 0.7rem; letter-spacing: 1.5px; text-transform: uppercase;">TERMINAL DE PAGO SEGURO</span>
                    <h5 class="modal-title fw-bold text-white mb-0 mt-1" id="modalMilestoneName" style="font-size: 1.2rem; letter-spacing: -0.3px;">Hito de Entrega</h5>
                </div>
                <button type="button" onclick="cerrarModalPago()" aria-label="Close" style="background: transparent; border: none; color: #94a3b8; font-size: 1.3rem; cursor: pointer; padding: 4px 8px; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.color='#ffffff'; this.style.transform='scale(1.15)';" onmouseout="this.style.color='#94a3b8'; this.style.transform='scale(1)';">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body px-4 py-3">
                <!-- Resumen de Costo -->
                <div class="d-flex justify-content-between align-items-center p-3 mb-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px;">
                    <div>
                        <span class="text-white-50 small d-block">Monto a Liquidar</span>
                        <span class="font-mono text-white fw-bold" id="modalMilestoneCost" style="font-size: 1.3rem;">$0.00 USD</span>
                    </div>
                    <div class="text-end">
                        <span class="text-white-50 small d-block">Proyecto</span>
                        <span class="font-mono text-info small">{{ $proyecto->nombre }}</span>
                    </div>
                </div>

                <!-- Selector de Pestañas Híbridas -->
                <div class="d-flex gap-2 mb-4">
                    <button type="button" class="pay-tab-btn active flex-fill text-center" id="tabBtnTransfer" onclick="cambiarTabPago('transfer')">
                        <i class="fas fa-university me-1"></i> Transferencia SPEI
                    </button>
                    <button type="button" class="pay-tab-btn flex-fill text-center" id="tabBtnCard" onclick="cambiarTabPago('card')">
                        <i class="fas fa-credit-card me-1"></i> Tarjeta Online
                    </button>
                </div>

                <!-- CONTENIDO PESTAÑA 1: TRANSFERENCIA BANCARIA -->
                <div id="tabContentTransfer">
                    <div class="p-3 mb-3" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; font-size: 0.85rem;">
                        <div class="d-flex justify-content-between py-1.5 border-bottom border-secondary border-opacity-10">
                            <span style="color: #cbd5e1 !important; font-weight: 500;">Banco:</span>
                            <span class="fw-bold text-white">{{ config('services.bank.banco') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-1.5 border-bottom border-secondary border-opacity-10">
                            <span style="color: #cbd5e1 !important; font-weight: 500;">Beneficiario:</span>
                            <span class="fw-bold text-white">{{ config('services.bank.beneficiario') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom border-secondary border-opacity-10">
                            <span style="color: #cbd5e1 !important; font-weight: 500;">CLABE Interbancaria:</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="font-mono text-info fw-bold" style="letter-spacing: 0.5px;">{{ config('services.bank.clabe') }}</span>
                                <button type="button" class="btn btn-sm btn-outline-info p-1" style="font-size: 0.65rem;" onclick="copiarTexto('{{ config('services.bank.clabe') }}', this)" title="Copiar CLABE">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        @if(!empty(config('services.bank.rfc')))
                        <div class="d-flex justify-content-between align-items-center py-1.5 border-bottom border-secondary border-opacity-10">
                            <span style="color: #cbd5e1 !important; font-weight: 500;">RFC:</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="font-mono text-white">{{ config('services.bank.rfc') }}</span>
                                <button type="button" class="btn btn-sm btn-outline-light p-1" style="font-size: 0.65rem;" onclick="copiarTexto('{{ config('services.bank.rfc') }}', this)" title="Copiar RFC">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center py-1.5">
                            <span style="color: #cbd5e1 !important; font-weight: 500;">Concepto / Referencia:</span>
                            <span class="font-mono text-warning fw-bold" id="payRefLabel">PROY-{{ $proyecto->id }}</span>
                        </div>
                    </div>

                    <!-- Enviar comprobante por WhatsApp -->
                    <a href="#" id="btnWhatsAppReceipt" target="_blank" class="btn w-100 py-2.5 mb-3 fw-bold text-white font-mono" style="background: #16a34a; border-radius: 10px; font-size: 0.88rem; box-shadow: 0 0 15px rgba(22, 163, 74, 0.4);">
                        <i class="fab fa-whatsapp me-2"></i> Reportar Pago por WhatsApp
                    </a>

                    <!-- Subir Comprobante a la Bóveda -->
                    <form action="" method="POST" enctype="multipart/form-data" id="receiptUploadForm" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 15px;">
                        @csrf
                        <label class="form-label modal-label-bright mb-1">O adjuntar comprobante en la plataforma (PDF / Imagen):</label>
                        <div class="d-flex gap-2">
                            <input type="file" name="comprobante" class="form-control form-control-sm form-glass" required accept=".pdf,.png,.jpg,.jpeg,.webp">
                            <button type="submit" class="btn btn-sm btn-outline-info font-mono px-3" style="border-radius: 10px;">Subir</button>
                        </div>
                    </form>
                </div>

                <!-- CONTENIDO PESTAÑA 2: PAGO CON TARJETA -->
                <div id="tabContentCard" style="display: none;">
                    <form action="" method="POST" id="cardPaymentForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label modal-label-bright mb-1">Nombre del Titular</label>
                            <input type="text" name="card_name" class="form-control form-glass py-2" required placeholder="" autocomplete="cc-name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label modal-label-bright mb-1">Número de Tarjeta</label>
                            <div class="input-group">
                                <input type="text" name="card_number" class="form-control form-glass py-2 font-mono" required placeholder="" maxlength="19" autocomplete="cc-number">
                                <span class="input-group-text" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.6);">
                                    <i class="fab fa-cc-visa me-1"></i> <i class="fab fa-cc-mastercard"></i>
                                </span>
                            </div>
                        </div>
                        <div class="row g-2 mb-4">
                            <div class="col-4">
                                <label class="form-label modal-label-bright mb-1">Mes</label>
                                <input type="text" name="exp_month" class="form-control form-glass py-2 font-mono text-center" placeholder="MM" maxlength="2" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label modal-label-bright mb-1">Año</label>
                                <input type="text" name="exp_year" class="form-control form-glass py-2 font-mono text-center" placeholder="AA" maxlength="2" required>
                            </div>
                            <div class="col-4">
                                <label class="form-label modal-label-bright mb-1">CVV</label>
                                <input type="password" name="cvv" class="form-control form-glass py-2 font-mono text-center" placeholder="CVV" maxlength="4" required autocomplete="cc-csc">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info w-100 py-2.5 fw-bold text-white font-mono" style="background: #06b6d4; border: none; border-radius: 12px; box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);">
                            <i class="fas fa-lock me-1"></i> Pagar <span id="btnPayCardAmount">$0.00</span> USD Seguro
                        </button>
                        <div class="text-center mt-2">
                            <span style="font-size: 0.68rem; color: #94a3b8;">
                                <i class="fas fa-shield-alt text-info me-1"></i> Transacción cifrada de extremo a extremo SSL 256-bit
                            </span>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function abrirModalPago(milestoneId, name, cost, ref) {
    document.getElementById('modalMilestoneName').innerText = name;
    document.getElementById('modalMilestoneCost').innerText = '$' + cost + ' USD';
    document.getElementById('btnPayCardAmount').innerText = '$' + cost;
    document.getElementById('payRefLabel').innerText = ref;

    // Configurar URLs dinámicas de los formularios
    document.getElementById('cardPaymentForm').action = `{{ url('/portal/milestones') }}/${milestoneId}/pay-card`;
    document.getElementById('receiptUploadForm').action = `{{ url('/portal/milestones') }}/${milestoneId}/comprobante`;

    // Configurar enlace dinámico de WhatsApp
    const waText = encodeURIComponent(`Hola equipo de Software Tech, he realizado la transferencia para liquidar el hito "${name}" por un monto de $${cost} USD para el proyecto "{{ $proyecto->nombre }}" con referencia ${ref}. Adjunto mi comprobante para su validación.`);
    document.getElementById('btnWhatsAppReceipt').href = `https://wa.me/?text=${waText}`;

    // Abrir Modal
    const modalEl = document.getElementById('paymentModal');
    if (window.bootstrap && window.bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    } else {
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
    }
}

function cambiarTabPago(tab) {
    const tabTransfer = document.getElementById('tabContentTransfer');
    const tabCard = document.getElementById('tabContentCard');
    const btnTransfer = document.getElementById('tabBtnTransfer');
    const btnCard = document.getElementById('tabBtnCard');

    if (tab === 'transfer') {
        tabTransfer.style.display = 'block';
        tabCard.style.display = 'none';
        btnTransfer.classList.add('active');
        btnCard.classList.remove('active');
    } else {
        tabTransfer.style.display = 'none';
        tabCard.style.display = 'block';
        btnTransfer.classList.remove('active');
        btnCard.classList.add('active');
    }
}

function copiarTexto(texto, btn) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(texto).then(() => {
            feedbackCopiado(btn);
        });
    } else {
        const temp = document.createElement('textarea');
        temp.value = texto;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        feedbackCopiado(btn);
    }
}

function feedbackCopiado(btn) {
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check text-success"></i>';
    setTimeout(() => {
        btn.innerHTML = original;
    }, 1800);
}

function cerrarModalPago() {
    const modalEl = document.getElementById('paymentModal');
    if (modalEl) {
        if (window.bootstrap && window.bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        modalEl.setAttribute('aria-hidden', 'true');
    }
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('paymentModal');
    if (modalEl) {
        modalEl.addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModalPago();
            }
        });
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModalPago();
    }
});

let currentMilestoneSlide = 0;
const totalMilestones = {{ $proyecto->milestones ? $proyecto->milestones->count() : 0 }};

function moveMilestoneSlider(direction) {
    if (totalMilestones <= 1) return;
    const track = document.getElementById('milestonesTrack');
    if (!track) return;

    currentMilestoneSlide += direction;
    if (currentMilestoneSlide < 0) currentMilestoneSlide = totalMilestones - 1;
    if (currentMilestoneSlide >= totalMilestones) currentMilestoneSlide = 0;

    track.style.transform = `translateX(-${currentMilestoneSlide * 100}%)`;

    const counter = document.getElementById('milestoneCounter');
    if (counter) {
        counter.innerText = `${currentMilestoneSlide + 1} / ${totalMilestones}`;
    }
}

window.addEventListener('resize', function() {
    currentMilestoneSlide = 0;
    const track = document.getElementById('milestonesTrack');
    if (track) track.style.transform = 'translateX(0px)';
    const counter = document.getElementById('milestoneCounter');
    if (counter) counter.innerText = totalMilestones > 1 ? `1 / ${totalMilestones}` : '';

    currentAssetSlide = 0;
    const aTrack = document.getElementById('assetsTrack');
    if (aTrack) aTrack.style.transform = 'translateX(0px)';
    const aCounter = document.getElementById('assetCounter');
    if (aCounter) aCounter.innerText = totalAssets > 1 ? `1 / ${totalAssets}` : '';
});

let currentAssetSlide = 0;
const totalAssets = {{ $proyecto->assets ? $proyecto->assets->count() : 0 }};

function moveAssetSlider(direction) {
    if (totalAssets <= 1) return;
    const track = document.getElementById('assetsTrack');
    if (!track) return;

    currentAssetSlide += direction;
    if (currentAssetSlide < 0) currentAssetSlide = totalAssets - 1;
    if (currentAssetSlide >= totalAssets) currentAssetSlide = 0;

    track.style.transform = `translateX(-${currentAssetSlide * 100}%)`;

    const counter = document.getElementById('assetCounter');
    if (counter) {
        counter.innerText = `${currentAssetSlide + 1} / ${totalAssets}`;
    }
}
</script>
@endsection

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
        padding: 60px 20px;
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
        max-width: 900px;
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
        font-size: 0.65rem;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .badge-status-cian {
        background: rgba(6, 182, 212, 0.1) !important;
        border: 1px solid #06b6d4 !important;
        color: #22d3ee !important;
    }

    .badge-status-blanco {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid #ffffff !important;
        color: #ffffff !important;
    }

    .badge-status-gris-claro {
        background: rgba(156, 163, 175, 0.1) !important;
        border: 1px solid #9ca3af !important;
        color: #e5e7eb !important;
    }

    .badge-status-gris-oscuro {
        background: rgba(55, 65, 81, 0.2) !important;
        border: 1px solid rgba(75, 85, 99, 0.4) !important;
        color: rgba(255, 255, 255, 0.3) !important;
    }
</style>

<div class="admin-viewport">
    <div class="container portal-container">

        <div class="mb-4">
            <a href="{{ route('portal.dashboard') }}" class="btn-back-portal">
                <i class="fas fa-arrow-left"></i> Volver a la Consola
            </a>
        </div>

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
                        {{ $proyecto->priority }}
                    </span>
                </div>
            </div>
        </div>

        <h4 class="fw-bold text-white mb-4" style="font-size: 0.85rem; font-family: monospace; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.5);">
            Línea de Hitos y Entregables Operativos
        </h4>

        <div class="milestone-timeline">
            @php
                $fase1Completada = in_array(strtolower($proyecto->estado), ['en desarrollo', 'en pruebas', 'finalizado', 'completado']);
            @endphp
            <div class="milestone-item">
                <div class="milestone-dot" style="
                    border-color: {{ $fase1Completada ? '#06b6d4' : 'rgba(75, 85, 99, 0.4)' }};
                    background: {{ $fase1Completada ? '#06b6d4' : '#1f2937' }};
                    box-shadow: {{ $fase1Completada ? '0 0 10px rgba(6,182,212,0.4)' : 'none' }};">
                </div>
                <div class="milestone-card">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title">Fase 01 - Inicialización Estructura Base</h5>
                        <span class="badge font-mono badge-transform {{ $fase1Completada ? 'badge-status-cian' : 'badge-status-gris-oscuro' }}">
                            {{ $fase1Completada ? 'Completado' : 'Pendiente' }}
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase1Completada ? 'color: rgba(255,255,255,0.2) !important;' : '' }}">
                        Levantamiento de requerimientos conceptuales, aprovisionamiento del repositorio de código Git y migración del esquema relacional base.
                    </p>
                </div>
            </div>

            @php
                $estadoActual = strtolower($proyecto->estado);
                $fase2Activa = ($estadoActual === 'en desarrollo');
                $fase2Pasada = in_array($estadoActual, ['en pruebas', 'finalizado', 'completado']);
            @endphp
            <div class="milestone-item">
                <div class="milestone-dot" style="
                    border-color: {{ $fase2Activa || $fase2Pasada ? '#9ca3af' : 'rgba(75, 85, 99, 0.4)' }};
                    background: {{ $fase2Pasada ? '#9ca3af' : '#1f2937' }};
                    box-shadow: {{ $fase2Activa ? '0 0 10px rgba(156,163,175,0.3)' : 'none' }};">
                </div>
                <div class="milestone-card">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title">Fase 02 - Programación del Núcleo & API Controladores</h5>
                        <span class="badge font-mono badge-transform {{ $fase2Pasada ? 'badge-status-cian' : ($fase2Activa ? 'badge-status-gris-claro' : 'badge-status-gris-oscuro') }}">
                            {{ $fase2Pasada ? 'Completado' : ($fase2Activa ? 'En Desarrollo' : 'Pendiente') }}
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase2Activa && !$fase2Pasada ? 'color: rgba(255,255,255,0.2) !important;' : '' }}">
                        Desarrollo activo de controladores de backend, endpoints lógicos del servicio interno y vinculación inicial de interfaces dinámicas.
                    </p>
                </div>
            </div>

            @php
                $fase3Activa = ($estadoActual === 'en pruebas');
                $fase3Pasada = in_array($estadoActual, ['finalizado', 'completado']);
            @endphp
            <div class="milestone-item">
                <div class="milestone-dot" style="
                    border-color: {{ $fase3Activa || $fase3Pasada ? '#ffffff' : 'rgba(75, 85, 99, 0.4)' }};
                    background: {{ $fase3Pasada ? '#ffffff' : '#1f2937' }};
                    box-shadow: {{ $fase3Activa ? '0 0 10px rgba(255,255,255,0.4)' : 'none' }};">
                </div>
                <div class="milestone-card">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title">Fase 03 - Entorno de Pruebas Sandbox & Control de Calidad</h5>
                        <span class="badge font-mono badge-transform {{ $fase3Pasada ? 'badge-status-cian' : ($fase3Activa ? 'badge-status-blanco' : 'badge-status-gris-oscuro') }}">
                            {{ $fase3Pasada ? 'Completado' : ($fase3Activa ? 'En Pruebas' : 'Pendiente') }}
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase3Activa && !$fase3Pasada ? 'color: rgba(255,255,255,0.2) !important;' : '' }}">
                        Despliegue provisional en servidor staging, auditoría de seguridad perimetral, depuración de logs de peticiones y optimización de UX/UI.
                    </p>
                </div>
            </div>

            @php
                $fase4Liberada = in_array($estadoActual, ['finalizado', 'completado']);
            @endphp
            <div class="milestone-item">
                <div class="milestone-dot" style="
                    border-color: {{ $fase4Liberada ? '#06b6d4' : 'rgba(75, 85, 99, 0.4)' }};
                    background: {{ $fase4Liberada ? '#06b6d4' : '#111827' }};
                    box-shadow: {{ $fase4Liberada ? '0 0 12px rgba(6,182,212,0.4)' : 'none' }};">
                </div>
                <div class="milestone-card">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="milestone-title">Fase 04 - Despliegue Final a Producción Estable</h5>
                        <span class="badge font-mono badge-transform {{ $fase4Liberada ? 'badge-status-cian' : 'badge-status-gris-oscuro' }}">
                            {{ $fase4Liberada ? 'Liberado' : 'Pendiente' }}
                        </span>
                    </div>
                    <p class="milestone-desc" style="{{ !$fase4Liberada ? 'color: rgba(255,255,255,0.2) !important;' : '' }}">
                        Lanzamiento oficial en el host de producción, monitoreo de contingencia inicial y entrega del entorno operativo completamente empaquetado.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

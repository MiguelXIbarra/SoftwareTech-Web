@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    .admin-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        color: #ffffff;
        position: relative;
        padding: 120px 20px 80px 20px;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 50% 20%, rgba(6, 182, 212, 0.08) 0%, transparent 60%),
                    radial-gradient(circle at 85% 65%, rgba(138, 43, 226, 0.06) 0%, transparent 50%),
                    radial-gradient(circle at 15% 85%, rgba(59, 130, 246, 0.05) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .portal-container {
        position: relative;
        z-index: 5;
        max-width: 1140px;
        margin: 0 auto;
    }

    /* Welcome Header */
    .welcome-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.7) 0%, rgba(3, 7, 18, 0.85) 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-top: 1px solid rgba(6, 182, 212, 0.3) !important;
        backdrop-filter: blur(28px) !important;
        -webkit-backdrop-filter: blur(28px) !important;
        border-radius: 24px;
        padding: 35px 40px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 300px;
        height: 100%;
        background: radial-gradient(circle at right center, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        pointer-events: none;
    }

    .console-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
        color: #22d3ee;
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.25);
        padding: 4px 12px;
        border-radius: 20px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .status-dot-pulse {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 10px #10b981;
        display: inline-block;
    }

    .status-dot-warning {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #f59e0b;
        box-shadow: 0 0 10px #f59e0b;
        display: inline-block;
    }

    /* KPI Summary Cards */
    .kpi-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .kpi-card {
        background: rgba(15, 23, 42, 0.55);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 18px;
        padding: 22px 26px;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .kpi-card:hover {
        border-color: rgba(6, 182, 212, 0.35);
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), 0 0 20px rgba(6, 182, 212, 0.1);
    }

    .kpi-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.72rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
        display: block;
    }

    .kpi-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .kpi-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .icon-cyan { background: rgba(6, 182, 212, 0.1); color: #22d3ee; border: 1px solid rgba(6, 182, 212, 0.25); }
    .icon-purple { background: rgba(138, 43, 226, 0.1); color: #c084fc; border: 1px solid rgba(138, 43, 226, 0.25); }
    .icon-emerald { background: rgba(16, 185, 129, 0.1); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25); }
    .icon-amber { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.25); }

    /* Project Cards */
    .project-card-premium {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.65) 0%, rgba(10, 15, 30, 0.8) 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        border-left: 4px solid #06b6d4 !important;
        backdrop-filter: blur(24px) !important;
        -webkit-backdrop-filter: blur(24px) !important;
        border-radius: 22px;
        padding: 35px 40px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .project-card-premium:hover {
        border-color: rgba(6, 182, 212, 0.45) !important;
        border-left-color: #06b6d4 !important;
        box-shadow: 0 25px 55px rgba(0, 0, 0, 0.6), 0 0 35px rgba(6, 182, 212, 0.15) !important;
        transform: translateY(-4px);
    }

    .tech-badge {
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.25);
        color: #22d3ee;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .badge-desarrollo { background: rgba(6, 182, 212, 0.1); border-color: rgba(6, 182, 212, 0.35); color: #38bdf8; }
    .badge-pruebas { background: rgba(168, 85, 247, 0.1); border-color: rgba(168, 85, 247, 0.35); color: #c084fc; }
    .badge-finalizado { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.35); color: #34d399; }
    .badge-prospecto { background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.35); color: #fbbf24; }

    .progress-container {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        height: 8px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar-cyan {
        background: linear-gradient(90deg, #3b82f6, #06b6d4, #8a2be2);
        height: 100%;
        border-radius: 20px;
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.6);
        transition: width 1s ease-in-out;
    }

    .meta-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        color: #94a3b8;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
        font-weight: 600;
    }

    .meta-value {
        font-size: 0.95rem;
        color: #f1f5f9;
        font-weight: 600;
    }

    .action-btn-portal {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(138, 43, 226, 0.15));
        border: 1px solid rgba(6, 182, 212, 0.4);
        color: #ffffff !important;
        padding: 12px 26px;
        border-radius: 12px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .action-btn-portal:hover {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(138, 43, 226, 0.3));
        border-color: #06b6d4;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.35);
        transform: translateY(-2px);
    }

    /* Empty State */
    .empty-state-card {
        background: rgba(15, 23, 42, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        backdrop-filter: blur(24px) !important;
        border-radius: 24px;
        padding: 70px 40px;
        text-align: center;
    }

    .empty-icon {
        width: 75px;
        height: 75px;
        background: rgba(6, 182, 212, 0.06);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 24px auto;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.1);
    }
</style>

<div class="admin-viewport">
    <div class="container portal-container">

        @php
            $totalProyectos = $proyectos->count();
            $promedioProgreso = $totalProyectos > 0 ? round($proyectos->avg('progreso')) : 0;
            $has2FA = auth()->user()->hasTwoFactorEnabled();
        @endphp

        {{-- Welcome Header --}}
        <div class="welcome-card mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="console-pill" style="{{ !$has2FA ? 'background: rgba(245, 158, 11, 0.08); border-color: rgba(245, 158, 11, 0.3); color: #fbbf24;' : '' }}">
                        <span class="{{ $has2FA ? 'status-dot-pulse' : 'status-dot-warning' }}"></span>
                        <span>CONSOLA CLIENTE // {{ $has2FA ? '2FA ACTIVO' : '2FA PENDIENTE' }}</span>
                    </div>
                    <h1 class="fw-bold text-white mt-1 mb-1" style="font-size: clamp(1.8rem, 3vw, 2.3rem); letter-spacing: -0.8px; font-weight: 800 !important;">
                        Bienvenido, {{ auth()->user()->name }}
                    </h1>
                    <p class="text-muted mb-0" style="color: #94a3b8 !important; font-size: 0.95rem;">
                        Monitorea el avance de tus despliegues, aprueba hitos y descarga tus entregables de ingeniería.
                    </p>
                </div>
                <div class="d-none d-md-flex align-items-center gap-3">
                    <a href="{{ route('portal.configuracion') }}" class="btn btn-sm" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; border-radius: 10px; padding: 10px 18px; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;">
                        <i class="fas fa-cog me-2 text-cyan"></i> Preferencias
                    </a>
                </div>
            </div>
        </div>

        @if(!$has2FA)
            <div class="mb-4 p-3 rounded-4 d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.25); backdrop-filter: blur(16px);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); display: flex; align-items: center; justify-content: center; color: #fbbf24; font-size: 1rem; flex-shrink: 0;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div>
                        <div class="text-white fw-bold" style="font-size: 0.88rem;">Eleva la seguridad de tu entorno con 2FA</div>
                        <div style="color: #94a3b8; font-size: 0.78rem;">Activa la autenticación de dos pasos para blindar tus accesos y entregables de proyecto.</div>
                    </div>
                </div>
                <a href="{{ route('portal.configuracion') }}" class="btn btn-sm font-mono" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; font-size: 0.75rem; font-weight: 700; border-radius: 8px; padding: 8px 16px;">
                    <i class="fas fa-key me-1"></i> Activar 2FA Ahora
                </a>
            </div>
        @endif

        {{-- KPI Cards --}}
        <div class="kpi-summary-grid">
            <div class="kpi-card">
                <div>
                    <span class="kpi-label">Ecosistemas Activos</span>
                    <div class="kpi-value">{{ $totalProyectos }}</div>
                </div>
                <div class="kpi-icon-wrap icon-cyan">
                    <i class="fas fa-cubes"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div>
                    <span class="kpi-label">Progreso Global</span>
                    <div class="kpi-value">{{ $promedioProgreso }}%</div>
                </div>
                <div class="kpi-icon-wrap icon-purple">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div>
                    <span class="kpi-label">Seguridad de Entorno</span>
                    @if($has2FA)
                        <div class="kpi-value" style="font-size: 1.35rem; color: #34d399;">Blindado</div>
                        <span class="font-mono text-emerald" style="font-size: 0.68rem;"><i class="fas fa-lock me-1"></i>2FA Activo</span>
                    @else
                        <div class="kpi-value" style="font-size: 1.35rem; color: #fbbf24;">Básica</div>
                        <a href="{{ route('portal.configuracion') }}" class="font-mono text-warning text-decoration-none" style="font-size: 0.68rem;"><i class="fas fa-unlock-alt me-1"></i>2FA Pendiente</a>
                    @endif
                </div>
                <div class="kpi-icon-wrap {{ $has2FA ? 'icon-emerald' : 'icon-amber' }}">
                    <i class="fas {{ $has2FA ? 'fa-shield-alt' : 'fa-shield-virus' }}"></i>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-white mb-0" style="font-size: 0.85rem; font-family: 'JetBrains Mono', monospace; letter-spacing: 1.5px; text-transform: uppercase; color: #94a3b8;">
                <i class="fas fa-layer-group me-2 text-cyan"></i> Proyectos en Ejecución
            </h4>
            <span class="font-mono" style="font-size: 0.75rem; color: #64748b;">
                {{ $totalProyectos }} {{ $totalProyectos === 1 ? 'Ecosistema asignado' : 'Ecosistemas asignados' }}
            </span>
        </div>

        @if($proyectos->isNotEmpty())
            <div class="d-flex flex-column gap-4">
                @foreach($proyectos as $proyecto)
                    @php
                        $estadoClass = match(strtolower($proyecto->estado ?? '')) {
                            'desarrollo', 'en desarrollo' => 'badge-desarrollo',
                            'pruebas', 'en pruebas' => 'badge-pruebas',
                            'finalizado', 'completado' => 'badge-finalizado',
                            default => 'badge-prospecto'
                        };
                    @endphp

                    <div class="project-card-premium">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                            <div>
                                <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: #38bdf8; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 700;">
                                    {{ $proyecto->servicio ?? 'Arquitectura Web' }} Core
                                </span>
                                <h3 class="fw-bold text-white mt-1 mb-0" style="font-size: 1.65rem; font-weight: 800 !important; letter-spacing: -0.5px;">
                                    {{ $proyecto->nombre }}
                                </h3>
                            </div>
                            <div class="tech-badge {{ $estadoClass }}">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i> {{ $proyecto->estado ?? 'En Proceso' }}
                            </div>
                        </div>

                        <div class="mt-4 mb-2">
                            <div class="progress-container">
                                <div class="progress-bar-cyan" style="width: {{ $proyecto->progreso }}%;"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.72rem; color: #64748b;">
                                Avance de Ingeniería
                            </span>
                            <span class="font-mono text-cyan fw-bold" style="font-size: 0.8rem;">
                                {{ $proyecto->progreso }}%
                            </span>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.06);">
                            <div class="d-flex flex-wrap gap-4 gap-md-5">
                                <div>
                                    <span class="meta-label"><i class="fas fa-calendar-alt me-1 text-cyan"></i> Próxima Entrega</span>
                                    <span class="meta-value font-mono">{{ $proyecto->siguiente_entrega ?? 'Por definir' }}</span>
                                </div>
                                <div>
                                    <span class="meta-label"><i class="fas fa-user-shield me-1 text-purple-400"></i> Desarrollador Líder</span>
                                    <span class="meta-value">{{ $proyecto->developer->name ?? 'Equipo Central' }}</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('portal.proyecto', $proyecto->id) }}" class="action-btn-portal">
                                    <span>Examinar Módulos e Hitos</span>
                                    <i class="fas fa-arrow-right" style="font-size: 0.75rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state-card">
                <div class="empty-icon">
                    <i class="fas fa-cubes"></i>
                </div>
                <h3 class="fw-bold text-white mb-2" style="font-size: 1.6rem; font-weight: 700 !important;">
                    Construyendo tu entorno operativo
                </h3>
                <p class="text-muted small mx-auto mb-4" style="max-width: 520px; line-height: 1.6; color: #94a3b8 !important;">
                    Tu cuenta se ha sincronizado correctamente. Actualmente nuestro equipo está configurando los módulos base de tu servicio en el administrador para mapear tus despliegues.
                </p>
                <div class="font-mono" style="font-size: 0.72rem; color: #64748b; letter-spacing: 1px;">
                    ESTADO DE RED: En espera de Asignación
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

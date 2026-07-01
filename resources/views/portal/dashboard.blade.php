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
        max-width: 1100px;
        margin: 0 auto;
    }

    .welcome-card {
        background: rgba(255, 255, 255, 0.01) !important;
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
        backdrop-filter: blur(24px) !important;
        -webkit-backdrop-filter: blur(24px) !important;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    }

    .project-card-premium {
        background: rgba(255, 255, 255, 0.01) !important;
        border: 1px solid rgba(6, 182, 212, 0.15) !important;
        backdrop-filter: blur(24px) !important;
        -webkit-backdrop-filter: blur(24px) !important;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .project-card-premium:hover {
        border-color: rgba(6, 182, 212, 0.4) !important;
        box-shadow: 0 0 30px rgba(6, 182, 212, 0.15) !important;
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
        position: relative;
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
        margin-bottom: 6px;
        display: block;
    }

    .meta-value {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
    }

    .action-btn-portal {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.7);
        padding: 10px 24px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .action-btn-portal:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .empty-state-card {
        background: rgba(255, 255, 255, 0.01) !important;
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
        backdrop-filter: blur(24px) !important;
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
    }

    .empty-icon {
        width: 65px;
        height: 65px;
        background: rgba(6, 182, 212, 0.03);
        border: 1px solid rgba(6, 182, 212, 0.12);
        color: #06b6d4;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 24px auto;
        box-shadow: 0 0 20px rgba(6, 182, 212, 0.05);
    }
</style>

<div class="admin-viewport">
    <div class="container portal-container">

        <div class="welcome-card d-flex flex-wrap justify-content-between align-items-center gap-3 mb-5">
            <div>
                <span style="font-family: monospace; font-size: 0.7rem; color: rgba(255,255,255,0.4); letter-spacing: 2px; text-transform: uppercase;">
                    CONSOLA DE CONTROL // INTEGRIDAD ESTABLE
                </span>
                <h1 class="fw-bold text-white mt-1 mb-0" style="font-size: 2rem; letter-spacing: -0.5px; font-weight: 800 !important;">
                    Bienvenido, {{ auth()->user()->name }}
                </h1>
            </div>
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="action-btn-portal" style="color: #f87171; border-color: rgba(248,113,113,0.15); background: rgba(248,113,113,0.02);">
                        <i class="fas fa-power-off me-2"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <h4 class="fw-bold text-white mb-4" style="font-size: 0.85rem; font-family: monospace; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.5);">
            Ecosistemas en Ejecución
        </h4>

        @if($proyectos->isNotEmpty())
            <div class="d-flex flex-column gap-4">
                @foreach($proyectos as $proyecto)
                    <div class="project-card-premium">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                            <div>
                                <span style="font-family: monospace; font-size: 0.7rem; color: #06b6d4; letter-spacing: 1px;">
                                    {{ $proyecto->servicio }} Core
                                </span>
                                <h3 class="fw-bold text-white mt-1 mb-0" style="font-size: 1.6rem; font-weight: 700 !important;">
                                    {{ $proyecto->nombre }}
                                </h3>
                            </div>
                            <div class="tech-badge">
                                <i class="fas fa-sync-alt fa-spin me-2" style="font-size: 0.7rem;"></i> En {{ $proyecto->estado }}
                            </div>
                        </div>

                        <div class="mt-4 mb-2">
                            <div class="progress-container">
                                <div class="progress-bar-cyan" style="width: {{ $proyecto->progreso }}%;"></div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <span style="font-family: monospace; font-size: 0.7rem; color: rgba(255,255,255,0.3); merge-bottom: 0;">Progreso de Arquitectura</span>
                            <span class="font-mono text-info fw-bold" style="font-size: 0.75rem;">{{ $proyecto->progreso }}%</span>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 pt-4" style="border-top: 1px solid rgba(255,255,255,0.04);">
                            <div class="d-flex gap-5">
                                <div>
                                    <span class="meta-label">Próxima Entrega</span>
                                    <span class="meta-value font-mono">{{ $proyecto->siguiente_entrega ?? 'Por definir' }}</span>
                                </div>
                                <div>
                                    <span class="meta-label">Desarrollador Líder</span>
                                    <span class="meta-value">{{ $proyecto->developer->name ?? 'Asignando...' }}</span>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('portal.proyecto', $proyecto->id) }}" class="action-btn-portal active" style="background: rgba(6, 182, 212, 0.1); border-color: #06b6d4; color: #fff;">
                                    Examinar Módulos e Hitos <i class="fas fa-chevron-right ms-2" style="font-size: 0.7rem;"></i>
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
                <p class="text-muted small mx-auto mb-4" style="max-width: 520px; line-height: 1.6; color: rgba(255, 255, 255, 0.4) !important;">
                    Tu cuenta se ha sincronizado correctamente. Actualmente nuestro equipo está configurando los módulos base de tu servicio en el administrador para mapear tus despliegues.
                </p>
                <div class="font-mono" style="font-size: 0.65rem; color: rgba(255, 255, 255, 0.2); letter-spacing: 1px;">
                    ESTADO DE RED: EN_ESPERA_DE_ASIGNACION_NEXUS
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

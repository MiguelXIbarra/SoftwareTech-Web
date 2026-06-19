@extends('layouts.app')
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    html, body {
        background-color: #030712 !important;
    }

    .navbar {
        background: rgba(3, 7, 18, 0.4) !important;
        backdrop-filter: blur(20px) !important;
        -webkit-backdrop-filter: blur(20px) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .portal-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        padding-top: 140px;
        padding-bottom: 60px;
        color: #ffffff;
        position: relative;
        margin-top: -75px;
    }

    .portal-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 15% 15%, rgba(6, 182, 212, 0.08) 0%, transparent 50%),
                    radial-gradient(circle at 85% 85%, rgba(138, 43, 226, 0.08) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .portal-container {
        position: relative;
        z-index: 5;
        max-width: 1100px;
    }

    .portal-header-panel {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-top-color: rgba(255, 255, 255, 0.12) !important;
        border-left-color: rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: blur(20px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(20px) saturate(160%) !important;
        border-radius: 20px;
        padding: 30px 40px;
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .portal-user-tag {
        font-family: monospace;
        font-size: 0.75rem;
        color: #06b6d4;
        letter-spacing: 2px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .portal-welcome-title {
        font-size: 1.7rem;
        font-weight: 800;
        letter-spacing: -1px;
        margin: 0;
    }

    .project-glass-card {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-top-color: rgba(255, 255, 255, 0.12) !important;
        border-left-color: rgba(255, 255, 255, 0.12) !important;
        backdrop-filter: blur(20px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(20px) saturate(160%) !important;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5) !important;
        border-radius: 24px;
        padding: 40px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .project-glass-card:hover {
        border-color: rgba(6, 182, 212, 0.25);
        transform: translateY(-3px);
        box-shadow: 0 30px 50px rgba(0, 0, 0, 0.6) !important;
    }

    .status-badge-active {
        font-family: monospace;
        font-size: 0.75rem;
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: #06b6d4;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
    }

    .custom-progress-track {
        height: 6px;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 3px;
        margin: 25px 0 15px 0;
        overflow: hidden;
    }

    .custom-progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #007bff, #06b6d4);
        border-radius: 3px;
    }

    .meta-label {
        font-family: monospace;
        font-size: 0.72rem;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .meta-value {
        font-size: 0.9rem;
        color: #e5e7eb;
        font-weight: 500;
        margin-top: 4px;
    }

    .btn-portal-action {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #ffffff !important;
        padding: 12px 22px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-portal-action:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(138, 43, 226, 0.3);
        box-shadow: 0 0 20px rgba(138, 43, 226, 0.15);
    }

    .logout-trigger {
        background: transparent;
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #ef4444;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .logout-trigger:hover {
        background: rgba(239, 68, 68, 0.06);
        border-color: #ef4444;
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.15);
    }
</style>

<div class="portal-viewport">
    <div class="container portal-container">

        <div class="portal-header-panel">
            <div>
                <span class="portal-user-tag">Consola de Control // Integridad Estable</span>
                <h1 class="portal-welcome-title text-white">Bienvenido, {{ Auth::user()->name }}</h1>
            </div>
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-trigger">
                        <i class="fas fa-power-off me-2"></i>Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <span class="portal-user-tag mb-4">Ecosistemas en Ejecución</span>

                @forelse($proyectos as $p)
                <div class="project-glass-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <span class="text-muted small d-block mb-1" style="font-family: monospace; color: #06b6d4 !important;">{{ $p['modulo'] }}</span>
                            <h3 class="fw-700 text-white mb-0" style="font-size: 1.3rem; letter-spacing: -0.5px;">{{ $p['nombre'] }}</h3>
                        </div>
                        <div>
                            <span class="status-badge-active"><i class="fas fa-sync fa-spin me-2"></i>{{ $p['estado'] }}</span>
                        </div>
                    </div>

                    <div class="custom-progress-track">
                        <div class="custom-progress-fill" style="width: {{ $p['progreso'] }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="small font-monospace" style="color: #9ca3af !important;">Progreso de Arquitectura</span>
                        <span class="small fw-bold font-monospace" style="color: #06b6d4;">{{ $p['progreso'] }}%</span>
                    </div>

                    <div class="row g-4 pt-4 border-top" style="border-color: rgba(255,255,255,0.06) !important;">
                        <div class="col-6 col-sm-3">
                            <div class="meta-label">Próxima Entrega</div>
                            <div class="meta-value font-monospace">{{ $p['siguiente_entrega'] }}</div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="meta-label">Servidor Asignado</div>
                            <div class="meta-value font-monospace">Staging-Cluster-04</div>
                        </div>
                        <div class="col-12 col-sm-6 text-sm-end d-flex align-items-end justify-content-sm-end">
                            <a href="{{ route('portal.proyecto', $p['id']) }}" class="btn-portal-action">
                                <span>Examinar Módulos e Hitos</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>  
                </div>
                @empty
                <div class="project-glass-card text-center py-5">
                    <i class="fas fa-terminal fa-2x mb-3 text-muted"></i>
                    <p class="text-muted mb-0">No se encontraron entornos lógicos o infraestructuras asociadas a esta cuenta corporativa.</p>
                </div>
                @endforelse

            </div>
        </div>

    </div>
</div>
@endsection

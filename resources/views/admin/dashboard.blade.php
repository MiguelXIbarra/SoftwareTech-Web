@extends('layouts.app')

@section('content')
@php
    // --- ÚNICAMENTE MÉTRICAS DE GRÁFICOS (Sincronizadas con el controlador) ---
    $totalProyectos = \App\Models\Project::count();
    $promedioProgreso = $totalProyectos > 0 ? round(\App\Models\Project::avg('progreso'), 1) : 0;

    $proyectosProspecto = \App\Models\Project::where('estado', 'Prospecto')->count();
    $proyectosDesarrollo = \App\Models\Project::where('estado', 'En Desarrollo')->count();
    $proyectosPruebas = \App\Models\Project::where('estado', 'En Pruebas')->count();
    $proyectosFinalizados = \App\Models\Project::where('estado', 'Finalizado')->count();

    $totalPorFases = $proyectosProspecto + $proyectosDesarrollo + $proyectosPruebas + $proyectosFinalizados;

    $circunferencia = 2 * 3.14159265359 * 70;

    $pctProspecto = $totalPorFases > 0 ? $proyectosProspecto / $totalPorFases : 0;
    $pctDesarrollo = $totalPorFases > 0 ? $proyectosDesarrollo / $totalPorFases : 0;
    $pctPruebas = $totalPorFases > 0 ? $proyectosPruebas / $totalPorFases : 0;
    $pctFinalizados = $totalPorFases > 0 ? $proyectosFinalizados / $totalPorFases : 0;

    $dashProspecto = $pctProspecto * $circunferencia;
    $dashDesarrollo = $pctDesarrollo * $circunferencia;
    $dashPruebas = $pctPruebas * $circunferencia;
    $dashFinalizados = $pctFinalizados * $circunferencia;

    $offsetDesarrollo = $dashProspecto;
    $offsetPruebas = $offsetDesarrollo + $dashDesarrollo;
    $offsetFinalizados = $offsetPruebas + $dashPruebas;
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    .admin-viewport {
        background: #030712 !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        min-height: calc(100vh - 75px);
        color: #ffffff;
        position: relative;
        padding: 40px 20px;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background:
            radial-gradient(circle at 20% 20%, rgba(6, 182, 212, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(138, 43, 226, 0.04) 0%, transparent 50%);
        z-index: 1;
        pointer-events: none;
    }

    .admin-container {
        position: relative;
        z-index: 5;
        width: 100%;
    }

    .card-glass-neon {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(6, 182, 212, 0.25) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6) !important;
        border-radius: 16px;
        padding: 28px;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .card-glass-neon:hover {
        border-color: #06b6d4 !important;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.35) !important;
        transform: translateY(-4px);
    }

    .stat-title {
        font-size: 0.75rem;
        color: #22d3ee;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1;
        color: #ffffff;
        letter-spacing: -1px;
    }

    .stat-meta {
        font-size: 0.7rem;
        color: #cbd5e1;
        font-weight: 600;
        margin-top: 12px;
        display: block;
        letter-spacing: 0.5px;
    }

    .dashboard-panel-grid {
        display: grid;
        grid-template-columns: 1.6fr 1.2fr;
        gap: 24px;
        margin-top: 32px;
        align-items: start;
    }

    @media (max-width: 992px) {
        .dashboard-panel-grid { grid-template-columns: 1fr; }
    }

    .panel-section-box {
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 32px;
        min-height: 520px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .panel-header-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #ffffff;
        margin-bottom: 24px;
    }

    .donut-svg-container {
        position: relative;
        width: 100%;
        max-width: 320px;
        margin: auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .donut-ring {
        fill: none;
        stroke-width: 22;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }

    .donut-center-text {
        position: absolute;
        text-align: center;
        pointer-events: none;
    }

    .donut-center-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 2px;
    }

    .donut-center-number {
        font-size: 2.3rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1;
    }

    .donut-center-label {
        font-size: 0.75rem;
        color: #cbd5e1;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    .side-metrics-wrapper {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .legend-list-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .custom-legend-card {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        overflow: hidden;
        height: 72px;
    }

    .legend-card-number {
        width: 65px;
        height: 100%;
        background: rgba(255, 255, 255, 0.03);
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffff;
    }

    .legend-card-info {
        padding-left: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .legend-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-card-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-card-subtitle {
        font-size: 0.65rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    .sprint-completion-box {
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 24px;
    }

    .progress-track-tech {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        height: 10px;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 12px;
    }

    .progress-bar-neon {
        height: 100%;
        background: linear-gradient(90deg, #06b6d4, #10b981);
        box-shadow: 0 0 12px rgba(6, 182, 212, 0.5);
        border-radius: 10px;
    }

    .action-hud-btn {
        background: rgba(6, 182, 212, 0.05);
        border: 1px solid rgba(6, 182, 212, 0.3);
        color: #22d3ee;
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .action-hud-btn:hover {
        color: #fff;
        background: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);
        transform: translateY(-1px);
    }

    .card-client-premium {
        background: rgba(255, 255, 255, 0.02) !important;
        border: 1px solid rgba(6, 182, 212, 0.2) !important;
        backdrop-filter: blur(166px) saturate(120%) !important;
        border-radius: 24px;
        padding: 50px 40px;
        position: relative;
        overflow: hidden;
    }

    .card-client-premium::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, #06b6d4, transparent);
    }
</style>

<div class="admin-viewport">
    <div class="container admin-container">

        @if(auth()->user()->role === 'cliente')
            @php
                $proyecto = \App\Models\Project::where('user_id', auth()->id())->first();
            @endphp

            @if($proyecto)
                <div class="card-client-premium">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                        <div>
                            <span style="font-size: 0.75rem; color: #22d3ee; letter-spacing: 1px; font-weight: 700;">
                                Proyecto Asignado
                            </span>
                            <h2 class="fw-bold text-white mt-1 mb-0" style="font-size: 2.3rem; letter-spacing: -1px; font-weight: 800;">
                                {{ $proyecto->nombre }}
                            </h2>
                        </div>
                        <span class="badge" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); color: #22d3ee; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                            {{ $proyecto->estado }}
                        </span>
                    </div>

                    <div class="row mt-5 pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span style="font-size: 0.7rem; color: #cbd5e1; font-weight: 600; display: block; margin-bottom: 4px;">Encargado</span>
                            <span class="text-white small" style="font-weight: 500;">{{ $proyecto->developer->name ?? 'Asignando personal...' }}</span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span style="font-size: 0.7rem; color: #cbd5e1; font-weight: 600; display: block; margin-bottom: 4px;">Próxima Entrega</span>
                            <span class="text-info small" style="font-weight: 600;">{{ $proyecto->siguiente_entrega ?? 'Por definir' }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="card-client-premium text-center py-5">
                    <h2 class="fw-bold text-white mb-2" style="font-size: 2rem; letter-spacing: -0.5px; font-weight: 800;">
                        Bienvenido a tu Panel de Control
                    </h2>
                    <p class="small mx-auto mb-4" style="max-width: 500px; line-height: 1.6; color: #cbd5e1;">
                        Actualmente no tienes ningún proyecto asignado. Tan pronto como el administrador registre tu servicio, aquí podrás supervisar tus avances.
                    </p>
                </div>
            @endif

        @else
            <div class="row mb-5">
                <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold text-white mb-1" style="font-size: 2.2rem; letter-spacing: -0.5px; font-weight: 800;">
                            Centro de Operaciones
                        </h2>
                        <span style="font-size: 0.75rem; color: #cbd5e1; letter-spacing: 1px; font-weight: 600; text-transform: uppercase;">
                            ID Terminal: 1001-1001
                        </span>
                    </div>
                    <div>
                        <a href="{{ route('admin.proyectos.crear') }}" class="action-hud-btn">
                            + Iniciar Nuevo Proyecto
                        </a>
                    </div>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card-glass-neon">
                        <span class="stat-title">
                            <span style="width: 8px; height: 8px; background: #06b6d4; border-radius: 50%; box-shadow: 0 0 8px #06b6d4;"></span>
                            Proyectos Activos
                        </span>
                        <div class="stat-number">{{ $proyectosActivos }}</div>
                        <span class="stat-meta">Desplegados en producción</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-glass-neon">
                        <span class="stat-title">
                            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 8px #10b981;"></span>
                            Clientes Registrados
                        </span>
                        <div class="stat-number">{{ $clientesRegistrados }}</div>
                        <span class="stat-meta">Cuentas verificadas activas</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card-glass-neon">
                        <span class="stat-title">
                            <span style="width: 8px; height: 8px; background: #a855f7; border-radius: 50%; box-shadow: 0 0 8px #a855f7;"></span>
                            Invitaciones Pendientes
                        </span>
                        <div class="stat-number">{{ $invitacionesPendientes }}</div>
                        <span class="stat-meta">Esperando activación de acceso</span>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel-grid">

                <div class="panel-section-box">
                    <div class="panel-header-title">Command Room</div>

                    <div class="donut-svg-container">
                        <svg viewBox="0 0 160 160" class="w-100 h-100">
                            <circle cx="80" cy="80" r="70" class="donut-ring" stroke="rgba(255,255,255,0.03)" />

                            @if($totalPorFases > 0)
                                <circle cx="80" cy="80" r="70" class="donut-ring"
                                    stroke="#a855f7"
                                    stroke-dasharray="{{ $dashProspecto }} {{ $circunferencia }}"
                                    stroke-dashoffset="0"
                                    style="filter: drop-shadow(0 0 6px rgba(168, 85, 247, 0.4));" />

                                <circle cx="80" cy="80" r="70" class="donut-ring"
                                    stroke="#facc15"
                                    stroke-dasharray="{{ $dashDesarrollo }} {{ $circunferencia }}"
                                    stroke-dashoffset="-{{ $offsetDesarrollo }}"
                                    style="filter: drop-shadow(0 0 6px rgba(250, 204, 21, 0.4));" />

                                <circle cx="80" cy="80" r="70" class="donut-ring"
                                    stroke="#06b6d4"
                                    stroke-dasharray="{{ $dashPruebas }} {{ $circunferencia }}"
                                    stroke-dashoffset="-{{ $offsetPruebas }}"
                                    style="filter: drop-shadow(0 0 6px rgba(6, 182, 212, 0.4));" />

                                <circle cx="80" cy="80" r="70" class="donut-ring"
                                    stroke="#4ade80"
                                    stroke-dasharray="{{ $dashFinalizados }} {{ $circunferencia }}"
                                    stroke-dashoffset="-{{ $offsetFinalizados }}"
                                    style="filter: drop-shadow(0 0 6px rgba(74, 222, 128, 0.4));" />
                            @else
                                <circle cx="80" cy="80" r="70" class="donut-ring" stroke="rgba(255,255,255,0.08)" />
                            @endif
                        </svg>

                        <div class="donut-center-text">
                            <div class="donut-center-title">Proyectos</div>
                            <div class="donut-center-number">{{ $totalPorFases }}</div>
                            <div class="donut-center-label">Total</div>
                        </div>
                    </div>
                </div>

                <div class="side-metrics-wrapper">

                    <div class="legend-list-container">

                        <div class="custom-legend-card">
                            <div class="legend-card-number">{{ $proyectosProspecto }}</div>
                            <div class="legend-card-info">
                                <div class="legend-card-title" style="color: #a855f7;">
                                    <span class="legend-card-dot" style="background: #a855f7;"></span>
                                    Prospectos
                                </div>
                                <div class="legend-card-subtitle">Propuestas bajo evaluación</div>
                            </div>
                        </div>

                        <div class="custom-legend-card">
                            <div class="legend-card-number">{{ $proyectosDesarrollo }}</div>
                            <div class="legend-card-info">
                                <div class="legend-card-title" style="color: #facc15;">
                                    <span class="legend-card-dot" style="background: #facc15;"></span>
                                    En Desarrollo
                                </div>
                                <div class="legend-card-subtitle">Fase activa de sprint</div>
                            </div>
                        </div>

                        <div class="custom-legend-card">
                            <div class="legend-card-number">{{ $proyectosPruebas }}</div>
                            <div class="legend-card-info">
                                <div class="legend-card-title" style="color: #06b6d4;">
                                    <span class="legend-card-dot" style="background: #06b6d4;"></span>
                                    En Pruebas
                                </div>
                                <div class="legend-card-subtitle">QA y control de estabilidad</div>
                            </div>
                        </div>

                        <div class="custom-legend-card">
                            <div class="legend-card-number">{{ $proyectosFinalizados }}</div>
                            <div class="legend-card-info">
                                <div class="legend-card-title" style="color: #4ade80;">
                                    <span class="legend-card-dot" style="background: #4ade80;"></span>
                                    Finalizados
                                </div>
                                <div class="legend-card-subtitle">Listos para entrega</div>
                            </div>
                        </div>

                    </div>

                    <div class="sprint-completion-box">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-white small fw-bold">Tasa de Completado de Sprints</span>
                            <span class="fw-bold" style="color: #cbd5e1; font-size: 0.95rem;">{{ $promedioProgreso }}%</span>
                        </div>
                        <div class="progress-track-tech">
                            <div class="progress-bar-neon" style="width: {{ $promedioProgreso }}%"></div>
                        </div>
                    </div>

                </div>

            </div>
        @endif

    </div>
</div>
@endsection

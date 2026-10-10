@extends('layouts.app')

@section('content')
@php
    $circunferencia = 2 * 3.14159265359 * 70;

    $pctProspecto = $totalPorFasesGlobal > 0 ? $fasesGlobal['proyectosProspecto'] / $totalPorFasesGlobal : 0;
    $pctDesarrollo = $totalPorFasesGlobal > 0 ? $fasesGlobal['proyectosDesarrollo'] / $totalPorFasesGlobal : 0;
    $pctPruebas = $totalPorFasesGlobal > 0 ? $fasesGlobal['proyectosPruebas'] / $totalPorFasesGlobal : 0;
    $pctFinalizados = $totalPorFasesGlobal > 0 ? $fasesGlobal['proyectosFinalizados'] / $totalPorFasesGlobal : 0;

    $dashProspecto = $pctProspecto * $circunferencia;
    $dashDesarrollo = $pctDesarrollo * $circunferencia;
    $dashPruebas = $pctPruebas * $circunferencia;
    $dashFinalizados = $pctFinalizados * $circunferencia;

    $offsetDesarrollo = $dashProspecto;
    $offsetPruebas = $offsetDesarrollo + $dashDesarrollo;
    $offsetFinalizados = $offsetPruebas + $dashPruebas;
@endphp

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
        background:
            radial-gradient(circle at 20% 20%, rgba(6, 182, 212, 0.07) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(138, 43, 226, 0.06) 0%, transparent 50%),
            radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.04) 0%, transparent 60%);
        z-index: 1;
        pointer-events: none;
    }

    .admin-container {
        position: relative;
        z-index: 5;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .card-glass-neon {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.65) 0%, rgba(10, 15, 30, 0.8) 100%) !important;
        border: 1px solid rgba(255, 255, 255, 0.08) !important;
        backdrop-filter: blur(24px) saturate(160%) !important;
        -webkit-backdrop-filter: blur(24px) saturate(160%) !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
        border-radius: 20px;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
    }

    .card-glass-neon:hover {
        border-color: rgba(6, 182, 212, 0.4) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6), 0 0 30px rgba(6, 182, 212, 0.15) !important;
        transform: translateY(-4px);
    }

    .stat-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.74rem;
        color: #22d3ee;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stat-number {
        font-size: 3.2rem;
        font-weight: 800;
        line-height: 1;
        background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    .stat-meta {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 12px;
        display: block;
        letter-spacing: 0.3px;
    }

    .dashboard-panel-grid {
        display: grid;
        grid-template-columns: 1.4fr 1.3fr;
        gap: 24px;
        margin-top: 32px;
        align-items: start;
    }

    @media (max-width: 992px) {
        .dashboard-panel-grid { grid-template-columns: 1fr; }
    }

    .panel-section-box {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(10, 15, 30, 0.75) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 35px;
        min-height: 520px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        backdrop-filter: blur(20px);
    }

    .panel-header-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #e2e8f0;
        margin-bottom: 28px;
        display: flex;
        align-items: center;
        gap: 8px;
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
        transition: stroke-dasharray 1s ease-in-out;
    }

    .donut-center-text {
        position: absolute;
        text-align: center;
        pointer-events: none;
    }

    .donut-center-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .donut-center-number {
        font-size: 2.8rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1;
        letter-spacing: -1px;
    }

    .donut-center-label {
        font-size: 0.75rem;
        color: #38bdf8;
        font-weight: 600;
        letter-spacing: 1px;
        margin-top: 4px;
        font-family: 'JetBrains Mono', monospace;
    }

    .side-metrics-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .legend-list-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .custom-legend-card {
        display: flex;
        align-items: center;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        overflow: hidden;
        height: 74px;
        transition: all 0.3s ease;
    }

    .custom-legend-card:hover {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(6, 182, 212, 0.3);
        transform: translateX(4px);
    }

    .legend-card-number {
        width: 70px;
        height: 100%;
        background: rgba(255, 255, 255, 0.03);
        border-right: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 800;
        color: #ffffff;
        font-family: 'JetBrains Mono', monospace;
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
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-card-subtitle {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.68rem;
        color: #94a3b8;
        font-weight: 500;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    .sprint-completion-box {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(10, 15, 30, 0.75) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 24px 28px;
    }

    .progress-track-tech {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        height: 10px;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 14px;
    }

    .progress-bar-neon {
        height: 100%;
        background: linear-gradient(90deg, #06b6d4, #10b981);
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.6);
        border-radius: 10px;
        transition: width 1s ease-in-out;
    }

    .action-hud-btn {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(59, 130, 246, 0.15));
        border: 1px solid rgba(6, 182, 212, 0.4);
        color: #ffffff !important;
        padding: 10px 22px;
        border-radius: 10px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .action-hud-btn:hover {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.3), rgba(59, 130, 246, 0.3));
        border-color: #06b6d4;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.4);
        transform: translateY(-2px);
    }
</style>

<div class="admin-viewport">
    <div class="container admin-container">

        @if(auth()->user()->role === 'cliente')
            @php
                $proyecto = \App\Models\Project::where('user_id', auth()->id())->first();
            @endphp

            @if($proyecto)
                <div class="card-glass-neon">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                        <div>
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: #22d3ee; letter-spacing: 1.5px; font-weight: 700; text-transform: uppercase;">
                                Proyecto Asignado
                            </span>
                            <h2 class="fw-bold text-white mt-1 mb-0" style="font-size: 2.2rem; letter-spacing: -1px; font-weight: 800;">
                                {{ $proyecto->nombre }}
                            </h2>
                        </div>
                        <span class="badge" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3); color: #22d3ee; padding: 8px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; font-family: 'JetBrains Mono', monospace;">
                            {{ $proyecto->estado }}
                        </span>
                    </div>

                    <div class="row mt-5 pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: #94a3b8; font-weight: 600; display: block; margin-bottom: 4px; text-transform: uppercase;">Encargado Líder</span>
                            <span class="text-white" style="font-weight: 600;">{{ $proyecto->developer->name ?? 'Asignando personal...' }}</span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: #94a3b8; font-weight: 600; display: block; margin-bottom: 4px; text-transform: uppercase;">Próxima Entrega</span>
                            <span class="text-info font-mono" style="font-weight: 700;">{{ $proyecto->siguiente_entrega ?? 'Por definir' }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="card-glass-neon text-center py-5">
                    <h2 class="fw-bold text-white mb-2" style="font-size: 2rem; letter-spacing: -0.5px; font-weight: 800;">
                        Bienvenido a tu Panel de Control
                    </h2>
                    <p class="small mx-auto mb-4" style="max-width: 500px; line-height: 1.6; color: #94a3b8;">
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
                        <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: #94a3b8; letter-spacing: 1.5px; font-weight: 600; text-transform: uppercase;">
                            <i class="fas fa-terminal text-cyan me-1"></i> ID Terminal: 1001-1001 // SYSTEM ACTIVE
                        </span>
                    </div>
                    @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                    <div>
                        <a href="{{ route('admin.proyectos.crear') }}" class="action-hud-btn">
                            <i class="fas fa-plus"></i> Iniciar Nuevo Proyecto
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            @if(auth()->user()->role === 'superadmin')
                <div class="row g-4 justify-content-center">
                    <div class="col-md-4">
                        <div class="card-glass-neon">
                            <span class="stat-title">
                                <span style="width: 8px; height: 8px; background: #06b6d4; border-radius: 50%; box-shadow: 0 0 10px #06b6d4;"></span>
                                Proyectos Activos
                            </span>
                            <div class="stat-number">{{ $proyectosActivos }}</div>
                            <span class="stat-meta"><i class="fas fa-server me-1 text-cyan"></i> Desplegados en producción</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-glass-neon">
                            <span class="stat-title">
                                <span style="width: 8px; height: 8px; background: #a855f7; border-radius: 50%; box-shadow: 0 0 10px #a855f7;"></span>
                                Clientes Registrados
                            </span>
                            <div class="stat-number">{{ $clientesRegistrados }}</div>
                            <span class="stat-meta"><i class="fas fa-user-check me-1 text-purple-400"></i> Cuentas verificadas activas</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card-glass-neon">
                            <span class="stat-title">
                                <span style="width: 8px; height: 8px; background: #f59e0b; border-radius: 50%; box-shadow: 0 0 10px #f59e0b;"></span>
                                Invitaciones Pendientes
                            </span>
                            <div class="stat-number">{{ $invitacionesPendientes }}</div>
                            <span class="stat-meta"><i class="fas fa-envelope-open-text me-1 text-yellow-400"></i> Esperando activación</span>
                        </div>
                    </div>
                </div>

                <div class="dashboard-panel-grid">
                    <div class="panel-section-box">
                        <div class="panel-header-title">
                            <i class="fas fa-chart-pie text-cyan"></i> Distribución de Fases // Command Room
                        </div>
                        <div class="donut-svg-container">
                            <svg viewBox="0 0 160 160" class="w-100 h-100">
                                <circle cx="80" cy="80" r="70" class="donut-ring" stroke="rgba(255,255,255,0.03)" />
                                @if($totalPorFasesGlobal > 0)
                                    <circle cx="80" cy="80" r="70" class="donut-ring" stroke="#a855f7" stroke-dasharray="{{ $dashProspecto }} {{ $circunferencia }}" stroke-dashoffset="0" style="filter: drop-shadow(0 0 8px rgba(168, 85, 247, 0.5));" />
                                    <circle cx="80" cy="80" r="70" class="donut-ring" stroke="#facc15" stroke-dasharray="{{ $dashDesarrollo }} {{ $circunferencia }}" stroke-dashoffset="-{{ $offsetDesarrollo }}" style="filter: drop-shadow(0 0 8px rgba(250, 204, 21, 0.5));" />
                                    <circle cx="80" cy="80" r="70" class="donut-ring" stroke="#06b6d4" stroke-dasharray="{{ $dashPruebas }} {{ $circunferencia }}" stroke-dashoffset="-{{ $offsetPruebas }}" style="filter: drop-shadow(0 0 8px rgba(6, 182, 212, 0.5));" />
                                    <circle cx="80" cy="80" r="70" class="donut-ring" stroke="#4ade80" stroke-dasharray="{{ $dashFinalizados }} {{ $circunferencia }}" stroke-dashoffset="-{{ $offsetFinalizados }}" style="filter: drop-shadow(0 0 8px rgba(74, 222, 128, 0.5));" />
                                @else
                                    <circle cx="80" cy="80" r="70" class="donut-ring" stroke="rgba(255,255,255,0.08)" />
                                @endif
                            </svg>
                            <div class="donut-center-text">
                                <div class="donut-center-title">Proyectos</div>
                                <div class="donut-center-number">{{ $totalPorFasesGlobal }}</div>
                                <div class="donut-center-label">Total Global</div>
                            </div>
                        </div>
                    </div>

                    <div class="side-metrics-wrapper">
                        <div class="legend-list-container">
                            <div class="custom-legend-card">
                                <div class="legend-card-number" style="color: #c084fc;">{{ $fasesGlobal['proyectosProspecto'] }}</div>
                                <div class="legend-card-info">
                                    <div class="legend-card-title" style="color: #c084fc;"><span class="legend-card-dot" style="background: #a855f7; box-shadow: 0 0 8px #a855f7;"></span>Prospectos</div>
                                    <div class="legend-card-subtitle">Propuestas bajo evaluación inicial</div>
                                </div>
                            </div>
                            <div class="custom-legend-card">
                                <div class="legend-card-number" style="color: #fde047;">{{ $fasesGlobal['proyectosDesarrollo'] }}</div>
                                <div class="legend-card-info">
                                    <div class="legend-card-title" style="color: #fde047;"><span class="legend-card-dot" style="background: #facc15; box-shadow: 0 0 8px #facc15;"></span>En Desarrollo</div>
                                    <div class="legend-card-subtitle">Fase activa de sprint y arquitectura</div>
                                </div>
                            </div>
                            <div class="custom-legend-card">
                                <div class="legend-card-number" style="color: #38bdf8;">{{ $fasesGlobal['proyectosPruebas'] }}</div>
                                <div class="legend-card-info">
                                    <div class="legend-card-title" style="color: #38bdf8;"><span class="legend-card-dot" style="background: #06b6d4; box-shadow: 0 0 8px #06b6d4;"></span>En Pruebas</div>
                                    <div class="legend-card-subtitle">Control de calidad SAST y QA</div>
                                </div>
                            </div>
                            <div class="custom-legend-card">
                                <div class="legend-card-number" style="color: #4ade80;">{{ $fasesGlobal['proyectosFinalizados'] }}</div>
                                <div class="legend-card-info">
                                    <div class="legend-card-title" style="color: #4ade80;"><span class="legend-card-dot" style="background: #4ade80; box-shadow: 0 0 8px #4ade80;"></span>Finalizados</div>
                                    <div class="legend-card-subtitle">Entregados y en soporte continuo</div>
                                </div>
                            </div>
                        </div>

                        <div class="sprint-completion-box">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-white small fw-bold" style="font-family: 'JetBrains Mono', monospace; letter-spacing: 1px;">
                                    <i class="fas fa-tasks text-cyan me-1"></i> TASA DE AVANCE GLOBAL
                                </span>
                                <span class="fw-bold text-cyan font-mono" style="font-size: 1.1rem;">{{ $promedioProgresoGlobal }}%</span>
                            </div>
                            <div class="progress-track-tech">
                                <div class="progress-bar-neon" style="width: {{ $promedioProgresoGlobal }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mb-4">
                    <div class="col-12">
                        <h4 class="fw-bold text-white mb-4" style="font-size: 0.85rem; font-family: 'JetBrains Mono', monospace; letter-spacing: 1.5px; text-transform: uppercase; color: #94a3b8;">
                            Proyectos Activos Asignados
                        </h4>

                        <div class="d-flex gap-4 overflow-auto pt-3 pb-4 px-2" style="scrollbar-width: none; -ms-overflow-style: none; margin-top: -12px;">
                            @forelse($proyectos as $proy)
                                <div style="min-width: 330px; max-width: 360px; flex: 1;">
                                    <div class="card-glass-neon position-relative" style="border-left: 4px solid {{ $proy->priority === 'critico' ? '#ef4444' : ($proy->priority === 'alto' ? '#f97316' : ($proy->priority === 'medio' ? '#06b6d4' : '#8a2be2')) }} !important; padding: 24px 20px;">

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span style="font-family: 'JetBrains Mono', monospace; font-size: 0.68rem; color: #22d3ee; letter-spacing: 1px; text-transform: uppercase; font-weight: 700;">
                                                En {{ $proy->estado }}
                                            </span>
                                            <span class="fw-bold text-cyan font-mono" style="font-size: 0.8rem;">
                                                {{ $proy->progreso }}%
                                            </span>
                                        </div>

                                        <h4 class="fw-bold text-white mb-3" style="font-size: 1.15rem; letter-spacing: -0.3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 700;">
                                            {{ $proy->nombre }}
                                        </h4>

                                        <div class="d-flex flex-column gap-2" style="font-size: 0.75rem; border-top: 1px solid rgba(255,255,255,0.06); padding-top: 14px; color: #94a3b8;">
                                            <div class="d-flex justify-content-between">
                                                <span style="font-family: 'JetBrains Mono', monospace;">TIPO:</span>
                                                <span class="text-white fw-semibold">{{ $proy->servicio }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span style="font-family: 'JetBrains Mono', monospace;">ENTREGA:</span>
                                                <span class="text-white font-mono">{{ $proy->siguiente_entrega ?? 'Por definir' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span style="font-family: 'JetBrains Mono', monospace;">CLIENTE:</span>
                                                <span class="text-white fw-semibold">{{ $proy->user->name ?? 'Sin asignar' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-4 pt-2 text-end">
                                            <a href="{{ route('admin.proyectos.index') }}" class="action-hud-btn" style="font-size: 0.72rem; padding: 6px 14px;">
                                                Ver Kanban <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <div class="w-100 py-5 text-center" style="background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(255,255,255,0.06); border-radius: 20px; padding: 45px;">
                                    <div class="empty-icon" style="width: 65px; height: 65px; background: rgba(6, 182, 212, 0.04); border: 1px solid rgba(6, 182, 212, 0.15); color: #06b6d4; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin: 0 auto 20px auto;">
                                        <i class="fas fa-terminal"></i>
                                    </div>
                                    <span class="font-mono" style="color: #64748b; font-size: 0.85rem; letter-spacing: 0.5px;">
                                        [SYSTEM_INFO]: No cuentas con proyectos activos asignados actualmente.
                                    </span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection

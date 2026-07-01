@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

    /* --- TABLERO KANBAN (Columnas anchas y alta respuesta lumínica) --- */
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
        background: radial-gradient(circle at 50% 30%, rgba(6, 182, 212, 0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    /* Filtros estilo cápsula */
    .filter-btn {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #94a3b8;
        padding: 8px 20px;
        border-radius: 4px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }

    .filter-btn:hover, .filter-btn.active {
        color: #fff;
        background: rgba(6, 182, 212, 0.1);
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.25);
        transform: translateY(-1px);
    }

    .filter-btn.prio-critico-btn.active { border-color: #ef4444; background: rgba(239, 68, 68, 0.15); box-shadow: 0 0 20px rgba(239, 68, 68, 0.45); }
    .filter-btn.prio-alto-btn.active { border-color: #f97316; background: rgba(249, 115, 22, 0.15); box-shadow: 0 0 20px rgba(249, 115, 22, 0.45); }
    .filter-btn.prio-bajo-btn.active { border-color: #8a2be2; background: rgba(138, 43, 226, 0.15); box-shadow: 0 0 20px rgba(138, 43, 226, 0.45); }

    /* Estructura de Columnas Kanban Anchas */
    .kanban-wrapper {
        display: flex;
        justify-content: center;
        width: 100%;
        padding: 0 10px;
    }

    .kanban-flex-container {
        display: flex;
        flex-direction: row;
        gap: 28px;
        overflow-x: auto;
        padding-bottom: 20px;
        width: 100%;
    }

    .kanban-col-wrapper {
        flex: 1;
        min-width: 330px;
        max-width: 380px;
        width: 100%;
    }

    .kanban-column {
        background: rgba(10, 15, 30, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 12px;
        padding: 20px;
        min-height: 70vh;
        position: relative;
        transition: all 0.3s ease;
    }

    .kanban-column::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 3px;
        background: #06b6d4;
        box-shadow: 0 2px 10px rgba(6, 182, 212, 0.25);
    }

    .kanban-column.drag-over {
        background: rgba(6, 182, 212, 0.05);
        border-color: #06b6d4;
        box-shadow: inset 0 0 20px rgba(6, 182, 212, 0.1);
    }

    .column-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .column-title {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .column-count {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 2px 8px;
        border-radius: 4px;
        color: #cbd5e1;
    }

    /* Estilo de Tarjetas con Bordes Izquierdos de Color e Iconos */
    .kanban-card {
        background: rgba(255, 255, 255, 0.02) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.25s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        cursor: grab;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
    }

    .kanban-card:active { cursor: grabbing; opacity: 0.5; }

    .prio-border-critico { border-left: 4px solid #ef4444 !important; }
    .prio-border-critico:hover { border-color: rgba(255, 255, 255, 0.08) !important; border-left-color: #ef4444 !important; box-shadow: 0 0 25px rgba(239, 68, 68, 0.25) !important; transform: translateY(-3px) scale(1.01); }

    .prio-border-alto { border-left: 4px solid #f97316 !important; }
    .prio-border-alto:hover { border-color: rgba(255, 255, 255, 0.08) !important; border-left-color: #f97316 !important; box-shadow: 0 0 25px rgba(249, 115, 22, 0.25) !important; transform: translateY(-3px) scale(1.01); }

    .prio-border-medio { border-left: 4px solid #06b6d4 !important; }
    .prio-border-medio:hover { border-color: rgba(255, 255, 255, 0.08) !important; border-left-color: #06b6d4 !important; box-shadow: 0 0 25px rgba(6, 182, 212, 0.22) !important; transform: translateY(-3px) scale(1.01); }

    .prio-border-bajo { border-left: 4px solid #8a2be2 !important; }
    .prio-border-bajo:hover { border-color: rgba(255, 255, 255, 0.08) !important; border-left-color: #8a2be2 !important; box-shadow: 0 0 25px rgba(138, 43, 226, 0.18) !important; transform: translateY(-3px) scale(1.01); }

    .tag-prio { font-size: 0.65rem; text-transform: uppercase; font-weight: 700; font-family: monospace; }
    .text-critico { color: #f87171; }
    .text-alto { color: #fb923c; }
    .text-medio { color: #22d3ee; }
    .text-bajo { color: #c084fc; }

    .card-label { color: rgba(255, 255, 255, 0.4) !important; font-weight: 500; }
    .card-value { color: rgba(255, 255, 255, 0.75) !important; }

    /* --- MODAL NEXUS (Estilo terminal original) --- */
    .table-dashboard-modal {
        background: #030712 !important;
        border: 1px solid rgba(6, 182, 212, 0.2) !important;
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.9), 0 0 30px rgba(6, 182, 212, 0.03) !important;
        border-radius: 16px !important;
        overflow: hidden;
    }

    .header-nexus-terminal {
        background: rgba(255, 255, 255, 0.005) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
        padding: 24px 32px !important;
    }

    .footer-nexus-terminal {
        background: rgba(255, 255, 255, 0.005) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.03) !important;
        padding: 16px 32px !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .meta-label-modal {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 0.75rem !important;
        color: #94a3b8 !important;
        letter-spacing: 1px !important;
        text-transform: uppercase !important;
        opacity: 1 !important;
    }

    .timeline-mini-item {
        position: relative !important;
        padding-bottom: 20px !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        opacity: 1 !important;
        transition: all 0.3s ease !important;
    }

    .timeline-mini-item span {
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 0.85rem !important;
        color: #94a3b8 !important;
        font-weight: 500 !important;
    }

    .timeline-mini-item .timeline-mini-dot {
        width: 8px !important;
        height: 8px !important;
        background: #475569 !important;
        border-radius: 50% !important;
        border: 2px solid #030712 !important;
        z-index: 2 !important;
    }

    .timeline-mini-item.timeline-active span {
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    .timeline-mini-item.timeline-active .timeline-mini-dot {
        background: #00d4ff !important;
        box-shadow: 0 0 10px #00d4ff !important;
    }

    #modalDeleteForm button {
        background: transparent !important;
        border: 2px solid #ff4d4d !important;
        color: #ff4d4d !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        padding: 10px !important;
        border-radius: 6px !important;
        cursor: pointer !important;
        letter-spacing: 0.5px !important;
        transition: all 0.2s ease !important;
        box-shadow: none !important;
    }

    #modalDeleteForm button:hover {
        background: #ff4d4d !important;
        color: #ffffff !important;
        box-shadow: 0 0 15px rgba(255, 77, 77, 0.4) !important;
    }

    .meta-value-modal { font-size: 0.95rem; font-weight: 600; }

    .btn-close-hardware {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.6);
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-close-hardware:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.12);
        color: #ffffff;
    }

    svg circle { fill: none; stroke-width: 8; transform: rotate(-90deg); transform-origin: 50% 50%; }
    .svg-track { stroke: rgba(255, 255, 255, 0.02); }
    .svg-progress {
        stroke: #06b6d4;
        stroke-linecap: round;
        stroke-dasharray: 427.25;
        stroke-dashoffset: 427.25;
        transition: stroke-dashoffset 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cpu-percentage-text { position: absolute; top: 54%; left: 50%; transform: translate(-50%, -50%); }
    .cpu-percentage-text span:first-child { font-size: 2.2rem; letter-spacing: -1px; }
    .monospace-sub { font-family: monospace; font-size: 0.55rem; letter-spacing: 1px; color: rgba(255, 255, 255, 0.3); margin-top: -2px; }

    .metrics-hardware-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .metric-hardware-card { background: rgba(255, 255, 255, 0.005); border: 1px solid rgba(255, 255, 255, 0.02); padding: 14px; border-radius: 10px; text-align: left; }

    .status-hardware-badge {
        background: rgba(6, 182, 212, 0.03);
        border: 1px solid rgba(6, 182, 212, 0.15);
        color: #22d3ee;
        padding: 12px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .timeline-mini-jira { padding-left: 15px; position: relative; }
    .timeline-mini-jira::before { content: ''; position: absolute; left: 3px; top: 5px; bottom: 5px; width: 2px; background: rgba(255, 255, 255, 0.08); }

    .timeline-mini-item {
        position: relative;
        padding-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        opacity: 0.5;
        color: #cbd5e1 !important;
        transition: opacity 0.4s ease;
    }
    .timeline-mini-item:last-child { padding-bottom: 0; }
    .timeline-mini-dot { width: 8px; height: 8px; background: #334155; border-radius: 50%; border: 2px solid #030712; z-index: 2; transition: all 0.4s ease; }

    .timeline-active { opacity: 1 !important; color: #ffffff !important; }
    .timeline-active .timeline-mini-dot { background: #06b6d4 !important; box-shadow: 0 0 10px #06b6d4; }

    .border-nexus-right { border-right: 1px solid rgba(255, 255, 255, 0.02); }
    .border-nexus-left { border-left: 1px solid rgba(255, 255, 255, 0.02); }
    .border-nexus-top { border-top: 1px solid rgba(255, 255, 255, 0.02); }
    .padding-nexus-center { padding-left: 32px !important; padding-right: 32px !important; }
</style>

<div class="admin-viewport">
    <div class="container-fluid">

        <div class="row mb-4 text-center">
            <div class="col-md-8 mx-auto" style="position: relative; z-index: 5;">
                <h2 class="fw-bold text-white mb-2" style="font-size: 2.2rem; letter-spacing: -0.5px; font-weight: 800;">
                    Sprint Board & Gestión Operativa
                </h2>
                <p class="mx-auto mb-4 small" style="max-width: 600px; color: #94a3b8 !important;">
                    Control centralizado de flujos de trabajo en el Módulo Nexus. Supervisa infraestructura y despliegues ágiles en tiempo real.
                </p>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-3" style="border-top: 1px solid rgba(255, 255, 255, 0.04); border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="text-white font-mono fw-bold" style="font-size: 0.7rem; letter-spacing: 1px; color: rgba(6, 182, 212, 0.8) !important;">
                            FILTRAR_POR:
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="filter-btn active" onclick="filterPriority('todos', this)">Todos</button>
                            <button class="filter-btn prio-critico-btn" onclick="filterPriority('critico', this)">Crítico</button>
                            <button class="filter-btn prio-alto-btn" onclick="filterPriority('alto', this)">Alto</button>
                            <button class="filter-btn" onclick="filterPriority('medio', this)">Medio</button>
                            <button class="filter-btn prio-bajo-btn" onclick="filterPriority('bajo', this)">Bajo</button>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('admin.proyectos.crear') }}" class="filter-btn active" style="background: rgba(6, 182, 212, 0.15); border-color: var(--neon-cyan); color: #fff; box-shadow: 0 0 12px var(--neon-glow-cyan);">
                            + Nuevo Proyecto
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="kanban-wrapper">
            <div class="kanban-flex-container">
                @php
                    $estados = [
                        'Prospecto' => '#a855f7',
                        'En Desarrollo' => '#facc15',
                        'En Pruebas' => '#06b6d4',
                        'Finalizado' => '#4ade80'
                    ];
                @endphp

                @foreach($estados as $estado => $colorColumna)
                    @php
                        $cleanStatus = str_replace(' ', '', $estado);
                        $proyectosFiltrados = $proyectos->where('estado', $estado);
                    @endphp

                    <div class="kanban-col-wrapper">
                        <div class="kanban-column d-flex flex-column" data-status="{{ $estado }}" id="col-{{ $cleanStatus }}" ondragover="allowDrop(event)" ondragenter="dragEnter(event)" ondragleave="dragLeave(event)" ondrop="drop(event)">
                            <div class="column-header">
                                <span class="column-title" style="color: {{ $colorColumna }};"> {{ $estado }}</span>
                                <span class="column-count" id="count-{{ $cleanStatus }}">{{ $proyectosFiltrados->count() }}</span>
                            </div>

                            <div class="cards-container" style="min-height: 200px;">
                                @foreach($proyectosFiltrados as $proyecto)
                                    <div class="kanban-card prio-border-{{ $proyecto->priority }} proyecto-card" id="project-card-{{ $proyecto->id }}" draggable="true" ondragstart="drag(event)" data-priority="{{ $proyecto->priority }}" data-id="{{ $proyecto->id }}" style="cursor: pointer;">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="tag-prio text-{{ $proyecto->priority }} font-mono">
                                                 {{ $proyecto->priority }}
                                            </span>
                                            <span style="font-family: monospace; font-size: 0.65rem; color: #06b6d4; letter-spacing: 0.5px;">
                                                 [{{ $proyecto->servicio }}]
                                            </span>
                                        </div>

                                        <h5 class="fw-bold text-white mb-2" style="font-size: 0.95rem;">{{ $proyecto->nombre }}</h5>

                                        <div class="mb-3" style="font-size: 0.75rem;">
                                            <div class="mb-1">
                                                <i class="fas fa-user-tie me-1" style="font-size: 0.7rem; color: rgba(255,255,255,0.35);"></i>
                                                <span class="card-label">Cliente:</span>
                                                <span class="font-mono card-value">{{ $proyecto->user->name ?? 'Sin Cliente' }}</span>
                                            </div>
                                            <div>
                                                <i class="fas fa-code-branch me-1" style="font-size: 0.7rem; color: rgba(255,255,255,0.35);"></i>
                                                <span class="card-label">Encargado:</span>
                                                <span class="font-mono card-value">{{ $proyecto->developer->name ?? 'Sin asignar' }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid rgba(255,255,255,0.05);">
                                            <div class="font-mono" style="font-size: 0.65rem; color: rgba(255, 255, 255, 0.45);">
                                                <i class="far fa-calendar-alt me-1"></i> {{ $proyecto->siguiente_entrega ?? 'Sin fecha' }}
                                            </div>
                                            <div class="font-mono text-info fw-bold" style="font-size: 0.7rem;">
                                                {{ $proyecto->progreso }}%
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="projectDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background: #0b0f19; border: 1px solid rgba(0, 212, 255, 0.15); box-shadow: 0 0 30px rgba(0, 0, 0, 0.6); border-radius: 8px;">

            <div class="modal-header d-flex justify-content-between align-items-center" style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 20px 24px;">
                <div>
                    <span style="font-size: 0.75rem; color: #00d4ff; letter-spacing: 1.5px; font-weight: 600;">Sprint Board y Gestión Operativa</span>
                    <h5 class="modal-title text-white fw-bold mt-1" id="modalProjectName" style="font-size: 1.5rem;">SYSTEM_OFFLINE</h5>
                </div>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" style="background: transparent; border: none; color: rgba(255, 255, 255, 0.5); font-size: 1.2rem; transition: color 0.2s;" onmouseover="this.style.color='#ff3b30'" onmouseout="this.style.color='rgba(255, 255, 255, 0.5)'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body p-4" style="background: #030712 !important;">
                <div class="row g-4">

                    <div class="col-12 col-lg-4 text-center" style="border-right: 1px solid rgba(255, 255, 255, 0.05); padding-right: 25px;">
                        <div class="mb-4 text-start">
                            <span style="font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 0.75rem !important; color: #94a3b8 !important; letter-spacing: 0.5px !important; font-weight: 600;">Monitor de rendimiento</span>
                        </div>

                        <div class="position-relative d-inline-block my-3">
                            <svg width="170" height="170" viewBox="0 0 160 160">
                                <circle cx="80" cy="80" r="68" style="fill: transparent; stroke: rgba(255, 255, 255, 0.02); stroke-width: 5;" />
                                <circle cx="80" cy="80" r="68" id="modalProgressCircle" style="fill: transparent; stroke: #00d4ff; stroke-width: 5; stroke-dasharray: 427; stroke-dashoffset: 427; transform: rotate(-90deg); transform-origin: 80px 80px; stroke-linecap: round; transition: stroke-dashoffset 0.8s cubic-bezier(0.4, 0, 0.2, 1);" />
                            </svg>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                <span id="modalProgressText" class="text-white" style="font-size: 2.5rem; font-weight: 300; display: block; font-family: 'Plus Jakarta Sans', sans-serif !important; letter-spacing: -1px;">0%</span>
                                <span style="font-size: 0.65rem; color: #94a3b8; letter-spacing: 0.5px; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 500;">Carga central</span>
                            </div>
                        </div>

                        <div class="mt-4" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.04); padding: 12px; border-radius: 8px; text-align: left;">
                                <span style="font-size: 0.65rem; color: #94a3b8; display: block; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 500;">Nivel crítico</span>
                                <span class="fw-bold text-danger mt-1 d-block" id="modalPriority" style="font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif !important;">Crítico</span>
                            </div>
                            <div style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.04); padding: 12px; border-radius: 8px; text-align: left;">
                                <span style="font-size: 0.65rem; color: #94a3b8; display: block; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 500;">Entorno de servicio</span>
                                <span class="text-info fw-bold mt-1 d-block" id="modalService" style="font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif !important;">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 px-lg-4" style="border-right: 1px solid rgba(255, 255, 255, 0.05);">
                        <div class="mb-4">
                            <span style="font-size: 0.75rem !important; color: #94a3b8 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; letter-spacing: 0.5px !important; display: block !important;">Sinopsis del microservicio</span>
                            <p class="mt-2" id="modalDescription" style="line-height: 1.6 !important; background: rgba(255, 255, 255, 0.01) !important; padding: 15px !important; border-radius: 8px !important; border: 1px solid rgba(255, 255, 255, 0.03) !important; border-left: 3px solid #00d4ff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 0.85rem !important; color: #cbd5e1 !important;">
                                Analizando registros de base de datos...
                            </p>
                        </div>

                        <div class="row g-3 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05) !important;">
                            <div class="col-6">
                                <span style="font-size: 0.75rem !important; color: #94a3b8 !important; font-weight: 500 !important; display: block !important; margin-bottom: 4px !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">
                                    <i class="fas fa-user-tie me-2" style="color: #00d4ff; font-size: 0.75rem;"></i>Cliente titular
                                </span>
                                <span class="text-white d-block fw-semibold" id="modalClient" style="font-size: 0.95rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">Buscando...</span>
                            </div>

                            <div class="col-6">
                                <span style="font-size: 0.75rem !important; color: #94a3b8 !important; font-weight: 500 !important; display: block !important; margin-bottom: 4px !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">
                                    <i class="fas fa-shield-alt me-2" style="color: #00d4ff; font-size: 0.75rem;"></i>Líder asignado
                                </span>
                                <span class="text-white d-block fw-semibold" id="modalLeader" style="font-size: 0.95rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">Buscando...</span>
                            </div>

                            <div class="col-12 mt-4">
                                <span class="d-block mb-2" style="font-size: 0.75rem !important; color: #94a3b8 !important; font-weight: 500 !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">
                                    <i class="fas fa-users me-2" style="color: #00d4ff; font-size: 0.75rem;"></i>Célula operativa de desarrollo
                                </span>
                                <div class="d-flex flex-wrap gap-2" id="modalOperators" style="color: #94a3b8 !important; font-size: 0.85rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3" style="padding-left: 25px !important;">
                        <span style="font-size: 0.75rem !important; color: #94a3b8 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; letter-spacing: 0.5px !important; display: block !important;">Estado de control</span>

                        <div class="my-3 text-center fw-semibold" id="modalStatusLabel" style="padding: 10px 12px !important; background: rgba(0, 212, 255, 0.03) !important; border: 1px solid rgba(0, 212, 255, 0.3) !important; color: #00d4ff !important; border-radius: 6px !important; font-size: 0.85rem !important; letter-spacing: 0.5px !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">
                            En desarrollo
                        </div>

                        <div class="my-4">
                            <form id="modalDeleteForm" action="/proyectos" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este proyecto? Se borrará también en ClickUp.');" style="margin: 0 !important; padding: 0 !important; background: transparent !important;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn w-100 fw-semibold" style="background: transparent !important; border: 1px solid rgba(239, 68, 68, 0.4) !important; color: #f87171 !important; font-size: 0.85rem !important; padding: 10px !important; border-radius: 6px !important; cursor: pointer !important; transition: all 0.2s ease !important; font-family: 'Plus Jakarta Sans', sans-serif !important; box-shadow: none !important;" onmouseover="this.style.background='rgba(239, 68, 68, 0.08)'; this.style.borderColor='#ef4444'; this.style.color='#ffffff';" onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(239, 68, 68, 0.4)'; this.style.color='#f87171';">
                                    Eliminar Proyecto
                                </button>
                            </form>
                        </div>

                        <div class="mt-4">
                            <span style="font-size: 0.75rem !important; color: #94a3b8 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; letter-spacing: 0.5px !important; display: block !important; margin-bottom: 15px !important;">Trazabilidad de hitos de fase</span>

                            <div class="timeline-mini-jira" style="display: flex !important; flex-direction: column !important; gap: 14px !important; padding-left: 15px !important;">

                                <div class="timeline-mini-item d-flex align-items-center" id="step-prospecto" style="opacity: 1 !important; padding-bottom: 0 !important; margin-bottom: 2px !important;">
                                    <div class="timeline-mini-dot" style="background: #00d4ff !important; border: 2px solid #030712 !important; width: 8px; height: 8px;"></div>
                                    <span class="text-white small fw-semibold ms-2" style="font-size: 0.85rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">01 / Inicialización</span>
                                </div>

                                <div class="timeline-mini-item d-flex align-items-center" id="step-desarrollo" style="opacity: 1 !important; padding-bottom: 0 !important; margin-bottom: 2px !important;">
                                    <div class="timeline-mini-dot" style="background: #00d4ff !important; border: 2px solid #030712 !important; width: 8px; height: 8px;"></div>
                                    <span class="text-white small fw-semibold ms-2" style="font-size: 0.85rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">02 / En Desarrollo</span>
                                </div>

                                <div class="timeline-mini-item d-flex align-items-center" id="step-pruebas" style="opacity: 1 !important; padding-bottom: 0 !important; margin-bottom: 2px !important;">
                                    <div class="timeline-mini-dot" style="background: #475569 !important; border: 2px solid #030712 !important; width: 8px; height: 8px;"></div>
                                    <span class="small fw-normal ms-2" style="font-size: 0.85rem !important; color: #64748b !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">03 / En Pruebas</span>
                                </div>

                                <div class="timeline-mini-item d-flex align-items-center" id="step-finalizado" style="opacity: 1 !important; padding-bottom: 0 !important;">
                                    <div class="timeline-mini-dot" style="background: #475569 !important; border: 2px solid #030712 !important; width: 8px; height: 8px;"></div>
                                    <span class="small fw-normal ms-2" style="font-size: 0.85rem !important; color: #64748b !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">04 / Despliegue</span>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between align-items-center" style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding: 15px 24px; background: rgba(5, 8, 14, 0.4);">
                <span style="font-size: 0.65rem; color: #6c757d; letter-spacing: 0.5px;">Nexus Terminal v3.2.0 Sistemas Operando OK</span>
                <button type="button" class="btn" data-bs-dismiss="modal" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: #8892b0; font-size: 0.75rem; padding: 6px 16px; border-radius: 4px; transition: all 0.2s;" onmouseover="this.style.background='rgba(255, 255, 255, 0.08)'; this.style.color='#ffffff';" onmouseout="this.style.background='rgba(255, 255, 255, 0.03)'; this.style.color='#8892b0';">Volver al tablero</button>
            </div>

        </div>
    </div>
</div>

<script>
    function filterPriority(priority, button) {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        document.querySelectorAll('.kanban-card').forEach(card => {
            if (priority === 'todos') {
                card.style.display = 'block';
            } else {
                if (card.getAttribute('data-priority') === priority) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }

    function drag(ev) {
        ev.dataTransfer.setData("text/plain", ev.target.id);
    }

    function allowDrop(ev) { ev.preventDefault(); }

    function dragEnter(ev) {
        ev.preventDefault();
        const column = ev.target.closest('.kanban-column');
        if (column) column.classList.add('drag-over');
    }

    function dragLeave(ev) {
        const column = ev.target.closest('.kanban-column');
        if (column) column.classList.remove('drag-over');
    }

    function drop(ev) {
        ev.preventDefault();
        const column = ev.target.closest('.kanban-column');
        if (!column) return;
        column.classList.remove('drag-over');

        const cardId = ev.dataTransfer.getData("text/plain");
        const cardElement = document.getElementById(cardId);
        if (!cardElement) return;

        const container = column.querySelector('.cards-container');
        if (container) {
            container.appendChild(cardElement);
            sortCardsByPriority(container);
        }

        updateColumnCounts();

        const newStatus = column.getAttribute('data-status');
        const projectId = cardId.replace('project-card-', '');

        fetch("{{ route('admin.proyectos.updateStatus') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: projectId, estado: newStatus })
        })
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
    }

    function sortCardsByPriority(container) {
        const priorityOrder = { 'critico': 1, 'alto': 2, 'medio': 3, 'bajo': 4 };
        const cards = Array.from(container.querySelectorAll('.kanban-card'));
        cards.sort((a, b) => (priorityOrder[a.getAttribute('data-priority')] || 3) - (priorityOrder[b.getAttribute('data-priority')] || 3));
        cards.forEach(card => container.appendChild(card));
    }

    function updateColumnCounts() {
        document.querySelectorAll('.kanban-column').forEach(col => {
            const status = col.getAttribute('data-status');
            const cleanStatus = status.replace(/\s+/g, '');
            const count = col.querySelectorAll('.kanban-card').length;
            const countBadge = document.getElementById(`count-${cleanStatus}`);
            if (countBadge) countBadge.innerText = count;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.proyecto-card');

        cards.forEach(card => {
            card.addEventListener('mousedown', function(e) {
                this.dataset.downX = e.clientX;
                this.dataset.downY = e.clientY;
            });

            card.addEventListener('click', function (e) {
                const moveX = Math.abs(e.clientX - (this.dataset.downX || 0));
                const moveY = Math.abs(e.clientY - (this.dataset.downY || 0));
                if (moveX > 5 || moveY > 5) return;

                const projectId = e.currentTarget.getAttribute('data-id');
                if (!projectId) return;

                fetch(`{{ url('/console/api/proyectos') }}/${projectId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('modalProjectName').innerText = data.nombre;
                        document.getElementById('modalDescription').innerText = data.descripcion || 'Sin descripción técnica asignada.';
                        document.getElementById('modalClient').innerText = data.user ? data.user.name : 'No asignado';
                        document.getElementById('modalLeader').innerText = data.developer ? data.developer.name : 'Sin asignar';
                        document.getElementById('modalService').innerText = data.servicio;
                        document.getElementById('modalPriority').innerText = `// ${data.priority.toUpperCase()}`;
                        document.getElementById('modalStatusLabel').innerText = data.estado.toUpperCase();

                        document.getElementById('modalDeleteForm').action = `{{ url('/console/proyectos') }}/${data.id}`;

                        const priorityEl = document.getElementById('modalPriority');
                        priorityEl.className = 'meta-value-modal text-uppercase ' + (data.priority === 'critico' ? 'text-danger' : 'text-info');

                        const operatorsContainer = document.getElementById('modalOperators');
                        operatorsContainer.innerHTML = '';
                        if (data.team && data.team.length > 0) {
                            data.team.forEach(emp => {
                                operatorsContainer.innerHTML += `<span class="badge font-mono" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); padding: 5px 10px;">${emp.name}</span>`;
                            });
                        } else {
                            operatorsContainer.innerHTML = '<span style="color: #94a3b8 !important; font-size: 0.85rem !important; font-weight: 500 !important;">Célula operativa compuesta únicamente por el Líder Desarrollador.</span>';
                        }

                        const progress = parseInt(data.progreso) || 0;
                        document.getElementById('modalProgressText').innerText = `${progress}%`;

                        const circle = document.getElementById('modalProgressCircle');
                        const radius = circle.r.baseVal.value;
                        const circumference = 2 * Math.PI * radius;
                        const offset = circumference - (progress / 100) * circumference;
                        circle.style.strokeDashoffset = offset;

                        const estado = data.estado.toLowerCase();

                        document.querySelectorAll('.timeline-mini-item').forEach(item => {
                            item.classList.remove('timeline-active');
                            item.style.setProperty('opacity', '1', 'important');
                            const textSpan = item.querySelector('span');
                            if (textSpan) {
                                textSpan.style.setProperty('color', '#94a3b8', 'important');
                            }
                            const dotDiv = item.querySelector('.timeline-mini-dot');
                            if (dotDiv) {
                                dotDiv.style.setProperty('background', '#334155', 'important');
                                dotDiv.style.setProperty('box-shadow', 'none', 'important');
                            }
                        });

                        const stepProspecto = document.getElementById('step-prospecto');
                        if (stepProspecto) {
                            stepProspecto.classList.add('timeline-active');
                            stepProspecto.querySelector('span').style.setProperty('color', '#ffffff', 'important');
                            stepProspecto.querySelector('.timeline-mini-dot').style.setProperty('background', '#06b6d4', 'important');
                        }

                        if (['en desarrollo', 'en pruebas', 'finalizado', 'completado'].includes(estado)) {
                            const stepDesarrollo = document.getElementById('step-desarrollo');
                            if (stepDesarrollo) {
                                stepDesarrollo.classList.add('timeline-active');
                                stepDesarrollo.querySelector('span').style.setProperty('color', '#ffffff', 'important');
                                stepDesarrollo.querySelector('.timeline-mini-dot').style.setProperty('background', '#06b6d4', 'important');
                            }
                        }

                        if (['en pruebas', 'finalizado', 'completado'].includes(estado)) {
                            const stepPruebas = document.getElementById('step-pruebas');
                            if (stepPruebas) {
                                stepPruebas.classList.add('timeline-active');
                                stepPruebas.querySelector('span').style.setProperty('color', '#ffffff', 'important');
                                stepPruebas.querySelector('.timeline-mini-dot').style.setProperty('background', '#06b6d4', 'important');
                            }
                        }

                        if (['finalizado', 'completado'].includes(estado)) {
                            const stepFinalizado = document.getElementById('step-finalizado');
                            if (stepFinalizado) {
                                stepFinalizado.classList.add('timeline-active');
                                stepFinalizado.querySelector('span').style.setProperty('color', '#ffffff', 'important');
                                stepFinalizado.querySelector('.timeline-mini-dot').style.setProperty('background', '#06b6d4', 'important');
                            }
                        }

                        const modalTarget = document.getElementById('projectDetailsModal');
                        modalTarget.classList.add('show');
                        modalTarget.style.display = 'block';
                        document.body.classList.add('modal-open');

                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        backdrop.id = 'modal-backdrop-nexus';
                        document.body.appendChild(backdrop);
                    })
                    .catch(error => console.error('Error fetching data:', error));
            });
        });

        document.querySelectorAll('[data-bs-dismiss="modal"], .btn-close-nexus, .btn-close-hardware').forEach(btn => {
            btn.addEventListener('click', function() {
                const modalTarget = document.getElementById('projectDetailsModal');
                if (modalTarget) {
                    modalTarget.classList.remove('show');
                    modalTarget.style.display = 'none';
                }
                document.body.classList.remove('modal-open');
                const backdrop = document.getElementById('modal-backdrop-nexus');
                if (backdrop) backdrop.remove();
            });
        });
    });
</script>
@endsection

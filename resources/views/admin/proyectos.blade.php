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
        padding: 120px 20px 60px 20px;
    }

    .admin-viewport::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
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

    .filter-btn:hover,
    .filter-btn.active {
        color: #fff;
        background: rgba(6, 182, 212, 0.1);
        border-color: #06b6d4;
        box-shadow: 0 0 15px rgba(6, 182, 212, 0.25);
        transform: translateY(-1px);
    }

    .filter-btn.prio-critico-btn.active {
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.15);
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.45);
    }

    .filter-btn.prio-alto-btn.active {
        border-color: #f97316;
        background: rgba(249, 115, 22, 0.15);
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.45);
    }

    .filter-btn.prio-bajo-btn.active {
        border-color: #8a2be2;
        background: rgba(138, 43, 226, 0.15);
        box-shadow: 0 0 20px rgba(138, 43, 226, 0.45);
    }

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
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
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

    .kanban-card:active {
        cursor: grabbing;
        opacity: 0.5;
    }

    .kanban-card.is-dragging {
        cursor: grabbing !important;
        opacity: 0.35 !important;
        border: 2px dashed #00d4ff !important;
        transform: scale(0.98);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.3) !important;
    }

    .prio-border-critico {
        border-left: 4px solid #ef4444 !important;
    }

    .prio-border-critico:hover {
        border-color: rgba(255, 255, 255, 0.08) !important;
        border-left-color: #ef4444 !important;
        box-shadow: 0 0 25px rgba(239, 68, 68, 0.25) !important;
        transform: translateY(-3px) scale(1.01);
    }

    .prio-border-alto {
        border-left: 4px solid #f97316 !important;
    }

    .prio-border-alto:hover {
        border-color: rgba(255, 255, 255, 0.08) !important;
        border-left-color: #f97316 !important;
        box-shadow: 0 0 25px rgba(249, 115, 22, 0.25) !important;
        transform: translateY(-3px) scale(1.01);
    }

    .prio-border-medio {
        border-left: 4px solid #06b6d4 !important;
    }

    .prio-border-medio:hover {
        border-color: rgba(255, 255, 255, 0.08) !important;
        border-left-color: #06b6d4 !important;
        box-shadow: 0 0 25px rgba(6, 182, 212, 0.22) !important;
        transform: translateY(-3px) scale(1.01);
    }

    .prio-border-bajo {
        border-left: 4px solid #8a2be2 !important;
    }

    .prio-border-bajo:hover {
        border-color: rgba(255, 255, 255, 0.08) !important;
        border-left-color: #8a2be2 !important;
        box-shadow: 0 0 25px rgba(138, 43, 226, 0.18) !important;
        transform: translateY(-3px) scale(1.01);
    }

    .tag-prio {
        font-size: 0.65rem;
        text-transform: uppercase;
        font-weight: 700;
        font-family: monospace;
    }

    .text-critico {
        color: #f87171;
    }

    .text-alto {
        color: #fb923c;
    }

    .text-medio {
        color: #22d3ee;
    }

    .text-bajo {
        color: #c084fc;
    }

    .card-label {
        color: rgba(255, 255, 255, 0.4) !important;
        font-weight: 500;
    }

    .card-value {
        color: rgba(255, 255, 255, 0.75) !important;
    }

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

    .meta-value-modal {
        font-size: 0.95rem;
        font-weight: 600;
    }

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

    svg circle {
        fill: none;
        stroke-width: 8;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }

    .svg-track {
        stroke: rgba(255, 255, 255, 0.02);
    }

    .svg-progress {
        stroke: #06b6d4;
        stroke-linecap: round;
        stroke-dasharray: 427.25;
        stroke-dashoffset: 427.25;
        transition: stroke-dashoffset 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cpu-percentage-text {
        position: absolute;
        top: 54%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .cpu-percentage-text span:first-child {
        font-size: 2.2rem;
        letter-spacing: -1px;
    }

    .monospace-sub {
        font-family: monospace;
        font-size: 0.55rem;
        letter-spacing: 1px;
        color: rgba(255, 255, 255, 0.3);
        margin-top: -2px;
    }

    .metrics-hardware-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .metric-hardware-card {
        background: rgba(255, 255, 255, 0.005);
        border: 1px solid rgba(255, 255, 255, 0.02);
        padding: 14px;
        border-radius: 10px;
        text-align: left;
    }

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

    .timeline-mini-jira {
        padding-left: 15px;
        position: relative;
    }

    .timeline-mini-jira::before {
        content: '';
        position: absolute;
        left: 3px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: rgba(255, 255, 255, 0.08);
    }

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

    .timeline-mini-item:last-child {
        padding-bottom: 0;
    }

    .timeline-mini-dot {
        width: 8px;
        height: 8px;
        background: #334155;
        border-radius: 50%;
        border: 2px solid #030712;
        z-index: 2;
        transition: all 0.4s ease;
    }

    .timeline-active {
        opacity: 1 !important;
        color: #ffffff !important;
    }

    .timeline-active .timeline-mini-dot {
        background: #06b6d4 !important;
        box-shadow: 0 0 10px #06b6d4;
    }

    .border-nexus-right {
        border-right: 1px solid rgba(255, 255, 255, 0.02);
    }

    .border-nexus-left {
        border-left: 1px solid rgba(255, 255, 255, 0.02);
    }

    .border-nexus-top {
        border-top: 1px solid rgba(255, 255, 255, 0.02);
    }

    .padding-nexus-center {
        padding-left: 32px !important;
        padding-right: 32px !important;
    }

    /* Slider de Hitos en Modal de Detalles (Controles Inferiores de Extremo a Centro) */
    .projects-viewport-wrapper {
        width: 100%;
        overflow: hidden;
        border-radius: 12px;
    }

    .projects-track-neon {
        display: flex;
        width: 100%;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .project-mini-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 16px 20px;
        flex: 0 0 100%;
        width: 100%;
        min-width: 100%;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    .project-mini-card:hover {
        border-color: rgba(6, 182, 212, 0.35);
        background: rgba(6, 182, 212, 0.025);
    }

    .btn-slider-bottom {
        background: rgba(0, 212, 255, 0.04);
        border: 1px solid rgba(0, 212, 255, 0.2);
        color: #00d4ff;
        font-size: 0.74rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 8px 14px;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
    }

    .btn-slider-bottom:hover {
        background: rgba(0, 212, 255, 0.15);
        border-color: #00d4ff;
        color: #ffffff;
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.25);
    }

    /* Controles Inferiores de Navegación del Slider */
    .slider-controls-bottom {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        width: 100%;
        transition: all 0.25s ease;
    }

    /* Pestañas HUD para Hitos / Bóveda en Modal */
    .btn-hud-tab {
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        color: #94a3b8;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.76rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        padding: 6px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 6px 6px 0 0;
    }

    .btn-hud-tab:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.02);
    }

    .btn-hud-tab.active {
        color: #00d4ff;
        border-bottom-color: #00d4ff;
        background: rgba(0, 212, 255, 0.05);
    }

    .btn-hud-tab .badge-count {
        background: rgba(255, 255, 255, 0.08);
        color: #cbd5e1;
        font-size: 0.65rem;
        padding: 1px 6px;
        border-radius: 8px;
        font-family: monospace;
    }

    .btn-hud-tab.active .badge-count {
        background: rgba(0, 212, 255, 0.2);
        color: #00d4ff;
    }

    .modal-assets-scroll {
        max-height: 165px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .modal-assets-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .modal-assets-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.02);
    }

    .modal-assets-scroll::-webkit-scrollbar-thumb {
        background: rgba(0, 212, 255, 0.3);
        border-radius: 4px;
    }
</style>

<div class="admin-viewport">
    <div class="container-fluid">

        <div class="row mb-3 text-center">
            <div class="col-md-8 mx-auto" style="position: relative; z-index: 5;">
                <h2 class="fw-bold text-white mb-2"
                    style="font-size: 2.2rem; letter-spacing: -0.5px; font-weight: 800;">
                    Sprint Board & Gestión Operativa
                </h2>
                <p class="mx-auto mb-3 small" style="max-width: 600px; color: #94a3b8 !important;">
                    Control centralizado de flujos intermediados por el método Kanban. Supervisa infraestructura y
                    despliegues ágiles en tiempo real con conexión a ClickUp.
                </p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12" style="position: relative; z-index: 5;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 py-3 px-2"
                    style="border-top: 1px solid rgba(255, 255, 255, 0.04); border-bottom: 1px solid rgba(255, 255, 255, 0.04);">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="text-white font-mono fw-bold"
                            style="font-size: 0.72rem; letter-spacing: 1.5px; color: #00d4ff !important;">
                            FILTRAR_POR:
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="filter-btn active" onclick="filterPriority('todos', this)">Todos</button>
                            <button class="filter-btn prio-critico-btn"
                                onclick="filterPriority('critico', this)">Crítico</button>
                            <button class="filter-btn prio-alto-btn"
                                onclick="filterPriority('alto', this)">Alto</button>
                            <button class="filter-btn" onclick="filterPriority('medio', this)">Medio</button>
                            <button class="filter-btn prio-bajo-btn"
                                onclick="filterPriority('bajo', this)">Bajo</button>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" onclick="sincronizarClickUp()" id="btnSyncClickUp" class="filter-btn"
                            style="background: rgba(255, 255, 255, 0.04); border-color: rgba(255, 255, 255, 0.15); color: #cbd5e1;">
                            <i class="fas fa-sync-alt me-1" id="iconSyncClickUp"></i> Sincronizar ClickUp
                        </button>
                        <a href="{{ route('admin.proyectos.crear') }}" class="filter-btn active"
                            style="background: rgba(6, 182, 212, 0.15); border-color: var(--neon-cyan); color: #fff; box-shadow: 0 0 12px var(--neon-glow-cyan);">
                            + Nuevo Proyecto
                        </a>
                    </div>
                    @endif
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
                    <div class="kanban-column d-flex flex-column" data-status="{{ $estado }}"
                        id="col-{{ $cleanStatus }}" ondragover="allowDrop(event)" ondragenter="dragEnter(event)"
                        ondragleave="dragLeave(event)" ondrop="drop(event)">
                        <div class="column-header">
                            <span class="column-title" style="color: {{ $colorColumna }};"> {{ $estado }}</span>
                            <span class="column-count" id="count-{{ $cleanStatus }}">{{ $proyectosFiltrados->count()
                                }}</span>
                        </div>

                        <div class="cards-container" style="min-height: 200px;">
                            @foreach($proyectosFiltrados as $proyecto)
                            @php
                            // Validamos si el usuario actual tiene permisos de edición corporativa
                            $puedeGestionar = in_array(auth()->user()->role, ['superadmin', 'admin']);
                            @endphp

                            <div class="kanban-card prio-border-{{ $proyecto->priority }} proyecto-card"
                                id="project-card-{{ $proyecto->id }}"
                                draggable="{{ $puedeGestionar ? 'true' : 'false' }}" @if($puedeGestionar)
                                ondragstart="drag(event)" ondragend="dragEnd(event)" @endif data-priority="{{ strtolower($proyecto->priority ?? 'medio') }}"
                                data-delivery="{{ $proyecto->siguiente_entrega ?? '' }}"
                                data-id="{{ $proyecto->id }}"
                                style="cursor: {{ $puedeGestionar ? 'grab' : 'pointer' }};">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="tag-prio text-{{ $proyecto->priority }} font-mono">
                                        {{ $proyecto->priority }}
                                    </span>
                                    <span
                                        style="font-family: monospace; font-size: 0.65rem; color: #06b6d4; letter-spacing: 0.5px;">
                                        [{{ $proyecto->servicio }}]
                                    </span>
                                </div>

                                <h5 class="fw-bold text-white mb-2" style="font-size: 0.95rem;">{{ $proyecto->nombre }}
                                </h5>

                                <div class="mb-3" style="font-size: 0.75rem;">
                                    <div class="mb-1">
                                        <i class="fas fa-user-tie me-1"
                                            style="font-size: 0.7rem; color: rgba(255,255,255,0.35);"></i>
                                        <span class="card-label">Cliente:</span>
                                        <span class="font-mono card-value">{{ $proyecto->user->name ?? 'Sin Cliente'
                                            }}</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-crown text-warning me-1"
                                            style="font-size: 0.7rem;"></i>
                                        <span class="card-label">Líder:</span>
                                        <span class="font-mono card-value" id="card-leader-name-{{ $proyecto->id }}">{{ $proyecto->developer->name ?? 'Sin asignar' }}</span>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-between pt-2"
                                    style="border-top: 1px solid rgba(255,255,255,0.05);">
                                    <div class="font-mono"
                                        style="font-size: 0.65rem; color: rgba(255, 255, 255, 0.45);">
                                        <i class="far fa-calendar-alt me-1"></i> {{ $proyecto->siguiente_entrega ?? 'Sin
                                        fecha' }}
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
        <div class="modal-content"
            style="background: #0b0f19; border: 1px solid rgba(0, 212, 255, 0.15); box-shadow: 0 0 30px rgba(0, 0, 0, 0.6); border-radius: 8px;">

            <div class="modal-header d-flex justify-content-between align-items-center"
                style="border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 20px 24px;">
                <div>
                    <span style="font-size: 0.75rem; color: #00d4ff; letter-spacing: 1.5px; font-weight: 600;">Sprint
                        Board y Gestión Operativa</span>
                    <h5 class="modal-title text-white fw-bold mt-1" id="modalProjectName" style="font-size: 1.5rem;">
                        SYSTEM_OFFLINE</h5>
                </div>
                <button type="button" data-bs-dismiss="modal" aria-label="Close"
                    style="background: transparent; border: none; color: rgba(255, 255, 255, 0.5); font-size: 1.2rem; transition: color 0.2s;"
                    onmouseover="this.style.color='#ff3b30'" onmouseout="this.style.color='rgba(255, 255, 255, 0.5)'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body p-4" style="background: #030712 !important;">
                <div class="row g-4">

                    <div class="col-12 col-lg-4 text-center"
                        style="border-right: 1px solid rgba(255, 255, 255, 0.05); padding-right: 25px;">
                        <div class="mb-4 text-start">
                            <span
                                style="font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 0.75rem !important; color: #94a3b8 !important; letter-spacing: 0.5px !important; font-weight: 600;">Monitor
                                de rendimiento</span>
                        </div>

                        <div class="position-relative d-inline-block my-3">
                            <svg width="170" height="170" viewBox="0 0 160 160">
                                <circle cx="80" cy="80" r="68"
                                    style="fill: transparent; stroke: rgba(255, 255, 255, 0.02); stroke-width: 5;" />
                                <circle cx="80" cy="80" r="68" id="modalProgressCircle"
                                    style="fill: transparent; stroke: #00d4ff; stroke-width: 5; stroke-dasharray: 427; stroke-dashoffset: 427; transform: rotate(-90deg); transform-origin: 80px 80px; stroke-linecap: round; transition: stroke-dashoffset 0.8s cubic-bezier(0.4, 0, 0.2, 1);" />
                            </svg>
                            <div
                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                <span id="modalProgressText" class="text-white"
                                    style="font-size: 2.5rem; font-weight: 300; display: block; font-family: 'Plus Jakarta Sans', sans-serif !important; letter-spacing: -1px;">0%</span>
                                <span
                                    style="font-size: 0.65rem; color: #94a3b8; letter-spacing: 0.5px; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 500;">Carga
                                    central</span>
                            </div>
                        </div>

                        <div class="mt-4" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <div
                                style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.04); padding: 12px; border-radius: 8px; text-align: left;">
                                <span
                                    style="font-size: 0.65rem; color: #94a3b8; display: block; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 500;">Nivel
                                    crítico</span>
                                <span class="fw-bold text-danger mt-1 d-block" id="modalPriority"
                                    style="font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif !important;">Crítico</span>
                            </div>
                            <div
                                style="background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(255, 255, 255, 0.04); padding: 12px; border-radius: 8px; text-align: left;">
                                <span
                                    style="font-size: 0.65rem; color: #94a3b8; display: block; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 500;">Entorno
                                    de servicio</span>
                                <span class="text-info fw-bold mt-1 d-block" id="modalService"
                                    style="font-size: 0.85rem; font-family: 'Plus Jakarta Sans', sans-serif !important;">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 px-lg-4" style="border-right: 1px solid rgba(255, 255, 255, 0.05);">
                        <!-- Sinopsis compacta -->
                        <div class="mb-3">
                            <span style="font-size: 0.72rem !important; color: #94a3b8 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; letter-spacing: 0.5px !important; display: block !important;">
                                Sinopsis del microservicio
                            </span>
                            <p class="mt-1 mb-0" id="modalDescription"
                                style="line-height: 1.5 !important; max-height: 68px; overflow-y: auto; background: rgba(255, 255, 255, 0.01) !important; padding: 10px 14px !important; border-radius: 8px !important; border: 1px solid rgba(255, 255, 255, 0.03) !important; border-left: 3px solid #00d4ff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 0.82rem !important; color: #cbd5e1 !important;">
                                Analizando registros de base de datos...
                            </p>
                        </div>

                        <!-- Metadatos de Equipo y Cliente -->
                        <div class="row g-2 pt-2 mb-3" style="border-top: 1px solid rgba(255, 255, 255, 0.05) !important;">
                            <div class="col-6">
                                <span style="font-size: 0.7rem !important; color: #94a3b8 !important; font-weight: 500 !important; display: block !important; margin-bottom: 2px !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">
                                    <i class="fas fa-user-tie me-1" style="color: #00d4ff; font-size: 0.7rem;"></i>Cliente titular
                                </span>
                                <span class="text-white d-block fw-semibold text-truncate" id="modalClient" style="font-size: 0.88rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">Buscando...</span>
                            </div>

                            <div class="col-6">
                                <span style="font-size: 0.7rem !important; color: #94a3b8 !important; font-weight: 500 !important; display: block !important; margin-bottom: 2px !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">
                                    <i class="fas fa-crown me-1 text-warning" style="font-size: 0.7rem;"></i>Líder asignado
                                </span>
                                @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                                    <div class="d-flex align-items-center gap-2">
                                        <select id="modalLeaderSelect" class="form-select font-mono" 
                                            style="background: rgba(255,255,255,0.04); border: 1px solid rgba(0,212,255,0.3); color: #fff; font-size: 0.8rem; padding: 3px 8px; border-radius: 6px; cursor: pointer;" 
                                            onchange="cambiarLiderProyecto(this.value)">
                                            @if(isset($desarrolladores))
                                                @foreach($desarrolladores as $dev)
                                                    <option value="{{ $dev->id }}" style="background: #030712; color: #fff;">{{ $dev->name }} ({{ ucfirst($dev->role) }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="leaderSavingSpinner" style="display: none;"><i class="fas fa-spinner fa-spin text-info"></i></span>
                                    </div>
                                @else
                                    <span class="text-white d-block fw-semibold text-truncate" id="modalLeader" style="font-size: 0.88rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">Buscando...</span>
                                @endif
                            </div>

                            <div class="col-12 mt-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span style="font-size: 0.7rem !important; color: #94a3b8 !important; font-weight: 500 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; flex-shrink: 0;">
                                        <i class="fas fa-users me-1" style="color: #00d4ff; font-size: 0.7rem;"></i>Operativos:
                                    </span>
                                    <div class="d-flex flex-wrap gap-1" id="modalOperators" style="color: #94a3b8 !important; font-size: 0.8rem !important;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN COMBINADA POR PESTAÑAS: HITOS vs BÓVEDA -->
                        <div class="pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.05);">
                            <!-- Cabecera de Pestañas Combinadas -->
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn-hud-tab active" id="tabBtnHitos" onclick="switchModalTab('hitos')">
                                        <i class="fas fa-file-invoice-dollar text-info"></i> Hitos Financieros <span class="badge-count" id="tabBadgeHitos">0</span>
                                    </button>
                                    <button type="button" class="btn-hud-tab" id="tabBtnAssets" onclick="switchModalTab('assets')">
                                        <i class="fas fa-folder-open text-info"></i> Bóveda Digital <span class="badge-count" id="tabBadgeAssets">0</span>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Contador para hitos -->
                                    <span id="modalMilestoneCounter" class="font-mono text-white-50" style="font-size: 0.72rem;"></span>
                                    <!-- Contador para assets -->
                                    <span id="modalAssetCounter" class="font-mono text-white-50" style="font-size: 0.72rem; display: none;"></span>
                                    @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                                    <!-- Botón configurar hitos -->
                                    <button type="button" id="btnHeaderManageHitos" class="btn btn-sm" onclick="openMilestonesModal()" title="Configurar Hitos Financieros" style="background: rgba(0, 212, 255, 0.12); border: 1px solid rgba(0, 212, 255, 0.4); color: #00d4ff; font-size: 0.8rem; width: 26px; height: 26px; padding: 0; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                    <!-- Botón subir para assets (Solo icono) -->
                                    <button type="button" id="btnHeaderUploadAsset" class="btn btn-sm" onclick="openUploadAssetModal()" title="Subir archivo a la bóveda" style="display: none; background: rgba(0, 212, 255, 0.12); border: 1px solid rgba(0, 212, 255, 0.4); color: #00d4ff; font-size: 0.8rem; width: 26px; height: 26px; padding: 0; border-radius: 6px; align-items: center; justify-content: center; transition: all 0.2s;">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Panel 1: Hitos Financieros Slider -->
                            <div id="modalTabContentHitos" class="w-100">
                                <div id="modalHitosSliderContainer" class="w-100 overflow-hidden">
                                    <div class="projects-viewport-wrapper w-100 overflow-hidden">
                                        <div id="modalMilestonesTrack" class="projects-track-neon w-100"></div>
                                    </div>

                                    <div id="modalSliderControlsBottom" class="slider-controls-bottom d-flex gap-2 mt-2 w-100">
                                        <button type="button" class="btn btn-slider-bottom flex-fill" onclick="moveModalHitosSlider(-1)">
                                            <i class="fas fa-chevron-left me-2"></i> Anterior
                                        </button>
                                        <button type="button" class="btn btn-slider-bottom flex-fill" onclick="moveModalHitosSlider(1)">
                                            Siguiente <i class="fas fa-chevron-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel 2: Bóveda de Entregables Slider -->
                            <div id="modalTabContentAssets" class="w-100" style="display: none;">
                                <div id="modalAssetsSliderContainer" class="w-100 overflow-hidden">
                                    <div class="projects-viewport-wrapper w-100 overflow-hidden">
                                        <div id="modalAssetsTrack" class="projects-track-neon w-100"></div>
                                    </div>

                                    <div id="modalAssetsSliderControlsBottom" class="slider-controls-bottom d-flex gap-2 mt-2 w-100">
                                        <button type="button" class="btn btn-slider-bottom flex-fill" onclick="moveModalAssetsSlider(-1)">
                                            <i class="fas fa-chevron-left me-2"></i> Anterior
                                        </button>
                                        <button type="button" class="btn btn-slider-bottom flex-fill" onclick="moveModalAssetsSlider(1)">
                                            Siguiente <i class="fas fa-chevron-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3" style="padding-left: 25px !important;">
                        <span
                            style="font-size: 0.75rem !important; color: #94a3b8 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; letter-spacing: 0.5px !important; display: block !important;">Estado
                            de control</span>

                        <div class="my-3 text-center fw-semibold" id="modalStatusLabel"
                            style="padding: 10px 12px !important; background: rgba(0, 212, 255, 0.03) !important; border: 1px solid rgba(0, 212, 255, 0.3) !important; color: #00d4ff !important; border-radius: 6px !important; font-size: 0.85rem !important; letter-spacing: 0.5px !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">
                            En desarrollo
                        </div>

                        @if(auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                        <div class="my-4">
                            <button type="button" class="btn w-100 fw-semibold" onclick="openConfirmDeleteModal()"
                                style="background: transparent !important; border: 1px solid rgba(239, 68, 68, 0.4) !important; color: #f87171 !important; font-size: 0.85rem !important; padding: 10px !important; border-radius: 6px !important; cursor: pointer !important; transition: all 0.2s ease !important; font-family: 'Plus Jakarta Sans', sans-serif !important; box-shadow: none !important;"
                                onmouseover="this.style.background='rgba(239, 68, 68, 0.08)'; this.style.borderColor='#ef4444'; this.style.color='#ffffff';"
                                onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(239, 68, 68, 0.4)'; this.style.color='#f87171';">
                                <i class="fas fa-trash-alt me-2"></i>Eliminar Proyecto
                            </button>
                        </div>
                        @endif

                        <div class="mt-4">
                            <span
                                style="font-size: 0.75rem !important; color: #94a3b8 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-weight: 600 !important; letter-spacing: 0.5px !important; display: block !important; margin-bottom: 15px !important;">Trazabilidad
                                de hitos de fase</span>

                            <div class="timeline-mini-jira"
                                style="display: flex !important; flex-direction: column !important; gap: 14px !important; padding-left: 15px !important;">

                                <div class="timeline-mini-item d-flex align-items-center" id="step-prospecto"
                                    style="opacity: 1 !important; padding-bottom: 0 !important; margin-bottom: 2px !important;">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 20px; height: 20px; border-radius: 50%; background: #00d4ff; color: #030712; font-size: 0.55rem; font-weight: bold;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <span class="text-white small fw-semibold ms-2"
                                        style="font-size: 0.85rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">01
                                        / Inicialización</span>
                                </div>

                                <div class="timeline-mini-item d-flex align-items-center" id="step-desarrollo"
                                    style="opacity: 1 !important; padding-bottom: 0 !important; margin-bottom: 2px !important;">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 20px; height: 20px; border-radius: 50%; background: #00d4ff; color: #030712; font-size: 0.55rem; font-weight: bold;">
                                        <i class="fas fa-hammer"></i>
                                    </div>
                                    <span class="text-white small fw-semibold ms-2"
                                        style="font-size: 0.85rem !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">02
                                        / En Desarrollo</span>
                                </div>

                                <div class="timeline-mini-item d-flex align-items-center" id="step-pruebas"
                                    style="opacity: 1 !important; padding-bottom: 0 !important; margin-bottom: 2px !important;">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 20px; height: 20px; border-radius: 50%; background: #00d4ff; color: #030712; font-size: 0.55rem; font-weight: bold; box-shadow: 0 0 8px #00d4ff;">
                                        <i class="fas fa-vial"></i>
                                    </div>
                                    <span class="small fw-normal ms-2"
                                        style="font-size: 0.85rem !important; color: #cbd5e1 !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">03
                                        / En Pruebas</span>
                                </div>

                                <div class="timeline-mini-item d-flex align-items-center" id="step-finalizado"
                                    style="opacity: 0.4 !important; padding-bottom: 0 !important;">
                                    <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 20px; height: 20px; border-radius: 50%; background: rgba(255,255,255,0.1); color: #fff; font-size: 0.55rem;">
                                        <i class="fas fa-rocket"></i>
                                    </div>
                                    <span class="small fw-normal ms-2"
                                        style="font-size: 0.85rem !important; color: #64748b !important; font-family: 'Plus Jakarta Sans', sans-serif !important;">04
                                        / Despliegue</span>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between align-items-center"
                style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding: 15px 24px; background: rgba(5, 8, 14, 0.4);">
                <span style="font-size: 0.65rem; color: #6c757d; letter-spacing: 0.5px;">Terminal v1.0.0 Sistemas Operando OK</span>
                <div class="d-flex align-items-center gap-2">
                    @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
                        <a href="#" id="modalEditBtn" class="btn btn-sm text-decoration-none"
                            style="background: rgba(6, 182, 212, 0.15); border: 1px solid #06b6d4; color: #00d4ff; font-size: 0.75rem; padding: 6px 16px; border-radius: 6px; font-weight: 600; transition: all 0.2s;"
                            onmouseover="this.style.background='rgba(6, 182, 212, 0.3)'; this.style.boxShadow='0 0 15px rgba(6,182,212,0.4)';"
                            onmouseout="this.style.background='rgba(6, 182, 212, 0.15)'; this.style.boxShadow='none';">
                            <i class="fas fa-edit me-1"></i> Editar Proyecto
                        </a>
                    @endif
                    <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: #8892b0; font-size: 0.75rem; padding: 6px 16px; border-radius: 6px; transition: all 0.2s;"
                        onmouseover="this.style.background='rgba(255, 255, 255, 0.08)'; this.style.color='#ffffff';"
                        onmouseout="this.style.background='rgba(255, 255, 255, 0.03)'; this.style.color='#8892b0';">
                        Volver al tablero
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal de Confirmación de Seguridad para Eliminación -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content"
            style="background: #070c18 !important; border: 1px solid rgba(239, 68, 68, 0.4) !important; box-shadow: 0 0 50px rgba(239, 68, 68, 0.25), 0 25px 60px rgba(0, 0, 0, 0.95) !important; border-radius: 16px !important; color: #ffffff !important; overflow: hidden;">

            <!-- Modal Header -->
            <div class="modal-header border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-start"
                style="background: rgba(239, 68, 68, 0.04);">
                <div class="d-flex align-items-center gap-3">
                    <div
                        style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0"
                            style="font-size: 1.15rem; font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.3px;">
                            Confirmar Eliminación</h5>
                        <span class="font-mono text-danger fw-semibold"
                            style="font-size: 0.72rem; letter-spacing: 0.8px; text-transform: uppercase;">
                            VERIFICACIÓN DE SEGURIDAD</span>
                    </div>
                </div>
                <button type="button" data-bs-dismiss="modal" aria-label="Close"
                    style="background: transparent; border: none; color: #94a3b8; font-size: 1.1rem; cursor: pointer; transition: color 0.2s;"
                    onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#94a3b8'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-3">
                <p class="mb-3"
                    style="font-family: 'Plus Jakarta Sans', sans-serif; color: #cbd5e1; font-size: 0.88rem; line-height: 1.5;">
                    Esta acción enviará el proyecto a la <span class="text-white fw-semibold">Papelera
                        </span> y eliminará automáticamente la lista correspondiente en <span
                        class="text-info fw-semibold">ClickUp</span>.
                </p>

                <div class="p-3 mb-2"
                    style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px;">
                    <label for="confirmDeleteInput" class="d-block font-mono mb-2"
                        style="font-size: 0.8rem; color: #e2e8f0; letter-spacing: 0.5px;">
                        Para confirmar, escribe la palabra <strong class="text-danger"
                            style="letter-spacing: 1px; font-size: 0.85rem;">ELIMINAR</strong> a continuación:
                    </label>
                    <input type="text" id="confirmDeleteInput" class="form-control font-mono text-white"
                        style="background: #030712 !important; border: 1px solid rgba(239, 68, 68, 0.4) !important; color: #ffffff !important; padding: 10px 14px; font-size: 0.9rem; border-radius: 8px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.6);"
                        placeholder="Escribe ELIMINAR para habilitar..." onkeyup="checkDeleteConfirmation()"
                        autocomplete="off">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-end gap-2"
                style="background: rgba(3, 7, 18, 0.5); border-top: 1px solid rgba(255, 255, 255, 0.04) !important;">
                <button type="button" class="btn fw-semibold" data-bs-dismiss="modal"
                    style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); color: #cbd5e1; font-size: 0.82rem; padding: 9px 20px; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all 0.2s;"
                    onmouseover="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.color='#ffffff';"
                    onmouseout="this.style.background='rgba(255, 255, 255, 0.05)'; this.style.color='#cbd5e1';">
                    Cancelar
                </button>
                <form id="modalDeleteForm" action="/proyectos" method="POST" class="m-0" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnSubmitDelete" class="btn fw-bold"
                        style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; font-size: 0.82rem; padding: 9px 22px; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; opacity: 0.4; cursor: not-allowed; transition: all 0.25s ease;"
                        disabled>
                        <i class="fas fa-trash-alt me-2"></i>Sí, Eliminar
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Modal para Subir Entregable / Archivo a la Bóveda -->
<div class="modal fade" id="uploadAssetModal" tabindex="-1" aria-hidden="true" style="z-index: 1070; backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="background: #080d1a !important; border: 1px solid rgba(0, 212, 255, 0.3) !important; box-shadow: 0 0 45px rgba(0, 212, 255, 0.15), 0 20px 50px rgba(0,0,0,0.9) !important; border-radius: 16px !important; color: #ffffff !important; overflow: hidden;">
            <div class="modal-header border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-start" style="background: rgba(0, 212, 255, 0.04);">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: rgba(0, 212, 255, 0.12); border: 1px solid rgba(0, 212, 255, 0.3); color: #00d4ff; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" style="font-size: 1.15rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                            Subir Entregable
                        </h5>
                        <span class="font-mono text-info fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.8px; text-transform: uppercase;">
                            BÓVEDA DIGITAL DEL PROYECTO
                        </span>
                    </div>
                </div>
                <button type="button" onclick="closeUploadAssetModal()" aria-label="Close" style="background: transparent; border: none; color: #94a3b8; font-size: 1.1rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#94a3b8'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formUploadAsset" onsubmit="submitUploadAsset(event)">
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Archivo (ZIP, PDF, APK, Imágenes, Documentos - Máx 100MB)</label>
                        <input type="file" id="assetFileInput" class="form-control font-mono" required style="background: #030712; border: 1px solid rgba(0, 212, 255, 0.2); color: #fff; font-size: 0.82rem; padding: 8px 12px; border-radius: 8px;" onchange="autoFillAssetName(this)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Nombre descriptivo (Opcional)</label>
                        <input type="text" id="assetNombreInput" class="form-control" placeholder="" style="background: #030712; border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.85rem; padding: 8px 12px; border-radius: 8px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-mono text-white-50" style="font-size: 0.75rem;">Categoría / Tipo de Archivo</label>
                        <select id="assetTipoSelect" class="form-select font-mono" style="background: #030712; border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.85rem; padding: 8px 12px; border-radius: 8px;">
                            <option value="" style="background: #080d1a;">Auto-detectar por extensión</option>
                            <option value="Código / Archivo" style="background: #080d1a;">Código / Archivo Comprimido (ZIP, RAR, 7Z)</option>
                            <option value="Documento" style="background: #080d1a;">Documento (PDF, DOCX, XLSX)</option>
                            <option value="Diseño / Imagen" style="background: #080d1a;">Diseño / Gráficos (PNG, JPG, SVG, Figma)</option>
                            <option value="Ejecutable / App" style="background: #080d1a;">Ejecutable / Aplicación (APK, IPA, EXE)</option>
                            <option value="Video" style="background: #080d1a;">Video / Grabación (MP4, WEBM)</option>
                        </select>
                    </div>

                    <div id="uploadAssetProgressContainer" class="mt-3" style="display: none;">
                        <div class="d-flex justify-content-between font-mono text-white-50 mb-1" style="font-size: 0.7rem;">
                            <span id="uploadStatusText">Almacenando archivo en el servidor...</span>
                            <span id="uploadPercentageText">0%</span>
                        </div>
                        <div class="progress" style="height: 6px; background: rgba(255,255,255,0.05); border-radius: 3px;">
                            <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-end gap-2" style="background: rgba(3, 7, 18, 0.5); border-top: 1px solid rgba(255, 255, 255, 0.04) !important;">
                    <button type="button" class="btn fw-semibold" onclick="closeUploadAssetModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); color: #cbd5e1; font-size: 0.82rem; padding: 8px 18px; border-radius: 8px;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitUploadAsset" class="btn fw-bold" style="background: #00d4ff; border: 1px solid #00d4ff; color: #00d4ff; color: #000; font-size: 0.82rem; padding: 8px 20px; border-radius: 8px;">
                        <i class="fas fa-upload me-1"></i> Subir Archivo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Configurar / Subir Hitos Financieros del Proyecto -->
<div class="modal fade" id="milestonesModal" tabindex="-1" aria-hidden="true" style="z-index: 1070; backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 640px;">
        <div class="modal-content" style="background: #080d1a !important; border: 1px solid rgba(0, 212, 255, 0.3) !important; box-shadow: 0 0 45px rgba(0, 212, 255, 0.15), 0 20px 50px rgba(0,0,0,0.9) !important; border-radius: 16px !important; color: #ffffff !important; overflow: hidden;">
            <div class="modal-header border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-start" style="background: rgba(0, 212, 255, 0.04);">
                <div class="d-flex align-items-center gap-3">
                    <div style="background: rgba(0, 212, 255, 0.12); border: 1px solid rgba(0, 212, 255, 0.3); color: #00d4ff; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" style="font-size: 1.15rem; font-family: 'Plus Jakarta Sans', sans-serif;">
                            Configurar Hitos Financieros
                        </h5>
                        <span class="font-mono text-info fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.8px; text-transform: uppercase;">
                            ESTRUCTURA DE COBROS Y FASES
                        </span>
                    </div>
                </div>
                <button type="button" onclick="closeMilestonesModal()" aria-label="Close" style="background: transparent; border: none; color: #94a3b8; font-size: 1.1rem; cursor: pointer; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#94a3b8'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formMilestones" onsubmit="submitSaveMilestones(event)">
                <div class="modal-body px-4 py-3">
                    <!-- Barra de acciones rápidas -->
                    <div class="d-flex align-items-center justify-content-between p-2 mb-3 rounded" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.06);">
                        <span class="text-white-50 font-mono" style="font-size: 0.75rem;">
                            <i class="fas fa-info-circle text-info me-1"></i> Asigna el precio por hito o personaliza:
                        </span>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm" onclick="loadDefault4Phases()" style="background: rgba(0, 212, 255, 0.1); border: 1px solid rgba(0, 212, 255, 0.3); color: #00d4ff; font-size: 0.72rem; padding: 4px 10px; border-radius: 6px; font-weight: 600;">
                                <i class="fas fa-magic me-1"></i> 4 Fases Estándar
                            </button>
                            <button type="button" class="btn btn-sm" onclick="addMilestoneRow()" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); color: #34d399; font-size: 0.72rem; padding: 4px 10px; border-radius: 6px; font-weight: 600;">
                                <i class="fas fa-plus me-1"></i> Añadir Hito
                            </button>
                        </div>
                    </div>

                    <!-- Contenedor dinámico de filas de hitos -->
                    <div id="milestonesRowsContainer" style="max-height: 320px; overflow-y: auto; padding-right: 4px; display: flex; flex-direction: column; gap: 8px;">
                        <!-- Filas renderizadas por JS -->
                    </div>

                    <!-- Resumen del total -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.06);">
                        <span class="font-mono text-white-50" style="font-size: 0.8rem;">Total cotizado del proyecto:</span>
                        <span class="font-mono fw-bold text-info" id="milestonesTotalSum" style="font-size: 1.1rem;">$0.00 USD</span>
                    </div>
                </div>

                <div class="modal-footer border-top-0 px-4 pb-4 pt-2 d-flex justify-content-end gap-2" style="background: rgba(3, 7, 18, 0.5); border-top: 1px solid rgba(255, 255, 255, 0.04) !important;">
                    <button type="button" class="btn fw-semibold" onclick="closeMilestonesModal()" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); color: #cbd5e1; font-size: 0.82rem; padding: 8px 18px; border-radius: 8px;">
                        Cancelar
                    </button>
                    <button type="submit" id="btnSubmitMilestones" class="btn fw-bold" style="background: #00d4ff; border: 1px solid #00d4ff; color: #000; font-size: 0.82rem; padding: 8px 20px; border-radius: 8px;">
                        <i class="fas fa-save me-1"></i> Guardar Hitos
                    </button>
                </div>
            </form>
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

    let draggedCard = null;

    function drag(ev) {
        draggedCard = ev.target.closest('.kanban-card');
        if (draggedCard) {
            draggedCard.classList.add('is-dragging');
            ev.dataTransfer.setData("text/plain", draggedCard.id);
            ev.dataTransfer.effectAllowed = "move";
        }
    }

    function dragEnd(ev) {
        if (draggedCard) {
            draggedCard.classList.remove('is-dragging');
            draggedCard = null;
        }
        document.querySelectorAll('.kanban-column').forEach(col => col.classList.remove('drag-over'));
        updateColumnCounts();
        saveKanbanOrder();
    }

    function allowDrop(ev) {
        ev.preventDefault();
        ev.dataTransfer.dropEffect = "move";

        const column = ev.target.closest('.kanban-column');
        if (!column) return;

        const container = column.querySelector('.cards-container');
        if (!container) return;

        const draggingEl = draggedCard || document.querySelector('.kanban-card.is-dragging');
        if (!draggingEl) return;

        const afterElement = getDragAfterElement(container, ev.clientY);
        if (afterElement == null) {
            container.appendChild(draggingEl);
        } else if (afterElement !== draggingEl) {
            container.insertBefore(draggingEl, afterElement);
        }
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.kanban-card:not(.is-dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function dragEnter(ev) {
        ev.preventDefault();
        const column = ev.target.closest('.kanban-column');
        if (column) column.classList.add('drag-over');
    }

    function dragLeave(ev) {
        const column = ev.target.closest('.kanban-column');
        if (column && !column.contains(ev.relatedTarget)) {
            column.classList.remove('drag-over');
        }
    }

    function getPriorityRank(prio) {
        const p = (prio || '').toLowerCase().trim();
        if (p === 'critico') return 1;
        if (p === 'alto') return 2;
        if (p === 'medio') return 3;
        if (p === 'bajo') return 4;
        return 5;
    }

    function sortKanbanCardsHierarchically() {
        document.querySelectorAll('.kanban-column').forEach(col => {
            const container = col.querySelector('.cards-container');
            if (!container) return;
            const cards = Array.from(container.querySelectorAll('.kanban-card'));
            cards.sort((a, b) => {
                const prioA = getPriorityRank(a.getAttribute('data-priority'));
                const prioB = getPriorityRank(b.getAttribute('data-priority'));
                if (prioA !== prioB) return prioA - prioB;

                const dateA = a.getAttribute('data-delivery') || '';
                const dateB = b.getAttribute('data-delivery') || '';
                if (dateA && dateB) {
                    if (dateA !== dateB) return dateA.localeCompare(dateB);
                } else if (dateA && !dateB) {
                    return -1;
                } else if (!dateA && dateB) {
                    return 1;
                }

                const idA = parseInt(a.getAttribute('data-id') || '0', 10);
                const idB = parseInt(b.getAttribute('data-id') || '0', 10);
                return idB - idA;
            });
            cards.forEach(card => container.appendChild(card));
        });
        updateColumnCounts();
    }

    function drop(ev) {
        ev.preventDefault();
        const userRole = "{{ auth()->user()->role }}";
        if (userRole !== 'superadmin' && userRole !== 'admin') return;

        const column = ev.target.closest('.kanban-column');
        if (!column) return;
        column.classList.remove('drag-over');

        const cardId = ev.dataTransfer.getData("text/plain");
        const cardElement = document.getElementById(cardId) || draggedCard;
        if (!cardElement) return;

        const container = column.querySelector('.cards-container');
        if (container && !container.contains(cardElement)) {
            container.appendChild(cardElement);
        }

        sortKanbanCardsHierarchically();

        const newStatus = column.getAttribute('data-status');
        const projectId = cardElement.getAttribute('data-id') || cardId.replace('project-card-', '');

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
        try {
            localStorage.removeItem('softwaretech_kanban_order');
        } catch(e) {}
        sortKanbanCardsHierarchically();
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
                        currentOpenProjectId = data.id;
                        currentProjectMilestonesData = data.milestones || [];
                        document.getElementById('modalProjectName').innerText = data.nombre || 'SYSTEM_OFFLINE';
                        document.getElementById('modalDescription').innerText = data.descripcion || 'Sin descripción técnica asignada.';
                        document.getElementById('modalClient').innerText = data.user ? data.user.name : 'No asignado';
                        
                        const modalLeaderSelect = document.getElementById('modalLeaderSelect');
                        if (modalLeaderSelect) {
                            modalLeaderSelect.value = data.developer ? data.developer.id : '';
                        }
                        const modalLeaderSpan = document.getElementById('modalLeader');
                        if (modalLeaderSpan) {
                            modalLeaderSpan.innerText = data.developer ? data.developer.name : 'Sin asignar';
                        }
                        document.getElementById('modalService').innerText = data.servicio;
                        document.getElementById('modalPriority').innerText = `${data.priority.toUpperCase()}`;
                        document.getElementById('modalStatusLabel').innerText = data.estado.toUpperCase();

                        const deleteForm = document.getElementById('modalDeleteForm');
                        if (deleteForm) deleteForm.action = `{{ url('/console/proyectos') }}/${data.id}`;

                        const editBtn = document.getElementById('modalEditBtn');
                        if (editBtn) editBtn.href = `{{ url('/console/proyectos') }}/${data.id}/editar`;

                        const priorityEl = document.getElementById('modalPriority');
                        priorityEl.className = 'meta-value-modal text-uppercase ' + (data.priority === 'critico' ? 'text-danger' : 'text-info');

                        const operatorsContainer = document.getElementById('modalOperators');
                        if (operatorsContainer) {
                            operatorsContainer.innerHTML = '';
                            if (data.team && data.team.length > 0) {
                                data.team.forEach(emp => {
                                    operatorsContainer.innerHTML += `<span class="badge font-mono" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); padding: 5px 10px;">${emp.name}</span>`;
                                });
                            } else {
                                operatorsContainer.innerHTML = '<span style="color: #94a3b8 !important; font-size: 0.85rem !important;">Proyecto compuesto únicamente por el Líder Desarrollador.</span>';
                            }
                        }

                        // SLIDER DE HITOS EN MODAL (Estilo Equipo)
                        const hitosTrack = document.getElementById('modalMilestonesTrack');
                        const hitosSliderContainer = document.getElementById('modalHitosSliderContainer');
                        const hitosCounter = document.getElementById('modalMilestoneCounter');
                        
                        if (hitosTrack) {
                            hitosTrack.innerHTML = '';
                            currentModalHitoIndex = 0;
                            hitosTrack.style.transform = 'translateX(0px)';

                            const milestonesList = data.milestones || [];
                            totalModalHitos = milestonesList.length;

                            const bottomControls = document.getElementById('modalSliderControlsBottom');
                            if (totalModalHitos > 1) {
                                if (bottomControls) bottomControls.style.display = 'flex';
                                if (hitosCounter) hitosCounter.innerText = `1 / ${totalModalHitos}`;
                            } else {
                                if (bottomControls) bottomControls.style.display = 'none';
                                if (hitosCounter) hitosCounter.innerText = totalModalHitos === 1 ? '1 / 1' : '';
                            }

                            if (totalModalHitos === 0) {
                                hitosTrack.innerHTML = `
                                    <div class="project-mini-card text-center py-3">
                                        <span style="color: #94a3b8; font-size: 0.82rem; display: block;">Este proyecto no tiene hitos financieros configurados.</span>
                                    </div>
                                `;
                            } else {
                                milestonesList.forEach(m => {
                                    const isPaid = m.is_paid == 1 || m.is_paid === true;
                                    const statusBadge = isPaid 
                                        ? `<span class="badge font-mono" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; font-size: 0.72rem;"><i class="fas fa-check-circle me-1"></i> Liquidado</span>` 
                                        : `<span class="badge font-mono" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; font-size: 0.72rem;">Pendiente</span>`;

                                    const toggleBtn = `
                                        <button type="button" class="btn btn-sm ${isPaid ? 'btn-outline-secondary' : 'btn-outline-success'}" 
                                            style="font-size: 0.7rem; padding: 4px 10px; border-radius: 6px;" 
                                            onclick="toggleHitoPago(${m.id}, this)">
                                            ${isPaid ? '<i class="fas fa-undo me-1"></i> Marcar Pendiente' : '<i class="fas fa-check me-1"></i> Marcar como Pagado'}
                                        </button>
                                    `;

                                    let receiptLink = '';
                                    if (data.assets && data.assets.length > 0) {
                                        const asset = data.assets.find(a => a.assetable_id == m.id && a.assetable_type && a.assetable_type.includes('Milestone'));
                                        if (asset) {
                                            receiptLink = `<a href="{{ url('/assets') }}/${asset.id}/download" download class="badge text-info text-decoration-none" style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.3); font-size: 0.72rem; padding: 5px 8px;"><i class="fas fa-download me-1"></i> Comprobante</a>`;
                                        }
                                    }

                                    hitosTrack.innerHTML += `
                                        <div class="project-mini-card">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold text-white" style="font-size: 0.92rem;">${m.name || m.title || 'Hito'}</span>
                                                <span class="font-mono text-info fw-bold" style="font-size: 0.95rem;">$${parseFloat(m.cost || 0).toFixed(2)} USD</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.04);">
                                                <div>${receiptLink}</div>
                                                <div class="d-flex align-items-center gap-2">
                                                    ${statusBadge}
                                                    ${toggleBtn}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                        }

                        // SLIDER DE BÓVEDA DE ENTREGABLES (Estilo carrusel idéntico a Hitos y Equipo)
                        const assetsTrack = document.getElementById('modalAssetsTrack');
                        const assetsCounter = document.getElementById('modalAssetCounter');
                        if (assetsTrack) {
                            assetsTrack.innerHTML = '';
                            currentModalAssetIndex = 0;
                            assetsTrack.style.transform = 'translateX(0px)';

                            const projectAssets = (data.assets || []).filter(a => !a.assetable_type || a.assetable_type.includes('Project'));
                            totalModalAssets = projectAssets.length;

                            const bottomAssetControls = document.getElementById('modalAssetsSliderControlsBottom');
                            if (totalModalAssets > 1) {
                                if (bottomAssetControls) bottomAssetControls.style.display = 'flex';
                                if (assetsCounter) assetsCounter.innerText = `1 / ${totalModalAssets}`;
                            } else {
                                if (bottomAssetControls) bottomAssetControls.style.display = 'none';
                                if (assetsCounter) assetsCounter.innerText = totalModalAssets === 1 ? '1 / 1' : '';
                            }

                            if (totalModalAssets === 0) {
                                assetsTrack.innerHTML = '<div class="project-mini-card text-center"><span style="color: #94a3b8; font-size: 0.82rem;">Este proyecto no tiene entregables o archivos subidos en la bóveda.</span></div>';
                            } else {
                                    projectAssets.forEach(asset => {
                                        const ext = (asset.path || '').split('.').pop().toLowerCase();
                                        let fullFileName = asset.nombre || 'archivo';
                                        if (ext && !fullFileName.toLowerCase().endsWith('.' + ext.toLowerCase())) {
                                            fullFileName += '.' + ext;
                                        }

                                        let iconClass = 'fa-file-alt text-info';
                                        if (['zip', 'rar', '7z', 'tar', 'gz'].includes(ext)) iconClass = 'fa-file-archive text-warning';
                                        else if (['pdf'].includes(ext)) iconClass = 'fa-file-pdf text-danger';
                                        else if (['apk', 'exe'].includes(ext)) iconClass = 'fa-android text-success';
                                        else if (['png', 'jpg', 'jpeg', 'svg', 'webp'].includes(ext)) iconClass = 'fa-file-image text-cyan';
                                        else if (['mp4', 'webm', 'mov'].includes(ext)) iconClass = 'fa-file-video text-purple';

                                        const downloadUrl = `{{ url('/assets') }}/${asset.id}/download`;

                                        assetsTrack.innerHTML += `
                                            <div class="project-mini-card">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                        <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255,255,255,0.04); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                            <i class="fas ${iconClass}"></i>
                                                        </div>
                                                        <span class="fw-bold text-white text-truncate font-mono" style="font-size: 0.9rem;" title="${fullFileName}">
                                                            ${fullFileName}
                                                        </span>
                                                    </div>
                                                    <span class="badge font-mono" style="background: rgba(0, 212, 255, 0.1); border: 1px solid rgba(0, 212, 255, 0.3); color: #00d4ff; font-size: 0.72rem; flex-shrink: 0;">
                                                        ${asset.tipo || 'Entregable'}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.04);">
                                                    <span class="font-mono text-white-50" style="font-size: 0.72rem;">
                                                        <i class="fas fa-file me-1 text-info"></i> ${ext.toUpperCase()}
                                                    </span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="${downloadUrl}" download="${fullFileName}" class="btn btn-sm" style="background: rgba(0, 212, 255, 0.12); border: 1px solid rgba(0, 212, 255, 0.35); color: #00d4ff; font-size: 0.72rem; padding: 4px 12px; border-radius: 6px; font-weight: 600;">
                                                            <i class="fas fa-download me-1"></i> Descargar
                                                        </a>
                                                    @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
                                                    <button type="button" class="btn btn-sm btn-outline-danger" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;" onclick="eliminarAsset(${asset.id}, this)" title="Eliminar entregable">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                            }
                        }

                        // Actualizar contadores de pestañas y activar hitos por defecto
                        const badgeHitos = document.getElementById('tabBadgeHitos');
                        if (badgeHitos) badgeHitos.innerText = (data.milestones || []).length;

                        const badgeAssets = document.getElementById('tabBadgeAssets');
                        if (badgeAssets) badgeAssets.innerText = ((data.assets || []).filter(a => !a.assetable_type || a.assetable_type.includes('Project'))).length;

                        switchModalTab('hitos');

                        const progress = parseInt(data.progreso) || 0;
                        document.getElementById('modalProgressText').innerText = `${progress}%`;

                        const circle = document.getElementById('modalProgressCircle');
                        if (circle) {
                            const radius = circle.r.baseVal.value;
                            const circumference = 2 * Math.PI * radius;
                            const offset = circumference - (progress / 100) * circumference;
                            circle.style.strokeDashoffset = offset;
                        }

                        const estado = (data.estado || '').toLowerCase();

                        ['step-prospecto', 'step-desarrollo', 'step-pruebas', 'step-finalizado'].forEach(stepId => {
                            const item = document.getElementById(stepId);
                            if (item) {
                                item.style.setProperty('opacity', '0.4', 'important');
                                const textSpan = item.querySelector('span');
                                if (textSpan) {
                                    textSpan.style.setProperty('color', '#64748b', 'important');
                                    textSpan.classList.remove('fw-semibold', 'text-white');
                                }
                                const iconBox = item.querySelector('div');
                                if (iconBox) {
                                    iconBox.style.background = 'rgba(255,255,255,0.1)';
                                    iconBox.style.color = '#fff';
                                    iconBox.style.boxShadow = 'none';
                                }
                            }
                        });

                        function activarPaso(stepId) {
                            const item = document.getElementById(stepId);
                            if (item) {
                                item.style.setProperty('opacity', '1', 'important');
                                const textSpan = item.querySelector('span');
                                if (textSpan) {
                                    textSpan.style.setProperty('color', '#ffffff', 'important');
                                    textSpan.classList.add('fw-semibold');
                                }
                                const iconBox = item.querySelector('div');
                                if (iconBox) {
                                    iconBox.style.background = '#00d4ff';
                                    iconBox.style.color = '#030712';
                                    iconBox.style.boxShadow = '0 0 8px #00d4ff';
                                }
                            }
                        }

                        activarPaso('step-prospecto');

                        if (['en desarrollo', 'en pruebas', 'finalizado', 'completado'].includes(estado)) {
                            activarPaso('step-desarrollo');
                        }

                        if (['en pruebas', 'finalizado', 'completado'].includes(estado)) {
                            activarPaso('step-pruebas');
                        }

                        if (['finalizado', 'completado'].includes(estado)) {
                            activarPaso('step-finalizado');
                        }

                        const modalTarget = document.getElementById('projectDetailsModal');
                        if (modalTarget) {
                            modalTarget.classList.add('show');
                            modalTarget.style.display = 'block';
                            document.body.classList.add('modal-open');

                            if (!document.getElementById('modal-backdrop-nexus')) {
                                const backdrop = document.createElement('div');
                                backdrop.className = 'modal-backdrop fade show';
                                backdrop.id = 'modal-backdrop-nexus';
                                document.body.appendChild(backdrop);
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            });
        });

        document.querySelectorAll('[data-bs-dismiss="modal"], .btn-close-nexus, .btn-close-hardware').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const uploadModalParent = btn.closest('#uploadAssetModal');
                if (uploadModalParent) {
                    e.stopPropagation();
                    closeUploadAssetModal();
                    return;
                }
                const milestonesModalParent = btn.closest('#milestonesModal');
                if (milestonesModalParent) {
                    e.stopPropagation();
                    closeMilestonesModal();
                    return;
                }

                ['projectDetailsModal', 'confirmDeleteModal', 'uploadAssetModal', 'milestonesModal'].forEach(id => {
                    const modalTarget = document.getElementById(id);
                    if (modalTarget) {
                        modalTarget.classList.remove('show');
                        modalTarget.style.display = 'none';
                    }
                });
                document.body.classList.remove('modal-open');
                const backdrop = document.getElementById('modal-backdrop-nexus');
                if (backdrop) backdrop.remove();
                const backdropUpload = document.getElementById('modal-backdrop-upload');
                if (backdropUpload) backdropUpload.remove();
                const backdropMilestones = document.getElementById('modal-backdrop-milestones');
                if (backdropMilestones) backdropMilestones.remove();
            });
        });
    });

    function openConfirmDeleteModal() {
        const input = document.getElementById('confirmDeleteInput');
        const btn = document.getElementById('btnSubmitDelete');
        if (input) input.value = '';
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.4';
            btn.style.cursor = 'not-allowed';
        }

        const detailsModalEl = document.getElementById('projectDetailsModal');
        if (detailsModalEl) {
            detailsModalEl.classList.remove('show');
            detailsModalEl.style.display = 'none';
        }

        const confirmModalEl = document.getElementById('confirmDeleteModal');
        if (confirmModalEl) {
            confirmModalEl.classList.add('show');
            confirmModalEl.style.display = 'block';
            if (!document.getElementById('modal-backdrop-nexus')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modal-backdrop-nexus';
                document.body.appendChild(backdrop);
            }
        }
    }

    function checkDeleteConfirmation() {
        const input = document.getElementById('confirmDeleteInput');
        const btn = document.getElementById('btnSubmitDelete');
        if (!input || !btn) return;

        if (input.value.trim().toUpperCase() === 'ELIMINAR') {
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
            btn.style.background = '#ef4444';
            btn.style.borderColor = '#ef4444';
            btn.style.color = '#ffffff';
            btn.style.boxShadow = '0 0 20px rgba(239, 68, 68, 0.55)';
        } else {
            btn.disabled = true;
            btn.style.opacity = '0.4';
            btn.style.cursor = 'not-allowed';
            btn.style.background = 'rgba(239, 68, 68, 0.15)';
            btn.style.borderColor = 'rgba(239, 68, 68, 0.3)';
            btn.style.color = '#f87171';
            btn.style.boxShadow = 'none';
        }
    }

    function sincronizarClickUp() {
        const btn = document.getElementById('btnSyncClickUp');
        const icon = document.getElementById('iconSyncClickUp');
        if (!btn || !icon) return;

        btn.disabled = true;
        icon.classList.add('fa-spin');
        btn.style.opacity = '0.7';

        fetch(`{{ route('admin.proyectos.syncClickup') }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            icon.classList.remove('fa-spin');
            btn.disabled = false;
            btn.style.opacity = '1';
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'No se pudo sincronizar con ClickUp.');
            }
        })
        .catch(err => {
            icon.classList.remove('fa-spin');
            btn.disabled = false;
            btn.style.opacity = '1';
            alert('Error de conexión al sincronizar con ClickUp.');
        });
    }

    function toggleHitoPago(milestoneId, btn) {
        btn.disabled = true;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`{{ url('/console/milestones') }}/${milestoneId}/toggle-payment`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Actualizar visualmente sin recargar toda la página
                const isPaid = data.is_paid;
                const parentRow = btn.closest('.d-flex.align-items-center.gap-2');
                if (parentRow) {
                    const badge = parentRow.querySelector('.badge');
                    if (badge) {
                        if (isPaid) {
                            badge.style = "background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; font-size: 0.72rem;";
                            badge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Liquidado';
                        } else {
                            badge.style = "background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; font-size: 0.72rem;";
                            badge.innerHTML = 'Pendiente';
                        }
                    }
                    btn.disabled = false;
                    btn.className = `btn btn-sm ${isPaid ? 'btn-outline-secondary' : 'btn-outline-success'}`;
                    btn.innerHTML = isPaid ? '<i class="fas fa-undo me-1"></i> Marcar Pendiente' : '<i class="fas fa-check me-1"></i> Marcar como Pagado';
                }
            } else {
                alert(data.message || 'Error al cambiar estado.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        })
        .catch(err => {
            alert('Error de red al actualizar estado del hito.');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        });
    }

    let currentModalHitoIndex = 0;
    let totalModalHitos = 0;

    function moveModalHitosSlider(direction) {
        if (totalModalHitos <= 1) return;

        currentModalHitoIndex += direction;
        if (currentModalHitoIndex < 0) {
            currentModalHitoIndex = totalModalHitos - 1;
        } else if (currentModalHitoIndex >= totalModalHitos) {
            currentModalHitoIndex = 0;
        }

        const track = document.getElementById('modalMilestonesTrack');
        if (track) {
            track.style.transform = `translateX(-${currentModalHitoIndex * 100}%)`;
        }

        const counter = document.getElementById('modalMilestoneCounter');
        if (counter) {
            counter.innerText = `${currentModalHitoIndex + 1} / ${totalModalHitos}`;
        }
    }

    let currentOpenProjectId = null;

    function cambiarLiderProyecto(newDevId) {
        if (!currentOpenProjectId) return;
        const spinner = document.getElementById('leaderSavingSpinner');
        if (spinner) spinner.style.display = 'inline-block';

        fetch("{{ route('admin.proyectos.updateLeader') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                project_id: currentOpenProjectId,
                developer_id: newDevId
            })
        })
        .then(res => res.json())
        .then(data => {
            if (spinner) spinner.style.display = 'none';
            if (data.success) {
                const cardLeader = document.getElementById(`card-leader-name-${currentOpenProjectId}`);
                if (cardLeader) cardLeader.innerText = data.developer_name;
            } else {
                alert(data.message || 'Error al actualizar líder.');
            }
        })
        .catch(err => {
            if (spinner) spinner.style.display = 'none';
            alert('Error de conexión al actualizar líder del proyecto.');
        });
    }

    let currentModalAssetIndex = 0;
    let totalModalAssets = 0;

    function moveModalAssetsSlider(direction) {
        if (totalModalAssets <= 1) return;

        currentModalAssetIndex += direction;
        if (currentModalAssetIndex < 0) {
            currentModalAssetIndex = totalModalAssets - 1;
        } else if (currentModalAssetIndex >= totalModalAssets) {
            currentModalAssetIndex = 0;
        }

        const track = document.getElementById('modalAssetsTrack');
        if (track) {
            track.style.transform = `translateX(-${currentModalAssetIndex * 100}%)`;
        }

        const counter = document.getElementById('modalAssetCounter');
        if (counter) {
            counter.innerText = `${currentModalAssetIndex + 1} / ${totalModalAssets}`;
        }
    }

    function reloadCurrentProjectData() {
        if (!currentOpenProjectId) return;
        const card = document.querySelector(`.proyecto-card[data-id="${currentOpenProjectId}"]`);
        if (card) {
            // Re-fetch project and update modal
            fetch(`{{ url('/console/api/proyectos') }}/${currentOpenProjectId}`)
                .then(res => res.json())
                .then(data => {
                    currentProjectMilestonesData = data.milestones || [];

                    // Re-render Hitos
                    const hitosTrack = document.getElementById('modalMilestonesTrack');
                    const hitosCounter = document.getElementById('modalMilestoneCounter');
                    if (hitosTrack) {
                        hitosTrack.innerHTML = '';
                        currentModalHitoIndex = 0;
                        hitosTrack.style.transform = 'translateX(0px)';

                        const milestonesList = data.milestones || [];
                        totalModalHitos = milestonesList.length;

                        const bottomControls = document.getElementById('modalSliderControlsBottom');
                        if (totalModalHitos > 1) {
                            if (bottomControls) bottomControls.style.display = 'flex';
                            if (hitosCounter) hitosCounter.innerText = `1 / ${totalModalHitos}`;
                        } else {
                            if (bottomControls) bottomControls.style.display = 'none';
                            if (hitosCounter) hitosCounter.innerText = totalModalHitos === 1 ? '1 / 1' : '';
                        }

                        if (totalModalHitos === 0) {
                            hitosTrack.innerHTML = `
                                <div class="project-mini-card text-center py-3">
                                    <span style="color: #94a3b8; font-size: 0.82rem; display: block;">Este proyecto no tiene hitos financieros configurados.</span>
                                </div>
                            `;
                        } else {
                            milestonesList.forEach(m => {
                                const isPaid = m.is_paid == 1 || m.is_paid === true;
                                const statusBadge = isPaid 
                                    ? `<span class="badge font-mono" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; font-size: 0.72rem;"><i class="fas fa-check-circle me-1"></i> Liquidado</span>` 
                                    : `<span class="badge font-mono" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; font-size: 0.72rem;">Pendiente</span>`;

                                const toggleBtn = `
                                    <button type="button" class="btn btn-sm ${isPaid ? 'btn-outline-secondary' : 'btn-outline-success'}" 
                                        style="font-size: 0.7rem; padding: 4px 10px; border-radius: 6px;" 
                                        onclick="toggleHitoPago(${m.id}, this)">
                                        ${isPaid ? '<i class="fas fa-undo me-1"></i> Marcar Pendiente' : '<i class="fas fa-check me-1"></i> Marcar como Pagado'}
                                    </button>
                                `;

                                let receiptLink = '';
                                if (data.assets && data.assets.length > 0) {
                                    const asset = data.assets.find(a => a.assetable_id == m.id && a.assetable_type && a.assetable_type.includes('Milestone'));
                                    if (asset) {
                                        receiptLink = `<a href="{{ url('/assets') }}/${asset.id}/download" download class="badge text-info text-decoration-none" style="background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.3); font-size: 0.72rem; padding: 5px 8px;"><i class="fas fa-download me-1"></i> Comprobante</a>`;
                                    }
                                }

                                hitosTrack.innerHTML += `
                                    <div class="project-mini-card">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold text-white" style="font-size: 0.92rem;">${m.name || m.title || 'Hito'}</span>
                                            <span class="font-mono text-info fw-bold" style="font-size: 0.95rem;">$${parseFloat(m.cost || 0).toFixed(2)} USD</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.04);">
                                            <div>${receiptLink}</div>
                                            <div class="d-flex align-items-center gap-2">
                                                ${statusBadge}
                                                ${toggleBtn}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        const badgeHitos = document.getElementById('tabBadgeHitos');
                        if (badgeHitos) badgeHitos.innerText = milestonesList.length;
                    }

                    // Update assets slider
                    const assetsTrack = document.getElementById('modalAssetsTrack');
                    const assetsCounter = document.getElementById('modalAssetCounter');
                    if (assetsTrack) {
                        assetsTrack.innerHTML = '';
                        currentModalAssetIndex = 0;
                        assetsTrack.style.transform = 'translateX(0px)';

                        const projectAssets = (data.assets || []).filter(a => !a.assetable_type || a.assetable_type.includes('Project'));
                        totalModalAssets = projectAssets.length;

                        const bottomAssetControls = document.getElementById('modalAssetsSliderControlsBottom');
                        if (totalModalAssets > 1) {
                            if (bottomAssetControls) bottomAssetControls.style.display = 'flex';
                            if (assetsCounter) assetsCounter.innerText = `1 / ${totalModalAssets}`;
                        } else {
                            if (bottomAssetControls) bottomAssetControls.style.display = 'none';
                            if (assetsCounter) assetsCounter.innerText = totalModalAssets === 1 ? '1 / 1' : '';
                        }

                        if (totalModalAssets === 0) {
                            assetsTrack.innerHTML = '<div class="project-mini-card text-center"><span style="color: #94a3b8; font-size: 0.82rem;">Este proyecto no tiene entregables o archivos subidos en la bóveda.</span></div>';
                        } else {
                            projectAssets.forEach(asset => {
                                const ext = (asset.path || '').split('.').pop().toLowerCase();
                                let fullFileName = asset.nombre || 'archivo';
                                if (ext && !fullFileName.toLowerCase().endsWith('.' + ext.toLowerCase())) {
                                    fullFileName += '.' + ext;
                                }

                                let iconClass = 'fa-file-alt text-info';
                                if (['zip', 'rar', '7z', 'tar', 'gz'].includes(ext)) iconClass = 'fa-file-archive text-warning';
                                else if (['pdf'].includes(ext)) iconClass = 'fa-file-pdf text-danger';
                                else if (['apk', 'exe'].includes(ext)) iconClass = 'fa-android text-success';
                                else if (['png', 'jpg', 'jpeg', 'svg', 'webp'].includes(ext)) iconClass = 'fa-file-image text-cyan';
                                else if (['mp4', 'webm', 'mov'].includes(ext)) iconClass = 'fa-file-video text-purple';

                                const downloadUrl = `{{ url('/assets') }}/${asset.id}/download`;

                                assetsTrack.innerHTML += `
                                    <div class="project-mini-card">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                <div style="width: 28px; height: 28px; border-radius: 6px; background: rgba(255,255,255,0.04); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                    <i class="fas ${iconClass}"></i>
                                                </div>
                                                <span class="fw-bold text-white text-truncate font-mono" style="font-size: 0.9rem;" title="${fullFileName}">
                                                    ${fullFileName}
                                                </span>
                                            </div>
                                            <span class="badge font-mono" style="background: rgba(0, 212, 255, 0.1); border: 1px solid rgba(0, 212, 255, 0.3); color: #00d4ff; font-size: 0.72rem; flex-shrink: 0;">
                                                ${asset.tipo || 'Entregable'}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.04);">
                                            <span class="font-mono text-white-50" style="font-size: 0.72rem;">
                                                <i class="fas fa-file me-1 text-info"></i> ${ext.toUpperCase()}
                                            </span>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="${downloadUrl}" download="${fullFileName}" class="btn btn-sm" style="background: rgba(0, 212, 255, 0.12); border: 1px solid rgba(0, 212, 255, 0.35); color: #00d4ff; font-size: 0.72rem; padding: 4px 12px; border-radius: 6px; font-weight: 600;">
                                                    <i class="fas fa-download me-1"></i> Descargar
                                                </a>
                                                @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
                                                <button type="button" class="btn btn-sm btn-outline-danger" style="font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;" onclick="eliminarAsset(${asset.id}, this)" title="Eliminar entregable">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        const badgeAssets = document.getElementById('tabBadgeAssets');
                        if (badgeAssets) badgeAssets.innerText = projectAssets.length;
                    }
                })
                .catch(err => console.error(err));
        }
    }

    function switchModalTab(tab) {
        const tabHitos = document.getElementById('tabBtnHitos');
        const tabAssets = document.getElementById('tabBtnAssets');
        const contentHitos = document.getElementById('modalTabContentHitos');
        const contentAssets = document.getElementById('modalTabContentAssets');
        const hitosCounter = document.getElementById('modalMilestoneCounter');
        const assetCounter = document.getElementById('modalAssetCounter');
        const btnUpload = document.getElementById('btnHeaderUploadAsset');
        const btnManageHitos = document.getElementById('btnHeaderManageHitos');

        if (!tabHitos || !tabAssets) return;

        if (tab === 'hitos') {
            tabHitos.classList.add('active');
            tabAssets.classList.remove('active');
            if (contentHitos) contentHitos.style.display = 'block';
            if (contentAssets) contentAssets.style.display = 'none';
            if (hitosCounter) hitosCounter.style.display = 'inline-block';
            if (assetCounter) assetCounter.style.display = 'none';
            if (btnManageHitos) btnManageHitos.style.display = 'inline-flex';
            if (btnUpload) btnUpload.style.display = 'none';
        } else {
            tabAssets.classList.add('active');
            tabHitos.classList.remove('active');
            if (contentAssets) contentAssets.style.display = 'block';
            if (contentHitos) contentHitos.style.display = 'none';
            if (hitosCounter) hitosCounter.style.display = 'none';
            if (assetCounter) assetCounter.style.display = 'inline-block';
            if (btnManageHitos) btnManageHitos.style.display = 'none';
            if (btnUpload) btnUpload.style.display = 'inline-flex';
        }
    }

    function openUploadAssetModal() {
        if (!currentOpenProjectId) return;
        const form = document.getElementById('formUploadAsset');
        if (form) form.reset();
        const progressContainer = document.getElementById('uploadAssetProgressContainer');
        if (progressContainer) progressContainer.style.display = 'none';
        const progressBar = document.getElementById('uploadProgressBar');
        if (progressBar) progressBar.style.width = '0%';
        const progressPercentage = document.getElementById('uploadPercentageText');
        if (progressPercentage) progressPercentage.innerText = '0%';
        const submitBtn = document.getElementById('btnSubmitUploadAsset');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Subir Archivo';
        }

        const modalEl = document.getElementById('uploadAssetModal');
        if (modalEl) {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.style.zIndex = '1070';
            document.body.classList.add('modal-open');

            if (!document.getElementById('modal-backdrop-upload')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modal-backdrop-upload';
                backdrop.style.zIndex = '1065';
                document.body.appendChild(backdrop);
            }
        }
    }

    function closeUploadAssetModal() {
        const modalEl = document.getElementById('uploadAssetModal');
        if (modalEl) {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
        }
        const backdrop = document.getElementById('modal-backdrop-upload');
        if (backdrop) backdrop.remove();
    }

    function autoFillAssetName(input) {
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const nameInput = document.getElementById('assetNombreInput');
            if (!nameInput.value) {
                nameInput.value = fileName;
            }
        }
    }

    function submitUploadAsset(e) {
        e.preventDefault();
        if (!currentOpenProjectId) return;

        const fileInput = document.getElementById('assetFileInput');
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Por favor selecciona un archivo para subir.');
            return;
        }

        const formData = new FormData();
        formData.append('archivo', fileInput.files[0]);
        formData.append('nombre', document.getElementById('assetNombreInput').value);
        formData.append('tipo', document.getElementById('assetTipoSelect').value);
        formData.append('_token', '{{ csrf_token() }}');

        const progressContainer = document.getElementById('uploadAssetProgressContainer');
        const progressBar = document.getElementById('uploadProgressBar');
        const progressPercentage = document.getElementById('uploadPercentageText');
        const submitBtn = document.getElementById('btnSubmitUploadAsset');

        progressContainer.style.display = 'block';
        submitBtn.disabled = true;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', `{{ url('/console/proyectos') }}/${currentOpenProjectId}/assets`, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                progressPercentage.innerText = percent + '%';
            }
        };

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                closeUploadAssetModal();
                reloadCurrentProjectData();
            } else {
                alert('Error al subir el archivo: ' + (xhr.responseText || 'Error del servidor'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Subir Archivo';
            }
        };

        xhr.onerror = function() {
            alert('Error de red al intentar subir el archivo.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-upload me-1"></i> Subir Archivo';
        };

        xhr.send(formData);
    }

    function eliminarAsset(assetId, buttonEl) {
        if (!confirm('¿Deseas eliminar permanentemente este archivo de la bóveda?')) return;

        buttonEl.disabled = true;
        fetch(`{{ url('/console/assets') }}/${assetId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                reloadCurrentProjectData();
            } else {
                alert(data.message || 'Error al eliminar');
                buttonEl.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error de conexión al eliminar archivo.');
            buttonEl.disabled = false;
        });
    }

    // ==========================================
    // GESTIÓN Y CARGA DE HITOS FINANCIEROS
    // ==========================================
    const defaultPhaseNames = [
        '01 / Inicialización & Arquitectura',
        '02 / En Desarrollo (Core & Funcionalidades)',
        '03 / En Pruebas (QA & Testing)',
        '04 / Despliegue & Entrega Final'
    ];

    function openMilestonesModal() {
        if (!currentOpenProjectId) return;

        const container = document.getElementById('milestonesRowsContainer');
        if (container) container.innerHTML = '';

        if (currentProjectMilestonesData && currentProjectMilestonesData.length > 0) {
            currentProjectMilestonesData.forEach(m => {
                addMilestoneRow(m.name || m.title || '', m.cost || 0, m.id);
            });
        } else {
            loadDefault4Phases();
        }

        calculateMilestonesTotal();

        const submitBtn = document.getElementById('btnSubmitMilestones');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Hitos';
        }

        const modalEl = document.getElementById('milestonesModal');
        if (modalEl) {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.style.zIndex = '1070';
            document.body.classList.add('modal-open');

            if (!document.getElementById('modal-backdrop-milestones')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modal-backdrop-milestones';
                backdrop.style.zIndex = '1065';
                document.body.appendChild(backdrop);
            }
        }
    }

    function closeMilestonesModal() {
        const modalEl = document.getElementById('milestonesModal');
        if (modalEl) {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
        }
        const backdrop = document.getElementById('modal-backdrop-milestones');
        if (backdrop) backdrop.remove();
    }

    function loadDefault4Phases() {
        const container = document.getElementById('milestonesRowsContainer');
        if (!container) return;
        container.innerHTML = '';
        defaultPhaseNames.forEach(name => {
            addMilestoneRow(name, '', null);
        });
        calculateMilestonesTotal();
    }

    function addMilestoneRow(name = '', cost = '', id = null) {
        const container = document.getElementById('milestonesRowsContainer');
        if (!container) return;

        const rowIndex = container.children.length + 1;
        const rowDiv = document.createElement('div');
        rowDiv.className = 'd-flex align-items-center gap-2 p-2 rounded milestone-row-item';
        rowDiv.style.background = 'rgba(255, 255, 255, 0.02)';
        rowDiv.style.border = '1px solid rgba(255, 255, 255, 0.06)';

        const idField = id ? `<input type="hidden" class="milestone-id-input" value="${id}">` : '';

        rowDiv.innerHTML = `
            ${idField}
            <div class="milestone-badge-num font-mono fw-bold flex-shrink-0" style="width: 24px; font-size: 0.75rem; text-align: center; color: #00d4ff;">
                #${rowIndex}
            </div>
            <div class="flex-grow-1">
                <input type="text" class="form-control milestone-name-input" value="${name}" placeholder="Nombre del hito o fase" required style="background: #030712; border: 1px solid rgba(255,255,255,0.1); color: #fff; font-size: 0.82rem; padding: 6px 10px; border-radius: 6px;">
            </div>
            <div style="width: 140px;" class="flex-shrink-0">
                <div class="input-group input-group-sm">
                    <span class="input-group-text font-mono" style="background: rgba(0, 212, 255, 0.1); border: 1px solid rgba(0, 212, 255, 0.25); color: #00d4ff; font-size: 0.75rem; padding: 4px 6px;">$</span>
                    <input type="number" step="0.01" min="0" class="form-control font-mono milestone-cost-input" value="${cost !== '' ? parseFloat(cost) : ''}" placeholder="0.00" required oninput="calculateMilestonesTotal()" style="background: #030712; border: 1px solid rgba(0, 212, 255, 0.25); color: #00d4ff; font-weight: 600; font-size: 0.82rem; padding: 6px 8px;">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger flex-shrink-0" onclick="removeMilestoneRow(this)" title="Eliminar hito" style="padding: 4px 8px; border-radius: 6px; font-size: 0.72rem;">
                <i class="fas fa-trash-alt"></i>
            </button>
        `;

        container.appendChild(rowDiv);
        renumberMilestoneRows();
        calculateMilestonesTotal();
    }

    function removeMilestoneRow(btn) {
        const row = btn.closest('.milestone-row-item');
        if (row) {
            row.remove();
            renumberMilestoneRows();
            calculateMilestonesTotal();
        }
    }

    function renumberMilestoneRows() {
        const container = document.getElementById('milestonesRowsContainer');
        if (!container) return;
        const rows = container.querySelectorAll('.milestone-row-item');
        rows.forEach((row, i) => {
            const badge = row.querySelector('.milestone-badge-num');
            if (badge) badge.innerText = `#${i + 1}`;
        });
    }

    function calculateMilestonesTotal() {
        const container = document.getElementById('milestonesRowsContainer');
        if (!container) return;
        const costInputs = container.querySelectorAll('.milestone-cost-input');
        let sum = 0;
        costInputs.forEach(input => {
            const val = parseFloat(input.value) || 0;
            sum += val;
        });
        const totalEl = document.getElementById('milestonesTotalSum');
        if (totalEl) {
            totalEl.innerText = `$${sum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD`;
        }
    }

    function submitSaveMilestones(e) {
        e.preventDefault();
        if (!currentOpenProjectId) return;

        const container = document.getElementById('milestonesRowsContainer');
        const rows = container ? container.querySelectorAll('.milestone-row-item') : [];

        const milestonesData = [];
        rows.forEach(row => {
            const nameInput = row.querySelector('.milestone-name-input');
            const costInput = row.querySelector('.milestone-cost-input');
            const idInput = row.querySelector('.milestone-id-input');

            milestonesData.push({
                id: idInput ? idInput.value : null,
                name: nameInput ? nameInput.value.trim() : 'Hito',
                cost: costInput ? parseFloat(costInput.value) || 0 : 0
            });
        });

        const submitBtn = document.getElementById('btnSubmitMilestones');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';
        }

        fetch(`{{ url('/console/proyectos') }}/${currentOpenProjectId}/milestones/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ milestones: milestonesData })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                currentProjectMilestonesData = data.milestones || [];
                closeMilestonesModal();
                reloadCurrentProjectData();
            } else {
                alert(data.message || 'Error al guardar hitos.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Hitos';
                }
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error de red al guardar los hitos.');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Guardar Hitos';
            }
        });
    }
</script>
@endsection

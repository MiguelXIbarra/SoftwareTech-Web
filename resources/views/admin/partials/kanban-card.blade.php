<div class="kanban-card prio-border-{{ $proyecto->priority }}" id="project-card-{{ $proyecto->id }}" draggable="true" ondragstart="drag(event)" data-priority="{{ $proyecto->priority }}">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <span class="tag-prio text-{{ $proyecto->priority }} font-mono">
            // {{ $proyecto->priority }}
        </span>
        <span class="text-muted font-mono" style="font-size: 0.65rem;">
            ID-{{ $proyecto->id }}
        </span>
    </div>

    <h5 class="fw-bold text-white mb-1" style="font-size: 0.95rem;">{{ $proyecto->nombre }}</h5>
    <p class="text-muted mb-3" style="font-size: 0.75rem;">Módulo: <span class="text-info">{{ $proyecto->servicio }}</span></p>

    <div class="mb-3" style="font-size: 0.75rem; border-top: 1px solid rgba(255,255,255,0.03); pt-2;">
        <div class="text-muted mb-1">
            <i class="fas fa-user-tie me-1 text-secondary"></i> Cliente:
            <span class="text-white font-mono">{{ $proyecto->user ? $proyecto->user->name : 'No asignado' }}</span>
        </div>
        <div class="text-muted">
            <i class="fas fa-code-branch me-1 text-secondary"></i> Encargado:
            <span class="text-white font-mono">{{ $proyecto->developer ? $proyecto->developer->name : 'No asignado' }}</span>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between pt-2" style="border-top: 1px solid rgba(255,255,255,0.05);">
        <div class="text-muted font-mono" style="font-size: 0.65rem;">
            <i class="far fa-calendar-alt me-1"></i> {{ $proyecto->siguiente_entrega ?? 'Sin fecha' }}
        </div>
        <div class="font-mono text-info fw-bold" style="font-size: 0.7rem;">
            {{ $proyecto->progreso }}%
        </div>
    </div>
</div>
</div>

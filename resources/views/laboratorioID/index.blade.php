@extends('layouts.page')
@section('title', 'Laboratorio I+D')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="text-bold text-dark">Laboratorio I+D</h1>
</div>
@stop

@section('content')
<style>
    /* --- TUS TARJETAS ORIGINALES --- */
    .btn-action-outline {
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.2s ease;
        background: transparent;
    }

    .btn-read-more {
        border: 1px solid #45a1b5;
        color: #45a1b5;
    }

    .btn-read-more:hover {
        background-color: #45a1b5;
        color: #ffffff;
    }

    .card-lab-id {
        background-color: #1a222b !important;
        border-radius: 8px;
        border-top: 4px solid #45a1b5 !important;
    }

    /* --- ENTORNO XBOX: TOPADO AL FOOTER --- */
    .xbox-cosmic-void {
        position: relative;
        width: auto;
        margin-left: -15px;
        margin-right: -15px;
        margin-bottom: -30px;
        /* Topa directamente con el footer */
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: transparent;
        margin-top: 20px;
        overflow: hidden;
    }

    /* Máscara radial */
    .void-mask-layer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 95%);
        mask-image: radial-gradient(circle at center, black 40%, transparent 95%);
        pointer-events: none;
    }

    /* Malla de ventilación Xbox Series X */
    .xbox-vent-mesh {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: radial-gradient(circle, transparent 24%, #0b1120 30%);
        background-size: 11px 11px;
        z-index: 2;
    }

    /* --- ONDA DE LUZ: CONTROLADA Y CONGELADA AL FINAL --- */
    .cosmic-blast-wave {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, rgba(69, 161, 181, 0.3) 30%, rgba(68, 114, 241, 0.15) 60%, transparent 85%);
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        /* TRUCO: "forwards" en lugar de "infinite" para congelar el final del primer ciclo */
        animation: xboxLightCycle 5.5s cubic-bezier(0.1, 0.8, 0.2, 1) forwards;
        z-index: 1;
    }

    /* --- TIPOGRAFÍA SIEMPRE VISIBLE Y CONGELADA EN EL ZOOM MAXIMO --- */
    .modern-tech-center {
        position: relative;
        z-index: 3;
        text-align: center;
        pointer-events: none;
        /* El zoom del texto se congela al mismo tiempo exacto (forwards) */
        animation: xboxTextZoom 5.5s cubic-bezier(0.1, 0.8, 0.2, 1) forwards;
    }

    .innovative-title {
        font-size: clamp(2.5rem, 6vw, 4.5rem);
        font-weight: 900;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 26px;
        margin-bottom: 12px;
        margin-left: 26px;
        /* Sombras oscuras para proteger el contraste y mantener la legibilidad */
        text-shadow: 0 0 20px rgba(0, 0, 0, 0.9), 0 0 35px rgba(69, 161, 181, 0.8);
    }

    .tech-visible-label {
        font-size: 1rem;
        color: #ffffff;
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 8px;
        text-transform: uppercase;
        font-weight: 700;
        /* Máxima visibilidad frente al fondo */
        text-shadow: 0 0 15px rgba(0, 0, 0, 0.9), 0 0 25px rgba(69, 161, 181, 0.7);
    }

    .tech-visible-label span {
        color: #45a1b5;
        font-weight: 900;
        font-size: 1.2rem;
    }

    /* --- ANIMACIONES DE UN SOLO CICLO (FORWARDS) --- */

    @keyframes xboxLightCycle {
        0% {
            transform: translate(-50%, -50%) scale(0);
            opacity: 0;
        }

        8% {
            opacity: 0.85;
        }

        /* Encendido elegante */
        /* Al llegar a 100%, se queda estática manteniendo un remanente suave de luz ambiental */
        100% {
            transform: translate(-50%, -50%) scale(5.2);
            opacity: 0.4;
        }
    }

    @keyframes xboxTextZoom {
        0% {
            transform: scale(0.85);
        }

        /* Se congela permanentemente en su escala máxima de perspectiva */
        100% {
            transform: scale(1.12);
        }
    }
</style>

<div class="row">
    @foreach($proyectos as $proyecto)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100 card-lab-id">
            <div class="card-header border-0 pt-3" style="background-color: transparent;">
                <h3 class="card-title text-bold text-white" style="font-size: 1.1rem;">
                    {{ $proyecto->title }}
                </h3>
            </div>

            <div class="card-body py-2">
                <span class="badge mb-2 text-white shadow-sm" style="background-color: #45a1b5;">
                    NODE_ID_{{ $proyecto->id }}
                </span>
                <p class="text-muted small" style="line-height: 1.6; color: #e0e6ed !important;">
                    {{ Str::limit(strip_tags($proyecto->body), 120) }}
                </p>
            </div>

            <div class="card-footer border-0 pb-3" style="background-color: transparent;">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('lab_posts.show', $proyecto->id) }}"
                        class="btn btn-sm btn-action-outline btn-read-more px-3">
                        <i class="fas fa-eye"></i> <span class="ml-1">Analizar</span>
                    </a>
                    <span class="small text-muted font-weight-bold" style="color: rgba(69, 161, 181, 0.7) !important;">
                        <i class="far fa-calendar-alt mr-1"></i> {{ $proyecto->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="xbox-cosmic-void">
    <div class="void-mask-layer">
        <div class="cosmic-blast-wave"></div>
        <div class="xbox-vent-mesh"></div>
    </div>

    <div class="modern-tech-center">
        <div class="innovative-title">Próximamente</div>
        <div class="tech-visible-label"><span>[</span> Software Tech || Innovation Lab <span>]</span></div>
    </div>
</div>
@endsection

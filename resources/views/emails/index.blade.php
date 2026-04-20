@extends('adminlte::page')
@section('title', 'Bandeja de Entrada')

@section('content_header')
<div class="d-flex justify-content-between align-items-center px-3">
    <h1 class="text-bold text-white">Bandeja de Entrada</h1>
    <a href="{{ route('emails.create') }}" class="btn text-white shadow-sm btn-redactar">
        <i class="fas fa-plus mr-1"></i> Redactar
    </a>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12 px-4">

        {{-- ENCABEZADOS --}}
        <div class="d-none d-md-flex row mb-2 px-3 text-muted small text-uppercase text-bold"
            style="letter-spacing: 1px;">
            <div class="col-md-1 text-center"></div>
            <div class="col-md-2">Remitente</div>
            <div class="col-md-5">Contenido</div>
            <div class="col-md-2 text-center">Fecha</div>
            <div class="col-md-2 text-right px-4">Acciones</div>
        </div>

        @forelse($emails as $email)
        <div class="card shadow-sm mb-2 email-card">
            <div class="card-body py-2 px-3">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <i class="far fa-star text-muted"></i>
                    </div>

                    <div class="col-md-2">
                        <span class="text-white font-weight-bold">{{ $email->sender->name ?? 'Sistema Tech' }}</span>
                    </div>

                    <div class="col-md-5 d-flex flex-column">
                        <span class="text-white font-weight-bold" style="font-size: 1.05rem;">
                            {{ $email->subject ?? 'Sin Asunto' }}
                        </span>
                        <span class="text-muted small text-truncate">
                            {{ Str::limit(strip_tags($email->content), 90) }}
                        </span>
                    </div>

                    <div class="col-md-2 text-center">
                        <span class="text-muted small">
                            {{ $email->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <div class="col-md-2 text-right">
                        <div class="action-container">
                            <a href="{{ route('emails.show', $email->id) }}" class="btn-glow" title="Ver">
                                <i class="fas fa-eye" style="color: #45a1b5;"></i>
                            </a>
                            @if($email->sender_id === Auth::id())
                            <a href="{{ route('emails.edit', $email->id) }}" class="btn-glow" title="Editar">
                                <i class="fas fa-edit" style="color: #ffc107;"></i>
                            </a>
                            <form action="{{ route('emails.destroy', $email->id) }}" method="POST"
                                id="del-{{ $email->id }}" style="display:inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-glow" onclick="confirmDelete('del-{{ $email->id }}')">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <p class="h5 text-muted">Tu bandeja de entrada está vacía.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('css')
<style>
    .email-card {
        background: rgba(255, 255, 255, 0.05) !important;
        border: none !important;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .email-card:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        transform: translateX(5px);
        border-left: 4px solid #4472f1 !important;
    }

    /* CONTENEDOR DE ACCIONES TRANSPARENTE */
    .action-container {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    /* BOTÓN CON EFECTO GLOW (Sin cuadro negro) */
    .btn-glow {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 6px 10px;
        border-radius: 6px;
        color: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
    }

    .btn-glow:hover {
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.05);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3), 0 0 8px rgba(255, 255, 255, 0.1);
    }

    .btn-glow i {
        transition: transform 0.3s ease;
    }

    .btn-glow:hover i {
        transform: scale(1.1);
    }

    .btn-redactar {
        background-color: #4472f1;
        border-radius: 20px;
        padding: 6px 20px;
        transition: all 0.3s ease;
    }

    .btn-redactar:hover {
        background-color: #355fd1;
        box-shadow: 0 0 20px rgba(68, 114, 241, 0.5);
        transform: scale(1.05);
    }
</style>
@endsection
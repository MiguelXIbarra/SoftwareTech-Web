@extends('adminlte::page')
@section('title', 'Lectura de Mensaje')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card card-outline shadow" style="border-top: 3px solid #45a1b5;">
            <div class="card-header" style="background-color: #45a1b5;">
                <h3 class="card-title text-bold text-white"><i class="fas fa-envelope-open-text mr-2"></i>Lectura de Mensaje</h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="text-bold mb-0" style="color: #45a1b5;">{{ $message->project->title }}</h4>
                        <span class="text-muted small">Enviado por: Usuario #{{ $message->sender_id }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-muted small d-block">RECIBIDO EL:</span>
                        <span class="badge badge-light border">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="p-4 bg-light border rounded shadow-inner" style="min-height: 150px; font-size: 1.1rem; line-height: 1.6;">
                    {{ $message->content }}
                </div>

                @if($message->attachment)
                <div class="mt-4 p-3 border rounded bg-white shadow-sm d-inline-block">
                    <label class="text-muted small d-block mb-1">ARCHIVO ADJUNTO</label>
                    <a href="{{ asset('storage/' . $message->attachment) }}" class="btn btn-outline-info btn-sm" target="_blank">
                        <i class="fas fa-download mr-1"></i> Descargar Adjunto
                    </a>
                </div>
                @endif
            </div>
            <div class="card-footer bg-white text-center">
                <a href="{{ route('messages.index') }}" class="btn btn-default border px-5">Regresar a la Bandeja</a>
            </div>
        </div>
    </div>
</div>
@endsection